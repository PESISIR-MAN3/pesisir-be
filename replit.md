# Laravel Charity/Donation Management System

## Overview
This is a Laravel-based charity and donation management system that provides API endpoints for managing donations, activities, volunteers, and complaints. The system uses Laravel Sanctum for authentication and is designed for non-profit organizations.

## Project Structure
- **Backend**: Laravel PHP framework with API routes
- **Frontend**: Vite + Tailwind CSS (minimal frontend, primarily API-focused)
- **Database**: SQLite (configured for Replit environment)
- **Authentication**: Laravel Sanctum

## Features
- Donation management with validation
- Activity/event management
- Volunteer registration and management
- Complaint handling system
- Authentication system with protected routes

## Recent Changes (September 22, 2025)
- Successfully imported GitHub project to Replit
- Configured SQLite database instead of MySQL for Replit compatibility
- Set up Vite configuration for Replit proxy environment
- Configured Laravel server to run on port 5000
- Set up deployment configuration for autoscale target
- All migrations successfully run, database is fully set up

## Configuration
- **Database**: SQLite at `database/database.sqlite`
- **Server**: Runs on port 5000 (host: 0.0.0.0)
- **Vite**: Runs on port 5173 for asset building
- **Queue Worker**: Runs for background job processing

## API Endpoints
### Public Endpoints
- `POST /api/donations` - Create donation
- `POST /api/complaints` - Submit complaint
- `POST /api/volunteers` - Register volunteer
- `POST /api/login` - User authentication

### Protected Endpoints (require authentication)
- Activities: GET, POST, PUT, DELETE `/api/activities`
- Donation Methods: Full CRUD `/api/donation-methods`
- Locations: Full CRUD `/api/locations`
- User management and logout

## Development
The application runs with multiple concurrent services:
- Laravel server (port 5000)
- Queue worker for background jobs
- Vite development server for asset compilation

## Deployment
Configured for autoscale deployment target with:
- Build step: `npm run build`
- Run command: Laravel server on port 5000 in production mode