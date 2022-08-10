window.onload = () => {
  console.log('Страница загружена');
  document.querySelector('.baurPrimaryHeader').classList.remove('stuck');
  // document.querySelector('.baur_navigation .site-header-cart').style = 'display: none';
// СБРОС НИЖНЕГО МЕНЮ В КОРЗИНЕ
  if (document.location.pathname === "/cart-2/") {
    document.querySelector('.pizzaro-handheld-footer-bar').style = 'display: none';
  }
// КОНСТАНТЫ
  const menu = document.querySelector('.baur_menu-btn');
  const menuContainer = document.querySelector('.baur_menu-container');
  const btnCloseMenu = document.querySelector('.baur_menu-close');
  const activeCategoryAll = document.querySelectorAll('.menu_baur-items li');
  const linkCategoryAll = document.querySelectorAll('.menu_baur-items li a');
  const heightBlockHeader = document.querySelector('#masthead').offsetHeight;
  // const activePrimaryMenuAll = document.querySelectorAll('#menu-main-menu li a');
  const activePrimaryMenuAll = document.querySelectorAll('#menu-food-menu li a');
  const imgLinksMenuAll = document.querySelectorAll('#menu-primarybaur li span');
  const sectionProducts = document.querySelectorAll('.section-products .section-title');
  const sectionProductsArr = [];
  const visibleBlockOnNight = document.querySelector('.top_header-date');
  const animation = document.querySelector('.animation');
  // якоря
  if (document.location.pathname === '/') {
    linkCategoryAll.forEach((item) => {
      item.addEventListener('click',(event)=>{
        event.preventDefault();
      })
      document.querySelectorAll('.section-products .section-title').forEach(el=>{
        if (item.textContent === el.textContent) {
          item.href = `#${el.parentElement.id}`;
        }
      })
    })
    activePrimaryMenuAll.forEach(item=>{
      item.addEventListener('click',(event)=>{
        event.preventDefault();
      })
      document.querySelectorAll('.section-products .section-title').forEach(el=>{
        if (item.textContent === el.textContent) {
          item.href = `#${el.parentElement.id}`;
        }
      })
    })
  }

  // прокрутка до якоря 
  sectionProducts.forEach(section => {
    activePrimaryMenuAll.forEach(item => {
      if (section.textContent === item.textContent) {// сравнить и добавить в массив
        sectionProductsArr.push(section.parentElement)
      }
    })
  })
  // 
  
  window.addEventListener('scroll', function() {
    // выпад меню
    if ( pageYOffset>90 ) {
      document.querySelector('.baurPrimaryHeader').classList.add('stuck')
      // document.querySelector('.baur_navigation .site-header-cart').style = 'display: block';
    }
    else {
      document.querySelector('.baurPrimaryHeader').classList.remove('stuck');
      // document.querySelector('.baur_navigation .site-header-cart').style = 'display: none';
    }
    sectionProductsArr.forEach(block => {
      activePrimaryMenuAll.forEach(item => {
        if (block.firstElementChild.textContent === item.textContent) {
          if (block.offsetTop - (pageYOffset + heightBlockHeader) > 0 || (block.offsetTop + block.offsetHeight) - (pageYOffset + heightBlockHeader) < 0 ) {
            item.classList.remove('hoverLink')
          } else {
            item.classList.add('hoverLink')
          }
        }
      })
    })
});

// вывод надписи вверху ночью с 22.30 до 11 утра
  if ( new Date().getHours() >= 23 || new Date().getHours() <= 8 ) {
    visibleBlockOnNight.style = "display: block";
  } 
// БОКОВОЕ МЕНЮ
  menu.addEventListener('click', ()=>{
    menuContainer.classList.add('baur_active');
  });
  btnCloseMenu.addEventListener('click', ()=>{
    menuContainer.classList.remove('baur_active');
  });
// УБРАТЬ "СКАЧАТЬ ПРИЛОЖЕНИЕ"
  const btnClose = document.querySelector('.top_header-close');
  btnClose.addEventListener('click', ()=>{
    document.querySelector('.top_header').classList.add('close-baur');
    sessionStorage.setItem('classNull', false)
  });
  if (sessionStorage.getItem('classNull') === "false") {
    document.querySelector('.top_header').classList.add('close-baur');
  }

  // КАКАЯ СТРАНИЦА ОТКРЫТА
  var id = "null";
  if (document.location.pathname === '/') {
    localStorage.removeItem('addClassLi');
  }
  if(localStorage.getItem('addClassLi')) {id = localStorage.getItem('addClassLi')};
  activePrimaryMenuAll.forEach((item, index) => {
    item.addEventListener('click', () => {
      // item.classList.add('activePrimaryLiA');
      localStorage.setItem('addClassLi', index);
    })
  })
  activeCategoryAll.forEach((item, index) => {
    item.addEventListener('click', () => {
      item.classList.add('activeLi');
      localStorage.setItem('addClassLi', index);
    })
  })
  
  if(id !== "null") {
    activePrimaryMenuAll[id].classList.add('activePrimaryLiA');
    activeCategoryAll[id].classList.add('activeLi');
    // $___мобильный вид
    linkCategoryAll[id].style = 'color: #fff!important;';
    imgLinksMenuAll[id].style = 'background: #FFFFFF;box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.2);';
    animation.style = `left: ${activeCategoryAll[id].offsetLeft}px;width: ${activeCategoryAll[id].clientWidth}px;`;
   
  }
  // наведение на категории 
  activeCategoryAll.forEach((item,index)=>{
    item.onmouseover = () => {
      imgLinksMenuAll[index].style = 'background: #FFFFFF;box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.2);';
      linkCategoryAll[index].style = 'color: #fff!important;';
      animation.style = `left: ${item.offsetLeft}px;width: ${item.clientWidth}px;`;
    }
    item.onmouseout = () => {
      imgLinksMenuAll[index].style = 'background: #FFFFFF;box-shadow: none;';
      linkCategoryAll[index].style = 'color: #000!important;';
    }
  })
// скролл верх вниз меню
  var scrolltop = pageYOffset; // запомнить
  window.addEventListener('scroll', function(){
    if (pageYOffset > scrolltop) { // сравнить
      document.querySelector('.baur_menu-btn').classList.add('down_btn');
      document.querySelector('.baurPrimaryHeader').classList.add('down');
      document.querySelector('.baur_mobile-logo .svg_logo-max').style = "display: none";
      
    } else {
      document.querySelector('.baur_menu-btn').classList.remove('down_btn');
      document.querySelector('.baurPrimaryHeader').classList.remove('down');
      document.querySelector('.baur_mobile-logo .svg_logo-max').style = "display: block";
    }
    scrolltop = pageYOffset; // запомнить для последующих срабатываний
  });

  if(document.querySelector('.pizzaro-wc-product-gallery')){
    document.querySelector('.entry-summary').prepend(document.querySelector('.pizzaro-wc-product-gallery'));
  }
  if (document.querySelector('.single_add_to_cart_button')) {
      document.querySelector('.single_add_to_cart_button').innerHTML = `<svg width="15" height="13" viewBox="0 0 15 13" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path fill-rule="evenodd" clip-rule="evenodd" d="M2.2457 2H0V0H3H3.7543L3.96152 0.725281L4.32573 2H13H14.2198L13.9806 3.19611L12.9806 8.19611L12.8198 9H12H5H4.2457L4.03848 8.27472L2.2457 2ZM4.89716 4L5.7543 7H11.1802L11.7802 4H4.89716ZM7 11.5C7 12.3284 6.32843 13 5.5 13C4.67157 13 4 12.3284 4 11.5C4 10.6716 4.67157 10 5.5 10C6.32843 10 7 10.6716 7 11.5ZM11.5 13C12.3284 13 13 12.3284 13 11.5C13 10.6716 12.3284 10 11.5 10C10.6716 10 10 10.6716 10 11.5C10 12.3284 10.6716 13 11.5 13Z" fill="white"/>
    </svg> В корзину`;
  }
  if (document.querySelectorAll('.add_to_cart_button.addticartmobile')) {
    document.querySelectorAll('.add_to_cart_button.addticartmobile').forEach(i=>{
      i.insertAdjacentHTML('afterbegin', `<svg width="15" height="14" viewBox="0 0 15 14" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path fill-rule="evenodd" clip-rule="evenodd" d="M2.2457 2.49146H0V0.491455H3H3.7543L3.96152 1.21674L4.32573 2.49146H13H14.2198L13.9806 3.68756L12.9806 8.68756L12.8198 9.49146H12H5H4.2457L4.03848 8.76617L2.2457 2.49146ZM4.89716 4.49146L5.7543 7.49146H11.1802L11.7802 4.49146H4.89716ZM7 11.9915C7 12.8199 6.32843 13.4915 5.5 13.4915C4.67157 13.4915 4 12.8199 4 11.9915C4 11.163 4.67157 10.4915 5.5 10.4915C6.32843 10.4915 7 11.163 7 11.9915ZM11.5 13.4915C12.3284 13.4915 13 12.8199 13 11.9915C13 11.163 12.3284 10.4915 11.5 10.4915C10.6716 10.4915 10 11.163 10 11.9915C10 12.8199 10.6716 13.4915 11.5 13.4915Z" fill="black"/>
      </svg>`)
    })
  }
  if (document.querySelector('.cart-collaterals .cart-subtotal td')) {
    document.querySelector('.cart-collaterals .cart-subtotal td').dataset.title = "Итого";
  }
  if (/Android/i.test(navigator.userAgent)) {
    document.querySelector('.top_header-button').href = "https://play.google.com/store/apps/details?id=ru.rodnaya.dostavka&hl=ru&gl=US";
  } if (/iPhone|iPad/i.test(navigator.userAgent)) {
    document.querySelector('.top_header-button').href = "https://apps.apple.com/ru/app/родная-доставка/id1534877300";
  }
  // добавить стили для плавного перемещения ползунка
  document.querySelector('#menu-primarybaur').append(document.querySelector('.animation'))
  // сылка на баннере
  // if (document.querySelector('rs-slide')) {
  //   document.querySelector('rs-slide').addEventListener('click', () => {
  //     if (/Android/i.test(navigator.userAgent)) {
  //       document.location.href = "https://play.google.com/store/apps/details?id=ru.rodnaya.dostavka&hl=ru&gl=US";
  //     } if (/iPhone|iPad/i.test(navigator.userAgent)) {
  //       document.location.href = "https://apps.apple.com/ru/app/родная-доставка/id1534877300";
  //     } else {
  //       console.log("Другое")
  //     }
  //   })
  // }
  document.querySelectorAll('rs-slide').forEach(i=>{
    i.classList.add('baur_banner')
  })
  const cartBaur = document.querySelector('.site-header-cart');
  const copyCartBaur = cartBaur.cloneNode(true);
  if (cartBaur) {
    document.querySelector('.baur_navigation').append(copyCartBaur)
  }
}
  // прокрутка до якоря
  $(document).ready(function(){
    $('#menu-food-menu li a').click(function() {
      var targets = $(this).attr('href');
      $('html, body').animate({
        scrollTop: $(targets).offset().top-135
      }, 500)
    })

    $('.menu_baur-items li a').click(function() {
      var target = $(this).attr('href');
      $('html, body').animate({
        scrollTop: $(target).offset().top-135
      }, 500)
    })
  })


document.querySelector('.addticartmobile').onclick = function() {
  document.querySelector('.pizzaro-handheld-footer-bar').classList.add('active_cart');
}


