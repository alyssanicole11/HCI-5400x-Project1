# JoyWatch: HCI/ME 5400 Project 1

JoyWatch is a private movie and TV tracker built with **HTML, CSS, PHP and MySQL**. You record what you've watched, what you want to watch, and what you think of it. JoyWatch analyzes those choices to recommend titles and help you pick something for tonight.

**Due:** Fri Oct 23, 2026, 11:59 PM. Submit a ZIP of the code and the Keymaker URL.

## Start here
| If you want to… | Read |
|---|---|
| Understand the assignment, the forms, and what's in or out of scope | [`docs/PROJECT_PLAN.md`](docs/PROJECT_PLAN.md) |
| See who does what, the calendar, and how we share work | [`docs/TEAM_PLAN.md`](docs/TEAM_PLAN.md) |
| Understand the database | [`docs/DATABASE.md`](docs/DATABASE.md) |
| Send the professor a one-page summary | [`docs/google-drive/TEACHER_SUMMARY.docx`](docs/google-drive/TEACHER_SUMMARY.docx) |
| Test before submitting | [`docs/TEST_CHECKLIST.md`](docs/TEST_CHECKLIST.md) |

Every doc also has a `.docx` copy in `docs/google-drive/` for Google Drive.

## Folder map
```
site/                  ← EVERYTHING in here gets uploaded to Keymaker and zipped for Canvas
  index.php            home page / dashboard                  ✅ working
  register.php         sign-up form                           ✅ working
  login.php, logout.php                                       ✅ working
  search.php           Add titles: search the catalog         ✅ working
  title.php            Title Details + decision form          ✅ working (the reference page)
  library.php          Library with view tabs and filters     🟡 tabs work, filters TODO
  taste.php            genre preferences + stats              🟡 form shows, save/stats TODO
  settings.php         profile, export, clear data            🟡 forms show, saving TODO
  tonight.php          Tonight filters + picks                🟡 form built, results TODO
  discover.php         recommendations with "Why this"        ⬜ stub
  trainer.php          Taste Trainer                          ⬜ stub
  export.php           CSV download                           ⬜ stub
  add-list.php         paste a list of titles                 ⬜ stretch
  about.php            REQUIRED write-up page                 🟡 outline to fill in
  setup-check.php      open after uploading: shows what works ✅
  includes/            shared PHP (config, database, login, helpers, header/footer)
  css/style.css        all styles; colors are at the top
  js/app.js            small script that shows/hides questions on Title Details
  images/              logo
database/
  01_schema.sql        creates the tables (import first)
  02_seed.sql          catalog + demo user (import second; GENERATED)
  titles.csv           the catalog as a spreadsheet (source of 02_seed.sql)
tools/                 developer scripts (not uploaded)
docs/                  planning documents
```
Every PHP file starts with a comment block that says what the page does, its status, who owns it, and step-by-step TODOs.

## Set up on Keymaker
The exact steps depend on what we learn in class. The general idea:
1. Create the MySQL database, then open **phpMyAdmin** → Import `database/01_schema.sql`, then `database/02_seed.sql`.
2. Copy `site/includes/config.sample.php` to `site/includes/config.php` and fill in the database login.
3. Upload the **contents** of `site/` to the web folder (e.g. `public_html/joywatch/`).
4. Open `…/joywatch/setup-check.php`. Every row should be ✅ (the TMDb rows are optional).
5. Open `…/joywatch/` and log in as **demo** / **joywatch**.

## Run it on your own computer (optional, for Partner A)
You need PHP 7.4+ and MySQL or MariaDB.
```bash
mysql -u root -e "CREATE DATABASE joywatch"
mysql -u root joywatch < database/01_schema.sql
mysql -u root joywatch < database/02_seed.sql
cp site/includes/config.sample.php site/includes/config.php   # then edit it
php -S localhost:8080 -t site
```

## Handy scripts
- `php tools/build_seed.php` rebuilds `02_seed.sql` after `titles.csv` changes
- `bash tools/make_zip.sh` creates `joywatch-submission.zip` for Canvas (code + images; leaves out config.php)
- `bash tools/build_docs.sh` rebuilds the `.docx` copies of the docs (needs pandoc)

## Rules we follow in the code
- Put `?` placeholders in all SQL (`db_all('... WHERE id = ?', [$id])`). Never glue user input into SQL.
- Wrap every printed value in `h()` so user text can't inject HTML.
- Every personal query includes `WHERE user_id = ?` for the logged-in user.
- Rating **0 = Meh** is a real rating. Check `=== null`, never `empty()`.
- Save with `save_user_title()` so changing one field never erases the others.
