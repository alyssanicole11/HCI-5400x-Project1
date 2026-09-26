<?php
/**
 * ABOUT  -  REQUIRED BY THE ASSIGNMENT (10 points)
 * "A 1-2 page writeup describing the application and how it was developed.
 *  This writeup is to be an HTML page inside your web application and
 *  accessible through the link 'About' in your application's navigation."
 *
 * STATUS: OUTLINE - replace every [bracketed] note with our own words.
 * OWNER: Partner B writes the first draft; both of us review.
 * This page works whether or not you are logged in.
 */
require __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'About';
$activeNav = 'about';
require __DIR__ . '/includes/header.php';
?>

<article class="prose">
    <h1>About JoyWatch</h1>
    <p class="lead">JoyWatch is a private movie and TV tracker that helps you remember what you've watched,
        keep a list of what you want to see, learn your taste, and decide what to watch tonight.</p>

    <h2>What you can do</h2>
    <ul>
        <li><strong>Library</strong> - [what it does: statuses, ratings, ownership, notes, filters]</li>
        <li><strong>Add titles</strong> - [search our catalog; add a list (if finished)]</li>
        <li><strong>Title Details</strong> - [the decision form: Not yet / Watching / Watched, Interested, rating, watch again]</li>
        <li><strong>Taste Trainer</strong> - [quick one-title-at-a-time questions]</li>
        <li><strong>Discover</strong> - [recommendations with "Why this" and an honest confidence level]</li>
        <li><strong>Tonight</strong> - [mood/time filters, your list first, random pick]</li>
        <li><strong>My Taste</strong> - [stats and genre analysis]</li>
    </ul>

    <h2>How to use it</h2>
    <p>[3-5 steps for a first-time user. Mention the demo account: username <code>demo</code>, password <code>joywatch</code>.]</p>

    <h2>How it works</h2>
    <p>[Plain-English explanation of the pieces:]</p>
    <ul>
        <li><strong>HTML forms</strong> - [list the forms: sign up, log in, search, title decisions, library filters, taste, trainer, tonight, settings]</li>
        <li><strong>PHP</strong> - [validates form data, runs SQL, calculates recommendations and stats; sessions for login]</li>
        <li><strong>MySQL</strong> - [the tables and what each stores; catalog vs personal data]</li>
        <li><strong>Flat file</strong> - [titles.csv is our catalog source, converted into SQL]</li>
        <li><strong>Computation</strong> - [explain the genre score formula, confidence levels, Tonight rules]</li>
        <li><strong>Security</strong> - [hashed passwords, prepared statements, htmlspecialchars, CSRF tokens]</li>
    </ul>

    <h2>How we built it</h2>
    <p>[Our process: started from a Figma prototype of JoyWatch, cut it down to an MVP, weekly Friday
        meetings, who did what, what was hard, what we'd add next.]</p>

    <h2>Team</h2>
    <ul>
        <li>[Name] - [main parts]</li>
        <li>[Name] - [main parts]</li>
    </ul>

    <h2>External resources and AI use</h2>
    <p>The course allows AI tools and external resources if they are listed here.</p>
    <ul>
        <li><strong>Claude (Anthropic)</strong> - used to plan the project scope, design the database, and
            generate the starter code (folder structure, shared includes, login/sign-up, search,
            Title Details, and the CSS starting point). [Update: list anything else AI helped with and
            how we checked it.]</li>
        <li><strong>The Movie Database (TMDb)</strong> - title IDs [and posters/extra data, if we finish the TMDb feature].
            This product uses the TMDb API but is not endorsed or certified by TMDb.</li>
        <li><strong>Figma</strong> - our earlier JoyWatch prototype was the design reference.</li>
        <li>[Any tutorials, e.g. W3Schools PHP/MySQL pages, php.net docs]</li>
    </ul>
</article>

<?php require __DIR__ . '/includes/footer.php'; ?>
