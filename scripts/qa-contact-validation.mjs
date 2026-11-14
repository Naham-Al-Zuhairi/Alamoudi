import { chromium } from 'playwright-core';

const browser = await chromium.launch({ headless: true, executablePath: 'C:/Program Files (x86)/Microsoft/Edge/Application/msedge.exe' });
try {
    const page = await browser.newPage();
    await page.goto('http://127.0.0.1:8000/ar/contact', { waitUntil: 'domcontentloaded', timeout: 15000 });
    await page.locator('.contact-form input').nth(1).fill('Validation QA');
    await page.locator('.contact-form input').nth(2).fill('validation@example.invalid');
    await page.locator('.contact-form input').nth(4).fill('Validation check');
    await page.locator('.contact-form textarea').evaluate((element) => element.removeAttribute('minlength'));
    await page.locator('.contact-form textarea').fill('short');
    await page.locator('.contact-form button[type="submit"]').click();
    const summary = page.locator('.contact-form .form-error-summary');
    await summary.waitFor();
    if (!(await summary.textContent()).includes('الرسالة')) throw new Error('Message field was not identified');
    const belowButton = await summary.evaluate((element) => element.previousElementSibling?.matches('button[type="submit"]'));
    if (!belowButton) throw new Error('Error summary is not below the submit button');
    console.log('Contact validation feedback identifies the message field below the form button.');
} finally {
    await browser.close();
}
