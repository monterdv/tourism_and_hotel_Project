// document.getElementById('next').onclick = function(){
//     let lists = document.querySelectorAll('.item');
//     document.getElementById('slide').appendChild(lists[0]);
// }

// document.getElementById('prev').onclick = function(){
//     let lists = document.querySelectorAll('.item');
//     document.getElementById('slide').prepend(lists[lists.length - 1]);
// }


const wrapper = document.querySelector('.wrapper');
// console.log(wrapper);
const loginLink = document.querySelector('.login-link');
const registerLink = document.querySelector('.register-link');
const btnPopup = document.querySelector('.btnLogin-popup');
const iconClose = document.querySelector('.icon-close');

function ButtonClick() {
    wrapper.classList.add('active');
}


// function showRegister(){
//     wrapper.classList.add('active');
// }
// registerLink.addEventListener('click',showRegister
// );

loginLink.addEventListener('click',function(){
    wrapper.classList.remove('active');
});

btnPopup.addEventListener('click',function(){
    wrapper.classList.add('active-poup');
});

iconClose.addEventListener('click',function(){
    wrapper.classList.remove('active-poup');
});

