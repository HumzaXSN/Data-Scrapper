const puppeteer = require('puppeteer');

var mysql = require('mysql');
require('dotenv').config({ path: '../../.env' });

var con = mysql.createConnection({
    host: process.env.DB_HOST,
    port: process.env.DB_PORT,
    user: process.env.DB_USERNAME,
    password: process.env.DB_PASSWORD,
    database: process.env.DB_DATABASE,
    connectionLimit: 10
});

con.connect(function (err) {
    if (err) throw err;
    console.log("DB Connected!");
});

async function autoScroll(page) { // scroll down
    await page.evaluate(async () => {
        await new Promise((resolve, reject) => {
            var totalHeight = 0;
            var distance = 300;
            var timer = setInterval(() => {
                const element = document.querySelectorAll('#pane > div > div:nth-child(1) > div > div > div:nth-child(2) > div:nth-child(1)')[0];
                var scrollHeight = element.scrollHeight;
                element.scrollBy(0, distance);
                totalHeight += distance;

                if (totalHeight >= scrollHeight) {
                    clearInterval(timer);
                    resolve();
                }
            }, 100);
        });
    });
}

async function parsePlaces(page) { // parse results from page
    let places = [];

    const elements = await page.$$('#pane > div > div > div > div > div > div > div > div > div > div > div > div > div > div > div:nth-child(1) > div > span');
    if (elements && elements.length) {
        for (const el of elements) {
            const name = await el.evaluate(span => span.textContent);
            places.push({ name });
        }
    }
    return places;
}

async function goToNextPage(page) { // go to next page
    await page.click('button[aria-label=" Next page "]');
}

async function hasNextPage(page) { // check if there is a next page
    const element = await page.$('button[aria-label=" Next page "]');
    if (!element) {
        throw new Error('No next page');
    }

    const disabled = await page.evaluate((el) => el.getAttribute('disabled'), element);
    if (disabled) {
        console.log('Next page disabled');
    }

    return !disabled;
}


async function parseLinks(page) { //parse links
    const elements = await page.$$('#pane > div > div > div > div > div > div > div > div > a');
    if (elements && elements.length) {
        let links = [];
        for (const el of elements) {
            const href = await el.evaluate(a => a.href);
            links.push(href);
        }
        return links;
    }
}

async function getData(page) { // get data from url
    // regex to check if the following is a url or not with space in start and end
    const regexWebsite = /^\s*(https?:\/\/)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)\s*$/gi;
    // regex to check the international phone number
    const regexPhone = /^\s*((?:\(?(?:00|\+)(?:[1-4]\d\d|[1-9]\d?)\)?)?[\-\.\ \\\/]?)?((?:\(?\d{1,}\)?[\-\.\ \\\/]?){0,})\s*$/gi;

    let getWebsite;
    for (let i = 2; i < 6; i++) {
        getWebsite = await page.$eval('#pane > div > div > div > div > div:nth-child(7) > div:nth-child('+i+') > button > div > div > div.fontBodyMedium'[0], el => el.textContent);
        if (regexWebsite.test(getWebsite)) {
            console.log('Valid URL');
            break;
        }
        else {
            if (i === 5) {
                console.log('Not a valid url');
                getWebsite = null;
                break;
            } else {
                continue;
            }
        }
    }

    let getPhone;
    for (let i = 2; i < 7; i++) {
        getPhone = await page.$eval('#pane > div > div > div > div > div:nth-child(7) > div:nth-child('+i+') > button > div > div > div.fontBodyMedium'[0], el => el.textContent);
        if (regexPhone.test(getPhone)) {
            console.log('Valid Phone Number');
            break;
        }
        else {
            if (i === 6) {
                console.log('Not a valid phone number');
                getPhone = null;
                break;
            } else {
                continue;
            }
        }
    }

    const results = await page.evaluate(({getWebsite, getPhone}) => {
        return ({
            scraper_job_id: new Date().getFullYear(),
            company: document.querySelectorAll('#pane > div > div > div > div > div > div > div > div > h1 > span:nth-child(1)')[0].textContent,
            address: document.querySelectorAll('#pane > div > div > div > div > div:nth-child(7) > div:nth-child(1) > button > div > div > div.fontBodyMedium')[0].textContent,
            website: getWebsite,
            phone: getPhone,
            created_at: new Date().getFullYear() + '-' + new Date().getMonth() + '-' + new Date().getDate() + ' ' + new Date().getHours() + ':' + new Date().getMinutes() + ':' + new Date().getSeconds(),
            updated_at: new Date().getFullYear() + '-' + new Date().getMonth() + '-' + new Date().getDate() + ' ' + new Date().getHours() + ':' + new Date().getMinutes() + ':' + new Date().getSeconds()
        })
    }, {getWebsite, getPhone});

    // insert into db
    con.query('INSERT INTO google_businesses SET ?', results, function (err) {
        if (err) throw err;
    });

    return results;
}

(async () => {
    const browser = await puppeteer.launch({ headless: false });
    const page = await browser.newPage();

    await page.setViewport({
        width: 1200,
        height: 800
    });

    await page.goto('https://www.google.com/maps/search/software+house/@31.4489924,74.3112558,14.25z');

    console.log('Scrolling...');
    await autoScroll(page);
    const size = 10;
    const links = await parseLinks(page);
    if (links.length < size) {
        while (links.length <= size) {
            await goToNextPage(page);
            await autoScroll(page);
            links.push(...await parseLinks(page));
        }
    }

    console.log(links.length);

    for (let i = 0; i < size; i++) {
        const link = links[i];
        await page.goto(link);
        const data = await getData(page);
        console.log(data);
    }

    browser.close();
})();
