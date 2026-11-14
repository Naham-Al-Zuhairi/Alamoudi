import { chromium } from 'playwright-core';
const browser = await chromium.launch({headless:true, executablePath:'C:/Program Files (x86)/Microsoft/Edge/Application/msedge.exe'});
for (const [locale,width] of [['ar',390],['en',1365]]) {
  for (const route of ['', '/projects', '/team', '/contact']) {
    const page = await browser.newPage({viewport:{width,height:844}});
    const response = await page.goto(`http://127.0.0.1:8000/${locale}${route}`);
    await page.locator('h1').first().waitFor();
    const data = await page.evaluate(() => ({viewport:innerWidth,body:document.body.scrollWidth,html:document.documentElement.scrollWidth,lang:document.documentElement.lang,dir:document.documentElement.dir,title:document.title}));
    console.log(locale+route, response.status(), JSON.stringify(data));
    if (data.html > data.viewport || data.body > data.viewport) throw new Error(`Horizontal overflow on ${locale}${route}`);
    if (data.dir !== (locale==='ar'?'rtl':'ltr')) throw new Error(`Wrong direction on ${locale}${route}`);
    if (locale==='ar' && route==='/contact') {
      await page.getByRole('button',{name:'القائمة'}).click();
      await page.getByRole('link',{name:'English'}).click();
      await page.waitForURL('**/en/contact');
      if (!page.url().endsWith('/en/contact')) throw new Error('Language switch did not preserve contact page');
      if (await page.locator('form.contact-form input').count() < 4) throw new Error('Contact form fields missing');
    }
    if (!route) await page.screenshot({path:`storage/app/qa-${locale}-${width}.png`});
    await page.close();
  }
}
await browser.close();
