const listWord = document.querySelectorAll('.conjunction__word');
const btnContinue = document.querySelector('.continue__btn');

const word1 = listWord[0];
const word2 = listWord[1];
const word3 = listWord[2];
const word4 = listWord[3];

word1.addEventListener('click', function() {
    word1.classList.add('conjunction__word--active');
    word2.classList.remove('conjunction__word--active');
    word3.classList.remove('conjunction__word--active');
    word4.classList.remove('conjunction__word--active');
    btnContinue.style.display = 'inline-flex';
})
word2.addEventListener('click', function() {
    word2.classList.add('conjunction__word--active');
    word1.classList.remove('conjunction__word--active');
    word3.classList.remove('conjunction__word--active');
    word4.classList.remove('conjunction__word--active');
    btnContinue.style.display = 'none';
})
word3.addEventListener('click', function() {
    word3.classList.add('conjunction__word--active');
    word2.classList.remove('conjunction__word--active');
    word1.classList.remove('conjunction__word--active');
    word4.classList.remove('conjunction__word--active');
    btnContinue.style.display = 'none';
})
word4.addEventListener('click', function() {
    word4.classList.add('conjunction__word--active');
    word2.classList.remove('conjunction__word--active');
    word3.classList.remove('conjunction__word--active');
    word1.classList.remove('conjunction__word--active');
    btnContinue.style.display = 'none';
})