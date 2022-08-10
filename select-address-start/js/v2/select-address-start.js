let address;
var map;
var deliveryZonesBaur;
let placemark_b;
var placemark;
let in_zone = false;
var baur_function;
const arrImgInCart = [];

function readTextFile(file, callback) {
  let test_json = "https://xn--80aaahhb8btheqsn2p.xn--p1ai/wp-content/themes/pizzaro/select-address-start/select-address-start-new.geojson";
  // let test_json = "https://xn--80ahe4adlmgc0k.xn--p1ai/rodndost/wp-content/themes/pizzaro/select-address-start/select-address-start-new.geojson";
    let rawFile = new XMLHttpRequest();
    rawFile.overrideMimeType("application/json");
    rawFile.open("GET", test_json, true);
    rawFile.onreadystatechange = function() {
        if (rawFile.readyState === 4 && rawFile.status === 200) {
            // console.log(rawFile.responseText);
            // console.log(JSON.parse(rawFile.responseText));
            callback(rawFile.responseText);
        }
    }
    rawFile.send(null);
}

function createPlacemark(coords) {
    return new ymaps.Placemark(coords, {
        iconCaption: 'поиск...'
    }, {
        preset: 'islands#orangeDotIconWithCaption',
        draggable: true
    });
}

function reZoomYM(map) {
    map.setBounds(map.geoObjects.getBounds(), {
            checkZoomRange: true,
            zoomMargin: 10
        }
    );
}

function onZonesLoad(json, map, items) {
    // let placemark;
    // var placemark;

    // Добавляем зоны на карту.
    // deliveryZonesBaur = ymaps.geoQuery(json).addToMap(map);
    const deliveryZones = ymaps.geoQuery(json).addToMap(map);
    // Задаём цвет и контент балунов полигонов.
    deliveryZones.each(function (obj) {
        obj.options.set({
            fillColor: obj.properties.get('fill'),
            fillOpacity: obj.properties.get('fill-opacity'),
            strokeColor: obj.properties.get('stroke'),
            strokeWidth: obj.properties.get('stroke-width'),
            strokeOpacity: obj.properties.get('stroke-opacity'),
            hasBalloon: false,
        });
        obj.properties.set('balloonContent', obj.properties.get('description'));
        obj.events.add('click', function (e) {
            const coords = e.get('coords');
            console.log(e);
            // console.log("Клик по карте");
            // console.log(placemark_b);
            map.geoObjects.remove(placemark_b);
            // getAddress(coords);
            baur_function(coords);
        });
    });
    deliveryZonesBaur = deliveryZones
    // Проверим попадание метки геолокации в одну из зон доставки.
    map.controls.get('geolocationControl').events.add('locationchange', function (e) {
        const coords = e.get('geoObjects').get(0).geometry.getCoordinates();
        // getAddress(coords);
        baur_function(coords);
    });

    map.events.add('click', function (e) {
        const coords = e.get('coords');
        
        // getAddress(coords);
        baur_function(coords);
    });
    
    // мое место положение
    
baur_function = function getAddress(coords) {
      // console.log('получение координат')
      // console.log(coords)
      // console.log(coords)
        if (placemark) {
            placemark.geometry.setCoordinates(coords);
        } else {
            placemark = createPlacemark(coords);
        }

        placemark.properties.set('iconCaption', 'поиск...');

        ymaps.geocode(coords).then(function (res) {
            const firstGeoObject = res.geoObjects.get(0);
            highlightResult(coords, deliveryZones);

            address = [
                firstGeoObject.getThoroughfare() || firstGeoObject.getPremise(),
                firstGeoObject.getPremiseNumber() || ''
            ].filter(Boolean).join(', ')

            placemark.properties
                .set({
                    iconCaption: address,
                    balloonContent: address
                });
            map.geoObjects.add(placemark);
            placemark.events.add('dragend', function () {
                getAddress(placemark.geometry.getCoordinates())
            });
            setAddressToHTML(items, address)
            // проверяем попадает ли в зону доставки и не даем нажать на кнопку подтаердить
            clickButton('.select-address-start__button--orange', onPressButtonForm)
        });

    }
    reZoomYM(map);
}

function setAddressToHTML(items, address) {
    const dom = document.querySelectorAll(items);
    localStorage.adsressStreet = address;
    if (dom) {
        for (let item of dom) {
          if (item.tagName === "INPUT" && in_zone) {
            item.style = 'border-color: #D5D5D5';
            item.value = address;
          } else {
            item.value = "";
            item.style = 'border-color: red';
          }
            // console.log('item.tagNam', item.tagName)
            // if (item.tagName === "INPUT") {
            //     item.value = address;
            // } else if (item.tagName === 'SPAN' | item.tagName === 'DIV') {
            //     item.innerHTML = address;
            // }
        }
    }
    if (address && in_zone) {
      in_zone_block.innerText = 'Ваш адрес в зоне доставки'
      in_zone_block.style = 'color: #94BC21'
    } else {
      in_zone_block.innerText = 'Ваш адрес не в зоне доставки'
      in_zone_block.style = 'color: #D62B07'
    }
}

function highlightResult(coords, zones) {
    const polygon = zones.searchContaining(coords).get(0);
    console.log('polygon',polygon);
    if (polygon) {
        // Уменьшаем прозрачнсть всех полигонов, кроме того, в который входят переданные координаты.
        zones.setOptions('fillOpacity', 0.4);
        polygon.options.set('fillOpacity', 0.8);
        in_zone = true;
        // console.log('check!!!!!');
        // console.log(polygon);
        // console.log('polygon.properties!!!!!');
        // console.log(polygon.properties._data);
        console.log(polygon.properties._data.id_storage);
        // время доставки........
        if(polygon.properties._data.hasOwnProperty('deliverytime')){
            localStorage.setItem('deliverytime', polygon.properties._data.deliverytime);
        }else {
            localStorage.removeItem('deliverytime');
        }

        if(polygon.properties._data.hasOwnProperty('id_storage')){
            // console.log("есть id склада");
            localStorage.setItem('storageId', polygon.properties._data.id_storage);
        }else {
            localStorage.removeItem('storageId');
        }
    } else {
        zones.setOptions('fillOpacity', 0.4);
        in_zone = false;
        return false;
    }
}
function geocode(map_baur, in_zone_block) {
    // Забираем запрос из поля ввода.
    var request = $(map_baur).val();
    // Геокодируем введённые данные.
    ymaps.geocode(request, {
      boundedBy: [ [61.264321,55.210758], [61.500946, 55.127232] ],strictBounds: true
    }).then(function (res) {
        var obj = res.geoObjects.get(0);
        showResult(obj,map_baur,in_zone_block);
    }, function (e) {
        console.log(e)
        in_zone_block.innerText = ''
    })

}
function showResult(obj,map_baur,in_zone_block) {
    var mapContainer = $(map_baur),
        bounds = obj.properties.get('boundedBy'),
    // Рассчитываем видимую область для текущего положения пользователя.
        mapState = ymaps.util.bounds.getCenterAndZoom(
            bounds,
            [mapContainer.width(), mapContainer.height()]
        );
        console.log(obj,'obj');
    // Сохраняем укороченный адрес для подписи метки.
        shortAddress = [obj.getThoroughfare(), obj.getPremiseNumber(), obj.getPremise()].join(' ');
    // Убираем контролы с карты.
    // Проверяем находится ли адрес в зоне доставки
        if (obj.getPremiseNumber() && in_zone) {
          in_zone_block.innerText = 'Ваш адрес в зоне доставки'
          in_zone_block.style = 'color: #94BC21'
        } else {
          in_zone_block.innerText = 'Ваш адрес не в зоне доставки'
          in_zone_block.style = 'color: #D62B07'
        }
        mapState.controls = [];
        if (in_zone) {
          localStorage.adsressStreet = shortAddress
        }
        // localStorage.adsressStreet = shortAddress
    // затемняем область попадания метки
    highlightResult(mapState.center, deliveryZonesBaur);
    // Ставим метку на карту.
    createMap(mapState, shortAddress);
}
function createMap(state, caption) {
  // Если карта еще не была создана, то создадим ее и добавим метку с адресом.
  // console.log(placemark_b)
  // console.log(placemark_b === undefined)
  
  if (!placemark_b || placemark_b === undefined) {
    placemark_b = new ymaps.Placemark(
          map.getCenter(), {
              iconCaption: caption,
              balloonContent: caption
          }, {
              preset: 'islands#redDotIconWithCaption'
          });
      // placemark.setBounds(bounds);
      map.geoObjects.add(placemark_b);
      map.geoObjects.remove(placemark);
      // Если карта есть, то выставляем новый центр карты и меняем данные и позицию метки в соответствии с найденным адресом.
  } else {
      // map.setCenter(state.center, state.zoom);
      map.setCenter(state.center, 14, {duration: 300});
      map.geoObjects.add(placemark_b);
      map.geoObjects.remove(placemark);
      // placemark_b.removeAll();
      placemark_b.geometry.setCoordinates(state.center);
      placemark_b.properties.set({iconCaption: caption, balloonContent: caption});
  }
}
// function createMap(state, caption,map_id_baur) {
//   // Если карта еще не была создана, то создадим ее и добавим метку с адресом.
//   // console.log(placemark)
//   // if (!placemark_b) {
//     placemark_b = new ymaps.Placemark(
//           map.getCenter(), {
//               iconCaption: caption,
//               balloonContent: caption
//           }, {
//               preset: 'islands#redDotIconWithCaption'
//           });
//       // placemark.setBounds(bounds);
//       map.geoObjects.add(placemark_b);
//       // Если карта есть, то выставляем новый центр карты и меняем данные и позицию метки в соответствии с найденным адресом.
//   // } else {
//       map.setCenter(state.center, state.zoom);
//       map.geoObjects.remove(placemark);
//   //     // placemark_b.removeAll();
//   //     placemark_b.geometry.setCoordinates(state.center);
//   //     placemark_b.properties.set({iconCaption: caption, balloonContent: caption});
//   // }
// }

// мое место положение
function location_me_good() {
  console.log('мое место положение');
  var geolocation = ymaps.geolocation;
  geolocation.get({
      provider: 'browser',
      mapStateAutoApply: true,
      autoReverseGeocode: false
  }).then(function (result) {
      // Синим цветом пометим положение, полученное через браузер.
      // Если браузер не поддерживает эту функциональность, метка не будет добавлена на карту.
      result.geoObjects.options.set('preset', 'islands#blueCircleIcon');
      let loc = result.geoObjects.position;
      // console.log(loc)
      // console.log(result.geoObjects.get(0).geometry.getCoordinates())
      // add_location(result.geoObjects.position);
      // highlightResult([61.435567, 55.166300], deliveryZonesBaur);
      highlightResult(loc, deliveryZonesBaur);
      // baur_function([61.415950, 55.168244 ])
      baur_function(loc)
      map.setCenter(result.geoObjects.get(0).geometry.getCoordinates(), 14, {duration: 300});
      // map.setCenter([61.415950, 55.168244 ], 14, {duration: 300});
      // baur_function([61.435567, 55.166300])
      // map.geoObjects.add(result.geoObjects);
  });
}

function initMapYM(element) {
    map = new ymaps.Map(element, {
        center: [61.398414, 55.165379],
        zoom: 7,
        // controls: ['geolocationControl'],
    });
    var searchControl = new ymaps.control.SearchControl({
        options: {
            // Будет производиться поиск по топонимам и организациям.
            provider: 'yandex#map',
            boundedBy:[ [61.264321,55.210758], [61.500946, 55.127232] ],
            strictBounds: true
      }
    });
    // var geolocationInMap = ymaps.geolocation;
    // geolocationInMap.get({
    //     provider: 'browser',
    //     mapStateAutoApply: true,
    //     autoReverseGeocode: false
    // }).then(function (result) {
    //     // Синим цветом пометим положение, полученное через браузер.
    //     // Если браузер не поддерживает эту функциональность, метка не будет добавлена на карту.
    //     result.geoObjects.options.set('preset', 'islands#blueCircleIcon');
    //     let loc = result.geoObjects.position;
    //     console.log(loc)
    //     // add_location(result.geoObjects.position);
    //     highlightResult(loc, deliveryZonesBaur);
    //     // baur_function([61.415950, 55.168244 ])
    //     baur_function(loc)
    //     // map.geoObjects.add(result.geoObjects);
    // });
    map.controls.add(searchControl);
    return map;
}


function initSuggestYM(element, map) {
    const suggest = new ymaps.SuggestView(element, {results: 3});
    return suggest;
}

function sayHi() {
  document.querySelector('.select-address-start__wrapper').style = "transform: translateY(0)!important;"
}

function xxx() {
  document.querySelector('.baur_modal-fix_onclick-null').classList.add('active')
  document.querySelector('.baur_modal-fix_container .in_address').addEventListener('click', () => {
    document.querySelector('.baur_modal-fix_onclick-null').classList.remove('active')
    if (window.innerWidth > 768) {
      document.querySelector('.editing_an_address').parentElement.click();
    } else {
      document.querySelector('.m_baur_search-form').click();
    }
  })
  document.querySelector('.baur_modal-fix_container .resume').addEventListener('click', () => {
    console.log('delete modal')
    document.querySelector('.baur_modal-fix_onclick-null').classList.remove('active')
  })
  // (window.innerWidth <= 768) && document.querySelector('.m_baur_search-form').click();
}

const clickButton = (x, y) => {
  if (in_zone) {
    document.querySelector(x).addEventListener('click', y)
    document.querySelector(x).classList.remove('no-active')
  } else {
    document.querySelector(x).removeEventListener('click', y)
    document.querySelector(x).classList.add('no-active')
  }
}

const clickNull = () => {
  if (window.innerWidth <= 768) {
    if (localStorage.adsressStreetAndFlat === "" || add_address.innerText === "Введите адрес доставки") {
      document.querySelectorAll('.baur_prici-opacity-null').forEach(item=>{
        item.addEventListener('click', xxx)
      })
    } else {
      document.querySelectorAll('.price').forEach(item=>{
        item.removeEventListener('click', xxx)
      })
    }
  } else {
    if (localStorage.adsressStreetAndFlat === "" || add_address.innerText === "Введите адрес доставки") {
      document.querySelector('.baur_modal-fix_onclick-null').addEventListener('click', () => {
        document.querySelector('.baur_modal-fix_onclick-null').classList.remove('active')
      })
      document.querySelectorAll('.ajax_add_to_cart_nopress').forEach(item=>{
        item.addEventListener('click', xxx)
      })
    } else {
      document.querySelectorAll('.hover-area').forEach(item=>{
        item.removeEventListener('click', xxx)
      })
    }
  }
  
} 

const noPress = () => {
  if (localStorage.adsressStreetAndFlat === "" || add_address.innerText === "Введите адрес доставки") {
    document.querySelectorAll('.woocommerce-loop-product__link').forEach(i=>{
      i.title = "Введите адрес доставки"
      i.style = "cursor: no-drop"
    })
    document.querySelectorAll('.hover-area').forEach(item=>{
      item.title = "Введите адрес доставки"
      item.classList.add('ajax_add_to_cart_nopress');
    })
    document.querySelectorAll('.site-header-cart.menu').forEach(item => {
      item.querySelector('.cart-contents').title = "Введите адрес доставки"
      item.querySelector('.cart-contents').style = "cursor: no-drop"
      item.onclick = event => {
        return false
      }
    })
    document.querySelectorAll('.product-inner .product-content-wrapper .price').forEach(item=>{
      item.classList.add('baur_prici-opacity-null')
    })
  } else {
    document.querySelectorAll('.woocommerce-loop-product__link').forEach(i=>{
      i.title = ""
      i.style = "cursor: pointer"
    })
    document.querySelectorAll('.site-header-cart.menu').forEach(item => {
      item.querySelector('.cart-contents').title = "Показать вашу карзину"
      item.querySelector('.cart-contents').style = "cursor: pointer"
      item.onclick = event => {        
        return true
      }
    })
    document.querySelectorAll('.hover-area').forEach(item=>{
      item.title = ""
      item.classList.remove('ajax_add_to_cart_nopress');
    })
    document.querySelectorAll('.product-inner .product-content-wrapper .price').forEach(item=>{
      item.classList.remove('baur_prici-opacity-null')
    })
  }
  

}


// noPress();

class Modal {
    element;

    constructor(element) {
        this.element = document.getElementById(element);
    }

    open(callback) {
        this.element.style.display = 'block';
        if (callback) {
            callback();
        }
    }

    close(callback) {
      // document.querySelector('.select-address-start__wrapper').style = "transform: translateY(100%);"
        // setTimeout(this.element.style.display = 'none', 1000)
        this.element.style.display = 'none';
        if (callback) {
            callback();
        }
    }
}

window.onload = config;

function config() {
  console.log('elll тут сработал')

    window.modal = (element) => new Modal(element);
    document.querySelector('.baurPrimaryHeader').classList.remove('stuck');
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
  const heightBlockHeaderMini = document.querySelector('.baurPrimaryHeader').offsetHeight;
  const activePrimaryMenuAll = document.querySelectorAll('#menu-food-menu li a');
  const mobileLinkMenu = document.querySelectorAll('#menu-food-menu-1 li');
  const sectionProducts = document.querySelectorAll('.section-products .section-title');
  const sectionProductsArr = [];
  const visibleBlockOnNight = document.querySelector('.top_header-date');
  const animation = document.querySelector('.animation');
  const addressBlock = document.querySelector('.baur_delivery-city'); 
  const scrollHorizontAuto = document.querySelector('.baur_header_bottom_menu');
  const cartBaur = document.querySelector('.site-header-cart');
  const copyCartBaur = cartBaur.cloneNode(true);

  if (cartBaur) {
    document.querySelector('.baur_navigation').append(copyCartBaur)
  }
  
  // фильтр по складам!!!!!!!!!!!!!!
  // if(document.querySelector('#baur_toggle-corol')) {
  //   document.querySelector('#baur_toggle-corol').addEventListener('click', () => {
  //     document.querySelectorAll('.type-product').forEach(item=>{
  //       item.style = "display:none"
  //       document.querySelectorAll('.baur_mega_test-corol').forEach(e=>{
  //         if (item.classList.contains(e.innerText)) {
  //             item.style = "display:block"
  //         }
  //       })
  //     })
  //   })
  //   document.querySelector('#baur_toggle-marc').addEventListener('click', () => {
  //     document.querySelectorAll('.type-product').forEach(item=>{
  //       item.style = "display:none"
  //       document.querySelectorAll('.baur_mega_test-mark').forEach(e=>{
  //         if (item.classList.contains(e.innerText)) {
  //             item.style = "display:block"
  //         }
  //       })
  //     })
  //   })
  //   document.querySelector('#baur_toggle-sbros').addEventListener('click', () => {
  //     document.querySelectorAll('.type-product').forEach(item=>{
  //       item.style = "display:block"
  //     })
  //   })
  // }
  

  // const noPress = () => {
  //   console.log('сработал')
  //   if (localStorage.adsressStreetAndFlat === "" || add_address.innerText === "Введите адрес доставки") {
  //     document.querySelectorAll('.hover-area a').forEach(item=>{
  //       item.classList.add('ajax_add_to_cart_nopress');
  //     })
  //     document.querySelector('.site-header-cart.menu').addEventListener('click', event => {
  //         event.preventDefault();
  //         console.log(event)
  //     })
  //   } else {
  //     document.querySelectorAll('.hover-area a').forEach(item=>{
  //       item.classList.remove('ajax_add_to_cart_nopress');
  //     })
  //   }
  // }
  noPress();
  clickNull();
  // if (localStorage.adsressStreetAndFlat === "" || add_address.innerText === "Введите адрес доставки") {
  //   noPress();
  // }
  // document.querySelector('.select-address-start__button--orange').addEventListener('click', () => {
  //   noPress();  
  // })

  // проверяет есть ли адрес???
  if (localStorage.adsressStreetAndFlat === undefined || localStorage.adsressStreetAndFlat === "") {
      add_address.innerText = "Введите адрес доставки";
      m_add_address.innerText = "Введите адрес доставки";
      addressBlock?addressBlock.innerText = "Введите ваш адрес доставки":null
  } else {
      addressBlock?addressBlock.innerText=localStorage.adsressStreetAndFlat:null
      add_address.innerText = localStorage.adsressStreetAndFlat;
      m_add_address.innerText = localStorage.adsressStreetAndFlat;
  }

  // замена кнопки выбрать адрес на страницы офрмления
  if (location.href === "https://xn--80aaahhb8btheqsn2p.xn--p1ai/checkout-2/") {
    document.querySelector('.baur_new_map_on_check').addEventListener('click', () => {
      if (window.innerWidth > 768) {
        document.querySelector('.editing_an_address').parentElement.click();
      } else {
        document.querySelector('.m_baur_search-form').click();
      }
      
    })
    console.log('Страница оформления загрузилась');
	console.log(parseInt(localStorage.deliverytime));
    document.querySelector("#delivery-address").innerText = localStorage.adsressStreetAndFlat;
    document.querySelector('#shipping_deliv_time').value = parseInt(localStorage.deliverytime);
    document.querySelector('#billing_address_1').value = localStorage.adsressStreetAndFlat;
    //document.querySelector('#billing_address_2').value = localStorage.apartmentInDone; 
        // }
  } 
  document.querySelectorAll('.baur_delivery-city').forEach((e) => {
    e.addEventListener('click', () => {
      menuContainer.classList.remove('baur_active');
      document.querySelector('.m_baur_search-form').click();
    })
  })
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
  // console.log(sectionProductsArr,'sectionProductsArr')
  //

  window.addEventListener('scroll', function() {
    // console.log("scroll")
    // выпад меню
    if ( pageYOffset>90 ) {
      document.querySelector('.baurPrimaryHeader').classList.add('stuck')
    }
    else {
      document.querySelector('.baurPrimaryHeader').classList.remove('stuck');
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
    // поставить на центр выбираймую категорию 
    sectionProductsArr.forEach(block => {
      mobileLinkMenu.forEach(item => {
        if (block.firstElementChild.textContent === item.textContent) {
          if (block.offsetTop - (pageYOffset + heightBlockHeaderMini) > 0 || (block.offsetTop + block.offsetHeight) - (pageYOffset + heightBlockHeaderMini) < 0 ) {
            item.lastElementChild.style = "color: #343941"
          } else {
            item.lastElementChild.style = "color: #fff"
            animation.style = `left: ${item.offsetLeft}px;width: ${item.clientWidth}px;`;
            scrollHorizontAuto.scrollLeft = item.offsetLeft + (item.offsetWidth/2) - (scrollHorizontAuto.clientWidth/2);
          }
        }
      })
    })
  });

// вывод надписи вверху ночью с 22.30 до 11 утра
  const url = 'https://роднаядоставка.рф/wp-json/wc/v3/general-info/'
  fetch(url)
  .then(data => {
    return data.json()
  })
  .then(data => {
    // console.log(data.week)
    const keys = Object.values(data.week)
    keys.unshift(keys.pop())
    // console.log('keys', keys)
    keys.forEach((item, index) => {
      if (index === new Date().getDay()) {
        console.log('item', item)
        console.log('item.from', item.from)
        document.querySelector('.top_header-date_top').innerText = `Мы работаем с ${item.from}, но уже сейчас готовы принять заказ.`;
        document.querySelector('.baur_phone-num span').innerText = `C ${item.from} до ${item.to}`;
        document.querySelector('#time_baur').innerText = `c ${item.from} до ${item.to}`;
        if ( new Date().toLocaleTimeString().slice(0,-3) >= item.to || new Date().toLocaleTimeString().slice(0,-3) <= item.from ) {
          visibleBlockOnNight.style = "display: flex";
        } else {
          console.log('Утро, не оповещения')
        }
      }
    })
    // return dateOfSite = data.week
  });
  // if ( new Date().getHours() >= 23 || new Date().getHours() <= 8 ) {
  //   visibleBlockOnNight.style = "display: flex";
  // }
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
    // imgLinksMenuAll[id].style = 'background: #FFFFFF;box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.2);';
    animation.style = `left: ${activeCategoryAll[id].offsetLeft}px;width: ${activeCategoryAll[id].clientWidth}px;`;

  }
  // наведение на категории
  activeCategoryAll.forEach((item,index)=>{
    item.onmouseover = () => {
      // imgLinksMenuAll[index].style = 'background: #FFFFFF;box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.2);';
      linkCategoryAll[index].style = 'color: #fff!important;';
      animation.style = `left: ${item.offsetLeft}px;width: ${item.clientWidth}px;`;
    }
    item.onmouseout = () => {
      // imgLinksMenuAll[index].style = 'background: #FFFFFF;box-shadow: none;';
      linkCategoryAll[index].style = 'color: #343941!important;';
    }
  })
// скролл верх вниз меню
  // var scrolltop = pageYOffset; // запомнить
  // window.addEventListener('scroll', function(){
  //   if (pageYOffset > scrolltop) { // сравнить
  //     // document.querySelector('.baur_menu-btn').classList.add('down_btn');
  //     // document.querySelector('.baurPrimaryHeader').classList.add('down');
  //     // document.querySelector('.baur_mobile-logo .svg_logo-max').style = "display: none";

  //   } else {
  //     // document.querySelector('.baur_menu-btn').classList.remove('down_btn');
  //     // document.querySelector('.baurPrimaryHeader').classList.remove('down');
  //     // document.querySelector('.baur_mobile-logo .svg_logo-max').style = "display: block";
  //   }
  //   scrolltop = pageYOffset; // запомнить для последующих срабатываний
  // });

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
  
  document.querySelectorAll('rs-slide').forEach(i=>{
    i.classList.add('baur_banner')
  })
  
}
