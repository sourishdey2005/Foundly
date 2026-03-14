import { defineSchema, defineTable } from "convex/server";
import { v } from "convex/values";

export default defineSchema({
  users: defineTable({
    name: v.string(),
    email: v.string(),
    password_hash: v.string(),
    role: v.string(), // "user" or "admin"
  }).index("by_email", ["email"]),

  items: defineTable({
    user_id: v.id("users"),
    item_name: v.string(),
    description: v.string(),
    location: v.string(),
    category: v.optional(v.string()),
    date_lost: v.string(),
    image_path: v.string(),
    status: v.string(), // "lost", "found", "claimed"
    created_at: v.number(),
  }),

  claims: defineTable({
    item_id: v.id("items"),
    claimer_id: v.id("users"),
    claimer_name: v.string(),
    phone: v.string(),
    proof_text: v.string(),
    proof_image: v.optional(v.string()),
    status: v.string(), // "pending", "approved", "rejected"
    created_at: v.number(),
  }),
});
