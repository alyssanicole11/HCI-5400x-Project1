# JoyWatch — Test Checklist

Run this on the **Keymaker** URL before submitting, at desktop width and at phone width (Chrome DevTools → device toolbar → 390px). Tick each box.

## Accounts
- [ ] Sign up with a new username → lands on Taste genres
- [ ] Sign up with an already-used username → friendly error
- [ ] Log out → log in again → same data
- [ ] Log in as `demo` / `joywatch` works
- [ ] Wrong password → "Username or password is incorrect"
- [ ] Opening `library.php` while logged out → sent to Log in

## Title Details (the main form)
- [ ] Search "toy" → Toy Story → open it
- [ ] Watched + **Meh** + Maybe + DVD + notes → Save → everything is still selected
- [ ] Change only the status to Watching → Save → rating, DVD and notes are all still there
- [ ] Not yet → the "Interested?" question appears; the rating question hides
- [ ] Watched again today → Last watched = today, and the rating is unchanged
- [ ] Remove from Library → confirm → gone from Library
- [ ] `title.php?id=99999` → "Title not found"

## Library
- [ ] Every tab shows the right titles (All, Interested, Watching, Watched, Owned, Finish my ratings, Not interested)
- [ ] Not interested titles do NOT show in "All"
- [ ] Search, Movie/TV and Sort work together
- [ ] A brand new user sees the empty-Library message with buttons

## Taste / Discover / Tonight / Trainer
- [ ] Save genre preferences → reload → still selected
- [ ] Stats numbers match what's in the Library
- [ ] Discover never shows a Not interested title; "Why this" makes sense
- [ ] Confidence says "Still learning" for a new user
- [ ] Tonight shows my Interested titles first; "Not tonight" vs "Not interested" look different
- [ ] Random pick + Re-roll work
- [ ] Taste Trainer: Watched → rating → watch again; Not yet → Interested; Skip; Back

## Everywhere
- [ ] "About" is in the navigation on every page, logged in and logged out
- [ ] No "🚧" messages left on the final site, or unfinished pages are hidden from the nav
- [ ] Typing `<b>hi</b>` into Notes shows the text literally (it doesn't turn bold)
- [ ] `DEBUG` is `false` in the final `config.php`
- [ ] Phone: bottom navigation never covers buttons, and nothing is cut off
