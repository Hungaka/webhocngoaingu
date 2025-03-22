const studyEnglishbtn = document.querySelector('.js-enlish-btn');
const studyJapanbtn = document.querySelector('.js-japan-btn');
const modalStudy = document.querySelector('.modal-study');
const closeModalstudy = document.querySelector('.js-modal-study__close');
const modalStudycontainer = document.querySelector('.js-modal-study__container')
    // modal
studyEnglishbtn.addEventListener('click', function() {
    modalStudy.classList.add('open-modal-study');
})
closeModalstudy.addEventListener('click', function() {
    modalStudy.classList.remove('open-modal-study');
})
modalStudy.addEventListener('click', function() {
    modalStudy.classList.remove('open-modal-study');
})
modalStudycontainer.addEventListener('click', function(event) {
    event.stopPropagation();
})