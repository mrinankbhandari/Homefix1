========================================================================
HomeFix - Profile Images Directory (Manual Photo Gap)
========================================================================

This folder is the designated storage location for user and provider profile pictures.

HOW TO ADD PROFILE IMAGES MANUALLY:
1. Copy your JPG, PNG, or WEBP image files directly into this folder:
   C:\xampp\htdocs\HomeFix\public\uploads\avatars\

2. To associate an image with a specific provider or user:
   Option A: Log in as the provider, go to "Photo Slot & Bio" (index.php?page=provider-profile), and choose your image file.
   Option B: Or update the `profile_image` column in the `users` table in MySQL:
      UPDATE users SET profile_image = 'ramesh.jpg' WHERE email = 'ramesh.electrician@example.com';
      UPDATE users SET profile_image = 'sunita.jpg' WHERE email = 'sunita.plumbing@example.com';

DEFAULT BEHAVIOR WHEN NO IMAGE IS ADDED:
HomeFix automatically renders a high-contrast maximalist monogram avatar
with initials (e.g. "RK", "SD") and an explicit "Photo Gap" badge, so the layout
remains structured, appealing, and ready for your manual photo additions.
========================================================================
