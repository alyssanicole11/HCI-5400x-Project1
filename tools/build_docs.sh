#!/usr/bin/env bash
# Rebuilds the Google-Drive-friendly .docx copies of docs/*.md (needs pandoc + python3).
set -e
cd "$(dirname "$0")/.."
mkdir -p docs/google-drive
for f in docs/*.md; do
    name=$(basename "$f" .md)
    python3 tools/table_widths.py < "$f" \
      | pandoc -f markdown --columns=40 -o "docs/google-drive/$name.docx" --reference-doc=tools/docx-reference.docx
    echo "docs/google-drive/$name.docx"
done
