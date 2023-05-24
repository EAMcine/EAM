import { delay } from '/js/delay.js';
window.addEventListener('load', async function () {
    await delay(300);
    $('#loader').fadeOut(500);
    await delay(500);
    $('.loading').addClass('loaded');
    $('.loaded').removeClass('loading');
});