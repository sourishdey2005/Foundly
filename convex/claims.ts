import { mutation, query } from "./_generated/server";
import { v } from "convex/values";

export const createClaim = mutation({
  args: {
    item_id: v.id("items"),
    claimer_id: v.id("users"),
    claimer_name: v.string(),
    phone: v.string(),
    proof_text: v.string(),
    proof_image: v.optional(v.string()),
  },
  handler: async (ctx, args) => {
    const existing = await ctx.db
      .query("claims")
      .filter((q) =>
        q.and(
          q.eq(q.field("item_id"), args.item_id),
          q.eq(q.field("claimer_id"), args.claimer_id)
        )
      )
      .unique();
    if (existing) return { success: false, error: "You already filed a claim for this item" };

    const id = await ctx.db.insert("claims", {
      ...args,
      status: "pending",
      created_at: Date.now(),
    });
    return { success: true, claimId: id };
  },
});

export const getClaims = query({
  args: { status: v.optional(v.string()) },
  handler: async (ctx, args) => {
    const claims = await ctx.db.query("claims").order("desc").collect();
    // Join with items for extra details
    const claimsWithItems = await Promise.all(
      claims.map(async (claim) => {
        const item = await ctx.db.get(claim.item_id);
        return { ...claim, item_name: item?.item_name || "Unknown Item" };
      })
    );
    if (args.status) {
      return claimsWithItems.filter((c) => c.status === args.status);
    }
    return claimsWithItems;
  },
});

export const updateClaimStatus = mutation({
  args: { id: v.id("claims"), status: v.string() },
  handler: async (ctx, args) => {
    const claim = await ctx.db.get(args.id);
    if (!claim) return { success: false, error: "Claim not found" };

    await ctx.db.patch(args.id, { status: args.status });

    if (args.status === "approved") {
      await ctx.db.patch(claim.item_id, { status: "claimed" });
      // Reject all other pending claims for this item
      const otherClaims = await ctx.db
        .query("claims")
        .filter((q) =>
          q.and(
            q.eq(q.field("item_id"), claim.item_id),
            q.eq(q.field("status"), "pending"),
            q.neq(q.field("_id"), args.id)
          )
        )
        .collect();
      for (const other of otherClaims) {
        await ctx.db.patch(other._id, { status: "rejected" });
      }
    }
    return { success: true };
  },
});
