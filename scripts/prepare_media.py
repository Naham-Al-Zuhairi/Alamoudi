"""Create web derivatives; leave legacy originals untouched."""
from pathlib import Path
from PIL import Image, ImageOps

root = Path(__file__).resolve().parents[1]
source = root / 'legacy/alamoudi/img'
target = root / 'public/media'
target.mkdir(parents=True, exist_ok=True)
for name in ['logo.png', 'logo-light.png', 'team_ar.png', 'team_en.png', 'favicon.png']:
    (target / name).write_bytes((source / name).read_bytes())
(target / 'arabic.ttf').write_bytes((root / 'legacy/alamoudi/fonts/arabic.ttf').read_bytes())
for name in ['slider-1.jpg', 'slider-2.jpg', 'slider-3.jpg', 'page-header.jpg']:
    with Image.open(source / name) as image:
        image = ImageOps.exif_transpose(image).convert('RGB')
        image.thumbnail((1920, 1080))
        image.save(target / (Path(name).stem + '.webp'), 'WEBP', quality=78, method=6)
works = target / 'works'
works.mkdir(exist_ok=True)
for original in (source / 'works').glob('*.jpg'):
    with Image.open(original) as image:
        image = ImageOps.exif_transpose(image).convert('RGB')
        image.thumbnail((1200, 900))
        image.save(works / (original.stem + '.webp'), 'WEBP', quality=78, method=6)
print('Prepared optimized public media')
