#!/usr/bin/env bash
# Creates joywatch-submission.zip for Canvas: all HTML/PHP/CSS/JS/images
# from site/ plus the database scripts. Leaves out config.php (it holds our password).
set -e
cd "$(dirname "$0")/.."
rm -f joywatch-submission.zip
zip -r joywatch-submission.zip site database/01_schema.sql database/02_seed.sql database/titles.csv README.md \
    -x "site/includes/config.php" -x "*.DS_Store"
echo "Created joywatch-submission.zip"
