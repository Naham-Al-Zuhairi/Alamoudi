"""Read legacy PHP as text; never execute it. Emit a reviewable JSON content snapshot."""
import html
import json
import re
from pathlib import Path

root = Path(__file__).resolve().parents[1]
source = (root / 'legacy/alamoudi/language.php').read_text(encoding='utf-8')
blocks = {}
for match in re.finditer(r'\$(\w+)\s*=\s*array\(', source):
    start = match.end()
    end = source.find('\n);', start)
    if end < 0:
        end = source.find('\n    );', start)
    chunk = source[start:end]
    locale_data = {}
    for locale in ('en', 'ar'):
        found = re.search(r"['\"]" + locale + r"['\"]\s*=>\s*array\(", chunk)
        if not found:
            continue
        rest = chunk[found.end():]
        next_locale = re.search(r"['\"](?:en|ar)['\"]\s*=>\s*array\(", rest)
        if next_locale:
            rest = rest[:next_locale.start()]
        entries = {}
        for entry in re.finditer(r"['\"]([^'\"]+)['\"]\s*=>\s*(['\"])(.*?)\2\s*[,\n]", rest, re.S):
            value = re.sub(r'<br\s*/?>', ' ', entry.group(3), flags=re.I)
            value = re.sub(r'<[^>]+>', ' ', value)
            entries[entry.group(1)] = ' '.join(html.unescape(value).split())
        locale_data[locale] = entries
    blocks[match.group(1)] = locale_data

out = root / 'database/seeders/legacy-content.json'
out.write_text(json.dumps(blocks, ensure_ascii=False, indent=2), encoding='utf-8')
print(f'Extracted {len(blocks)} bilingual content groups to {out}')
