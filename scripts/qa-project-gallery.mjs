import { chromium } from 'playwright-core';
import { join } from 'node:path';

const browser = await chromium.launch({
    headless: true,
    executablePath: 'C:/Program Files (x86)/Microsoft/Edge/Application/msedge.exe',
});
try {
    for (const width of [390, 1365]) {
        const page = await browser.newPage({ viewport: { width, height: 844 } });
        const response = await page.goto('http://127.0.0.1:8000/ar/projects/solar1', { waitUntil: 'domcontentloaded', timeout: 15000 });
        if (response.status() !== 200) throw new Error(`Project page returned ${response.status()}`);
        await page.locator('.project-image-stage img').waitFor();
        const thumbnails = await page.locator('.project-image-strip button').count();
        if (thumbnails > 1) {
            await page.locator('.project-image-strip button').nth(1).click();
            if (await page.locator('.project-image-strip button').nth(1).getAttribute('aria-current') !== 'true')
                throw new Error('Thumbnail selection failed');
        }
        await page.getByRole('button', { name: 'عرض الصورة بالحجم الكامل' }).click();
        const dialog = page.getByRole('dialog', { name: 'عرض صور المشروع' });
        await dialog.waitFor();
        if (thumbnails > 1) {
            const before = await dialog.locator('img').getAttribute('src');
            await dialog.getByRole('button', { name: 'الصورة التالية' }).click();
            if (before === await dialog.locator('img').getAttribute('src')) throw new Error('Next image did not change');
        }
        await page.keyboard.press('Escape');
        if (await dialog.isVisible()) throw new Error('Escape did not close the viewer');
        await page.locator('.project-detail').scrollIntoViewIfNeeded();
        await page.screenshot({ path: join(process.env.TEMP || '.', `alamoudi-project-${width}.png`), fullPage: true });
        const overflow = await page.evaluate(() => document.documentElement.scrollWidth > innerWidth);
        if (overflow) throw new Error(`Horizontal overflow at ${width}px`);
        console.log(`PASS solar1 ${width}px, ${Math.max(thumbnails, 1)} available images`);
        await page.close();
    }
} finally {
    await browser.close();
}
