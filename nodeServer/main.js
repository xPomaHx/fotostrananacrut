var express = require('express');
var app = express();

app.listen(9999, () => {
    console.log('We are live on ');
});

app.get('/', (req, res) => {
    var autologinlink = req.query.autologinlink;
    var proxynow = req.query.proxynow;
    var likeid = req.query.likeid;
    var brores = {
        status: 0,
        text: "Не задано",
        img: "",
    };
    if (autologinlink == undefined ||
        proxynow == undefined ||
        likeid == undefined) {
        res.json(brores);
        throw brores.text;
    }
    autologinlink = autologinlink.replace(/&amp;/g, "&");
    console.dir(autologinlink);
    console.dir(proxynow);
    console.dir(likeid);

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
    driver.manage().timeouts().pageLoadTimeout(5555);

    var broWait = function(css, t = 5555) {
        return new Promise((resolve, regect) => {
            driver.wait(until.elementLocated(By.css(css)), t).then(() => {
                return driver.wait(until.elementIsVisible(driver.findElement(By.css(css))), t);
            }).then(() => {
                resolve();
            }, () => {
                regect();
            });
        });
    }

    var errorAlredy = false;
    var broErr = function(text) {

        if (errorAlredy) {
            throw text;
        } else {
            errorAlredy = true;
            brores.text = text;
            console.log(text);
            return driver.takeScreenshot().then((data) => {
                var base64Data = data.replace(/^data:image\/png;base64,/, "");
                var out = "<img src='data:image/png;base64," + data + "'>";
                brores.img = out;
                res.json(brores);

            }, () => {
                brores.text = "не удалость сделать скрин";
                res.json(brores);

            }).then(() => {
                throw brores.text
            });
        }
    }

    driver.get('https://fotostrana.ru').then(() => {
        return broWait("#footer .nclear");
    }, () => {
        return broErr("Главная не загружена");
    }).then(() => {
        return driver.get(autologinlink);
    }, () => {
        return broErr("Главная загружена не полностью");
    }).then(() => {
        return broWait(".btn-registr", 7777).then(() => {
            broErr("Автологин не действителен");
        }, () => {
            return broWait(".ban-block-karavaggio", 7777);
        }).then(() => {
            broErr("Аккаунт заблокирован");
        }, () => {
            return driver.get("https://fotostrana.ru/rating/?viewUserId=" + likeid);
        });
    }, () => {
        return broErr("Проблемы с авторизацией");
    }).then(() => {
        return broWait("#fsr-photo-like-fs .ibtn-disabled", 7777);
    }, () => {
        return broErr("Не удалось зайти на страницу с фото");
    }).then((data) => {
        return broErr("Лайк не засчитан");
    }, () => {
        return broErr("Лайк засчитан");
    }).catch((er) => {
        //brores.text = "Что то пошло не так";
        driver.quit();
        console.log(er);
        //res.json(er);
    });
});