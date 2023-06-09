const carousel = document.querySelector('.carousel');
const carouselInner = carousel.querySelector('.carousel-inner');
const prevButton = carousel.querySelector('#prevButton');
const nextButton = carousel.querySelector('#nextButton');
const imageWidth = carousel.clientWidth;
let currentPosition = 0;

prevButton.addEventListener('click', () => {
    currentPosition += imageWidth;
    carouselInner.style.transform = `translateX(${currentPosition}px)`;
});

nextButton.addEventListener('click', () => {
    currentPosition -= imageWidth;
    carouselInner.style.transform = `translateX(${currentPosition}px)`;
});
