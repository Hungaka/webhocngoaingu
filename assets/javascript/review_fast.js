const lifes = document.querySelectorAll('.life__item');
const newWord = document.querySelector('.new-words');
const losed = document.querySelector('.losed');
const listWord = document.querySelectorAll('.new-words__item');
const word1 = listWord[0];
const word2 = listWord[1];
const word3 = listWord[2];
const word4 = listWord[3];

word1.addEventListener('click', function() {
    word1.classList.add('new-words__item--choose');
    word2.classList.remove('new-words__item--choose');
    word3.classList.remove('new-words__item--choose');
    word4.classList.remove('new-words__item--choose');
    var checkLife = 3;
    lifes.forEach(life => {
        if (life.classList.contains('life__item--no-active')) {
            checkLife--;
        }
    })

    if (checkLife == 0) {
        newWord.style.display = 'none';
        losed.style.display = 'flex';
    }
})
word2.addEventListener('click', function() {
    word2.classList.add('new-words__item--choose');
    word1.classList.remove('new-words__item--choose');
    word3.classList.remove('new-words__item--choose');
    word4.classList.remove('new-words__item--choose');
    lifes[0].classList.add('life__item--no-active');
    var checkLife = 3;
    lifes.forEach(life => {
        if (life.classList.contains('life__item--no-active')) {
            checkLife--;
        }
    })

    if (checkLife == 0) {
        newWord.style.display = 'none';
        losed.style.display = 'flex';
    }
})
word3.addEventListener('click', function() {
    word3.classList.add('new-words__item--choose');
    word1.classList.remove('new-words__item--choose');
    word2.classList.remove('new-words__item--choose');
    word4.classList.remove('new-words__item--choose');
    lifes[0].classList.add('life__item--no-active');
    var checkLife = 3;
    lifes.forEach(life => {
        if (life.classList.contains('life__item--no-active')) {
            checkLife--;
        }
    })

    if (checkLife == 0) {
        newWord.style.display = 'none';
        losed.style.display = 'flex';
    }
})
word4.addEventListener('click', function() {
    word4.classList.add('new-words__item--choose');
    word1.classList.remove('new-words__item--choose');
    word3.classList.remove('new-words__item--choose');
    word2.classList.remove('new-words__item--choose');
    lifes[0].classList.add('life__item--no-active');
    var checkLife = 3;
    lifes.forEach(life => {
        if (life.classList.contains('life__item--no-active')) {
            checkLife--;
        }
    })

    if (checkLife == 0) {
        newWord.style.display = 'none';
        losed.style.display = 'flex';
    }
})