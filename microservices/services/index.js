const puppeteer = require('puppeteer');

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

async function parsePlaces(page) { // parse places
    let places = [];

    const elements = await page.$$('.fontHeadlineSmall');
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
    await page.waitForNetworkIdle();
}

async function hasNextPage(page) { // check if there is a next page
    const element = await page.$('button[aria-label=" Next page "]');
    if(!element) {
        throw new Error('No next page');
    }

    const disabled = await page.evaluate((el) => el.getAttribute('disabled'), element);
    if(disabled) {
        console.log('Next page disabled');
    }

    return !disabled;
}

(async () => {
    const browser = await puppeteer.launch({headless: false});
    const page = await browser.newPage();

    await page.setViewport({
        width: 1200,
        height: 768
    });

    await page.goto('https://www.google.com/maps/search/software+house/@31.4489924,74.3112558,14.25z');

    let places = [];

    do{
        await autoScroll(page);

        places = places.concat(await parsePlaces(page));

        console.log('Parsed' + places.length + 'places');

        await goToNextPage(page);
    }while(await hasNextPage(page));

    console.log(places);
    
})();