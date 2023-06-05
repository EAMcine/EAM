import { delay } from '/js/delay.js';
import { upfirst } from '/js/strings.js';
async function fillText(htmlelement, text, speed, random) {
    for (let i = 0; i < text.length; i++) {
        await delay(speed + Math.random() * random);
        htmlelement.innerHTML += text.charAt(i);
    }
}

let $title = $('#title');
let title_content = title.innerHTML;
title.innerHTML = '';
let $title_name = $('#title_name');
let title_name_content = title_name.innerHTML;
title_name.innerHTML = '';
let $ping = $('#ping');
let ping_content = ping.innerHTML;
ping.innerHTML = '';
let ping_button = $('#ping_button');

async function pingpong() {
    ping.innerHTML = '';
    await fillText(ping, ping_content, 75, 50);
    fetch(API_URL + '/ping', { method: 'GET' })
    .then(response => response.json())
    .then(data => {
        ping.innerHTML = upfirst(data.message) + ' !';
    });
    ping_button.attr('disabled', false);
}

$(document).ready(
    async function () {
        ping_button.attr('disabled', true);
        await delay(1000);
        await fillText(title, title_content, 50, 50);
        await fillText(title_name, title_name_content, 250, 50);
        await pingpong();
    }
);

ping_button.click(async function () {
    ping_button.attr('disabled', true);
    await pingpong();
});


