# Pearl Pulse Safaris

Laravel + Livewire destinations showcase and admin panel for Pearl Pulse Safaris.

## Requirements

- PHP 8.3+ with GD extension
- Composer, Node.js 18+
- MySQL (or SQLite)

## Setup

```bash
composer install
cp .env.example .env   # if needed
php artisan key:generate

# Configure DB_* in .env, then:
php artisan migrate --seed
php artisan storage:link

npm install
npm run build

php artisan serve
```

Visit `http://localhost:8000`. Admin: `http://localhost:8000/admin` (redirects to login).

### Admin credentials

- **Email:** `admin@pearlpulse.test`
- **Password:** `password`

## Features

- Public homepage, destinations grid with Livewire country filter + search, destination detail pages, about & contact
- Enquiry forms (stored + emailed to admin)
- Admin CRUD for destinations (cover, gallery, highlights, draft/published)
- Enquiries inbox with status
- Site settings (hero, about, contact, social)
- `sitemap.xml`

## Production notes

Set `FILESYSTEM_UPLOADS_DISK=s3` and configure AWS_* for S3-compatible storage. Uploads default to the local `public` disk.

Image resizing uses PHP GD (web max 1600px, thumb max 600px).
