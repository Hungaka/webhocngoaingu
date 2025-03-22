const listNewWord = document.querySelectorAll('.new-words__item');
const btnContinue = document.querySelector('.continue__btn');

const newWord1 = listNewWord[0];
const newWord2 = listNewWord[1];
const newWord3 = listNewWord[2];
const newWord4 = listNewWord[3];

newWord1.addEventListener('click', function() {
    newWord1.classList.add('new-words__item--choose');
    newWord2.classList.remove('new-words__item--choose');
    newWord3.classList.remove('new-words__item--choose');
    newWord4.classList.remove('new-words__item--choose');
    btnContinue.style.display = 'inline-flex';
})
newWord2.addEventListener('click', function() {
    newWord2.classList.add('new-words__item--choose');
    newWord1.classList.remove('new-words__item--choose');
    newWord3.classList.remove('new-words__item--choose');
    newWord4.classList.remove('new-words__item--choose');
    btnContinue.style.display = 'none';
})
newWord3.addEventListener('click', function() {
    newWord3.classList.add('new-words__item--choose');
    newWord2.classList.remove('new-words__item--choose');
    newWord1.classList.remove('new-words__item--choose');
    newWord4.classList.remove('new-words__item--choose');
    btnContinue.style.display = 'none';
})
newWord4.addEventListener('click', function() {
    newWord4.classList.add('new-words__item--choose');
    newWord2.classList.remove('new-words__item--choose');
    newWord3.classList.remove('new-words__item--choose');
    newWord1.classList.remove('new-words__item--choose');
    btnContinue.style.display = 'none';
})