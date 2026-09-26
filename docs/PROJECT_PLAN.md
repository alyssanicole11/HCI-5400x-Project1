# JoyWatch — Project 1 Plan

**Course:** HCI/ME 5400, Fall 2026 · **Assignment:** Project #1 – Build a Dynamic Web Service
**Due:** Friday, Oct 23, 2026, 11:59 PM (100 points + extra credit)
**Submit:** (1) a ZIP of all HTML/PHP code and images, and (2) the Keymaker URL of the start page. The site must work for the professor "as a typical user".

---

## 1. What the assignment asks for, and how JoyWatch meets it

| Rubric item | Pts | What it means | How JoyWatch covers it |
|---|---:|---|---|
| **Project write-up** | 10 | A 1–2 page HTML page inside the app, reached from an **"About"** link in the navigation. It describes the app and how it was built, and it lists external resources and AI use. | `about.php`, linked in the header on every page. The outline is already written with the headings to fill in. |
| **HTML pages, including forms** | 10 | Real pages and real forms. | 11 forms (list below), plus about 12 pages. |
| **PHP code** | 40 | The biggest item. Custom PHP that does real work. | Login sessions, validating and saving form data, building SQL from filters, recommendation scoring, stats, CSV export. |
| **External data source (MySQL and/or flat files)** | 20 | Store and read data outside the page. | A MySQL database with 6 tables. `titles.csv` is the flat-file source for the catalog. TMDb API is optional extra credit. |
| **UI** | 20 | Graphics, text formatting, navigation. | Poster cards, colored placeholders, pill buttons, a responsive layout with a bottom nav on phones, and a consistent header and footer. |
| **Extra credit** | + | "Advanced UI, use of data, innovative functions, advanced PHP functions." | Recommendations with "Why this" and confidence, TMDb API posters/search, bulk edit, CSV export, dark mode, accessibility. |

The assignment also lists these goals, and each one maps to a part of JoyWatch:

- *"Create a useful and dynamic web application."* JoyWatch is a full app with accounts, not a one-time calculator.
- *"Effective user interface to gather data."* Title Details, Taste Trainer and the genre form are all built to collect data quickly.
- *"Use an external data source in conjunction with the collected user data."* The shared movie catalog (MySQL + CSV, optionally TMDb) is combined with each user's own ratings and decisions.
- *"Perform data analysis / computation and return results."* This covers the Taste stats, genre scores, Discover ranking, Tonight picks and confidence level.

> The assignment recommends checking the idea with the professor **before coding**. Email the one-page summary (`TEACHER_SUMMARY.docx`) to Prof. Winer (ewiner@iastate.edu). He says he doesn't check Canvas or Teams messages. Or bring it to office hours (Mon 4–5, Thu 3–4).

---

## 2. Every form in the app

"Form" means an HTML `<form>` the user fills in and submits to PHP.

| # | Form | Page | Method | What PHP does with it | Status in starter |
|---|---|---|---|---|---|
| 1 | Sign up | `register.php` | POST | Validates input, hashes the password, INSERTs into `users`, logs the user in | ✅ working |
| 2 | Log in | `login.php` | POST | Checks the password with `password_verify`, starts the session | ✅ working |
| 3 | Search titles | `search.php` | GET | Runs `LIKE` search on the catalog and marks titles already in the Library | ✅ working |
| 4 | **Title decisions** (status, interest, rating, watch again, owned formats, notes, last watched) | `title.php` | POST | Validates every field and saves without erasing other fields. Also has "Watched again" and "Remove" | ✅ working (the reference page) |
| 5 | Library filters (view tabs, search, type, sort) | `library.php` | GET | Builds the `WHERE` / `ORDER BY` from the filters | 🟡 tabs work; search, type and sort are TODO |
| 6 | Genre preferences (Love…Hate per genre) | `taste.php` | POST | Upserts `user_genre_prefs` | 🟡 displays; saving is TODO |
| 7 | Taste Trainer (Watched? → rating → watch again / Interested?) | `trainer.php` | POST | Saves one answer and picks the next title | ⬜ stub + algorithm |
| 8 | Tonight filters (mood, type, time, random) | `tonight.php` | GET | Runs the pick rules and returns a short list | 🟡 form built; results TODO |
| 9 | Profile settings | `settings.php` | POST | UPDATEs `users` | 🟡 displays; saving is TODO |
| 10 | Clear my data (type CLEAR to confirm) | `settings.php` | POST | DELETEs the user's rows | 🟡 displays; TODO |
| 11 | Add a list (paste many titles) | `add-list.php` | POST | Parses, matches, groups, then a review step | ⬜ stretch |

Discover's Seen it / Interested / Not interested buttons are also small POST forms. Export is a download link, not a form.

---

## 3. Scope: what we keep, simplify, or cut from the Figma prototype

JoyWatch's full spec was written for a Supabase/React app. For a beginner-friendly PHP class project due in 4 weeks, here is the plan.

### MVP: must work by **Fri Oct 9** (enough to pass on its own)
- Sign up, log in, log out (sessions and hashed passwords)
- Search the catalog, then open Title Details
- Title Details form with all personal fields, saved without erasing other fields
- Library with view tabs, search, type filter and sort
- Taste page with the genre preference form and "at a glance" stats
- About page (first draft)
- Styled header and nav, working on phone and desktop
- Running on Keymaker with the database imported

### Version 1: target **Fri Oct 16** (this is where the "computation" points come from)
- `recommend.php`: genre scores, title scoring, "Why this", confidence label
- **Discover**: one card at a time with Why this and confidence
- **Taste Trainer**: quick one-at-a-time questions
- **Tonight**: personal list first, then suggestions, mood and time filters, random pick with Re-roll
- Taste page "Your genres" analysis table with bars
- Settings: save profile, export CSV, clear data

### Stretch goals (extra credit), only after V1 works
- **TMDb API**: real posters, live search that adds new titles to our catalog, trailers. *First check `setup-check.php` on Keymaker; the school server may block outbound internet.*
- Add a list (paste many titles, review groups)
- Library quick-status buttons on cards; grid/list toggle; select multiple → Edit selected (bulk edit)
- Rate actors (people tables are sketched in `01_schema.sql`)
- Import a CSV backup

### Simplified or cut (and why)
| Spec feature | What we do instead | Why |
|---|---|---|
| Supabase auth + row-level security | PHP sessions, and every query says `WHERE user_id = ?` | Same protection, and it's the class's stack |
| Offline durable write queue, retry, ordering | Normal form submit → save → redirect | Not needed for a server-rendered PHP site |
| JSON import with Merge/Replace transactions | CSV export only; import is a stretch goal | Too much for the time we have |
| Streaming providers / "where to watch", region, languages | Cut (maybe via TMDb stretch) | Needs live provider data |
| Continuous paging of new candidates | Our catalog is finite (116+ titles), so we show an honest "you've seen everything" message | No live catalog unless TMDb works |
| Swipe carousel animations | Each step is a page load with big buttons | Works without JavaScript and is easy to understand |
| Household profiles, social features | Cut | Deferred in the spec too |
| Onboarding wizard | After sign up, go straight to the genre form on the Taste page | Same idea, one page |

### Rules we keep from the spec (they cost nothing and show care)
- `NULL` = "not answered", so **Meh = 0 is a real rating** (never use `empty()` on a rating).
- **Not interested** titles never appear in Trainer, Discover or Tonight.
- Changing one field never erases the others (`save_user_title()` does this for us).
- A title's identity is media type + TMDb ID (a movie and a show can share a name).
- Confidence stays "Still learning" when there is little data. We never show a fake "100%".
- The same words everywhere: *Watching, Watched, Interested, Not interested, Taste Trainer, Random pick.*

---

## 4. The data

See `DATABASE.md` for every column and some example rows. In short:

```
users ──< user_titles >── titles ──< title_genres >── genres
  └────< user_genre_prefs >───────────────────────────┘
```
- **Catalog (same for everyone):** `titles`, `genres`, `title_genres`. It comes from `database/titles.csv` (edit it in Google Sheets). `tools/build_seed.php` converts the CSV into `02_seed.sql`.
- **Personal (per user):** `user_titles` (one row per title the user has any opinion on) and `user_genre_prefs`.
- **Demo account:** `demo` / `joywatch` already has 13 titles, so the professor sees a full app right away.

---

## 5. Risks and open questions (to ask in class this week)
1. **Keymaker PHP and MySQL versions.** The starter needs PHP 7.4+ and PDO MySQL. Upload the files and open `setup-check.php`; it shows what works.
2. **How do we upload files?** (SFTP, a web file manager, or a mapped drive?) Is phpMyAdmin available for importing the `.sql` files?
3. **One Keymaker account for the group, or one each?** Which URL do we submit?
4. **Can Keymaker reach the internet** (for TMDb)? `setup-check.php` tests this. If not, TMDb extra credit is out; everything else still works.
5. **Is an unused database table or stub page a problem?** No. Before submitting, just remove "🚧" stubs we didn't finish, or hide them from the nav.

---

## 6. Suggested build order (each step works on its own)
1. Get the starter running on Keymaker (`setup-check.php` all green, demo login works).
2. Finish the Library TODOs → Taste form saving → Taste stats.
3. `recommend.php` functions (write and test with the demo account).
4. Discover → Tonight → Taste Trainer.
5. Settings, export and clear data.
6. About page final, remove stubs, set `DEBUG` to false, zip and submit.
7. Stretch goals in any leftover time.
