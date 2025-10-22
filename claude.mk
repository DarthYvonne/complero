# Complero - Project Brief for Claude Code

## Project Overview

Building **Complero** - a community platform for sharing free content and paid courses with real member communication.

**Tech Stack:**
- Laravel (already installed)
- Bootstrap 5 (standard components - buttons, forms, cards, modals, etc.)
- MySQL database: `complero_local` (local), `complero` (production)
- File-based sessions/cache (not database)
- Self-hosted videos (local storage, MP4 files)
- Deployment: Git → GitHub → Server pull
- Email: Brevo (formerly Sendinblue)

**Local Development:**
- Path: `C:\Users\Anders\Dropbox\__saas\complero`
- URL: `http://complero.test`
- Database: MySQL via DBngin, user: root, no password

**Production:**
- Server: 157.180.91.162 at `/var/www/complero`
- URL: `https://mere.vaneinstituttet.dk`
- Database: MySQL, user: complero, password in .env

---

## Core Concept

**It's a community platform, not a sales funnel.**

1. People sign up (email + name)
2. Get instant access to free content
3. Can purchase paid courses/resources
4. Admin can email members via Brevo
5. Members can reply - replies come INTO the platform
6. Admin sees real-time activity feed of everything happening

---

## Visual Design - Instagram Style

**Instagram-inspired aesthetic throughout:**
- Beautiful hero images for all content
- Clean, modern card layouts
- Image-focused design (like Instagram posts)
- Grid layouts for browsing content
- Large images on detail pages
- Minimalist, content-first approach

**Content Cards (Catalog/Grid View):**
- Hero image fills card top
- Title overlaid or below image
- "Free" or "€XX" price badge
- Clean typography
- Hover effects
- 2-3 columns on desktop, 1 on mobile

**Detail Pages:**
- Large hero image at top (full width or contained)
- Content below in clean layout
- Video player (if applicable)
- Download section for files
- Clear CTA button (Enroll/Purchase)

**Bootstrap Usage:**
- Use all standard Bootstrap components
- Customize with additional CSS for Instagram aesthetic
- Focus on imagery and whitespace
- Modern, clean, professional

---

## User Roles

**admin:**
- Full platform access
- Create/manage all courses and resources
- Send emails to members
- View activity feed
- Manage all users
- Import users from Simplero
- Upload videos and files

**creator:**
- Create and manage their own courses/resources
- Upload videos and files for their content
- View their enrollments
- Cannot access payments or other creators' content

**member (default):**
- Sign up and access free content
- Purchase paid courses/resources
- Learn at their own pace
- Download resources
- Reply to emails

---

## Content System

### **Two Main Content Types:**

**1. Courses**
- Collection of lessons in sequence
- Each lesson can have:
  - Self-hosted video (MP4 upload)
  - Text content (rich text)
  - File attachments (PDFs, documents, etc.)
- Progress tracking
- Can be free (price = 0) or paid
- Instagram-style card in catalog
- Purchase gives access to entire course

**2. Resources (Standalone Content)**
- Standalone content pages (ebooks, guides, templates, worksheets)
- Not part of a course
- Components:
  - Title, description
  - Hero image
  - One or more downloadable files
- Can be free or paid
- Purchase/enroll for access to downloads
- Instagram-style card in catalog

**Key Difference:**
- **Course** = sequential learning path with multiple lessons
- **Resource** = single piece of downloadable content

---

## Mailing Lists & Signup Forms

**Multiple Lists Feature:**
- Admin can create multiple mailing lists (e.g., "Newsletter", "Course Updates", "Special Offers")
- Each list has:
  - Name
  - Description (internal)
  - Unique identifier/slug
  - Custom signup form URL
- Each list can have separate Brevo list/tag integration

**Simple Signup Forms:**
- Each list has its own clean, simple signup form
- Forms are accessible via: `/tilmeld/{list-slug}` (e.g., `/tilmeld/nyhedsbrev`)
- Form fields:
  - Name (required)
  - Email (required)
  - Optional: Phone number
  - Optional: Custom fields per list
- Clean, minimal design with Complero branding
- Instant confirmation message after signup
- Optional: Redirect to thank you page
- Double opt-in via Brevo (send confirmation email)
- Store list subscriptions separately from user accounts
- Users can be on multiple lists

**Integration:**
- When someone signs up via form, also create Brevo contact
- Tag/segment by list in Brevo
- Track signup source (which list)
- Admin can view all subscribers per list
- Export subscribers as CSV

---

## Database Schema

### **users**id
name
email
password
role (enum: admin, creator, member) default: member
website (nullable)
bio (nullable)
imported_from (nullable) - 'simplero' if imported
external_id (nullable) - Simplero user ID
created_at
updated_at

### **courses**id
creator_id → users.id
title
slug (unique, for URLs)
description (text)
image_url - hero image path
price (decimal, nullable) - null or 0 = free
is_free (boolean)
is_published (boolean) default: false
stripe_price_id (nullable) - for paid courses
created_at
updated_at

### **lessons**id
course_id → courses.id
title
slug (unique within course)
video_path (nullable) - local storage path to MP4
content (text) - rich text content
order (integer) - for sequencing
duration_minutes (nullable)
created_at
updated_at

### **lesson_files** (attachments for lessons)id
lesson_id → lessons.id
filename - original filename
file_path - storage path
file_size (integer) - in bytes
mime_type
created_at

### **resources**id
creator_id → users.id
title
slug (unique)
description (text)
image_url - hero image path
price (decimal, nullable) - null or 0 = free
is_free (boolean)
is_published (boolean) default: false
stripe_price_id (nullable) - for paid resources
created_at
updated_at

### **resource_files** (attachments for resources)id
resource_id → resources.id
filename - original filename
file_path - storage path
file_size (integer) - in bytes
mime_type
created_at

### **enrollments** (for courses)id
user_id → users.id
course_id → courses.id
enrolled_at
payment_id (nullable) - Stripe payment ID if paid
completed_at (nullable)
progress_percentage (integer) default: 0
created_at
updated_at

### **resource_access** (for resources)id
user_id → users.id
resource_id → resources.id
accessed_at
payment_id (nullable) - Stripe payment ID if paid
created_at

### **lesson_completions**id
user_id → users.id
lesson_id → lessons.id
completed_at
created_at

### **messages**id
from_user_id → users.id (nullable if system message)
to_user_id → users.id (nullable if broadcast)
subject
body (text)
type (enum: outbound, reply)
parent_id → messages.id (nullable, for threading replies)
sent_at
read_at (nullable)
brevo_message_id (nullable) - for tracking
created_at

### **activity_log**id
user_id → users.id (nullable)
action (string) - joined, started_course, completed_lesson, replied, purchased, accessed_resource, imported_from_simplero, etc.
description (text) - human-readable description
metadata (json) - additional details
created_at

### **import_logs**id
type (string) - 'simplero_users'
status (enum: pending, processing, completed, failed)
file_path (string) - path to uploaded CSV
total_records (integer)
processed_records (integer)
success_count (integer)
error_count (integer)
errors (json) - array of error messages
started_at (nullable)
completed_at (nullable)
created_at
updated_at

### **mailing_lists**id
name
slug (unique) - for URL
description (internal, nullable)
brevo_list_id (nullable) - for Brevo integration
thank_you_message (text, nullable)
redirect_url (nullable)
is_active (boolean) default: true
created_at
updated_at

### **list_subscribers**id
mailing_list_id → mailing_lists.id
name
email
phone (nullable)
custom_fields (json, nullable)
source (string) - 'signup_form', 'admin', 'import'
brevo_contact_id (nullable)
subscribed_at
unsubscribed_at (nullable)
created_at
updated_at

---

## Key Features to Build

### **1. Authentication & User Management**
- Use Laravel Breeze for authentication (Blade stack)
- Use standard Bootstrap styling for auth pages
- Registration captures: email, name, basic profile
- Automatic role assignment: member (default)
- Admin can change user roles
- Email verification via Brevo

### **2. Simplero Import Feature**
- Admin-only feature
- Upload CSV export from Simplero
- Expected CSV format: email, name, additional fields
- Preview import (show first 10 rows, validate data)
- Process import:
  - Create users with role: member
  - Generate random password (send password reset email via Brevo)
  - Mark as `imported_from: 'simplero'`
  - Store Simplero ID if available in `external_id`
  - Skip duplicates (check by email)
  - Log all activity
  - Show import summary (created, skipped, errors)
- Import history/logs
- Ability to re-send welcome emails to imported users

### **3. Course Management**
- CRUD for courses (admin/creator)
- Auto-generate slugs from titles
- Image upload for hero images
- CRUD for lessons within courses
- Video upload (MP4 files to `/storage/app/public/videos/`)
- File upload for lesson attachments
- Manual lesson ordering (up/down buttons)
- Auto-generate lesson slugs from titles
- Mark course as free (price = 0) or paid (price > 0)
- For paid courses: integrate with Stripe (create price in Stripe, store price_id)
- Publish/unpublish courses
- Preview mode for unpublished courses

### **4. Resource Management**
- CRUD for resources (admin/creator)
- Auto-generate slugs from titles
- Image upload for hero images
- File upload for resource attachments (multiple files per resource)
- Mark resource as free or paid
- For paid resources: integrate with Stripe
- Publish/unpublish resources

### **5. Content Catalog (Public)**
- Browse all published courses and resources
- Instagram-style grid layout
- Filter: all / courses / resources / free / paid
- Search by title/description
- Course/Resource cards with hero image, title, price
- Detail pages for each item

### **6. Course Detail & Enrollment**
- Course detail page:
  - Large hero image
  - Description
  - Lessons list preview
  - Enroll/Purchase button
- Free courses: "Enroll Free" → instant enrollment
- Paid courses: "Purchase" → Stripe checkout
- After enrollment: access course player

### **7. Resource Detail & Access**
- Resource detail page:
  - Large hero image
  - Description
  - List of downloadable files
  - Access/Purchase button
- Free resources: "Get Access" → instant access, show download links
- Paid resources: "Purchase" → Stripe checkout → access
- Download tracking (optional: log downloads)

### **8. Course Player (Member)**
- Clean video player (HTML5 video tag)
- Video controls (play, pause, seek, volume, fullscreen)
- Text content below video
- Lesson list sidebar showing all lessons
- Current lesson highlighted
- "Mark Complete" checkbox
- Progress indicator (X of Y lessons complete)
- "Next Lesson" button (automatically advances)
- "Previous Lesson" button
- Download lesson files (if attached)
- Track completion in lesson_completions table
- Update enrollment progress_percentage

### **9. Member Dashboard**
- View enrolled courses (Instagram-style grid)
- "Continue Learning" section (courses in progress)
- Progress bars for each course
- Access to purchased resources
- Browse more content (link to catalog)
- Profile management

### **10. Admin Dashboard**
- Overview stats:
  - Total members
  - Total courses
  - Total resources
  - Total enrollments
  - Total revenue
- Quick actions:
  - Create course
  - Create resource
  - Send email
  - Import users
  - View activity feed
- Activity feed widget (last 20 activities)
- User management link
- Content management links

### **11. Email System via Brevo**
- **Send emails:**
  - Select recipients (all members, specific users, filter by role)
  - Compose: subject + body (textarea or simple rich text)
  - Send via Brevo API or SMTP
  - Log in messages table (type: outbound)
  - Track Brevo message ID
- **Receive replies:**
  - Configure Brevo inbound email forwarding
  - Webhook endpoint receives forwarded emails
  - Parse email, extract sender, body
  - Create entry in messages table (type: reply)
  - Log in activity feed
  - Show in conversation thread
- **Conversation view:**
  - See all messages with a specific member
  - Threaded view (original + replies)
  - Reply directly from platform (sends via Brevo)
  - Mark as read/unread

### **12. Activity Feed**
- Real-time log of platform activity
- Shows: signups, course starts, lesson completions, resource access, replies, purchases, imports
- Admin dashboard widget (last 20)
- Full page view with pagination
- Click activity item to see details
- Filterable by action type
- Date range filter

### **13. Stripe Integration**
- Each paid course/resource has stripe_price_id
- Admin creates Stripe Price via Stripe dashboard, pastes ID
- "Purchase" button → Stripe Checkout (hosted)
- Webhook endpoint receives payment confirmation
- On successful payment:
  - Create enrollment (courses) or resource_access (resources)
  - Log activity
  - Send receipt email via Brevo
- Test mode for development, production mode for live

---

## Video & File Upload Details

### **Videos (Self-hosted MP4):**

**Upload:**
- Admin/creator uploads MP4 file via form
- Validate: file type (video/mp4, video/webm), size limit (500MB?)
- Store in `/storage/app/public/videos/`
- Save path to `lessons.video_path`
- No encoding in MVP - admin provides web-ready MP4 files

**Display:**
- Use HTML5 `<video>` tag with controls
- Source: `/storage/videos/filename.mp4`
- Responsive player (width: 100%)
- Basic controls: play, pause, seek, volume, fullscreen
- No fancy player library needed for MVP

**Example:**
```html<video controls style="width: 100%; max-width: 800px;">
    <source src="{{ Storage::url($lesson->video_path) }}" type="video/mp4">
    Your browser does not support video playback.
</video>