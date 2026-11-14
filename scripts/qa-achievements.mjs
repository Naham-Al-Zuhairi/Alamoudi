import { chromium } from 'playwright-core';
import { join } from 'node:path';
import { tmpdir } from 'node:os';

const browser = await chromium.launch({ headless: true, executablePath: 'C:/Program Files (x86)/Microsoft/Edge/Application/msedge.exe' });
try {
    const page = await browser.newPage({ viewport: { width: 1365, height: 900 } });
    await page.goto('http://127.0.0.1:8000/ar', { waitUntil: 'domcontentloaded', timeout: 15000 });
    const section = page.locator('.achievements-section');
    await section.scrollIntoViewIfNeeded();
    await page.waitForTimeout(900);
    const image = await section.evaluate((element) => getComputedStyle(element).backgroundImage);
    if (image.includes('data:image/png')) throw new Error('Achievements still has the dot pattern');
    const before = await page.locator('.achievement-card p').textContent();
    await page.getByRole('button', { name: 'التالي' }).click();
    await page.waitForTimeout(800);
    if (before === await page.locator('.achievement-card p').textContent()) throw new Error('Achievement did not change');
    await section.screenshot({ path: join(tmpdir(), 'alamoudi-achievements.png') });
    console.log('Achievement carousel, plain background and next control passed.');
} finally {
    await browser.close();
}
