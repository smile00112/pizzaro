let address;
var map;
var deliveryZonesBaur;
var placemark_b;
var placemark;
var last_obj; //Последний геокодируемый объект
let in_zone = false;
var baur_function;
const arrImgInCart = [];
var divliv_settings_zones = [];
var geocodeTimer;
var address_obj_tmp;
var adress_replace_str = 'Россия, Челябинск, ';
var debug = true;

var __center = null;
var __boundedBy = null;
var __map_json = null;
var __conditions = null;

var address_field_selector = '#address-map';
var inputSetAddress = document.querySelector('#address-map');
var buttonSetAddress = document.querySelector('.editing_an_address').parentElement;
var add_address = document.querySelector('.add_address');
var input_apartment = document.querySelector('#apartment');
const location_me = document.querySelector('.select-address-location');
var in_zone_block = document.querySelector('.select-address-in_zone');

// var consosole = {
//   error: function(t1, t2, t3){
//     if(debug){
//       if(typeof t1 != 'undefined') console.error(t1)
//       if(typeof t2 != 'undefined') console.error(t2)
//     }
//   }
// }

function show_address_map(){
  if (typeof map == 'undefined')
  {

    /* Получаем настройки для карты*/
    $.getJSON('/wp-json/systeminfo/v1/delivery') 
    .done(function( data ) {
        console.warn( "JSON Data: ", data );

        data ?? {};
        if(data){
            __center = data.dot_map_init.split(',');
            __boundedBy = JSON.parse( data.square_coord ); //[ [61.264321,55.210758], [61.500946, 55.127232] ];//
            __map_json = data.url_file_map;   
            __conditions = data.conditions;       

            const pathJSON = data.url_file_map;
            
            ymaps.ready(function(){

              /*Инициализация карты*/ 
              map = initMapYM('map-select-first');
              readTextFile(__map_json, (text) => onZonesLoad(JSON.parse(text), map, '#address-map, .m_add_address'));
              const mapBlock = document.querySelector('#map-select-first');
              $('#map-preloader').remove();
              //mapBlock.classList.add('map-select-first--active')
                
              /* Поле ввода адреса */
              var suggestView_m = new ymaps.SuggestView('address-map',{
                  results: 4,
                  //provider: 'yandex#map',
                  provider: {  //по другому убрать страну и город из адреса не вышло
                    suggest: (function(request, options) {
                        return ymaps.suggest(adress_replace_str+' '+request).then(function(items){
                          return  items.map( (item) => {
                            item.value = item.value.replace(adress_replace_str, '');
                            return item;
                          });
                        });
                    })
                   },
                  boundedBy:__boundedBy,
                  strictBounds: true,
                  // layout: 'islands#options'
              });

              suggestView_m.events.add('select', function (e) {

                geocode(address_field_selector);

                /*проверить и убрать*/
                const s = e.originalEvent.item.value.replace(adress_replace_str, '');
                e.originalEvent.item.value = s;
                
                //  $('#address-map').val(s)
                  // $('#address-map').val(e.get('item').value.replace(adress_replace_str, ''));
                  // $('.select-address-start-input ymaps').hide(); 
                  // $(inputSetAddress).focus(); 
              });

              // Определение местоположения.
              const location_me = document.querySelector('.select-address-location');
              if(location_me){
                location_me.addEventListener('click', () => {
                  location_me_good(address_field_selector);
                });
              }

              $(document).on('keyup', address_field_selector, function(e){
                
              //inputSetAddress.addEventListener('keyup', (e) => {
                // clearTimeout( geocodeTimer );
                // geocodeTimer = setTimeout( function (){
                //     geocode(address_field_selector);
                // }, 1000);
                  //clickButton('.select-address-start__button--orange', onPressButtonForm)
              });
              
              $(document).on('keypress', address_field_selector, function(e){
              //inputSetAddress.addEventListener('keypress', (e) => {
                  if (e.key === 'Enter') {
                      geocode(address_field_selector);
                      //clickButton('.select-address-start__button--orange', onPressButtonForm)
                  } 
              });

              // $(document).on('change', address_field_selector, function(e){

              //     //inputSetAddress.addEventListener('keypress', (e) => {
              //     // console.error('SuggestView value__', $(this).val())
              //     // alert($(this).val().replace('Россия, Челябинск,', ''));
              //     // $(this).val($(this).val().replace('Россия, Челябинск,', ''));
              // });
              
              // $(document).on('click', address_field_selector, function(e){
              // //inputSetAddress.addEventListener('click', (e) => {
              //     //geocode(address_field_selector,in_zone_block,inputSetAddress);
              //     //clickButton('.select-address-start__button--orange', onPressButtonForm)
              //     // inputSetAddress.value.replace('Россия, Челябинск, ', '')
              // });


          });


          // console.warn('_________',__center); 
          // console.warn('_________',__boundedBy); 
          // console.warn('____с2_____',[ [61.264321,55.210758], [61.500946, 55.127232] ]);           
          // console.warn('_________', data); 

          /* Если при загрузке адрес не пустой, запустим геокодирование */
          setTimeout( function (){
            geocode(address_field_selector);
          }, 1000); 
        }


          
    })
    .fail(function( jqxhr, textStatus, error ) {
        var err = textStatus + ", " + error;
        console.error( "Request Failed: ", err );
        alert('Ошибка загрузки карты')
    });
  }
  
  show_modal('select-address_form');

  /*Переопределение переменных после dspjsdf модального окна*/
    in_zone_block = document.querySelector('.select-address-in_zone');
    inputSetAddress = document.querySelector('#address-map');
    buttonSetAddress = document.querySelector('.editing_an_address').parentElement;
    add_address = document.querySelector('.add_address');
    input_apartment = document.querySelector('#apartment');
}


function onPressButtonForm() {
  alert('z1  onPressButtonForm')
  localStorage.adsressStreetAndFlat = `${localStorage.adsressStreet}${input_apartment.value !== '' ? localStorage.apartment : ''}`;
  // localStorage.adsressStreetAndFlat = localStorage.adsressStreet + (localStorage.apartment ? input_apartment.value !== '' : '');

  add_address.innerText = localStorage.adsressStreetAndFlat;
  m_add_address.innerText = localStorage.adsressStreetAndFlat;
  if (document.querySelector('#delivery-address')) {
      document.querySelector('#delivery-address').innerText = localStorage.adsressStreetAndFlat;
      document.querySelector('#billing_address_1').value = localStorage.adsressStreetAndFlat;
      document.querySelector('#billing_address_2').value = localStorage.apartmentInDone;
  }
  if (window.innerWidth > 768) {
      // document.querySelector('.select-address-start__button--orange').onclick = () => {
         // modal('set-first-address').close()
      // }
  } else {
     
       //   closeBaur()
   
  }
  //noPress();
  //clickNull();

}


//координаты магазина
var map_strg_lat1;
var map_strg_lat2;
var origin_url   = window.location.origin;


function readTextFile(file, callback) {
    let rawFile = new XMLHttpRequest();
    rawFile.overrideMimeType("application/json");
    rawFile.open("GET", file, true);
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
    // Добавляем зоны на карту.
    // deliveryZonesBaur = ymaps.geoQuery(json).addToMap(map);
    __json = json;
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
        //console.log(obj.properties._data.description);

        //////////////координаты магазина на карте. в коде карты должен быть #storage
        let zone_desc = obj.properties._data.description;
        if(zone_desc.indexOf('#storage') >= 0){
          //console.log(zone_desc);
          map_strg_lat1 =  obj.geometry._coordinates[0];
          map_strg_lat2 =  obj.geometry._coordinates[1];
          //console.warn(map_strg_lat1,' , ',map_strg_lat2);
          //document.cookie = 'map_point_storage='+map_strg_lat1+','+map_strg_lat2;
        }

        ////////////
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
      //console.error('baur_function', coords)

      if(typeof placemark != "undefined")
        placemark.properties.set('iconCaption', 'поиск...');

        ymaps.geocode(coords).then(function (res) {

            const firstGeoObject = res.geoObjects.get(0);

            //console.error('geocode coord obj', firstGeoObject);

            //выбраный адрес пишем в куки
            var address_cookie = firstGeoObject.properties._data.text; 
            document.cookie = 'address_sel='+address_cookie; 
            // console.log('firstGeoObj: ', address_cookie);
            // console.log('placemark.properties: ', placemark.properties);

            /* Если в зоне, сохраняем объект */
            if( highlightResult(coords, deliveryZones) )
              last_obj = firstGeoObject;
            
            //ставим метку
            show_placemark(firstGeoObject, coords);
            
            // placemark.events.add('dragend', function () {
            //     getAddress(placemark.geometry.getCoordinates())
            // });

            
            // проверяем попадает ли в зону доставки и не даем нажать на кнопку подтаердить
            // clickButton('.select-address-start__button--orange', onPressButtonForm)

        });
    }
    reZoomYM(map);
}

function show_placemark(GeoObject, coords_exists){
  //console.error('show_placemark',GeoObject );

  var coords = (!!coords_exists) ? coords_exists : GeoObject.geometry.getCoordinates();
  //console.error('show_placemark  coords',coords );  
  if (placemark) {
      placemark.geometry.setCoordinates(coords);
  } else {
      placemark = createPlacemark(coords);
  }

  address = [
    GeoObject.getThoroughfare() || GeoObject.getPremise(),
    GeoObject.getPremiseNumber() || ''
  ].filter(Boolean).join(', ')

  //console.error('show_placemark  address',address );  

  //.geometry.getCoordinates()
  placemark.properties
      .set({
          iconCaption: address,
          balloonContent: address
      });
  map.geoObjects.add(placemark);

  /* Если был клик по карте, надо вполе адреса подставить адрес */
  if(!!coords_exists) $(address_field_selector).val(address);
  
};

function setAddressToHTML(items, address) { 
  
    //console.eror('setAddressToHTML');
    return  false;
    const dom = document.querySelectorAll(items);
    localStorage.adsressStreet = address;

	  //$('.m_add_address').text( localStorage.adsressStreet );
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
    } else { console.log('not in zone text');
      in_zone_block.innerText = 'Ваш адрес не в зоне доставки'
      in_zone_block.style = 'color: #D62B07'
    }
}

function highlightResult(coords, zones) {
  //console.error('highlightResult');
  const polygon = zones.searchContaining(coords).get(0);
  if (polygon) {
      // Уменьшаем прозрачнсть всех полигонов, кроме того, в который входят переданные координаты.
      zones.setOptions('fillOpacity', 0.4);
      polygon.options.set('fillOpacity', 0.8);
      in_zone = true;
      var stock_error = false;
      //console.log('in_zone = true');

      in_zone_block.innerText = 'Ваш адрес в зоне доставки'
      in_zone_block.style = 'color: #94BC21'


 		 var store_data = polygon.properties._data.description;
		 var map_store = '';
    //  console.log(__json);
		//  console.log(__conditions);
		//  console.log(store_data.split('#cid=')[1].replace('#cid=', ''));//.split('#cid=').replace('#cid=', '')
     map_store = store_data.split('#cid=')[1].replace('#cid=', '');

     /* Ищем склад по адресу */
     if(!!__conditions){
      for (var k in __conditions) {
        //console.error(__conditions[k]);
        if(map_store == __conditions[k].zone){
          id_storage = __conditions[k].warehouse_id;
          time_deliv = __conditions[k].time_deliv;
          // console.warn('warehouse_id', __conditions[k].warehouse_id);
          // console.warn('time_deliv', __conditions[k].time_deliv);
        }
      }
      if(!id_storage) stock_error = true;
      
      if (id_storage) {
          localStorage.setItem('storageId', id_storage);
      } else {
          localStorage.removeItem('storageId');
      } 
    }

    if(stock_error) alert('Склад не найден!')


    //set_cookie_deliv(polygon, coords);

      /*let zone_desc = polygon.properties._data.description;
      let zone_id = zone_desc.split('#cid=').pop();
      if(zone_id){
        delete_cookie('zone_deliv','','');
        document.cookie = 'zone_deliv='+zone_id;
        delete_cookie('zone_deliv2','/','');
        document.cookie = 'zone_deliv2='+zone_id+'; path=/;';
      }
      
      console.log(coords[0],coords[1],map_strg_lat1,map_strg_lat2);
      let range_km = coordLocToKm(coords[0],coords[1],map_strg_lat1,map_strg_lat2);
      range_km = parseFloat(range_km).toFixed(2);
      document.cookie = 'range_deliv='+range_km+'; path=/;';;
      console.log(range_km);*/

  
      // время доставки........
      // if(polygon.properties._data.hasOwnProperty('deliverytime')){
      //     localStorage.setItem('deliverytime', polygon.properties._data.deliverytime);
      // }else {
      //     localStorage.removeItem('deliverytime');
      // }

      // if(polygon.properties._data.hasOwnProperty('id_storage')){
      //     // console.log("есть id склада");
      //     localStorage.setItem('storageId', polygon.properties._data.id_storage);
      // }else {
      //     localStorage.removeItem('storageId');
      // }




      return true;

  } else {

      in_zone_block.innerText = 'Ваш адрес не в зоне доставки'
      in_zone_block.style = 'color: #D62B07'
      
      console.log('in_zone = false');
      zones.setOptions('fillOpacity', 0.4);
      in_zone = false;
      
      return false;
  }
}

function geocode(address_field_selector) {
    // Забираем запрос из поля ввода.
    var request = $(address_field_selector).val(); 
    //console.error('__geocode');

    if(!request) return;

    var already_find = ( (!!address_obj_tmp) && (address_obj_tmp.getAddressLine().replace($adress_replace_str, '') == address_field_selector.replace('  ', ' ') ) );
    // console.log('input addr: ',request);
    // console.log('already_find: ',already_find);
    //address_obj_tmp.getAddressLine().replace($adress_replace_str, '');

    //Если адреса не совпадают
    if( already_find == false ){
     // console.log('to geocode__'+request);
      // Геокодируем введённые данные.
      ymaps.geocode(request, {
        boundedBy: __boundedBy,
        strictBounds: true
      }).then(function (res) {
          var obj = res.geoObjects.get(0);
          //console.warn('geocode obj', obj);

          /* Если в зоне, сохраняем объект */
          if( highlightResult(obj.geometry.getCoordinates(), deliveryZonesBaur) ){
            last_obj = obj;
          }
          showResult(obj, address_field_selector);
          //address_obj_tmp = obj;
      }, function (e) {
          console.error('map ERROR!!!', e)
          in_zone_block.innerText = ''
      });
    }else{
      console.log('already_find');
      var obj = address_obj_tmp;
    }

   // if(!!obj)
    //  showResult(obj,address_field_selector,in_zone_block);
}

function showResult(obj, address_field_selector) { 
    //console.error('show result');
    var mapContainer = $(address_field_selector),
    bounds = obj.properties.get('boundedBy'),
    // Рассчитываем видимую область для текущего положения пользователя.
    mapState = ymaps.util.bounds.getCenterAndZoom(
        bounds,
        [mapContainer.width(), mapContainer.height()],
    );
    mapState.controls = [];

    show_placemark(obj);

        // console.error('show result bounds', bounds);
        // console.error('show result mapState', mapState);
        // console.log('obj ',obj.getPremiseNumber());
        // // Сохраняем укороченный адрес для подписи метки.
        // shortLabelAddress = [obj.getThoroughfare(), obj.getPremiseNumber(), obj.getPremise()].join(' ');
        // Убираем контролы с карты.
        // Проверяем находится ли адрес в зоне доставки
       
        
        // if ( in_zone ) {
        //   console.log('in zone');
        //   in_zone_block.innerText = 'Ваш адрес в зоне доставки';
        //   in_zone_block.style = 'color: #94BC21';
        // } else { 
        //   console.log('not in zone text');
        //   in_zone_block.innerText = 'Ваш адрес не в зоне доставки';
        //   in_zone_block.style = 'color: #D62B07';
        // }
        

      // if (in_zone) {
      //   localStorage.adsressStreet = shortLabelAddress
      // }
    // localStorage.adsressStreet = shortLabelAddress
    // затемняем область попадания метки
    // highlightResult(mapState.center, deliveryZonesBaur);
    // Ставим метку на карту.

    //createPlacemark(mapState, shortLabelAddress);
}

// function createPlacemark(coords) {
//   return new ymaps.Placemark(coords, {
//       iconCaption: 'поиск...'
//   }, {
//       preset: 'islands#orangeDotIconWithCaption',
//       draggable: true
//   });
// }

// мое место положение
function location_me_good() {
  //console.log('мое место положение');
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
        center: __center,
        zoom: 12,
        controls: ['geolocationControl'],
    });
    // var searchControl = new ymaps.control.SearchControl({
    //     options: {
    //         // Будет производиться поиск по топонимам и организациям.
    //         provider: 'yandex#map',
    //         boundedBy: __boundedBy,
    //         strictBounds: true
    //   }
    // });
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
    //map.controls.add(searchControl);
    return map;
}

function save_map_address(obj){

  var geo_obj = (!!obj) ? obj : last_obj;
  var $data = {};

  console.error('save_map_address', geo_obj);

  
  address = [
    geo_obj.getThoroughfare() || geo_obj.getPremise(),
    geo_obj.getPremiseNumber() || ''
  ].filter(Boolean).join(', ')
  $data.coordinates = geo_obj.geometry.getCoordinates();
  $data.short_address = address;
  $data.country = geo_obj.getCountry();
  $data.city = geo_obj.getLocalities();
  $data.street = geo_obj.getThoroughfare();
  $data.premice = geo_obj.getPremise();
  $data.premice_number = geo_obj.getPremiseNumber();
  $data.apartment = $('#apartment').val();
  $data.AddressLine = geo_obj.getAddressLine();
  $data.StockId = localStorage.storageId;

  $data.mode = localStorage.address_mode ?? 'new';
  

	$data.action = 'save_address';

	/*   var $data = {
		action: 'save_address',
		adsressStreet: $('.select-address-start__content [name="address"]').val(),
		entrance: $('.select-address-start__content [name="entrance"]').val(),
		domofon:  $('.select-address-start__content [name="domofon"]').val(),
		apartment: $('.select-address-start__content [name="apartment"]').val(),
		floor:  $('.select-address-start__content [name="floor"]').val(),
		user_id: $('.select-address-start__content [name="user_id"]').val()

	  }; 
	  */
  
	$.ajax({
	  type : 'POST',
	  url : '/wp-admin/admin-ajax.php',
	  async: true,
	  data : $data,
	  dataType: 'html',
	  beforeSend: function (xhr) {
		
		//preloader.style = 'display:block';
	  },
	  complete: function() {
		//preloader.style = 'display:none';
	  },
	  success: function (data) {
      //console.log(data);
      //if(confirm('обновить?'))
        location.reload();
      //localStorage.removeItem('address_mode');
	  },
	});

}

// function initSuggestYM(element, map) {
//     const suggest = new ymaps.SuggestView(element, {results: 3});
//     return suggest;
// }

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
    //console.log('delete modal')
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
  updateCheckoutFromMap();
}

$(document).on('click', '[data-address_select]', function(event){
  var $data = {
    action: 'select_address',
    index: $(this).data('address_select')
  }
  $.ajax({
    type : 'POST',
    url : '/wp-admin/admin-ajax.php',
    async: true,
    data : $data,
    dataType: 'html',
    beforeSend: function (xhr) {
      
      //preloader.style = 'display:block';
    },
    complete: function() {
      //preloader.style = 'display:none';
    },
    success: function (data) {
    //console.log(data);
    //if(confirm('обновить?'))
      location.reload();
    //localStorage.removeItem('address_mode');
    },
  });
});

$(document).on('click', '[data-address_remove]', function(event){
  var $data = {
    action: 'delete_address',
    index: $(this).data('address_remove')
  }
  $.ajax({
    type : 'POST',
    url : '/wp-admin/admin-ajax.php',
    async: true,
    data : $data,
    dataType: 'html',
    beforeSend: function (xhr) {
      
      //preloader.style = 'display:block';
    },
    complete: function() {
      //preloader.style = 'display:none';
    },
    success: function (data) {
    //console.log(data);
    //if(confirm('обновить?'))
      location.reload();
    //localStorage.removeItem('address_mode');
    },
  });
});	

function  ajax_get_user_addres(){
  if(localStorage.shipping_method != 'pickup')
  $.ajax({
    type : 'POST',
    url : '/wp-admin/admin-ajax.php',
    async: true,
    data : {action: 'get_user_addres'},
    dataType: 'json',
    beforeSend: function (xhr) {
      
      //preloader.style = 'display:block';
    },
    complete: function() {
      //preloader.style = 'display:none';
    },
    success: function (data) {
      //console.log('!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!', data);

      if(typeof data.AddressLine == "undefined"){
        //$('.custom-products').click()
        $('.custom-products').prepend('<div class="disabled-area"></div>');
        //$(document).on('click', '[data-address_cansel]', function(event){})
      }else{
        var adsressStreetAndFlat = data.short_address+(data.premice_number ? ' кв. '+data.premice_number : '');
        localStorage.adsressStreetAndFlat = adsressStreetAndFlat;

        var url_host = 'https://'+window.location.host;
        if (location.href === url_host + "/checkout-2/") {
          //alert('поправить адрес!!')
            // console.log('Страница оформления загрузилась');
            // console.log(parseInt(localStorage.deliverytime));
            document.querySelector("#delivery-address").innerText = localStorage.adsressStreetAndFlat;
            // document.querySelector('#shipping_deliv_time').value = parseInt(localStorage.deliverytime);
            if(typeof localStorage.adsressStreetAndFlat !== "undefined") document.querySelector('#billing_address_1').value = localStorage.adsressStreetAndFlat;  
            //console.log('---',localStorage.adsressStreetAndFlat);
            //if(typeof localStorage.apartmentInDone !== "undefined") document.querySelector('#billing_address_2').value = localStorage.apartmentInDone;  
            //console.log('---|',localStorage.apartmentInDone);

        }
      }
        


    //localStorage.removeItem('address_mode');
    },
  });
}


$(function() {
  ajax_get_user_addres();

  // замена кнопки выбрать адрес на страницы офрмления



});














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
    window.modal = (element) => new Modal(element);
    document.querySelector('.baurPrimaryHeader').classList.remove('stuck');
// СБРОС НИЖНЕГО МЕНЮ В КОРЗИНЕ
  if (document.location.pathname === "/cart-2/" || document.location.pathname === "/checkout-2/") {
    document.querySelector('.pizzaro-handheld-footer-bar').style = 'display: none';
  }
// КОНСТАНТЫ
  const menu = document.querySelector('.baur_menu-btn');
  const bodyBaur = document.querySelector('body');
  const menuContainer = document.querySelector('.baur_menu-container');
  const btnCloseMenu = document.querySelector('.baur_menu-close');
  const activeCategoryAll = document.querySelectorAll('.menu_baur-items li');
  const linkCategoryAll = document.querySelectorAll('.menu_baur-items li a');
  const heightBlockHeader = document.querySelector('#masthead').offsetHeight;
  const heightBlockHeaderMini = document.querySelector('.baurPrimaryHeader').offsetHeight;
  const activePrimaryMenuAll = document.querySelectorAll('#menu-food-menu li a');
  // const mobileLinkMenu = document.querySelectorAll('#menu-food-menu-1 li');
  const mobileLinkMenu = document.querySelectorAll('#menu-primarybaur li');
  const sectionProducts = document.querySelectorAll('.section-products .section-title');
  const sectionProductsArr = [];
  const visibleBlockOnNight = document.querySelector('.top_header-date');
  const animation = document.querySelector('.animation');
  const addressBlock = document.querySelector('.baur_delivery-city'); 
  const scrollHorizontAuto = document.querySelector('.baur_header_bottom_menu');
  const cartBaur = document.querySelector('.site-header-cart');
  const copyCartBaur = cartBaur.cloneNode(true);
  if(document.body.contains(document.querySelector('.footer-cart_count'))){
	var num = parseInt(document.querySelector('.footer-cart_count').innerText.match(/\d+/));
  }

  if (cartBaur) {
    document.querySelector('.baur_navigation').append(copyCartBaur)
  }
  if (localStorage.deliverytime) {
    // document.querySelector('.footer-cart_time').innerText = localStorage.deliverytime
    document.querySelector('.footer-cart_time').insertAdjacentHTML('afterbegin', `<span>${localStorage.deliverytime}</span>`)
  } 
  // появление корзины если есть товары
  // let num = parseInt(document.querySelector('.footer-cart_count').innerText.match(/\d+/));
  if (num > 0) {
    document.querySelector('.pizzaro-handheld-footer-bar').classList.add('active_cart')
  } else {
	if(document.body.contains(document.querySelector('.pizzaro-handheld-footer-bar')))
		document.querySelector('.pizzaro-handheld-footer-bar').classList.remove('active_cart')
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
  //noPress();
  //clickNull(num);
  // if (localStorage.adsressStreetAndFlat === "" || add_address.innerText === "Введите адрес доставки") {
  //   noPress();
  // }
  // document.querySelector('.select-address-start__button--orange').addEventListener('click', () => {
  //   noPress();  
  // })

  // проверяет есть ли адрес??? 01.04.22 перевёл на php
  // if (localStorage.adsressStreetAndFlat === undefined || localStorage.adsressStreetAndFlat === "") {
  //     add_address.innerText = "Введите адрес доставки";
  //     m_add_address.innerText = "Введите адрес доставки";
  //     addressBlock?addressBlock.innerText = "":null//Введите ваш адрес доставки - можно поставить в кавычки
  // } else {
  //     addressBlock?addressBlock.innerText=localStorage.adsressStreetAndFlat:null
  //     add_address.innerText = localStorage.adsressStreetAndFlat;
  //     m_add_address.innerText = localStorage.adsressStreetAndFlat;
  // }


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
  }else{ //на отличных от главной страницах делаем просто якоря на главную
    linkCategoryAll.forEach((item) => {
      var url = new URL(item.href);
      //console.warn(url.pathname.replace('/product-category/', '').replace('/', ''));         
      
      item.href = `/#section-${url.pathname.replace('/product-category/', '').replace('/', '')}`;
    });
    
    activePrimaryMenuAll.forEach((item) => {
      var url = new URL(item.href);
      //console.warn(url.pathname.replace('/product-category/', '').replace('/', ''));         
      
      item.href = `/#section-${url.pathname.replace('/product-category/', '').replace('/', '')}`;
    });    
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

  const url = origin_url+'/wp-json/wc/v3/general-info/'
  fetch(url)
  .then(data => {
    return data.json()
  })
  .then(data => {
    //console.log('data.week',data.week)
    if(typeof data.week == "undefined") return;
    
    const keys = Object.values(data.week)
    keys.unshift(keys.pop())
    // console.log('keys', keys)
    keys.forEach((item, index) => {
      if (index === new Date().getDay()) {
        // console.log('item', item)
        // console.log('item.from', item.from)
        document.querySelector('.top_header-date_top').innerText = `Мы работаем с ${item.from}, но уже сейчас готовы принять заказ.`;
        document.querySelector('.baur_phone-num span').innerText = `C ${item.from} до ${item.to}`;
        //document.querySelector('#time_baur').innerText = `c ${item.from} до ${item.to}`;
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
    bodyBaur.classList.add('baur_active_body');
    menuContainer.classList.add('baur_active');
  });
  btnCloseMenu.addEventListener('click', ()=>{
    bodyBaur.classList.remove('baur_active_body');
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
  /*if (/Android/i.test(navigator.userAgent)) {
    document.querySelector('.top_header-button').href = "https://play.google.com/store/apps/details?id=ru.dolinger.lovefood";
  } if (/iPhone|iPad/i.test(navigator.userAgent)) {
    document.querySelector('.top_header-button').href = "https://apps.apple.com/us/app/%D0%BB%D1%8E%D0%B1%D0%BB%D1%8E%D0%B5%D0%B4%D1%83/id1562836735";
  }*/
  // добавить стили для плавного перемещения ползунка
  //document.querySelector('#menu-primarybaur').append(document.querySelector('.animation'))
  // сылка на баннере
  
  document.querySelectorAll('rs-slide').forEach(i=>{
    i.classList.add('baur_banner')
  })
  
}



function delete_cookie( name, path, domain ) {

  if( getCookie( name ) ) {
    document.cookie = name + "=" +
      ((path) ? ";path="+path:"")+
      ((domain)?";domain="+domain:"") +
      ";expires=Thu, 01 Jan 1970 00:00:01 GMT";
  }
}

function getCookie(name) {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  let cookie_val = parts.pop().split(';').shift();
  if (parts.length === 2) { //console.log(name,' ',cookie_val);
	  return cookie_val;
  }
}

function updateCheckoutFromMap() { console.log('update checkout');
    jQuery(document.body).trigger("wc_update_cart");
    jQuery(document.body).trigger("update_checkout");
}

////расстояние в км между координатами
function coordLocToKm(lat1,lon1,lat2,lon2) {
  var R = 6371; // Radius of the earth in km
  var dLat = deg2rad(lat2-lat1);  // deg2rad below
  var dLon = deg2rad(lon2-lon1); 
  var a = 
    Math.sin(dLat/2) * Math.sin(dLat/2) +
    Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * 
    Math.sin(dLon/2) * Math.sin(dLon/2)
    ; 
  var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
  var d = R * c; // Distance in km
  return d;
}

function deg2rad(deg) {
  return deg * (Math.PI/180)
}

function set_cookie_deliv(polygon, coords){
  /* Отключил */
	let zone_desc = polygon.properties._data.description;
	let zone_id = zone_desc.split('#cid=').pop();
  divliv_settings_zones = __conditions;

  console.warn('set_cookie_deliv');
  console.warn(zone_id);
  
	if(zone_id){
		delete_cookie('zone_deliv','','');
		document.cookie = 'zone_deliv='+zone_id;
		delete_cookie('zone_deliv2','/','');
		document.cookie = 'zone_deliv2='+zone_id+'; path=/;';

    divliv_settings_zones.forEach((item, index) => {
      if(item.zone == zone_id){
       alert(1121);
        localStorage.setItem('storageId', item.sklad); 
        
        console.log('storageId  '+localStorage.getItem('storageId'));
      }
      console.warn(item);
    });
	}



	console.log(zone_id);
	//console.log(coords);
		
	console.log(coords[0],coords[1],map_strg_lat1,map_strg_lat2);
	let range_km = coordLocToKm(coords[0],coords[1],map_strg_lat1,map_strg_lat2);
	range_km = parseFloat(range_km).toFixed(2);
	document.cookie = 'range_deliv='+range_km+'; path=/;';;
	console.log('range_deliv=', range_km);

}

/* Запрещаем кликать ссылки в модальной корзине */
$(document).on('click', '.woocommerce-mini-cart-item a:not(.remove_from_cart_button), .woocommerce-cart-form a:not(.remove, .checkout-button)', function(event){
  event.preventDefault();
});

$(document).on('click', '[data-address_confirm]', function(event){
    save_map_address();
});
$(document).on('click', '.m_baur_search-form-shipping-data-delivery', function(event){
  //show_address_map();
  show_modal('select-user-address_form');
});
$(document).on('click', '[data-address_cansel]', function(event){
  close_modal()
});
$(document).on('click', '.disabled-area', function(event){
  show_modal('select-delivery-type');
});