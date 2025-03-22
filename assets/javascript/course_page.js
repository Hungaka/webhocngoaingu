const imgEnglish = document.querySelector('.js-english__img');
const imgJapan = document.querySelector('.js-japan__img');
const listCourseContent = document.querySelector('.js-list-course__content');
const listCourseEnglish = document.querySelector('.js-list-course__english');
const courseEnglishContent = document.querySelector('.js-course-english__content');
const stageEnglishStart = document.querySelector('.js-stage-english__start');
const courseEnglishStart = document.querySelector('.js-course-english__start');

// listCourse
imgEnglish.addEventListener('click', function() {
    listCourseContent.style.display = "none";
    listCourseEnglish.style.display = "block";
})

stageEnglishStart.addEventListener('click', function() {
    courseEnglishContent.style.display = "none";
    courseEnglishStart.style.display = "block";
})