<template>
    <div ref="mapContainer" style="width: 450px; height: 200px;"></div>
</template>
  
  <script>
  export default {
    name: 'MapComponent',
    props: {
      coordinates: {
        type: String,
        required: true
      }
    },
    mounted() {
      this.loadMap();
    },
    methods: {
      loadMap() {
        const geolocationStr = this.coordinates;
        const coordinates = geolocationStr.match(/-?\d+\.\d+/g).map(Number);
  
        const script = document.createElement('script');
        script.src = 'https://api-maps.yandex.ru/2.1/?apikey=88ed630c-1d1b-497f-8893-1b4e4057c36f&lang=ru_RU';
        script.onload = () => {
          ymaps.ready(() => {
            const map = new ymaps.Map(this.$refs.mapContainer, {
              center: coordinates,
              zoom: 12
            });
            map.geoObjects.add(new ymaps.Placemark(map.getCenter(), { hintContent: 'Центр карты' }));
          });
        };
        document.head.appendChild(script);
      }
    }
  };
  </script>