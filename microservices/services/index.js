const puppeteer = require('puppeteer');

var mysql = require('mysql');
const { toArray } = require('lodash');
require('dotenv').config({ path: '../../.env' });

var args = require('minimist')(process.argv.slice(2), { string: "url" });
var url = args.url;
var limit = parseInt(process.argv[3]);
var jobId = parseInt(process.argv[4]);
var criteriaId = parseInt(process.argv[5]);

console.log(url);

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
            var distance = 100;
            var timer = setInterval(() => {
                if (document.querySelectorAll('#QA0Szd > div > div > div > div > div > div > div > div > div > div')[0] != null) {
                    var element = document.querySelectorAll('#QA0Szd > div > div > div > div > div > div > div > div > div > div')[0];
                } else {
                    var element = document.querySelectorAll('#pane > div > div:nth-child(1) > div > div > div:nth-child(2) > div:nth-child(1)')[0];
                }
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
        console.log("No more pages");
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

    let getAddress = await page.evaluate(() => {
        let address = document.querySelectorAll('#pane > div > div > div > div > div:nth-child(7) > div:nth-child(1) > button > div > div > div.fontBodyMedium')[0];
        if (address) {
            address = address.textContent;
        } else {
            address = '';
        }
        return address;
    });

    let getWebsite;
    for (let i = 2; i < 6; i++) {
        if (await page.$('#pane > div > div > div > div > div:nth-child(7) > div:nth-child('+ i +') > button > div > div > div.fontBodyMedium'[0]) !== null) {
            getWebsite = await page.$eval('#pane > div > div > div > div > div:nth-child(7) > div:nth-child('+ i +') > button > div > div > div.fontBodyMedium'[0], el => el.textContent);
            if (regexWebsite.test(getWebsite)) {
                break;
            } else {
                if (i === 5) {
                    getWebsite = null;
                    break;
                } else {
                    continue;
                }
            }
        } else {
            getWebsite = null;
            break;
        }
    }

    let getPhone;
    for (let i = 2; i < 8; i++) {
        if (await page.$('#pane > div > div > div > div > div:nth-child(7) > div:nth-child('+ i +') > button > div > div > div.fontBodyMedium'[0]) !== null) {
            getPhone = await page.$eval('#pane > div > div > div > div > div:nth-child(7) > div:nth-child('+ i +') > button > div > div > div.fontBodyMedium'[0], el => el.textContent);
            if (regexPhone.test(getPhone)) {
                break;
            }
            else {
                if (i === 7) {
                    getPhone = null;
                    break;
                } else {
                    continue;
                }
            }
        } else {
            getPhone = null;
            break;
        }
    }

    const results = await page.evaluate(({ getWebsite, getPhone, jobId, getAddress}) => {
        return ({
            scraper_job_id: jobId,
            company: document.querySelectorAll('#pane > div > div > div > div > div > div > div > div > h1 > span:nth-child(1)')[0].textContent,
            address: getAddress,
            website: getWebsite,
            phone: getPhone,
            created_at: new Date().getFullYear() + '-' + new Date().getMonth() + '-' + new Date().getDate() + ' ' + new Date().getHours() + ':' + new Date().getMinutes() + ':' + new Date().getSeconds(),
            updated_at: new Date().getFullYear() + '-' + new Date().getMonth() + '-' + new Date().getDate() + ' ' + new Date().getHours() + ':' + new Date().getMinutes() + ':' + new Date().getSeconds()
        })
    }, { getWebsite, getPhone, jobId, getAddress});

    con.query('INSERT INTO google_businesses SET ?', results, function (err) {
        if (err && err.code === 'ER_DUP_ENTRY') {
            console.log('Data already exist');
        } else if (err) {
            console.log(err);
        }
    });

    return results;
}

(async () => {
    const browser = await puppeteer.launch({ headless: false });
    const page = await browser.newPage();

    await page.setViewport({
        width: 1280,
        height: 786
    });

    con.query('SELECT * FROM scraper_jobs WHERE scraper_criteria_id = ' + criteriaId + ' ORDER BY id DESC LIMIT 1,1', function (err, result) {
        if (err) {
            console.log(err);
        } else {
            if (result.length) {
                last_index = result[0].last_index;
            } else {
                last_index = 0;
                console.log('No data found');
            }
        }
    });

    await page.goto(url);

    console.log('Scrolling...');
    await autoScroll(page);

    console.log('last_index: '+last_index);

    var size = limit + last_index;
    console.log('size: '+ size);

    var links = await parseLinks(page);
    if (links.length < size) {
        while (links.length <= size) {
            if (await hasNextPage(page)) {
                await goToNextPage(page);
                await autoScroll(page);
                links.push(...await parseLinks(page));
            } else {
                break;
            }
        }
    }

    var getSize = size - last_index;

    console.log('getSize: '+ getSize);

    links = toArray(links).slice(last_index);

    console.log('links: '+ links.length);

    for (let i = 0; i < getSize; i++) {
        const link = links[i];
        await page.goto(link);
        const data = await getData(page);
        console.log(data);
    }

    con.query('UPDATE scraper_jobs SET last_index = ' + size + ' WHERE id = ' + jobId, function (err, result) {
        if (err) {
            console.log(err);
        }
    });


    await browser.close();

    // close connection
    con.end(function (err) {
        if (err) throw err;
    });

})();
