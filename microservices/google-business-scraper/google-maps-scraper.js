const puppeteer = require('puppeteer-extra')
var mysql = require('mysql');
require('dotenv').config({ path: '../../.env' });
const RecaptchaPlugin = require('puppeteer-extra-plugin-recaptcha');

puppeteer.use(
  RecaptchaPlugin({
    provider: { id: '2captcha', token: '1be613256e80c89cb207b6c157feb73e' },
    visualFeedback: true // colorize reCAPTCHAs (violet = detected, green = solved)
  })
)

var args=require('minimist')(process.argv.slice(2),{string:"url"});
var url = args.url;
var limit = parseInt(process.argv[3]);
var jobId = parseInt(process.argv[4]);
console.log(url);

var con = mysql.createConnection({
    host: process.env.DB_HOST,
    port: process.env.DB_PORT,
    user: process.env.DB_USERNAME,
    password: process.env.DB_PASSWORD,
    database: process.env.DB_DATABASE,
    connectionLimit: 10
});

con.connect(function(err) {
    if (err) throw err;
    console.log("DB Connected!");
});


async function getBusinessis() {

    const browser = await puppeteer.launch({
        headless: true,
        defaultViewport: null
    });

    const page = await browser.newPage()
    await page.setViewport({
        width: 1200,
        height: 768
    });

    await page.setDefaultNavigationTimeout(0);

    await page.goto(url);
    await page.solveRecaptchas();

    var allItems = [];
    while(allItems.length <= limit)
    {
        if (await page.$('.V79n2d-di8rgd-aVTXAb-title')) {
            break;
        }

        for (let j = 1; j <= 5; j++) {

            await page.waitForTimeout(5000);

            await page.evaluate(() => {
                const container = document.querySelector('#pane > div > div.Yr7JMd-pane-content.cYB2Ge-oHo7ed > div > div > div.siAUzd-neVct.section-scrollbox.cYB2Ge-oHo7ed.cYB2Ge-ti6hGc.siAUzd-neVct-Q3DXx-BvBYQ > div.siAUzd-neVct.section-scrollbox.cYB2Ge-oHo7ed.cYB2Ge-ti6hGc.siAUzd-neVct-Q3DXx-BvBYQ');
                container.scrollTop = container.scrollHeight;
            });
            // await page.evaluate(() => {
            //     const scrollableSection = document.getElementsByClassName(
            //         'section-layout section-scrollbox cYB2Ge-oHo7ed cYB2Ge-ti6hGc siAUzd-neVct-Q3DXx-BvBYQ'
            //     );
            //     scrollableSection[1].scrollTop = scrollableSection[1].scrollHeight;
            // });
        }

        var items = await page.$$eval('.V0h1Ob-haAclf a', as => as.map(a => a.href));
        allItems = allItems.concat(items);

        await page.waitForTimeout(5000);
        if (await page.$('#ppdPk-Ej1Yeb-LgbsSe-tJiF1e[disabled]') == null) {
            await page.click('#ppdPk-Ej1Yeb-LgbsSe-tJiF1e');
        }else{
            break;
        }

    }

    for (let k = 0; k < allItems.length; k++) {

        await page.waitForTimeout(5000);
        await page.goto(allItems[k]);
        await page.solveRecaptchas();

        await page.waitForTimeout(5000);
        if (await page.$('div.x3AX1-LfntMc-header-title-ij8cu > div > h1') !== null) {
            var titleElement = await page.$('div.x3AX1-LfntMc-header-title-ij8cu > div > h1');
            var title = await page.evaluate(el => el.textContent, titleElement);
            console.log('title', title);
        }

        if (await page.$('div.QSFF4-text.gm2-body-2') !== null) {
            var addressElement = await page.$('div.QSFF4-text.gm2-body-2');
            var address = await page.evaluate(el => el.textContent, addressElement);
            console.log('address', address);
        }

        if (await page.$('div.rogA2c.HY5zDd > div.QSFF4-text.gm2-body-2') !== null) {
            var websiteElement = await page.$('div.rogA2c.HY5zDd > div.QSFF4-text.gm2-body-2');
            var website = await page.evaluate(el => el.textContent, websiteElement);
            console.log('website', website);
        }

        if (await page.$('[data-item-id^="phone"]') !== null) {
            var phoneElement = await page.$('[data-item-id^="phone"]');
            var phone = await page.evaluate(el => el.innerText, phoneElement);
           console.log('phone', phone);
        }

        var sql = "INSERT INTO scraper_data (scraper_job_id, company, phone, address, website, created_at, updated_at) VALUES ?";
        var values = [[jobId, title, phone, address, website, new Date(), new Date()]];
        con.query(sql, [values], function (err, result) {
            if (err) {
                console.log(err);
            }else{
                console.log("Number of records inserted: " + result.affectedRows);
            }
        });
    }
    process.exit();

}

getBusinessis();
