const swiper = new Swiper('.swiper', {
  // Optional parameters

  loop: false,

  slidesPerView: 2.3,
  spaceBetween: 20,

  navigation: {
    nextEl: '.next',
    prevEl: '.preview',
  }
});


const burger = document.querySelector('.header-burger');
const menu = document.querySelector('.header-menu');
const body = document.querySelector('.body');

let i = 0;

function toggleClass() {
  if (i === 0) {
    burger.classList.add('active');
    burger.classList.add('active:before');
    burger.classList.add('active:after');
    menu.classList.add('active');
    body.classList.add('lock');
    i = 1;
  } else {
    burger.classList.remove('active');
    menu.classList.remove('active');
    body.classList.remove('lock');
    i = 0;
  }
}

let List = document.querySelectorAll('.header-link')
List.forEach(item => {
  item.addEventListener('click', () => {
    burger.classList.remove('active');
    menu.classList.remove('active');
    body.classList.remove('lock');
    i = 0;
  })
})

burger.addEventListener('click', toggleClass);