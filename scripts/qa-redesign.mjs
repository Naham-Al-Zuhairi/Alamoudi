import { chromium } from 'playwright-core';
import { tmpdir } from 'node:os';
import { join } from 'node:path';

const browser = await chromium.launch({
    headless: true,
    executablePath:
        'C:/Program Files (x86)/Microsoft/Edge/Application/msedge.exe',
});
try {
    for (const [locale, width] of [
        ['ar', 390],
        ['en', 1365],
    ]) {
        const page = await browser.newPage({
            viewport: { width, height: 844 },
        });
        for (const path of ['', '/projects', '/team', '/contact']) {
            const response = await page.goto(
                `http://127.0.0.1:8000/${locale}${path}`,
            );
            await page.locator('h1').first().waitFor();
            const layout = await page.evaluate(() => ({
                width: innerWidth,
                body: document.body.scrollWidth,
                html: document.documentElement.scrollWidth,
                direction: document.documentElement.dir,
            }));
            if (
                response.status() !== 200 ||
                layout.body > layout.width ||
                layout.html > layout.width
            )
                throw new Error(
                    `Page failed or overflowed: ${locale}${path} ${JSON.stringify(layout)}`,
                );
            if (layout.direction !== (locale === 'ar' ? 'rtl' : 'ltr'))
                throw new Error(`Wrong text direction: ${locale}${path}`);
            if (!path) {
                await page.screenshot({
                    path: join(tmpdir(), `alamoudi-home-${locale}.png`),
                    fullPage: true,
                });
                await page.evaluate(async () => {
                    for (let y = 0; y < document.body.scrollHeight; y += 500) {
                        window.scrollTo(0, y);
                        await new Promise((resolve) =>
                            setTimeout(resolve, 110),
                        );
                    }
                    window.scrollTo(0, 0);
                });
                const hidden = await page
                    .locator('.reveal-pending:not(.revealed)')
                    .count();
                if (hidden)
                    throw new Error(
                        `${hidden} sections did not reveal while scrolling`,
                    );
                await page.screenshot({
                    path: join(
                        tmpdir(),
                        `alamoudi-home-revealed-${locale}.png`,
                    ),
                    fullPage: true,
                });
                const current = await page
                    .locator('.slide-controls button[aria-current="true"]')
                    .textContent();
                await page.waitForTimeout(6800);
                const next = await page
                    .locator('.slide-controls button[aria-current="true"]')
                    .textContent();
                if (current === next)
                    throw new Error('Hero did not advance automatically');
                const achievement = await page
                    .locator('.achievement-card p')
                    .textContent();
                await page
                    .getByRole('button', {
                        name: locale === 'ar' ? 'التالي' : 'Next achievement',
                    })
                    .click();
                if (
                    achievement ===
                    (await page.locator('.achievement-card p').textContent())
                )
                    throw new Error('Achievement carousel did not advance');
            }
            if (path === '/contact' && locale === 'en') {
                await page.screenshot({
                    path: join(tmpdir(), 'alamoudi-contact-en.png'),
                    fullPage: true,
                });
                await page.locator('input').first().waitFor();
                if (process.env.QA_SUBMIT_CONTACT === '1') {
                    await page
                        .locator('.contact-form input')
                        .nth(1)
                        .fill('QA Browser');
                    await page
                        .locator('.contact-form input')
                        .nth(2)
                        .fill('qa-browser@example.invalid');
                    await page
                        .locator('.contact-form input')
                        .nth(4)
                        .fill('Website inquiry test');
                    await page
                        .locator('.contact-form textarea')
                        .fill(
                            'This is a browser check of the contact form success message.',
                        );
                    await page
                        .locator('.contact-form button[type="submit"]')
                        .click();
                    await page
                        .getByRole('status')
                        .filter({
                            hasText: 'Your message was sent successfully',
                        })
                        .waitFor();
                }
            }
            console.log(`PASS ${locale}${path || '/'} ${width}px`);
        }
        await page.close();
    }
} finally {
    await browser.close();
}
