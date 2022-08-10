var inited = false;
var data_json;
var center;

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

readTextFile("/select-address.json", function(text){
    data_json = JSON.parse(text);
    center = data_json.features[0].geometry.coordinates[0][0]
});

function init() {
    var pointEnter;
    var myPlacemark;
    var myMap = new ymaps.Map('map', {
            center,
            zoom: 12,
            controls: ['geolocationControl', 'searchControl']
        }),
        searchControl = myMap.controls.get('searchControl');
    searchControl.options.set({noPlacemark: true, placeholderContent: 'Введите адрес доставки'});
    onZonesLoad(data_json);
    inited = true;

    function onZonesLoad(json) {
        // Добавляем зоны на карту.
        var deliveryZones = ymaps.geoQuery(json).addToMap(myMap);
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
            obj.events.add('click', function (e) {
                var coords = e.get('coords');
                getAddress(coords);
            });
        });

        // Проверим попадание результата поиска в одну из зон доставки.
        searchControl.events.add('resultshow', function (e) {
            getAddress(searchControl.getResultsArray()[e.get('index')].geometry.getCoordinates());
        });

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

        function setPlacemark(coords) {
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
            myPlacemark.properties.set('iconCaption', 'поиск...');
            ymaps.geocode(coords).then(function (res) {
                var firstGeoObject = res.geoObjects.get(0);
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

                showResult(firstGeoObject)
            });
        }



        function highlightResult(coords) {
            var polygon = deliveryZones.searchContaining(coords).get(0);

            if (polygon) {
                // Уменьшаем прозрачность всех полигонов, кроме того, в который входят переданные координаты.
                deliveryZones.setOptions('fillOpacity', 0.4);
                polygon.options.set('fillOpacity', 0.8);
                pointEnter = true;
            } else {
                deliveryZones.setOptions('fillOpacity', 0.4);
                pointEnter = false;
                return false;
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

            cityInput.value = city ? city : '';
            cityShipInput.value = cityInput.value;

            addressShipInput.value = addressInput.value;
            addressDelivery.innerHTML = `${cityShipInput.value}, ${addressShipInput.value}`;

            if (pointEnter) {
                blockOK.style.display = 'block';
                blockError.style.display = 'none';
            } else {
                blockOK.style.display = 'none';
                blockError.style.display = 'block';
                cityInput.value = ''
                cityShipInput.value = ''
                addressInput.value = ''
                addressShipInput.value = ''
            }

        }
    }

}