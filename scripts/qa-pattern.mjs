import { chromium } from 'playwright-core';
import { tmpdir } from 'node:os';
import { join } from 'node:path';

const browser = await chromium.launch({
    headless: true,
    executablePath:
        'C:/Program Files (x86)/Microsoft/Edge/Application/msedge.exe',
});
try {
    for (const path of ['/ar', '/ar/projects', '/ar/team', '/ar/contact']) {
        const page = await browser.newPage({
            viewport: { width: 1456, height: 900 },
        });
        await page.goto('http://127.0.0.1:8000' + path);
        const sample = await page.evaluate(() => {
            const section = document.querySelector(
                location.pathname.endsWith('/contact')
                    ? '.contact-section'
                    : location.pathname.endsWith('/projects')
                      ? '.projects-listing'
                      : location.pathname.endsWith('/team')
                        ? '.team-section'
                        : '.about-section',
            );
            if (!section) throw new Error('Pattern target missing');
            const styles = getComputedStyle(section);
            return {
                image: styles.backgroundImage,
                color: styles.backgroundColor,
            };
        });
        if (!sample.image.includes('data:image/png;base64,'))
            throw new Error(
                path +
                    ' has no embedded dot background: ' +
                    JSON.stringify(sample),
            );
        console.log(path, { embeddedPattern: true, color: sample.color });
        if (path.endsWith('/contact')) {
            await page.locator('.contact-section').scrollIntoViewIfNeeded();
            await page.waitForTimeout(900);
            await page.locator('.contact-section').screenshot({
                path: join(tmpdir(), 'alamoudi-pattern-contact.png'),
            });
        }
        await page.close();
    }
} finally {
    await browser.close();
}
