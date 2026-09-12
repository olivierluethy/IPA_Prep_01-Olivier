<div align="center">
  <img src="images/logo.png" alt="Journey logo" width="140" />
  <h1>Journey</h1>
  <p><b>A learning-journal web app for Swiss apprenticeships.</b><br/>Role-based areas where apprentices keep daily and weekly reports and specialists review them.</p>
  <p>
    <a href="LICENSE"><img alt="License: MIT" src="https://img.shields.io/badge/License-MIT-blue.svg"></a>
    <img alt="PHP" src="https://img.shields.io/badge/PHP-777BB4?logo=php&logoColor=white">
    <img alt="MySQL" src="https://img.shields.io/badge/MySQL-4479A1?logo=mysql&logoColor=white">
    <img alt="Composer" src="https://img.shields.io/badge/Composer-885630?logo=composer&logoColor=white">
    <img alt="CKEditor" src="https://img.shields.io/badge/CKEditor-1EBC61?logo=ckeditor4&logoColor=white">
  </p>
</div>

---

**Journey** is a learning-journal (*Lernjournal*) application for Swiss vocational
training. Apprentices document their work in daily and weekly reports and in keyword
entries written with a rich-text editor; specialists and administrators supervise the
apprentices and their submissions. It is built on a small hand-rolled PHP MVC
foundation — a front controller, plain PHP controllers, PDO models and PHP view
templates — with no full-stack framework.

This is the first version of the project.

## Features

- **Three roles, three areas.** *Lernender* (apprentice), *Fachkraft* (specialist) and
  *Admin*, each with its own dashboard and permitted actions.
- **Daily and weekly reports.** Apprentices create, edit, delete and release
  (*freigeben*) daily and weekly journal entries.
- **Keyword entries.** Apprentices maintain their own keyword (*Stichwort*) list with
  add / edit / delete.
- **Rich-text authoring.** Report and keyword content is written with a bundled
  [CKEditor](https://ckeditor.com/) instance.
- **Specialist review.** Fachkräfte get an overview of released reports and can open
  individual daily and weekly entries.
- **Admin user management.** User overview plus edit and delete of accounts.
- **Profiles and settings.** Each user can update their profile and change their
  password.
- **Search.** A search view across journal content.
- **Login.** Email/password accounts (passwords hashed) alongside optional Google
  OAuth sign-in via the Google API client.

## Tech stack

- **PHP** with a hand-rolled MVC structure — front controller in `index.php`, a route
  table, `app/Controllers`, `app/Models` and `app/Views`.
- **MySQL / MariaDB** accessed through **PDO** with prepared statements (`benutzer`
  and journal tables).
- **[CKEditor](https://ckeditor.com/)** (bundled under `ckeditor/`) for rich-text input.
- **[Composer](https://getcomposer.org/)** — dependency: `google/apiclient` for Google
  OAuth login.
- **Apache** with URL rewriting (`.htaccess`) routing every request to `index.php`.

## Project layout

```
index.php                 Front controller + route table
core/bootstrap.php        App bootstrap (autoload, DB connection helper)
app/Controllers/          One controller per area (Journal, DailyReport, ...)
app/Models/               PDO models (Login, DailyReport, WeeklyReport, ...)
app/Views/
  general/                Shared head/foot/config, login, profile, search
  lernender/              Apprentice views (reports, keywords, dashboard)
  fachkraft/              Specialist views (overview, see daily/weekly)
  admin/                  Admin views (user overview, edit user)
ckeditor/                 Bundled rich-text editor
images/                   Assets (logo)
```

## Getting started

### Requirements

- PHP with the PDO MySQL extension
- A MySQL / MariaDB database
- [Composer](https://getcomposer.org/)
- A web server with URL rewriting (Apache + `mod_rewrite`, or equivalent)

### 1. Install dependencies

```bash
composer install
```

This installs `google/apiclient` into `vendor/`.

### 2. Configure the database

The models connect through a `connectDatabase()` helper and query tables such as
`benutzer`. Create a MySQL database, import your schema, and set the connection
credentials used by the bootstrap/config for your environment.

### 3. Configure Google OAuth (optional login)

Google sign-in reads its credentials from environment variables, or from a local,
untracked secrets file:

```bash
cp app/Views/general/secrets.local.example.php app/Views/general/secrets.local.php
```

Then fill in your values, or set them as environment variables (these take precedence):

```bash
export GOOGLE_CLIENT_ID="your-id.apps.googleusercontent.com"
export GOOGLE_CLIENT_SECRET="your-secret"
export GOOGLE_REDIRECT_URI="http://localhost/01-Olivier/home"
```

`secrets.local.php` is excluded from version control via `.gitignore`.

### 4. Serve the app

Point your web server's document root at the project directory. The included
`.htaccess` rewrites all requests that are not real files or directories to
`index.php?url=...`, which the front controller dispatches to the matching controller
action.

```apache
RewriteEngine on
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*)$ index.php?url=$1 [L,QSA]
```

Open the app in your browser and sign in to reach the dashboard for your role.

## License

Released under the [MIT License](LICENSE) © 2026 Olivier Lüthy. You're free to use, modify and distribute this
software, including commercially, as long as the copyright notice and license are included.

## Author

Built by **Olivier Lüthy** — [GitHub](https://github.com/olivierluethy).
