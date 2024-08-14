<template>
  <div class="parks">

    <!-- <Tabs /> -->
    <div>
      <div class="tabs">
        <div
          v-for="(tab, index) in tabs"
          :key="index"
          :class="{ 'active': activeTab === index }"
          @click="activeTab = index"
        >
          {{ tab.title }}
        </div>
      </div>
      <div class="tab-content">
        <component :is="tabs[activeTab].content" :parks="parks" :nearestParks="nearestParks" :totalItems="parks.length"/>
      </div>
    </div>

  </div>
</template>

<script>

import Tab1 from '../components/ParkCards.vue';
import Tab2 from '../components/ParksMap.vue';

export default {
  data() {
    return {
      parks: [],
      nearestParks: [],
      activeTab: 0,
        tabs: [
          { title: 'Парки в виде карточек', content: Tab1 },
          { title: 'Парки на карте', content: Tab2 },
        ],
    };
  },
  components: {
    Tab1,
    Tab2
  },
  mounted() {
      this.fetchParks();
      this.fetchNearestParks();
  },
  methods: {
      async fetchParks() {
        try {
            const response = await fetch('http://127.0.0.1:8000/api/parks');
            const data = await response.json();
            this.parks = data.data;
        } catch (error) {
            console.error('Error fetching parks:', error);
        }
      },
      async fetchNearestParks() {
          try {
              // Получаем текущую геолокацию пользователя
              if (navigator.geolocation) {
                  navigator.geolocation.getCurrentPosition(async position => {
                      const latitude = position.coords.latitude;
                      const longitude = position.coords.longitude;

                      console.log("Lati", latitude);
                      console.log("Longi", longitude);
                      
                      // Формируем объект с координатами
                      const coordinates = {
                          "latitude": latitude,
                          "longitude": longitude
                      };

                      // Отправляем POST запрос с текущими координатами
                      const response = await fetch('http://127.0.0.1:8000/api/nearest-parks', {
                          method: 'POST',
                          headers: {
                              'Content-Type': 'application/json'
                          },
                          body: JSON.stringify(coordinates)
                      });

                      const data = await response.json();
                      this.nearestParks = data.data;
                  });
              } else {
                  console.error('Geolocation is not supported by this browser.');
              }
          } catch (error) {
              console.error('Error fetching parks:', error);
          }
      }
  }
};
</script>

<style scoped>
  .park-card {
    border: 1px solid #ccc;
    border-radius: 10px;
    padding: 10px;
    margin-bottom: 10px;
  }
  .tabs {
    display: flex;
    justify-content: center;
    color: #FFFFFF;
    font-size: 18px;
    font-weight: bold;
    margin: 10px;
  }
  
  .tab-content {
    flex: 1;
    border: 2px solid #FFCC66;
    border-radius: 15px;
    padding: 10px;
    
  }

  .tabs div {
    padding: 10px;
    cursor: pointer;
    border: 2px solid #FFCC66;
    border-radius: 15px;
  }

  .tabs div.active {
    background-color: #6633FF;
  }
</style>
