# JoyWatch — Team Plan

Two people, four weeks, one working web app.

- **Partner A** has coding experience. Replace "Partner A" with your name.
- **Partner B** is new to coding. Replace "Partner B" with her name.

The owner of each page is also written at the top of that page's PHP file (`OWNER:`).

---

## 1. Who does what

The split is built so that **Partner B owns real, gradable parts of the project** (UI, data, stats, forms, the write-up). Each task copies a pattern that already works somewhere in the starter code. Partner A owns the trickier logic and reviews everything.

### Partner A (experienced): foundation, recommendations, deployment
| Task | Files | Target |
|---|---|---|
| Keymaker setup: upload, import SQL, `setup-check.php` all ✅ | – | Oct 2 |
| Walk Partner B through the starter code (Friday meeting) | – | Oct 2 |
| Recommendation functions: genre scores, scoring, reasons, confidence | `includes/recommend.php` | Oct 13 |
| Discover page | `discover.php` | Oct 14 |
| Tonight results + Random pick | `tonight.php` | Oct 16 |
| Taste Trainer | `trainer.php` | Oct 18 |
| Code review of Partner B's pages (explain each change) | – | weekly |
| Final zip, `DEBUG` off, submission | – | Oct 22 |
| Stretch: TMDb posters/search, Add a list, bulk edit | `includes/tmdb.php`, `add-list.php`, `library.php` | if time |

### Partner B (new to coding): pages, data, UI, write-up
| Task | Files | Pattern to copy | Target |
|---|---|---|---|
| Grow the movie catalog in Google Sheets (add favorites, check genres) | `database/titles.csv` | the existing rows | Oct 6 |
| Library: search box, Movie/TV filter, Sort | `library.php` (TODO 1–3) | the "view" filter right above them | Oct 8 |
| Save the genre preferences form (pair with A) | `taste.php` (TODO 1) | `title.php` saving | Oct 9 |
| About page first draft | `about.php` | outline is there | Oct 9 |
| Taste "At a glance" numbers | `taste.php` (TODO 2) | `index.php` stats query | Oct 13 |
| Settings: save profile + clear data | `settings.php` | `register.php` | Oct 15 |
| Export CSV | `export.php` | step-by-step comments in the file | Oct 16 |
| Taste "Your genres" table with bars | `taste.php` (TODO 3) | SQL is given in `recommend.php` | Oct 18 |
| Colors and fonts from Figma → CSS variables | `css/style.css` (section 1) | – | Oct 18 |
| Test checklist on phone and desktop, screenshots for About | `docs/TEST_CHECKLIST.md` | – | Oct 21 |
| About page final | `about.php` | – | Oct 21 |

**Both of us:** we must each be able to explain every file. The professor may ask either of us about any part.

---

## 2. Calendar

Today is Sat Sep 26. Classes are Tue/Thu. Office hours are Mon 4–5 and Thu 3–4. **Team meetings are Fridays at 4.**

| Week | Dates | Goal | Friday 4:00 meeting |
|---|---|---|---|
| **0: Set up** | Sep 26 – Oct 2 | Email the teacher summary. Learn Keymaker in class. Starter running on Keymaker. B does the learning path (section 4). | **Oct 2:** A walks through the starter (30 min). B makes a first change live (edit the home page text). Confirm Keymaker answers. Assign week 1. |
| **1: MVP** | Oct 3 – Oct 9 | Library filters, Taste form save, About draft, bigger catalog | **Oct 9:** Demo the MVP on Keymaker. Pair-program anything stuck. Assign week 2. |
| **2: Smart features** | Oct 10 – Oct 16 | `recommend.php`, Discover, Tonight, Settings, Export, Taste stats | **Oct 16:** Feature freeze for core features. Everything is on Keymaker. Pick stretch goals. |
| **3: Polish and submit** | Oct 17 – Oct 23 | Trainer, genre bars, Figma styling, testing, About final | **Submit Thu Oct 22 night** (a day of buffer). **Fri Oct 23 at 4:** final check on Keymaker. Hard deadline is 11:59 PM. |

**Optional second weekly meeting:** a short Tuesday or Wednesday check-in (30 min, can be online) to unblock things mid-week. The professor's online interactive time on **Tuesday 7–8 PM** is also a good place to ask questions, and it counts toward participation.

### Friday meeting agenda (copy into Drive each week)
1. Demo: each person shows what works on Keymaker (10 min)
2. Blockers: what's stuck? Fix it together or pair-program (20 min)
3. Teach-back: one concept, explained by whoever built it (10 min)
4. Next week: pick tasks from the tables above and update the tracker (10 min)
5. Log any AI help used this week in the AI & resources log (2 min)

---

## 3. How we share work (Google Drive + Keymaker)

### Google Drive folder
```
JoyWatch – Project 1/
  01 Planning/            PROJECT_PLAN, TEAM_PLAN, Teacher summary (as Google Docs)
  02 Meeting notes/       one Doc per Friday (use the agenda above)
  03 Catalog data/        "JoyWatch titles" Google Sheet (source of titles.csv)
  04 Testing & screenshots/
  05 Code snapshots/      joywatch-YYYY-MM-DD.zip every Friday (a backup)
  06 Submission/          final zip + the URL we submitted
  AI & resources log      (Doc) what tool/site helped, with what, and how we checked it
```
The `.md` files open in Google Drive as plain text. The `.docx` copies in `docs/google-drive/` convert into Google Docs (right-click → Open with → Google Docs).

### Code: avoiding overwriting each other
1. **One official copy** lives on Keymaker (in the account the group will submit from) and in the GitHub repo.
2. **Each person owns specific files** (the tables above). Only edit files you own. If you need to change someone else's file, ask first.
3. **Practice copy:** if we each have our own Keymaker space, Partner B keeps a personal copy there with its own database, so mistakes never break the team site.
4. Partner B sends finished files to Partner A (Drive `05 Code snapshots/` or GitHub Desktop, whichever is easier). Partner A reviews them, explains any changes, and updates the official copy.
5. Never upload `includes/config.php` to Drive or GitHub, because it has the database password. Each copy has its own.

### Suggested tools for Partner B
- **VS Code** (free editor that colors code and shows mistakes) with the "PHP Intelephense" extension
- An **SFTP/file upload** method, which we'll learn in class for Keymaker
- The browser, plus **phpMyAdmin** to see the database tables

---

## 4. Learning path for Partner B (about 5–6 hours total, week 0)

Do these in order and skip anything already familiar.
1. **HTML forms**: W3Schools "HTML Forms" and "HTML Input Types" (30 min)
2. **PHP basics**: W3Schools PHP Intro → Syntax → Variables → Echo → If…Else → Arrays → Loops → Functions (2 hrs)
3. **PHP forms**: W3Schools "PHP Form Handling" and "Form Validation" (45 min)
4. **SQL**: W3Schools SQL SELECT, WHERE, ORDER BY, INSERT, UPDATE, DELETE, COUNT/AVG/SUM, GROUP BY, JOIN (1.5 hrs). Try the queries on our tables in phpMyAdmin.
5. **Read our code in this order**, with comments turned into questions for Friday:
   `index.php` → `includes/header.php` → `register.php` → `includes/db.php` → `title.php` → `library.php`

**The one pattern to learn.** Every page in JoyWatch works like this:
```
require bootstrap           ← turn the app on
if the form was submitted:  ← is_post()
    read the fields         ← post_param('name')
    check they're valid     ← if (...) $errors[] = '...'
    save to the database    ← db_run('UPDATE ... WHERE user_id = ?', [...])
    redirect with a message ← flash('Saved!'); redirect('page.php')
load data for the page      ← db_all('SELECT ...')
include header
print HTML, with h() around every value
include footer
```

**Using AI while learning:** allowed in this course, but it must be listed on the About page. It helps most when you ask it to *explain* a line of our code. Then try writing the next part yourself.

---

## 5. Definition of done (for any task)
- [ ] Works on Keymaker, not just on one computer
- [ ] Works when logged in as `demo` **and** as a brand new user with an empty Library
- [ ] Looks fine at phone width (Chrome DevTools → phone icon → 390px)
- [ ] Every value printed on the page goes through `h()`, and every SQL value goes through `?` placeholders
- [ ] You can explain what every line does
- [ ] "🚧" TODO message removed from the page
