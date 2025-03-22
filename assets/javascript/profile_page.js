const overView = document.querySelector('.profile__nav-overview');
const overViewContent = document.querySelector('.profile__overview');
const active = document.querySelector('.profile__nav-active');
const acitveContent = document.querySelector('.profile__active');
const navActive = document.querySelector('.profile__nav--active');
const questionActive = document.querySelector('.interact__question-active');
const congratulations = document.querySelector('.interact__question-congratulations');

overView.addEventListener('click', function() {
    overView.classList.add('profile__nav--active');
    active.classList.remove('profile__nav--active');
    overViewContent.style.display = 'block';
    acitveContent.style.display = 'none';
    congratulations.style.display = 'block';
    questionActive.style.display = 'none';
})
active.addEventListener('click', function() {
    active.classList.add('profile__nav--active');
    overView.classList.remove('profile__nav--active');
    acitveContent.style.display = 'block';
    overViewContent.style.display = 'none';
    congratulations.style.display = 'none';
    questionActive.style.display = 'block';
})