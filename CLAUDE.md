# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Life Updates is a web app for sharing periodic video updates with friends. Users (content creators) upload monthly video updates; viewers (friends) watch them and leave time-stamped comments. Access is controlled via permission levels (full/limited).

## Tech Stack

- **Backend:** PHP / Laravel
- **Database:** MySQL
- **Auth:** Google OAuth (both users and viewers)

## Important Rules

- **Never run `migrate:fresh` or `migrate:reset`** unless explicitly told to. The database has test data. Always use `php artisan migrate` instead.

## Development Commands

```bash
composer install          # Install PHP dependencies
php artisan serve         # Run local dev server
php artisan migrate       # Run database migrations
php artisan test          # Run test suite
php artisan test --filter=TestName  # Run a single test
```

## Architecture

### Two Actor Types
- **Users** — content creators who upload videos and manage their own viewers/videos via admin pages (`/adm/*`)
- **Viewers** — friends who watch videos and leave comments; added by users (no self-registration)

### Data Scoping
Each user manages only their own viewers and videos. Viewers see only videos shared with them at their permission level. Comments are private per viewer (only the video uploader sees all comments).

### Key Entities
- `User` / `Viewer` — both authenticate via Google, but have separate tables and roles
- `UserViewer` — many-to-many with permission level (full/limited)
- `Video` — belongs to a user, has a permission level controlling visibility
- `Comment` — tied to a viewer, video, and timestamp within the video
- `VideoView` — tracks viewing history

### Routes
- `/login` — Google auth entry point (redirects unauthenticated visitors here)
- `/` — video list (filtered by viewer permissions, reverse chronological)
- `/update/{id}` — video player with time-stamped comments
- `/adm/` — admin dashboard (users only)
- `/adm/users/`, `/adm/viewers/`, `/adm/videos/` — CRUD management
