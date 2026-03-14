# Campus Lost & Found Portal

A production-ready LOST & FOUND portal for university campuses with automated claim verification.

## Features
- User Registration & Login
- Report Lost Items with Image Upload
- Search & Filter Items
- File Claims with Proof
- Admin Dashboard for Claim Approval/Rejection
- Responsive Bootstrap 5 UI

## Installation Steps

1. **XAMPP Setup**:
   - Place the project folder in `C:\xampp\htdocs\`.
   - Ensure PHP 8.0 or higher is installed.

2. **Convex Database Setup**:
   - Create a Convex project at [convex.dev](https://www.convex.dev/).
   - Copy the files from `convex/` in this project to your Convex `convex/` directory.
   - Run `npx convex dev` to deploy the schema and functions.

3. **Configure Project**:
   - Open `config/convex.php`.
   - Update `$deploymentUrl` with your Convex Deployment URL (e.g., `https://happy-otter-123.convex.cloud`).

4. **Run Application**:
   - Start Apache via XAMPP Control Panel.
   - Navigate to `http://localhost/project/index.php`.

## Folder Structure
- `/admin`: Management dashboard and claim logic.
- `/api`: Backend processing for auth, items, and claims.
- `/config`: Convex integration settings.
- `/uploads`: Storage for item and proof images.
- `/css` & `/js`: Styling and interactivity.

## Security
- Password hashing using PHP `password_hash()`.
- File upload validation (type and size).
- Sanitized form inputs.
- Session-based access control.
