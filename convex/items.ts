import { mutation, query } from "./_generated/server";
import { v } from "convex/values";

export const createItem = mutation({
  args: {
    user_id: v.id("users"),
    item_name: v.string(),
    description: v.string(),
    location: v.string(),
    date_lost: v.string(),
    category: v.string(),
    image_path: v.string(),
  },
  handler: async (ctx, args) => {
    return await ctx.db.insert("items", {
      ...args,
      status: "lost",
      created_at: Date.now(),
    });
  },
});

export const getItems = query({
  args: { status: v.union(v.string(), v.null()) },
  handler: async (ctx, args) => {
    let q = ctx.db.query("items");
    if (args.status && args.status !== null) {
      q = q.filter((f) => f.eq(f.field("status"), args.status));
    }
    return await q.order("desc").collect();
  },
});

export const updateItem = mutation({
  args: {
    id: v.id("items"),
    item_name: v.string(),
    description: v.string(),
    location: v.string(),
    category: v.string(),
    date_lost: v.string(),
    image_path: v.optional(v.string()),
  },
  handler: async (ctx, args) => {
    const { id, ...updates } = args;
    await ctx.db.patch(id, updates);
  },
});

export const updateItemStatus = mutation({
  args: { id: v.id("items"), status: v.string() },
  handler: async (ctx, args) => {
    await ctx.db.patch(args.id, { status: args.status });
  },
});
