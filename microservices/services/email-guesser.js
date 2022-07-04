const { exec } = require("child_process");
var TelnetSocket, socket, conn;
var net = require("net");
({ TelnetSocket } = require("telnet-stream"));
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
console.log(moment().format('YYYY-MM-DD HH:mm:ss') + ' - Getting Emails for decision makers');
console.error(moment().format('YYYY-MM-DD HH:mm:ss') + ' - Getting Errors for Email Guesser');

// connect to database
var con = mysql.createConnection({
    host: host,
    port: port,
    user: username,
    password: password,
    database: database,
    connectionLimit: 10
});

async function runCommand(companyNameNoSpaces) {
    return new Promise((resolve, reject) => {
        exec(`nslookup -q=mx ${companyNameNoSpaces.toLowerCase()}`, (error, stdout) => {
            if (error) {
                reject(error);
            }
            let mailServer = stdout.split("\n");
            if (mailServer[4].includes('mail exchanger')) {
                let mailServer1 = mailServer[4].split(" ");
                let mailServer2 = mailServer1[mailServer1.length - 1];
                resolve(mailServer2.split("\r")[0]);
            } else {
                reject('No mail server found');
            }
        });
    });
}

async function verifyEmail(mailServer, unique) {
    return new Promise((resolve, reject) => {
        socket = net.createConnection(25, mailServer);
        conn = new TelnetSocket(socket);
        var email;
        var arr = [];

        conn.setEncoding('ascii');
        conn.setTimeout(10000);

        conn.on('error', function (err) {
            reject(err);
        });

        conn.on('connect', () => {
            console.log('connected to ' + mailServer);
            conn.write('HELO ' + mailServer + '\r\n');
            conn.write('MAIL FROM: <info@ashlarglobal.net>\r\n');
            for (let i = 0; i < unique.length; i++) {
                setTimeout(() => {
                    email = unique[i];
                    conn.write('RCPT TO: <' + unique[i] + '>\r\n');
                    if (i === unique.length - 1) {
                        conn.write('QUIT\r\n');
                    }
                }, i * 1000);
            }
        });

        conn.on('data', function (data) {
            if ((data.toString().includes('250') || data.toString().includes('450'))) {
                arr.push(email);
            }
            console.log(email, data.toString());
        });

        conn.on('close', function () {
            console.log('disconnected from ' + mailServer);
            if (arr.slice(2).length > 15) {
                reject('Server is Catch-all');
            } else {
                resolve(arr.slice(2));
            }
        });

        conn.on('timeout', function () {
            conn.end();
            conn.destroy();
            reject('timeout from ' + mailServer);
        });
    });
}

(async () => {
    let character = ['_', '-', '.', ''];
    let jobId = await new Promise(resolve => {
        con.query('SELECT scraper_jobs.id AS jobId FROM scraper_jobs WHERE scraper_jobs.decision_makers_email_status = 0 ORDER BY scraper_jobs.id DESC LIMIT 1;', function (err, result) {
            if (err) {
                console.error(err);
                Sentry.captureException(err);
                con.query(`UPDATE scraper_jobs SET decision_makers_email_status = 2 WHERE id = ${emailJobId};`);
                throw 'Error getting data from scraper_jobs table';
            } else {
                resolve(result);
            }
        });
    });

    let emailJobId = jobId[0].jobId;

    let getDecisionData = await new Promise((resolve) => {
        con.query(`SELECT decision_makers.id, decision_makers.name, decision_makers.url , google_businesses.website AS website FROM decision_makers INNER JOIN google_businesses ON decision_makers.google_business_id = google_businesses.id WHERE google_businesses.scraper_job_id = ${emailJobId};`, function (err, result) {
            if (err) {
                console.error(err);
                Sentry.captureException(err);
                con.query(`UPDATE scraper_jobs SET decision_makers_email_status = 2 WHERE id = ${emailJobId};`);
                throw 'Error getting data from decision_makers table';
            } else {
                resolve(result);
            }
        });
    });

    for (var j = 0; j < getDecisionData.length; j++) {
        let website = getDecisionData[j].website;
        let url = getDecisionData[j].url;
        let arr = [];

        if (website !== null && url.includes('linkedin.com')) {
            let name = getDecisionData[j].name;
            let name1 = name.split('-');
            let name2 = name1[0].split(' ');
            let firstName = name2[0];
            let firstChar = firstName.charAt(0);
            let lastName = name2[name2.length - 2];
            let lastChar = lastName.charAt(0);
            let company1 = website.split(' ');
            let company = company1[7];
            let companyNameNoSpaces = company.replace(/\s/g, '');
            for (let i = 0; i < character.length; i++) {
                let firstNameGuessLast = `${firstName.toLowerCase()}${character[i].toLowerCase()}@${companyNameNoSpaces.toLowerCase()}`;
                let lastNameGuessLast = `${lastName.toLowerCase()}${character[i].toLowerCase()}@${companyNameNoSpaces.toLowerCase()}`;
                let firstNameGuessFirst = `${character[i].toLowerCase()}${firstName.toLowerCase()}@${companyNameNoSpaces.toLowerCase()}`;
                let lastNameGuessFirst = `${character[i].toLowerCase()}${lastName.toLowerCase()}@${companyNameNoSpaces.toLowerCase()}`;
                let fullNameGuess = `${firstName.toLowerCase()}${character[i].toLowerCase()}${lastName.toLowerCase()}@${companyNameNoSpaces.toLowerCase()}`;
                let firstCharGuess = `${firstChar.toLowerCase()}${character[i].toLowerCase()}${lastName.toLowerCase()}@${companyNameNoSpaces.toLowerCase()}`;
                let lastCharGuess = `${lastChar.toLowerCase()}${character[i].toLowerCase()}${firstName.toLowerCase()}@${companyNameNoSpaces.toLowerCase()}`;
                arr.push(firstNameGuessLast, lastNameGuessLast, firstNameGuessFirst, lastNameGuessFirst, fullNameGuess, firstCharGuess, lastCharGuess);
            }
            let unique = arr.filter(function (item, index) {
                return arr.indexOf(item) >= index;
            });

            try {
                var mailServer = await runCommand(companyNameNoSpaces);
                if (mailServer === 'No mail server found') {
                    break;
                }
                let verified = await verifyEmail(mailServer, unique);
                for (let p = 0; p < verified.length; p++) {
                    console.log('Emails Caught ' + verified[p]);
                    con.query(`INSERT IGNORE INTO decision_makers_emails SET email = '${verified[p]}', decision_maker_id = ${getDecisionData[j].id}, created_at = '${moment().format('YYYY-MM-DD HH:mm:ss')}', updated_at = '${moment().format('YYYY-MM-DD HH:mm:ss')}';`, function (err) {
                        if (err) {
                            console.error(err);
                            Sentry.captureException(err);
                        }
                    });
                }
                con.query(`UPDATE scraper_jobs SET decision_makers_email_status = 1 WHERE id = ${emailJobId};`);
            } catch (err) {
                console.error(err);
                Sentry.captureException(err);
                con.query(`UPDATE scraper_jobs SET decision_makers_email_status = 2 WHERE id = ${emailJobId};`);
            }
        }
    }

    // close connection
    con.end(function (err) {
        if (err) {
            console.error(err);
            Sentry.captureException(err);
        }
    });

})();

