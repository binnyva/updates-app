# Life Updates

A web app for sharing periodic video updates with friends. Users (content creators) upload monthly video updates; viewers (friends) watch them and leave time-stamped comments. Access is controlled via permission levels (full/limited).

## User Flow

- Users and viewers log in using their Google Account (separate login pages)
- Viewers see all videos shared with them based on their permission level, in reverse chronological order
- They click a video to go to the player and watch it
- They can leave comments; if they pause the video, the comment is tagged to that timestamp so the uploader can see the context
- View progress is tracked automatically (saved every 30 seconds)

## Tech Stack

- PHP 8.2+ / Laravel 12
- MySQL
- Google OAuth (via Laravel Socialite)
- Tailwind CSS 4, Alpine.js 3, Vite 7

## Setup

```bash
composer install           # Install PHP dependencies
npm install                # Install JS dependencies
cp .env.example .env       # Configure environment
php artisan key:generate   # Generate app key
php artisan migrate        # Run database migrations
npm run dev                # Start Vite dev server
php artisan serve          # Start Laravel dev server
```

Configure Google OAuth credentials in `.env`:

```
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=
```

## Architecture

### Two Actor Types

- **Users** — content creators who upload videos and manage their own viewers/videos via admin pages (`/adm/*`). A super admin flag grants access to user management.
- **Viewers** — friends who watch videos and leave comments. Added by users (no self-registration).

### Data Scoping

Each user manages only their own viewers and videos. Viewers see only videos shared with them at their permission level. Comments are private per viewer — only the video uploader sees all comments.

### Authentication

Dual-guard system: `web` guard for users, `viewer` guard for viewers. Both use Google OAuth with session-based auth. Three middleware layers enforce access:

- `auth.any` — requires either user or viewer login
- `auth.user` — requires user login (admin pages)
- `auth.superadmin` — requires super admin (user management)

## Pages

### Login

- `/login` — viewer login via Google
- `/adm/login` — user login via Google
- Unauthenticated visitors are redirected to the appropriate login page

### Update List

- URL: `/`
- Grid of video thumbnails with name and time period
- Filtered by viewer permission level; users see their own videos

### Update Viewer

- URL: `/update/{id}`
- Video player with streaming support (`/video/{id}/stream`)
- Time-stamped comment field (tags to current video position on pause)
- Viewers see only their own comments; users see all comments
- Playback progress tracked automatically

### Admin Dashboard

- URL: `/adm/`
- Stats overview: video count, viewer count, comment count

### Admin: Users (super admin only)

- URL: `/adm/users/`
- CRUD for user accounts, including super admin flag

### Admin: Viewers

- URL: `/adm/viewers/`
- CRUD for the current user's viewers
- Manage permission levels (full/limited) via pivot table

### Admin: Videos

- URL: `/adm/videos/`
- CRUD for the current user's videos
- Upload via file upload or URL (async download via queued job)
- Thumbnail upload support
- Set permission level (full/limited) per video

## DB Schema

- **users** — id, name, email, google_id, avatar, is_super_admin, timestamps
- **viewers** — id, name, email, google_id, avatar, last_login_on, timestamps
- **user_viewer** — id, user_id, viewer_id, level (full/limited)
- **videos** — id, name, time_period, video_url, thumbnail_url, user_id, content, level (full/limited), timestamps
- **comments** — id, content, viewer_id, video_time, video_id, timestamps
- **video_views** — id, video_id, viewer_id, video_view_time, timestamps
