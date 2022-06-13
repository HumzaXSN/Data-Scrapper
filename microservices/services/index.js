const puppeteer = require('puppeteer');
const Sentry = require('./sentry/sentry.js');
var mysql = require('mysql');
const { toArray } = require('lodash');
const moment = require('moment');
const minimist = require('minimist');

// getting the data from laravel command
var args = minimist(process.argv.slice(2), { string: "url" });
var url = args.url;
var limit = parseInt(process.argv[3]);
var jobId = parseInt(process.argv[4]);
var criteriaId = parseInt(process.argv[5]);
var host = minimist(process.argv.slice(2), { string: "host" }).host;
var port = parseInt(process.argv[7]);
var database = minimist(process.argv.slice(2), { string: "database" }).database;
var username = minimist(process.argv.slice(2), { string: "username" }).username;
var password = minimist(process.argv.slice(2), { string: "password" }).password;

console.log('');
console.error('');
console.log(moment().format('YYYY-MM-DD HH:mm:ss') + ' - Starting scraper');
console.error(moment().format('YYYY-MM-DD HH:mm:ss') + ' - Getting Scraper Errors');
console.log('URL: "'+ url +'"');

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
        con.query(`UPDATE scraper_jobs SET status = 2, message = "Error while connecting to Database" WHERE id = ${jobId};`);
        throw 'Data base connection error';
    }
});

// produce random number for the delay upto 3 digits
function randomInt() {
    return Math.floor(Math.random() * (40 - 10) + 10) + '00';
}

async function autoScroll(page, randomInt) { // scroll down
    await page.evaluate(async (randomInt) => {
        await new Promise((resolve) => {
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
            }, randomInt);
        });
    }, randomInt);
}

async function parsePlaces(page) { // parse results from page
    let places = [];
    if (await page.$('#QA0Szd > div > div > div > div > div > div > div > div > div > div > div > div > div > div > div > div > div > div > div:nth-child(1) > div > span') != null) {
        var elements = await page.$$('#QA0Szd > div > div > div > div > div > div > div > div > div > div > div > div > div > div > div > div > div > div > div:nth-child(1) > div > span');
    } else {
        var elements = await page.$$('#pane > div > div > div > div > div > div > div > div > div > div > div > div > div > div > div:nth-child(1) > div > span');
    }
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
    if (await page.$('#QA0Szd > div > div > div > div > div > div > div > div > div > div > div > div > a') != null) {
        var elements = await page.$$('#QA0Szd > div > div > div > div > div > div > div > div > div > div > div > div > a');
    } else {
        var elements = await page.$$('#pane > div > div > div > div > div > div > div > div > a');
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

async function getData(page) { // get data from url

    // regex to check if the following is a url or not with space in start and end
    const regexWebsite = /^\s*(https?:\/\/)?(?!(www\.)?(?:google|facebook|whatsapp|instagram|youtube))[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)\s*$/gi;
    // regex to check the international phone number with space in start and end
    const regexPhone = /^\s*((?:\(?(?:00|\+)(?:[1-4]\d\d|[1-9]\d?)\)?)?[\-\.\ \\\/]?)?((?:\(?\d{1,}\)?[\-\.\ \\\/]?){0,})\s*$/gi;

    // get the value of heading
    var getheading = await page.evaluate(() => {
        if (document.querySelectorAll('#QA0Szd > div > div > div > div > div > div > div > div > div > div > div > div > h1 > span')[0] != null) {
            var heading = document.querySelectorAll('#QA0Szd > div > div > div > div > div > div > div > div > div > div > div > div > h1 > span')[0].textContent;
        } else {
            var heading = document.querySelectorAll('#pane > div > div > div > div > div > div > div > div > h1 > span:nth-child(1)')[0].textContent;
        }
        return heading;
    });

    console.log("Heading: " + getheading);

    // get the value of address
    var getAddress = await page.evaluate(() => {
        if (document.querySelectorAll('#QA0Szd > div > div > div > div > div > div > div > div > div:nth-child(7) > div > button > div > div > div.fontBodyMedium')[0] != null) {
            var address = document.querySelectorAll('#QA0Szd > div > div > div > div > div > div > div > div > div:nth-child(7) > div > button > div > div > div.fontBodyMedium')[0];
        } else {
            var address = document.querySelectorAll('#pane > div > div > div > div > div:nth-child(7) > div:nth-child(1) > button > div > div > div.fontBodyMedium')[0];
        }
        if (address) {
            address = address.textContent;
        } else {
            address = '';
            console.log('No address available');
        }
        return address;
    });

    // get the value of website
    var getWebsite;
    for (let i = 2; i < 6; i++) {
        if (await page.$('#QA0Szd > div > div > div > div > div > div > div > div > div:nth-child(7) > div:nth-child('+ i +') > button > div > div > div.fontBodyMedium'[0]) != null) {
            if (await page.$('#QA0Szd > div > div > div > div > div > div > div > div > div:nth-child(7) > div:nth-child(' + i + ') > button > div > div > div.fontBodyMedium'[0]) !== null) {
                getWebsite = await page.$eval('#QA0Szd > div > div > div > div > div > div > div > div > div:nth-child(7) > div:nth-child(' + i + ') > button > div > div > div.fontBodyMedium'[0], el => el.textContent);
                if (regexWebsite.test(getWebsite)) {
                    break;
                } else {
                    if (i === 5) {
                        getWebsite = null;
                        console.log('No website available');
                        break;
                    } else {
                        continue;
                    }
                }
            } else {
                getWebsite = null;
                console.log('No website available');
                break;
            }
        } else {
            if (await page.$('#pane > div > div > div > div > div:nth-child(7) > div:nth-child(' + i + ') > button > div > div > div.fontBodyMedium'[0]) !== null) {
                getWebsite = await page.$eval('#pane > div > div > div > div > div:nth-child(7) > div:nth-child(' + i + ') > button > div > div > div.fontBodyMedium'[0], el => el.textContent);
                if (regexWebsite.test(getWebsite)) {
                    break;
                } else {
                    if (i === 5) {
                        getWebsite = null;
                        console.log('No website available');
                        break;
                    } else {
                        continue;
                    }
                }
            } else {
                getWebsite = null;
                console.log('No website available');
                break;
            }
        }
    }

    // get the value of phone
    var getPhone;
    for (let i = 2; i < 8; i++) {
        if (await page.$('#QA0Szd > div > div > div > div > div > div > div > div > div:nth-child(7) > div:nth-child(' + i + ') > button > div > div > div.fontBodyMedium'[0]) != null) {
            if (await page.$('#QA0Szd > div > div > div > div > div > div > div > div > div:nth-child(7) > div:nth-child(' + i + ') > button > div > div > div.fontBodyMedium'[0]) !== null) {
                getPhone = await page.$eval('#QA0Szd > div > div > div > div > div > div > div > div > div:nth-child(7) > div:nth-child(' + i + ') > button > div > div > div.fontBodyMedium'[0], el => el.textContent);
                if (regexPhone.test(getPhone)) {
                    break;
                }
                else {
                    if (i === 7) {
                        getPhone = null;
                        console.log('No Phone available');
                        break;
                    } else {
                        continue;
                    }
                }
            } else {
                getPhone = null;
                console.log('No Phone available');
                break;
            }
        } else {
            if (await page.$('#pane > div > div > div > div > div:nth-child(7) > div:nth-child(' + i + ') > button > div > div > div.fontBodyMedium'[0]) !== null) {
                getPhone = await page.$eval('#pane > div > div > div > div > div:nth-child(7) > div:nth-child(' + i + ') > button > div > div > div.fontBodyMedium'[0], el => el.textContent);
                if (regexPhone.test(getPhone)) {
                    break;
                }
                else {
                    if (i === 7) {
                        getPhone = null;
                        console.log('No Phone available');
                        break;
                    } else {
                        continue;
                    }
                }
            } else {
                getPhone = null;
                console.log('No Phone available');
                break;
            }
        }
    }

    // get Industry
    var getIndustry = await page.evaluate(() => {
        if (document.querySelector('#QA0Szd > div > div > div > div > div > div > div > div > div > div > div > div > div > div > span > span > button') != null) {
            var industry = document.querySelector('#QA0Szd > div > div > div > div > div > div > div > div > div > div > div > div > div > div > span > span > button').textContent;
        } else {
            var industry = null;
        }
        return industry;
    });

    var getTime = moment().format('YYYY-MM-DD HH:mm:ss');

    // get all the values and pass them in the below function to insert into the database
    const results = await page.evaluate(({ getWebsite, getPhone, jobId, getAddress, getheading, getIndustry, getTime }) => {
        return ({
            scraper_job_id: jobId,
            company: getheading,
            address: getAddress,
            website: getWebsite,
            phone: getPhone,
            industry: getIndustry,
            created_at: getTime,
            updated_at: getTime
        })
    }, { getWebsite, getPhone, jobId, getAddress, getheading, getIndustry, getTime });

    // return the results to insert into the database
    con.query('INSERT INTO google_businesses SET ?', results, function (err) {
        if (err && err.code === 'ER_DUP_ENTRY') {
            console.log('Data already exist');
        } else if (err) {
            console.error(err);
            Sentry.captureException(err);
        }
    });

    return results;
}

async function bringData() {
    return new Promise(resolve => {
        con.query('SELECT google_businesses.id, company, industry, scraper_criterias.location AS location FROM google_businesses INNER JOIN scraper_jobs ON google_businesses.scraper_job_id = scraper_jobs.id INNER JOIN scraper_criterias ON scraper_jobs.scraper_criteria_id = scraper_criterias.id ORDER BY google_businesses.id DESC LIMIT 1;', function (err, result) {
            if (err) {
                console.error(err);
                Sentry.captureException(err);
            } else {
                resolve(result);
            }
        });
    });
}

// Main function
(async () => {
    const browser = await puppeteer.launch({
        headless: true,
        args: ["--no-sandbox"]
    });
    const page = await browser.newPage();

    await page.setViewport({
        width: 1280,
        height: 786
    });

    // get the value of last index
    con.query(`SELECT * FROM scraper_jobs WHERE scraper_criteria_id = ${criteriaId} ORDER BY id DESC LIMIT 1,1`, function (err, result) {
        if (err) {
            console.error(err);
            Sentry.captureException(err);
        } else {
            if (result.length) {
                last_index = result[0].last_index;
            } else {
                last_index = 0;
            }
        }
    });

    try {
        await page.goto(url, { waitUntil: 'networkidle2' });
    } catch(err) {
        console.error('Error while going to the url')
        console.error(err);
        Sentry.captureException(err);
    };

    await autoScroll(page, randomInt());

    console.log('last_index: '+last_index);

    var size = limit + last_index;

    // get the links until where it is defined in size
    page.waitForTimeout(randomInt());
    var links = await parseLinks(page);
    try {
        if (links.length < size) {
            while (links.length <= size) {
                if (await hasNextPage(page)) {
                    await page.waitForTimeout(randomInt());
                    await goToNextPage(page);
                    await page.waitForTimeout(randomInt());
                    await autoScroll(page, randomInt());
                    links.push(...await parseLinks(page));
                } else {
                    con.query(`UPDATE scraper_criterias SET status = "In-Active" WHERE id = ${criteriaId};`);
                    break;
                }
            }
        }
    } catch(err) {
        console.error('Error while getting links from different pages in loop')
        console.error(err);
        Sentry.captureException(err);
        con.query(`UPDATE scraper_jobs SET status = 2, message = "Error while parsing links" WHERE id = ${jobId};`);
    }

    var getSize = size - last_index;

    // removed already parsed links
    links = toArray(links).slice(last_index);
    // get the data from the links
    let i = 0;
    try {
        for (i; i < getSize; i++) {
            const link = links[i];
            await page.waitForTimeout(randomInt());
            await page.goto(link);
            await page.waitForTimeout(randomInt());
            await getData(page);
            let getLatestRecord = await bringData();
            var company = getLatestRecord[0].company.replace(/'/g, '%27');
            var query = 'https://www.google.com/search?q=CEO OR PRESIDENT OR FOUNDER OR CHAIRMAN OR Co-FOUNDER OR PARTNER in ' + company + ' in ' + getLatestRecord[0].location + ' "@LinkedIn."com';
            var makeQuery = query.replace(/\s/g, '%20');
            con.query(`UPDATE google_businesses SET url = '${makeQuery}' WHERE id = ${getLatestRecord[0].id};`);
        }
        con.query(`UPDATE scraper_jobs SET status = 1, message = "Scraper Completed Successfuly" WHERE id = ${jobId};`);
    } catch(err) {
        console.error('Error while getting data from links')
        console.error(err);
        Sentry.captureException(err);
        con.query(`UPDATE scraper_jobs SET status = 2, message = "Error while getting data from links" WHERE id = ${jobId};`);
    }

    // update the last index of the current scraper_job
    let getLast = i - 1 + last_index;
    con.query(`UPDATE scraper_jobs SET last_index = ${getLast} WHERE id = ${jobId}`, function (err, result) {
        if (err) {
            console.error(err);
            Sentry.captureException(err);
        }
    });

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
