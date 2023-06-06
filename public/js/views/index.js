import { delay } from '/js/delay.js';

async function fillText(htmlelement, text, speed, random) {
    for (let i = 0; i < text.length; i++) {
        await delay(speed + Math.random() * random);
        htmlelement.text(htmlelement.text() + text.charAt(i));
    }
}

// let writing_title = $('#writing-title');
// let writing_title_content = writing_title.text();
// writing_title.text('');

// $(document).ready(
//     async function () {
//         await delay(1000);
//         await fillText(writing_title, writing_title_content, 50, 50);
//     }
// );
