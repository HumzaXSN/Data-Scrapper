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
console.log(moment().format('YYYY-MM-DD HH:mm:ss') + ' - Starting Next Script');
console.error(moment().format('YYYY-MM-DD HH:mm:ss') + ' - Getting Next Script Erros');

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

async function bringData() {
    return new Promise(resolve => {
        con.query('SELECT * FROM google_businesses;', function (err, result) {
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
    if (await page.$('#rso > div > block-component > div > div > div > div > div > div > div > div > div > div > div > div > div > div > span > span') != null) {
        var elements = await page.$$('#rso > div > block-component > div > div > div > div > div > div > div > div > div > div > div > div > div > div > span > span');
    } else {
        var elements = await page.$$('#rso > div > div > div > div > a > h3');
    }
    if (elements && elements.length) {
        let getHead = [];
        for (const el of elements) {
            const name = await el.evaluate(span => span.textContent);
            getHead.push({ name });
        }
        return getHead;
    }
}

async function parseLinks(page) { //parse links
    if (await page.$('#rso > div > block-component > div > div > div > div > div > div > div > div > div > div > div > div > div > div > div > div > a') != null) {
        var elements = await page.$$('#rso > div > block-component > div > div > div > div > div > div > div > div > div > div > div > div > div > div > div > div > a');
    } else {
        var elements = await page.$$('#rso > div > div > div > div > a');
    }
    if (elements && elements.length) {
        let links = [];
        for (const el of elements) {
            const href = await el.evaluate(a => a.href);
            links.push(href);
        }
        return links;
    }
}

(async () => {
    let getData = await bringData();

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
            await page.goto(getData[i].url, { waitUntil: 'networkidle2' });
            await page.waitForTimeout(randomInt());
            var googleNames = await getName(page);
            var requiredNames = googleNames.slice(0, 5);
            var names = requiredNames.map(function (item) {
                return item.name;
            });
            var link = await parseLinks(page);
            var requiredLinks = link.slice(0, 5);
            var sql = 'INSERT IGNORE INTO decision_makers (name, url, google_business_id, created_at, updated_at) VALUES ?';
            var values = [
                [names[0], requiredLinks[0], getData[i].id, moment().format('YYYY-MM-DD HH:mm:ss'), moment().format('YYYY-MM-DD HH:mm:ss')],
                [names[1], requiredLinks[1], getData[i].id, moment().format('YYYY-MM-DD HH:mm:ss'), moment().format('YYYY-MM-DD HH:mm:ss')],
                [names[2], requiredLinks[2], getData[i].id, moment().format('YYYY-MM-DD HH:mm:ss'), moment().format('YYYY-MM-DD HH:mm:ss')],
                [names[3], requiredLinks[3], getData[i].id, moment().format('YYYY-MM-DD HH:mm:ss'), moment().format('YYYY-MM-DD HH:mm:ss')],
                [names[4], requiredLinks[4], getData[i].id, moment().format('YYYY-MM-DD HH:mm:ss'), moment().format('YYYY-MM-DD HH:mm:ss')]
            ];
            con.query(sql, [values], function (err) {
                if (err) {
                    console.error(err);
                    Sentry.captureException(err);
                }
            });
        }
    } catch (error) {
        console.error(error);
        Sentry.captureException(error);
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
