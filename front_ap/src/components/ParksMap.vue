<template>

  <div style="margin-left: 75px; font-size: 16px; margin-bottom: 20px;">
    
      <h4>Все парки:</h4> <Toggle v-model="this.toggleVal"  />
  </div>

  <div style="margin: auto;">
    <div ref="map" style="width: 90%; height: 700px;margin-left: 75px;"></div>
  </div>
</template>
  
<script>

  import Toggle from '@vueform/toggle'
  export default {
    data() {
        return {
            toggleVal: {
              value: true,
            },
        };
    },
    components: {
      Toggle
    },
    props: {
        parks: {
          type: Array,
          required: true
        },
        nearestParks: {
              type: Array,
              required: true
            },

    },
    mounted() {
      this.toggleVal = true;
      const script = document.createElement('script');
      script.src = 'https://api-maps.yandex.ru/2.1/?apikey=88ed630c-1d1b-497f-8893-1b4e4057c36f&lang=ru_RU';
      script.onload = () => {
        ymaps.ready(() => {
          const map = new ymaps.Map(this.$refs.map, {
            center: [55.755820, 37.617633],
            zoom: 11
          });
          
          map.geoObjects.add(new ymaps.Placemark(map.getCenter(), { hintContent: 'Центр карты' }));

          const updateMarkers = () => {
          map.geoObjects.removeAll();
          const parks = this.toggleVal ? this.parks : this.nearestParks;
          parks.forEach(park => {
            const geolocation = JSON.parse(park.geolocation.replace('[', '[').replace(']', ']'));
            map.geoObjects.add(new ymaps.Placemark(geolocation, { hintContent: park.name }));
          });
          map.container.fitToViewport();
        };

        updateMarkers(); // вызов функции для первоначального отображения маркеров

        // Обновление маркеров при изменении значения тоглера
        this.$watch('toggleVal', updateMarkers);

          
          // if(!this.toggleVal.value){
          //   map.geoObjects.removeAll();
          //   this.parks.forEach(park => {
          //     const geolocation = JSON.parse(park.geolocation.replace('[', '[').replace(']', ']'));
          //     map.geoObjects.add(new ymaps.Placemark(geolocation, { hintContent: park.name }));
          //   });
          // }
          // else {
          //   map.geoObjects.removeAll();
          //   this.nearestPark.forEach(park => {
          //     const geolocation = JSON.parse(park.geolocation.replace('[', '[').replace(']', ']'));
          //     map.geoObjects.add(new ymaps.Placemark(geolocation, { hintContent: park.name }));
          //   });
          // }

        });
      };
  
      document.head.appendChild(script);
    },
  
    watch: {
      'toggleVal.value'(newVal){
        this.toggleVal.value
      }
    },
    methods: {
      async addMarks(map, value){
        map.geoObjects.removeAll();
        if(!value){
            this.parks.forEach(park => {
              const geolocation = JSON.parse(park.geolocation.replace('[', '[').replace(']', ']'));
              map.geoObjects.add(new ymaps.Placemark(geolocation, { hintContent: park.name }));
            });
          }
          else {
            this.nearestPark.forEach(park => {
              const geolocation = JSON.parse(park.geolocation.replace('[', '[').replace(']', ']'));
              map.geoObjects.add(new ymaps.Placemark(geolocation, { hintContent: park.name }));
            });
          }
      },
      // async fetchNearestParks() {
      //     try {
      //         // Получаем текущую геолокацию пользователя
      //         if (navigator.geolocation) {
      //             navigator.geolocation.getCurrentPosition(async position => {
      //                 const latitude = position.coords.latitude;
      //                 const longitude = position.coords.longitude;

      //                 console.log("Lati", latitude);
      //                 console.log("Longi", longitude);
                      
      //                 // Формируем объект с координатами
      //                 const coordinates = {
      //                     "latitude": latitude,
      //                     "longitude": longitude
      //                 };
      //                 console.log(coordinates);

      //                 // Отправляем POST запрос с текущими координатами
      //                 const response = await fetch('http://127.0.0.1:8000/api/nearest-parks', {
      //                     method: 'POST',
      //                     headers: {
      //                         'Content-Type': 'application/json'
      //                     },
      //                     body: JSON.stringify(coordinates)
      //                 });

      //                 const data = await response.json();
      //                 console.log(data);
      //                 this.nearestPark = data.data;
      //             });
      //         } else {
      //             console.error('Geolocation is not supported by this browser.');
      //         }
      //     } catch (error) {
      //         console.error('Error fetching parks:', error);
      //     }
      // }
  }
  };
</script>
<style scoped>
/* Подключение CSS стилей напрямую */
@import url('https://cdn.jsdelivr.net/npm/@vueform/toggle/themes/default.css');
</style>