//var login = 'bro-dev';
//var pass = '7q8a9z4w5s6x7q8a9z4w5s6x';
//var accto = process.argv[2];
//var message =  process.argv[3];
//
//console.dir(accto);
//console.dir(message);

var express = require('express');
var app = express();

// respond with "hello world" when a GET request is made to the homepage


app.listen(9999, () => {
    console.log('We are live on ');
});

app.get('/', function(req, res) {
    var autologinlink = req.query.autologinlink;
    var proxynow = req.query.proxynow;
    var likeid = req.query.likeid;
    if (autologinlink == undefined ||
        proxynow == undefined ||
        likeid == undefined) {
        res.send("Нехватает данных");
    }
    autologinlink = autologinlink.replace(/&amp;/g, "&");
    //console.dir(autologinlink);
    //console.dir(proxynow);
    //console.dir(likeid);

    var webdriver = require('selenium-webdriver'),
        By = webdriver.By,
        until = webdriver.until,
        proxy = require('selenium-webdriver/proxy'),
        fs = require('fs'),
        util = require("util");

    var driver = new webdriver.Builder()
        .forBrowser('phantomjs')
        .setProxy(proxy.manual({ http: proxynow }))
        .build();
    driver.manage().timeouts().pageLoadTimeout(22222);

    var broWaitByCss = function(css, t = 22222) {
        driver.wait(until.elementLocated(By.css(css)), t).then(function() {
            driver.wait(until.elementIsVisible(driver.findElement(By.css(css))), t).then(function() {
                driver.sleep(111);
            }, function() {
                driver.sleep(111);
            });
        }, function() {
            driver.sleep(111);
        });

    }
    var broWaitByCssAndClick = function(css, t) {
        broWaitByCss(css, t);
        driver.findElement(By.css(css)).click().then(function() {
            driver.sleep(111);
        }, function() {
            driver.sleep(111);
        });
    }
    var broWaitByCssAndSend = function(css, mess, t) {
        broWaitByCss(css, t);
        driver.findElement(By.css(css)).sendKeys(mess).then(function() {
            driver.sleep(111);
        }, function() {
            driver.sleep(111);
        });
    }

    driver.get('https://fotostrana.ru').then(() => {
        driver.sleep(111);
    }, () => {
        driver.sleep(111);
    });
    var brook = "neok";
    driver.wait(until.elementLocated(By.css('#VKADSRetarget')), 22222).then(function() {
        brook = "ok";
        driver.get(autologinlink).then(function() {
            driver.sleep(111);

            driver.get("https://fotostrana.ru/rating/?viewUserId=" + likeid).then(() => {
                var scrinname = proxynow.replace(/\./g, "").replace(/:/g, "") + ".png";
                var base64Data = "emply";
                driver.takeScreenshot().then((data) => {
                    base64Data = data.replace(/^data:image\/png;base64,/, "");
                    var out = "";
                    out += brook;
                    out += "<img src='data:image/png;base64," + data + "'>"
                    res.send(out);
                    /*fs.writeFile(scrinname, base64Data, 'base64', function(err) {
                        if (err) console.log(err);
                    });*/
                });
                driver.quit();

            }, () => { res.status(408).send("Прокси не работает"); });

        }, function() {
            driver.sleep(111);
        });;

    }, function() {
        res.send("Прокси не работает");
        driver.quit();
    });



});

//broWaitByCssAndSend('#username', login);
//broWaitByCssAndClick('#signIn');
//broWaitByCssAndSend('#i0118', pass);
//broWaitByCssAndClick('#idSIButton9');
//broWaitByCssAndSend('.search .input input', accto);
//broWaitByCssAndClick('.search .results .searchDirectory');
//broWaitByCssAndClick('.swx .side .searchItem');
//broWaitByCssAndClick('.conversation .contactRequestSend', 3333);
//broWaitByCssAndClick('.conversation .contactRequestResendMessage a', 3333);
//message.split("\n").forEach((el)=>{
//broWaitByCssAndSend('#chatInputContainer textarea', el);
//broWaitByCssAndClick('.swx-chat-input-send-btn');
//driver.sleep(111);
//});