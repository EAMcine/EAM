import { delay } from '/js/delay.js';
window.addEventListener('load', async function () {
    await delay(500);
    $('#loader').fadeOut(500);
    await delay(300);
    $('.loading').addClass('loaded');
    $('.loaded').removeClass('loading');
});
let $burger = $('.burger-menu');
$burger.on('click', function () {
  $burger.toggleClass('active');
});
let $aside = $('#aside-menu');
let $asideOpener = $('#asideOpener');
$asideOpener.on('click', function () {
  $aside.toggleClass('active');
});