<template>
  <button @click="goBack">Назад</button>
  <div class="all" style="margin-left: 160px;  align-items: center; outline: 2px solid #d9d9d9; border-radius: 10px; overflow: hidden;">
  <div class="vb"><h2>{{ park.name }}</h2></div>
  <div class="tab">
    <!-- {{ park.name }} -->
    <div class="tab-container">
      <ul class="tab-nav">
        <li v-for="(tab, index) in tabs" :key="index" :class="{ 'active': tab.active }" @click="switchTab(index)">
          {{ tab.label }}
        </li>
      </ul>
      <div v-for="(tab, index) in tabs" :key="index" :class="{ 'active': tab.active }" v-bind:id="'tab-body-' + index">
        <div v-if="tab.active">
          <div v-if="tab.type === 'text'">
            <div class="tab-col-left">
              <div class="park-image">
                <img :src="park.image" alt="Park Image">
              </div>
            </div>
            <div class="tab-col-right" style="color:white; font-size: 14px;">
              {{ park.desc }}
            </div>
          </div>
          <div v-else-if="tab.type === 'mapl'">
            <div class="tab-col-left">
              <MapComponent :coordinates="park.geolocation" />
            </div>
            <div class="tab-col-right">
              <div class="info-item">
                <i class="fas fa-m"></i>
                <span>{{ park.metrostations}}</span>
              </div>

              <div class="info-item">
                <i class="fas fa-location-dot"></i>
                <span>{{ park.address }}</span>
              </div>

              <div class="info-item">
                <i class="fas fa-phone"></i>
                <span>{{ park.phone }}</span>
              </div>

              <div class="info-item">
                <i class="fas fa-globe"></i>
                <span>{{ park.website }}</span>
              </div>

              <div class="info-item">
                <i class="fas fa-clock"></i>
                <span>{{ park.work_time }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div >

<div class="event-cards-container" style="margin-top: 25px;">
<div v-for="event in paginatedItems" :key="event.id">
        
        <Cardt :event="event"/>
    </div>
  </div>

<Pag :totalItems="events.length" :itemsPerPage="itemsPerPage" @update:currentPage="currentPage = $event" />
</template>

<script>
import { ref } from "vue";
import axios from 'axios';
import Pag from "../components/Pagination.vue";
import MapComponent from "../components/MapComponent.vue";
import Cardt from '../components/Card.vue';

export default {
  name: 'ParkView',
  data() {
    return {
      parkId: null,
      park: {},
      events: [],

      itemsPerPage: 4,
      currentPage: ref(1),
      // totalItems: 10,

      tabs: [
        { label: 'Описание', type: 'text', active: true },
        { label: 'Контактная информация', type: 'mapl', active: false }
      ],
      activeTabIndex: 0
    }
  },
  computed: {
    paginatedItems() {
      const startIndex = (this.currentPage - 1) * this.itemsPerPage;
      const endIndex = startIndex + this.itemsPerPage;
      return this.events.slice(startIndex, endIndex);
    }
  },
  mounted() {
    this.parkId = this.$route.query.id;
    this.fetchPark();
    this.fetchEvents();
  },
  methods: {
    switchTab(index) {
      this.tabs.forEach((tab, i) => {
        tab.active = i === index;
      });
      this.activeTabIndex = index;
    },
    async fetchPark() {
      try {
        const response = await fetch(`http://127.0.0.1:8000/api/parks/${this.parkId}`);
        const data = await response.json();
        this.park = data.data;
      } catch (error) {
        console.error('Error fetching parks:', error);
      }
    },
    goBack() {
      this.$router.back();
    },
    async fetchEvents() {
      try {
        const response = await fetch(`http://127.0.0.1:8000/api/events?status=одобрено&park_id=${this.parkId}`);
        const data = await response.json();
        this.events = data.data;
      } catch (error) {
        console.error('Error fetching events:', error);
      }
    },
  },
  components: {
    Pag,
    MapComponent,
    Cardt
  }
};
</script>
    



<style scoped>

button{
    position: absolute;
    top: 0;
    left: 250px;
    margin-top: 8px;

    background: black linear-gradient(rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.1));
    border-radius: 10px;
    height: 40px;
    font-size: 18px;
    font-weight: bold;
    color: #D9D9D9;
    align-items: center;

    display: flex;
    justify-content: flex-end;
    
    border: none;

    padding-left: 10px;
    padding-right: 10px;
    margin-left: 15px;
  }
 
  .vb{
    width: 90%;
    background: black linear-gradient(rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.1));
    display: flex; justify-content: center;
    align-items: center;
    color: #d9d9d9;

  }
.park-map {
  margin-top: 3px;
  
  outline: 2px solid #FFCC66;
  border-radius: 0 0 15px 0;
  overflow: hidden;
}

.park-image {
  
  outline: 2px solid #FFCC66;
  border-radius: 15px;
  width: 350px;
  height: 210px;
  overflow: hidden;
  
}

.park-image img {
    width: 100%;
    object-fit: cover;
}
    
.tab {
  width: 90%;
  background: black linear-gradient(rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.1));
  display: flex; justify-content: center;
  align-items: center;
}

.tab-container {
  max-width: 900px; height: 320px;

}

.tab-nav {
  display: flex;
  width: fit-content;
  margin: 0 auto;
  margin-bottom: 30px;
  list-style: none;
  border-bottom: 1px solid #999;
  position: relative;
}

.tab-nav li {
  padding: 20px 30px;
  color: #333;
  font-size: 20px;
  cursor: pointer;
  transition: all 0.3s;
}

.tab-nav li.active {
  color: #339933;
  transform: scale(1.2);
}

.tab-nav li:not(:last-child) {
  margin-right: 70px;
}
.event-cards-container{
        display: flex;
        flex-wrap: wrap; 
        justify-content: space-between; 

    }

.tab-body {
   
    display: none;
/*     align-items: center; */
    animation: fade 0.8s;
  }
  
  .tab-body.active { display: flex; }
  
  @keyframes fade {
    from {
      opacity: 0;
      transform: translateX(-20px);
    }
  
    to {
      opacity: 1;
      transform: translateX(0);
    }
  }
  
  .tab-col-left {
  float: left;
  width: 50%;
}

.tab-col-right {
  float: left;
  width: 50%;
}
  

  .tab-col-right p {
    color: white;
  }
  

</style>