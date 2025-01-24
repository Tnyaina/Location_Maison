// Image slider
const imgs = document.querySelectorAll('.img-select a');
const imgBtns = [...imgs];
let imgId = 1;

imgBtns.forEach((imgItem) => {
    imgItem.addEventListener('click', (event) => {
        event.preventDefault();
        imgId = imgItem.dataset.id;
        slideImage();
    });
});

document.querySelector('.left-arrow').addEventListener('click', () => {
    imgId = imgId > 1 ? imgId - 1 : imgBtns.length;
    slideImage();
});

document.querySelector('.right-arrow').addEventListener('click', () => {
    imgId = imgId < imgBtns.length ? imgId + 1 : 1;
    slideImage();
});

function slideImage() {
    const displayWidth = document.querySelector('.img-showcase img:first-child').clientWidth;
    document.querySelector('.img-showcase').style.transform = `translateX(${- (imgId - 1) * displayWidth}px)`;
}

// Date picker initialization
flatpickr(".flatpickr", {
    minDate: "today",
    dateFormat: "Y-m-d"
});