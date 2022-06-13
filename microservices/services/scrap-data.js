const puppeteer = require('puppeteer');
const Sentry = require('./sentry/sentry.js');
var mysql = require('mysql');
const minimist = require('minimist');
const moment = require('moment');

var host = minimist(process.argv.slice(2), { string: "host" }).host;
var port = minimist(process.argv.slice(2), { string: "port" }).port;
var database = minimist(process.argv.slice(2), { string: "database" }).database;
var username = minimist(process.argv.slice(2), { string: "username" }).username;
var password = minimist(process.argv.slice(2), { string: "password" }).password;

console.log('');
console.error('');
console.log(moment().format('YYYY-MM-DD HH:mm:ss') + ' - Scraping data from Google Businesses Table');
console.error(moment().format('YYYY-MM-DD HH:mm:ss') + ' - Getting Errors while Scraping from Google Businesses Table');

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
    return Math.floor(Math.random() * (40 - 10) + 10) + '00';
}

async function getJob() {
    return new Promise(resolve => {
        con.query('SELECT scraper_jobs.id AS jobId FROM scraper_jobs WHERE scraper_jobs.decision_makers_status = 0 ORDER BY scraper_jobs.id DESC LIMIT 1;', function (err, result) {
            if (err) {
                console.error(err);
                Sentry.captureException(err);
                throw 'Error getting data from scraper_jobs table';
            } else {
                resolve(result);
            }
        });
    });
}

async function bringData(jobId) {
    return new Promise(resolve => {
        con.query(`SELECT google_businesses.id, google_businesses.url, company, scraper_criterias.location AS location FROM google_businesses INNER JOIN scraper_jobs ON google_businesses.scraper_job_id = scraper_jobs.id INNER JOIN scraper_criterias ON scraper_jobs.scraper_criteria_id = scraper_criterias.id WHERE scraper_jobs.id = ${jobId};`, function (err, result) {
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

async function getScrapData(page) {
    let links = [], getHead = [];

    // Parsing Headings
    if (await page.$('#rso > div > block-component > div > div > div > div > div > div > div > div > div > div > div > div > div > div > span > span') != null) {
        var elements = await page.$$('#rso > div > block-component > div > div > div > div > div > div > div > div > div > div > div > div > div > div > span > span');
    } else {
        var elements = await page.$$('#rso > div > div > div > div > a > h3');
    }
    if (elements && elements.length) {
        for (const el of elements) {
            const name = await el.evaluate(span => span.textContent);
            getHead.push(name);
        }
    }

    // Parsing Links
    if (await page.$('#rso > div > block-component > div > div > div > div > div > div > div > div > div > div > div > div > div > div > div > div > a') != null) {
        var elements = await page.$$('#rso > div > block-component > div > div > div > div > div > div > div > div > div > div > div > div > div > div > div > div > a');
    } else {
        var elements = await page.$$('#rso > div > div > div:nth-child(1) > div > a');
    }
    if (elements && elements.length) {
        for (const el of elements) {
            const href = await el.evaluate(a => a.href);
            links.push(href);
        }
    }
    return { links, getHead };
}

(async () => {
    let jobId = await getJob();
    let getData = await bringData(jobId[0].jobId);

    const browser = await puppeteer.launch({
        headless: true,
        args: ['--no-sandbox']
    });
    const page = await browser.newPage();

    await page.setViewport({
        width: 1280,
        height: 786
    });

    try {
        for (let i = 0; i < getData.length; i++) {
            if (getData[i].url == null) {
                let company = getData[i].company.replace(/'/g, '%27');
                let query = 'https://www.google.com/search?q=CEO OR PRESIDENT OR FOUNDER OR CHAIRMAN OR Co-FOUNDER OR PARTNER in ' + company + ' in ' + getData[i].location + ' "@LinkedIn."com';
                let makeQuery = query.replace(/\s/g, '%20');
                con.query(`UPDATE google_businesses SET url = '${makeQuery}' WHERE id = ${getData[i].id};`);
                await page.goto(makeQuery, { waitUntil: 'networkidle2' });
            } else {
                await page.goto(getData[i].url, { waitUntil: 'networkidle2' });
            }
            await page.waitForTimeout(randomInt());
            var googleData = await getScrapData(page);
            var requiredNames = googleData.getHead.slice(0, 5);
            await page.waitForTimeout(randomInt());
            var requiredLinks = googleData.links.slice(0, 5);
            var sql = 'INSERT IGNORE INTO decision_makers (name, url, google_business_id, created_at, updated_at) VALUES ?';
            var values = [
                [requiredNames[0], requiredLinks[0], getData[i].id, moment().format('YYYY-MM-DD HH:mm:ss'), moment().format('YYYY-MM-DD HH:mm:ss')],
                [requiredNames[1], requiredLinks[1], getData[i].id, moment().format('YYYY-MM-DD HH:mm:ss'), moment().format('YYYY-MM-DD HH:mm:ss')],
                [requiredNames[2], requiredLinks[2], getData[i].id, moment().format('YYYY-MM-DD HH:mm:ss'), moment().format('YYYY-MM-DD HH:mm:ss')],
                [requiredNames[3], requiredLinks[3], getData[i].id, moment().format('YYYY-MM-DD HH:mm:ss'), moment().format('YYYY-MM-DD HH:mm:ss')],
                [requiredNames[4], requiredLinks[4], getData[i].id, moment().format('YYYY-MM-DD HH:mm:ss'), moment().format('YYYY-MM-DD HH:mm:ss')]
            ];
            con.query(sql, [values], function (err) {
                if (err) {
                    console.error(err);
                    Sentry.captureException(err);
                }
            });
            console.log('Company Name: ' + getData[i].company);
            console.log('Names: ' + requiredNames);
        }
        con.query(`UPDATE scraper_jobs SET decision_makers_status = 1 WHERE id = ${jobId[0].jobId};`)
    } catch (error) {
        console.error(error);
        Sentry.captureException(error);
        con.query(`UPDATE scraper_jobs SET decision_makers_status = 2 WHERE id = ${jobId[0].jobId};`)
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
