# Laravel Restaurant Finder 🍽️

A simple Laravel web application to search and display restaurants based on a user-input city using **OpenStreetMap (Nominatim)** and **Overpass API**.

---

## 📌 Features

- Input a city name via a simple HTML form
- Fetches up to 10 restaurants from OpenStreetMap APIs
- Displays Name, Address, and placeholder Rating
- No external JavaScript frameworks — Laravel only

---

## ⚙️ Setup Instructions

### 1. Clone the repo

```bash
git clone https://github.com/your-username/restaurant-finder.git
cd restaurant-finder
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Create `.env` file and set up SQLite

```bash
cp .env.example .env
touch database/database.sqlite
```

Then open `.env` and update:

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
SESSION_DRIVER=file
```

### 4. Generate app key and run migrations

```bash
php artisan key:generate
php artisan migrate
```

### 5. Run the Laravel development server

```bash
php artisan serve
```

Open your browser and visit: [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## 🧠 How It Works

1. User enters a city name in the form.
2. App uses **Nominatim API** to convert city name into OSM ID.
3. The OSM ID is converted into an `areaId` for Overpass API.
4. App sends a query to **Overpass API** for restaurants in that area.
5. First 10 restaurants (if available) are displayed with:
   - ✅ Name
   - ✅ Address (or lat/lon fallback)
   - ❌ Rating (not available from OSM — shown as "N/A")

---

## 📁 Minimal File Structure

Only these files and folders are strictly required to **run** the app:

```
restaurant-finder/
├── app/                  # Controllers, models, etc.
├── bootstrap/            # Laravel bootstrapping
├── config/               # Config files
├── database/
│   └── database.sqlite   # SQLite DB
├── public/               # index.php entry point
├── resources/
│   └── views/            # Blade templates
├── routes/
│   └── web.php           # Web routes
├── storage/              # Framework cache, logs
├── .env                  # Environment config
├── artisan               # Laravel CLI tool
├── composer.json         # PHP dependencies
├── composer.lock
```

---

## 🗃️ Optional/Unnecessary Files

You **don’t need** these files just to run the project:

- `.editorconfig`, `.gitattributes`, `.gitignore` (dev/git tools)
- `.env.example`, `.env.swp` (samples/backups)
- `phpunit.xml`, `tests/` (for testing only)
- `README.md` (for documentation only)
- `package.json`, `vite.config.js` (used only for JS/CSS asset compilation)

---

## 🔐 Important

Nominatim API **requires** a valid User-Agent in your HTTP headers.

In your `RestaurantController.php`, make sure this line includes your real email:

```php
'User-Agent' => 'LaravelRestaurantApp/1.0 (your@email.com)' (Use your own email as per your OpenStreetMap account)
```

If this is missing or fake, **your requests may be blocked**.

---

## 🧰 APIs Used

- 🌍 [Nominatim (OpenStreetMap)](https://nominatim.org/release-docs/latest/api/Search/)
- 📡 [Overpass API](https://overpass-api.de/)

Both are **free and public**, and don't require an API key.

---

## ✅ Requirements

- PHP ≥ 8.1
- Composer
- Laravel ≥ 10
- SQLite (default)

---

## 📄 License

This project is open for personal, educational, and non-commercial use.

---

## ✨ Author

Built with Laravel and ❤️  

