"""Rewrites markdown table separator rows so pandoc gives each column a width
proportional to its content (used by build_docs.sh; the .md files are unchanged)."""
import re, sys

lines = sys.stdin.read().split('\n')
is_row = lambda l: l.strip().startswith('|') and l.strip().endswith('|')
is_sep = lambda l: is_row(l) and re.fullmatch(r'\|[\s:\-|]+\|', l.strip()) is not None
cells = lambda l: [c.strip() for c in l.strip()[1:-1].split('|')]

i = 0
while i < len(lines):
    if i > 0 and is_sep(lines[i]) and is_row(lines[i - 1]):
        j = i + 1
        while j < len(lines) and is_row(lines[j]):
            j += 1
        rows = [cells(lines[i - 1])] + [cells(l) for l in lines[i + 1:j]]
        n = len(rows[0])
        widths = []
        for c in range(n):
            col = [re.sub(r'[*`]', '', r[c]) if c < len(r) else '' for r in rows]
            longest_word = max((len(w) for text in col for w in text.split()), default=4)
            average = sum(len(t) for t in col) / len(col)
            # never narrower than the longest word; long text columns get more room
            widths.append(max(longest_word * 1.4 + 3, min(average, 45)))
        total = sum(widths)
        seps = []
        for c, old in enumerate(cells(lines[i])):
            dashes = '-' * max(3, round(100 * widths[c] / total))
            seps.append((':' if old.startswith(':') else '') + dashes + (':' if old.endswith(':') and len(old) > 1 else ''))
        lines[i] = '|' + '|'.join(seps) + '|'
        i = j
    else:
        i += 1
print('\n'.join(lines))
