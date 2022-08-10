var inited = false;
var data_json;
var center;
var selectedPlace;
var selectedCoords;
var pointEnter;

// modal = document.querySelector('.select-address-modal');
// modal.events.add('click', function (e) {
// alert();

// });

//var geojson = 'https://' + window.location.hostname+ '/wp-content/uploads/2021/03/example_map_delivery_zone2805_1.geojson';
var geojson = 'https://' + window.location.hostname+ '/wp-content/uploads/2021/12/rodndost_24-12-2021_18-45-51.geojson';
console.log('geojson',geojson);

function readTextFile(file, callback) {
    var rawFile = new XMLHttpRequest();
    rawFile.overrideMimeType("application/json");
    rawFile.open("GET", file, true);
    rawFile.onreadystatechange = function() {
        if (rawFile.readyState === 4 && rawFile.status == "200") {
            callback(rawFile.responseText);
        }
    }
    rawFile.send(null);
}

function toggleSelectAddressModal(close = false) {
    const modal = document.querySelector('.select-address-modal');
    if (!close) {
        modal.style.display = 'block';
    } else {
        modal.style.display = 'none';
    }

    if (!inited) {
        ymaps.ready(init);
    }
}


function submitAddress () {
    showResult(selectedPlace);
    toggleSelectAddressModal(true);
    updateCheckoutFromMap();
}

function clearAddress () {
    selectedPlace = null;
    pointEnter = null;
    changedAddress();
}

function changedAddress () {
    var acceptButton = document.querySelector('.select-address-modal__accept');
    var modalError = document.querySelector('.select-address-modal__error');
    if (selectedPlace) {
        if (pointEnter) {
            acceptButton.style.display = 'block'
            modalError.style.display = 'none'
        } else {
            acceptButton.style.display = 'none'
            modalError.style.display = 'flex'
        }
        // console.error('selected Place', selectedPlace)
        // console.log('selected Coords', selectedCoords)        
        var map_strg_lat1 = center[0],
        map_strg_lat2 = center[1];
        /* Считаем расстояние до клиента */
        console.log(selectedCoords[0],selectedCoords[1],map_strg_lat1,map_strg_lat2);
        let range_km = coordLocToKm(map_strg_lat1,map_strg_lat2, selectedCoords[0], selectedCoords[1]);
        range_km = parseFloat(range_km).toFixed(2);
        document.cookie = 'range_deliv='+range_km+'; path=/;';;

       console.log('range_km',range_km);
        
    } else {
        acceptButton.style.display = 'none'
        modalError.style.display = 'none'

    }

}

function showResult(obj) {
    var addressInput = document.querySelector('input[name="billing_address_1"]');
    var cityInput = document.querySelector('input[name="billing_city"]');
    var addressShipInput = document.querySelector('input[name="shipping_address_1"]');
    var cityShipInput = document.querySelector('input[name="shipping_city"]');
    var addressDelivery = document.querySelector('#delivery-address');
    var blockOK = document.querySelector('.select-address__info');
    var blockError = document.querySelector('.select-address__error');
    var address;
    var city;
    var house;
    city = obj.getLocalities().length ? obj.getLocalities() : obj.getAdministrativeAreas();
    address = obj.getThoroughfare() || obj.getPremise()
    house = obj.getPremiseNumber();

	console.log(city, address, house);

    if (addressInput) {
        if (address) {
            addressInput.value = address;
            if (house) {
                addressInput.value += `, ${house}`
            } else {
                addressInput.value = address;
            }
        } else {
            addressInput.value = '';
        }
        if (addressShipInput) {
            addressShipInput.value = addressInput.value;
        }
    }

    if (cityInput) {
        cityInput.value = city ? city : '';
        if (cityShipInput) {
            cityShipInput.value = cityInput.value;
        }
    }

    if (addressInput && cityInput) {
        addressDelivery.innerHTML = `${cityInput.value}, ${addressInput.value}`;
    }

    if (pointEnter) {
        blockOK.style.display = 'block';
        blockError.style.display = 'none';
    } else {
        blockOK.style.display = 'none';
        blockError.style.display = 'block';
        if (cityInput) {
            cityInput.value = ''
            cityShipInput.value = ''
        }
        if (addressInput) {
            addressInput.value = ''
            addressShipInput.value = ''
        }
    }
}

readTextFile(geojson, function(text){
    data_json = JSON.parse(text);
    center = [61.406228, 55.161430]//data_json.features[0].geometry.coordinates[0][0]
    console.error(center);
    console.error(data_json);    
});

function init() {
    if(!!data_json){
        data_json = JSON.parse(text);
        center = [61.406228, 55.161430]//data_json.features[0].geometry.coordinates[0][0]
    }
    console.log(data_json);
    console.log('iiiiiiiiiiiinint')
    var myPlacemark;
    var myMap = new ymaps.Map('map', {
        center,
        zoom: 12,
        controls: ['geolocationControl', 'searchControl']
    }),
    searchControl = myMap.controls.get('searchControl');
    searchControl.options.set({
        noPlacemark: true, 
        placeholderContent: 'Введите адрес доставки', 
        boundedBy: [[61.237801,55.320720], [61.598915,54.986936]], 
        strictBounds: true
    })
    setTimeout(function() {
        var mobile_search = document.getElementsByClassName('ymaps-2-1-77-search_layout_panel');

        for (var i=0;i<mobile_search.length; i++) {
            mobile_search[i].classList.add('ymaps-2-1-77-search_layout_panel_show');
        }
    }, 100)
    onZonesLoad(data_json);
    inited = true;
	
jQuery(document).on('click', '#my_custom_checkout_field button', function() {
console.log('#my_custom_checkout_field button');
	myMap.container.fitToViewport();
})
	
    function onZonesLoad(json) {
        // Добавляем зоны на карту.
        var deliveryZones = ymaps.geoQuery(json).addToMap(myMap);
        // Задаём цвет и контент балунов полигонов.
        deliveryZones.each(function (obj) {
            obj.options.set({
                fillColor: obj.properties.get('fill'),
                fillOpacity: 0,
                strokeColor: obj.properties.get('stroke'),
                strokeWidth: obj.properties.get('stroke-width'),
                strokeOpacity: obj.properties.get('stroke-opacity'),
                hasBalloon: false,
            });
            obj.events.add('click', function (e) { //obj - объект с данными, которые вводились в конструкторе, например description
                var coords = e.get('coords'); 
				//console.log('координаты: ',coords); 
				//console.log('--обьект зоны: ',obj);  
				//console.log(obj.properties._data.description);
				 
				/*let zone_desc = obj.properties._data.description;
				let zone_id = zone_desc.split('#cid=').pop();
				if(zone_id){
					delete_cookie('zone_deliv','','');
					document.cookie = 'zone_deliv='+zone_id;
					delete_cookie('zone_deliv2','/','');
					document.cookie = 'zone_deliv2='+zone_id+'; path=/;';
				}
				updateCheckoutFromMap();
				console.log(zone_id);*/
				
				
                getAddress(coords);
				set_cookie_addr(obj);
                //var polygon = deliveryZones.searchContaining(coords).get(0);
                //console.log(zone_id);
                // console.log('coords',coords);
                // console.log('center', center);


                //alert('range_km'+'___'+range_km+'___'+coords[0]+'__'+coords[1]+'__'+map_strg_lat1);
                
            });
        });

        // Проверим попадание результата поиска в одну из зон доставки.
        searchControl.events.add('resultshow', function (e) { 
            getAddress(searchControl.getResultsArray()[e.get('index')].geometry.getCoordinates());
        }); console.log('проверка ',searchControl);

        // Проверим попадание метки геолокации в одну из зон доставки.
        myMap.controls.get('geolocationControl').events.add('locationchange', function (e) {
            getAddress(e.get('geoObjects').get(0).geometry.getCoordinates());
        });

        // Слушаем клик на карте.
        myMap.events.add('click', function (e) {
            var coords = e.get('coords');
            
            // Если метка уже создана – просто передвигаем ее.
            getAddress(coords); 

        });

        function setPlacemark(coords) { //console.log(myPlacemark);
            if (myPlacemark) {
                myPlacemark.geometry.setCoordinates(coords);
            }
            // Если нет – создаем.
            else {
                myPlacemark = createPlacemark(coords);
                myMap.geoObjects.add(myPlacemark);
                // Слушаем событие окончания перетаскивания на метке.
                myPlacemark.events.add('dragend', function () {
                    getAddress(myPlacemark.geometry.getCoordinates());
                });
            }
        }

        // Создание метки.
        function createPlacemark(coords) {
            return new ymaps.Placemark(coords, {
                iconCaption: 'поиск...'
            }, {
                preset: 'islands#violetDotIconWithCaption',
                draggable: true
            });
        }

        // Определяем адрес по координатам (обратное геокодирование).
        function getAddress(coords) {
            setPlacemark(coords);
            selectedCoords = coords;
            myPlacemark.properties.set('iconCaption', 'поиск...');
            ymaps.geocode(coords).then(function (res) {
                var firstGeoObject = res.geoObjects.get(0); var address_cookie = firstGeoObject.properties._data.text; document.cookie = 'address_sel='+address_cookie; console.log('firstGeoObj: ', address_cookie);
                highlightResult(coords);
                myPlacemark.properties
                    .set({
                        // Формируем строку с данными об объекте.
                        iconCaption: [
                            firstGeoObject.getThoroughfare() || firstGeoObject.getPremise(),
                            firstGeoObject.getPremiseNumber() || ''
                        ].filter(Boolean).join(', '),
                        // В качестве контента балуна задаем строку с адресом объекта.
                        balloonContent: firstGeoObject.getAddressLine()
                    });
                selectedPlace = firstGeoObject; 
                changedAddress();
            });
			
			
        }



        function highlightResult(coords) {
            var polygon = deliveryZones.searchContaining(coords).get(0);

            if (polygon) { 
				set_cookie_addr(polygon); //после выбора адреса выполняем запись куки о зоне доставки
                // Уменьшаем прозрачность всех полигонов, кроме того, в который входят переданные координаты.
                deliveryZones.setOptions('fillOpacity', 0.1);
                polygon.options.set('fillOpacity', 0.1);
                pointEnter = true;
            } else {
                deliveryZones.setOptions('fillOpacity', 0.1);
                pointEnter = false;
                return false;
            }
        }
    }

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

//обновление данных в оформлении заказа(можно для ajax)
function updateCheckoutFromMap() { console.log('update checkout');
    jQuery(document.body).trigger("wc_update_cart");
    jQuery(document.body).trigger("update_checkout");							  
}


function set_cookie_addr(obj){
	let zone_desc = obj.properties._data.description; //console.log(zone_desc);
	let zone_id = zone_desc.split('#cid=').pop();
	if(zone_id){
		delete_cookie('zone_deliv','','');
		document.cookie = 'zone_deliv='+zone_id;
		delete_cookie('zone_deliv2','/','');
		document.cookie = 'zone_deliv2='+zone_id+'; path=/;';
	}
	updateCheckoutFromMap();
	console.log(zone_id);
}