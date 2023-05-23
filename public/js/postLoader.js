import { delay } from '/js/delay.js';
window.addEventListener('load', async function () {
    await delay(500);
        $loader.fadeOut(300, function () {
            $loader.remove();
        });
        $body.fadeIn(200);
        body.style.opacity = 1;
        loader.style.opacity = 0;
});