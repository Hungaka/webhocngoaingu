const navAccount = document.querySelector('.setting-nav-account');
const navPassword = document.querySelector('.setting-nav-password');
const navSystem = document.querySelector('.setting-nav-system');
const navTarget = document.querySelector('.setting-nav-target');
const account = document.querySelector('.setting-account');
const password = document.querySelector('.setting-password');
const system = document.querySelector('.setting-system');
const target = document.querySelector('.setting-target');
const navActive = document.querySelector('.setting-nav-active');
const listtarget = document.querySelectorAll('.edit-target__content-item');

// nav 
navAccount.addEventListener('click', function() {
    navAccount.classList.add('setting-nav-active');
    navPassword.classList.remove('setting-nav-active');
    navSystem.classList.remove('setting-nav-active');
    navTarget.classList.remove('setting-nav-active');
    account.style.display = 'flex';
    password.style.display = 'none';
    system.style.display = 'none';
    target.style.display = 'none';
})
navPassword.addEventListener('click', function() {
    navPassword.classList.add('setting-nav-active');
    navAccount.classList.remove('setting-nav-active');
    navSystem.classList.remove('setting-nav-active');
    navTarget.classList.remove('setting-nav-active');
    password.style.display = 'flex';
    account.style.display = 'none';
    system.style.display = 'none';
    target.style.display = 'none';
})
navSystem.addEventListener('click', function() {
    navSystem.classList.add('setting-nav-active');
    navAccount.classList.remove('setting-nav-active');
    navPassword.classList.remove('setting-nav-active');
    navTarget.classList.remove('setting-nav-active');
    system.style.display = 'flex';
    account.style.display = 'none';
    password.style.display = 'none';
    target.style.display = 'none';
})
navTarget.addEventListener('click', function() {
    navTarget.classList.add('setting-nav-active');
    navAccount.classList.remove('setting-nav-active');
    navSystem.classList.remove('setting-nav-active');
    navPassword.classList.remove('setting-nav-active');
    target.style.display = 'flex';
    account.style.display = 'none';
    system.style.display = 'none';
    password.style.display = 'none';
})



