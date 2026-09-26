# JoyWatch: Personal Movie and TV Tracker

**HCI/ME 5400, Project 1 proposal** · Team: [Name], [Name] · Fall 2026

## What it is
JoyWatch is a private web app that helps people decide what to watch. Users keep a library of movies and TV shows: what they've watched, what they're watching, what they want to see, and what they never want suggested. They rate titles and genres. JoyWatch then analyzes those choices to recommend new titles and to pick something for tonight, explaining *why* each title was suggested. We are rebuilding it from scratch in PHP and MySQL, starting from a Figma prototype one of us designed earlier.

## Main features
- **Accounts:** sign up and log in (hashed passwords, sessions). Each user's data is private.
- **Search and Title Details:** find a title and record *Not yet / Watching / Watched*, *Interested / Unsure / Not interested*, a 5-level rating (Love to Hate), *Watch again?*, owned formats (DVD, Blu-ray, 4K, digital), notes, and dates.
- **Library:** all saved titles, with view tabs (Interested, Watching, Watched, Owned, Unrated, Not interested), search, filters and sort.
- **My Taste:** a genre preference form plus statistics: titles watched, average rating, hours watched, and average rating per genre shown as a bar chart.
- **Taste Trainer:** a quick one-title-at-a-time flow that asks *Watched?* and then asks for a rating or interest level.
- **Discover:** recommendations ranked by a genre-score formula, each with a plain-English "Why this" and an honest confidence level (*Still learning → Established*).
- **Tonight:** filter by mood, time available, and movie/TV. Titles already on the user's list come first. Includes a Random pick with Re-roll.
- **Settings and About:** profile, CSV export, clear data. The About page holds the project write-up.

## How it meets the requirements
| Requirement | JoyWatch |
|:----------------|:----------------------------------------------------------------------|
| HTML pages with forms | About 12 pages and 10+ forms: sign up, log in, search, title decisions, library filters, genre preferences, Taste Trainer, Tonight filters, settings, clear data |
| PHP scripts | Form validation, sessions, dynamic SQL from filters, recommendation scoring, statistics, CSV export |
| External data (MySQL and flat files) | A MySQL database with 6 tables (users, titles, genres, title genres, user titles, genre preferences). A CSV flat file of 116+ movies and shows seeds the catalog |
| Analysis and computation | Per-genre taste scores from ratings and preferences, ranked recommendations with reasons, confidence based on how much data exists, Tonight eligibility rules, and summary statistics |
| UI | Poster cards, consistent navigation, responsive layout for phone and desktop, clear selected states, accessible labels |

**Extra credit (if time allows):** live data from The Movie Database (TMDb) API for posters and search, pasting a whole list of titles at once, bulk editing, and rating actors.

**Tools:** HTML, CSS, a little JavaScript, PHP, and MySQL on Keymaker. AI assistance (Claude, for planning and starter code) and all other resources will be listed on the About page.

**Question for you:** Does this scope fit the project's expectations? Is using the free TMDb API acceptable as the extra-credit external data source?
