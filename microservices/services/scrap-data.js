const puppeteer = require('puppeteer');
const Sentry = require('./sentry/sentry.js');
var mysql = require('mysql');
const minimist = require('minimist');
const moment = require('moment');

var host = '127.0.0.1';
var port = '3306';
var database = 'laravel_spider';
var username = 'root';
var password = '';

// var jobId = minimist(process.argv.slice(2), { string: "jobId" }).jobId;
// var host = minimist(process.argv.slice(2), { string: "host" }).host;
// var port = minimist(process.argv.slice(2), { string: "port" }).port;
// var database = minimist(process.argv.slice(2), { string: "database" }).database;
// var username = minimist(process.argv.slice(2), { string: "username" }).username;
// var password = minimist(process.argv.slice(2), { string: "password" }).password;

console.log('');
console.error('');
console.log(moment().format('YYYY-MM-DD HH:mm:ss') + ' - Starting scraper');
console.error(moment().format('YYYY-MM-DD HH:mm:ss') + ' - Getting Scraper Errors');

// connect to database
var con = mysql.createConnection({
    host: host,
    port: port,
    user: username,
    password: password,
    database: database,
    connectionLimit: 10
});

// connect to database and check for errors
con.connect(function (err) {
    if (err) {
        console.error(err);
        Sentry.captureException(err);
        throw 'Data base connection error';
    }
});

// produce random number for the delay upto 3 digits
function randomInt() {
    return Math.floor(Math.random() * (35 - 10) + 10) + '00';
}

async function bringData() {
    return new Promise(resolve => {
        con.query('SELECT google_businesses.id, company, industry, scraper_criterias.location AS location FROM google_businesses INNER JOIN scraper_jobs ON google_businesses.scraper_job_id = scraper_jobs.id INNER JOIN scraper_criterias ON scraper_jobs.scraper_criteria_id = scraper_criterias.id WHERE scraper_jobs.id = ' + 1 + ';', function (err, result) {
            if (err) {
                console.error(err);
                Sentry.captureException(err);
                throw 'Error getting data from google_businesses table';
            } else {
                resolve(result);
            }
        });
    });
}

async function getName(page) {
    await page.waitForTimeout(5000);
    let getHead = [];
    if (await page.$('#rso > div > block-component > div > div > div > div > div > div > div > div > div > div > div > div > div > div > span > span') != null) {
        var elements = await page.$$('#rso > div > block-component > div > div > div > div > div > div > div > div > div > div > div > div > div > div > span > span');
    } else {
        var elements = await page.$$('#rso > div > div > div > div > a > h3');
    }
    if (elements && elements.length) {
        for (const el of elements) {
            const name = await el.evaluate(span => span.textContent);
            getHead.push({ name });
        }
    }
    return getHead;
    // var evalName = await page.evaluate(() => {
    //     if (document.querySelector('#rso > div > block-component > div > div > div > div > div > div > div > div > div > div > div > div > div > div > span > span') != null) {
    //        var headName = document.querySelector('#rso > div > block-component > div > div > div > div > div > div > div > div > div > div > div > div > div > div > span > span').textContent;
    //     } else if (document.querySelector('#rso > div > div > div > div > a > h3') != null) {
    //         let getHead = [];
    //         for (var i = 0; i < document.querySelectorAll('#rso > div > div > div > div > a > h3').length; i++) {
    //            var headName = document.querySelectorAll('#rso > div > div > div > div > a > h3')[0].textContent;
    //             getHead.push(headName);
    //         }
    //         return getHead;
    //     }

    // });
}

(async () => {
    let getData = await bringData();

    const browser = await puppeteer.launch({
        headless: false,
        args: ['--no-sandbox']
    });
    const page = await browser.newPage();

    await page.setViewport({
        width: 1280,
        height: 786
    });

    // Loop throgh data from database
    for (let i = 0; i < getData.length; i++) {
        await page.goto('https://www.google.com/');
        await page.waitForTimeout(randomInt());
        var query = 'CEO OR PRESIDENT OR FOUNDER OR CHAIRMAN OR Co-FOUNDER OR PARTNER @' + getData[i].company + ' in ' + getData[i].location;
        await page.type('input[name="q"]', query);
        await page.waitForTimeout(randomInt());
        await page.keyboard.press("Enter");
        await page.waitForTimeout(randomInt());
        var googleNames = await getName(page);
        requiredNames = googleNames.slice(0, 5);

        con.query()
    }

    const pages = await browser.pages();
    for (const page of pages) await page.close();
    await browser.close();

    // close connection
    con.end(function (err) {
        if (err) {
            console.error(err);
            Sentry.captureException(err);
        }
    });
})();
