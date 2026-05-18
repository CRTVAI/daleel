# CRTVAI Mentor Platform

An advanced, context-aware AI learning mentor platform integrated with a browser extension that maps user browsing activity to specific tutorial videos.

## Features

- **Contextual Recommendation Engine**: Automatically matches active browser tab domains (e.g., ChatGPT, Slack, Notion) to learning content.
- **Chrome Extension Data Sync**: Tracks active browsing time, tab switches, page views, and focus levels, syncing data to a user dashboard.
- **Professional Analytics Dashboard**: Beautiful, high-fidelity tabbed interface featuring productivity/focus scores, web usage trends, daily reports, and chronological metrics snapshots.
- **Premium Exploration Hub**: Stunning, indigo-themed learning catalog complete with interactive filter chips, personal-path recommendations, and search capabilities matching titles, tags, and connected tools.

## Setup Instructions

1. Clone the repository.
2. Install dependencies:
   ```bash
   composer install
   npm install && npm run build
   ```
3. Set up the environment variables:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Run migrations and database seeders:
   ```bash
   php artisan migrate --seed
   ```
5. Start the local server:
   ```bash
   php artisan serve
   ```
