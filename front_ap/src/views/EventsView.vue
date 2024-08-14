<template>
  <button class="show-modal-button" @click="showModal">Фильтрация</button>
  <div class="events">

    <!-- <div v-if="parks.length > 0">
      <div v-for="park in parks" :key="park.id" class="park-card">
        <h2>{{ park.name }}</h2>
        <p>{{ park.desc }}</p>
        <p>{{ park.address }}</p>
      </div>
    </div> -->
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
        <component 
          :is="tabs[activeTab].content" 
          :tempEvents="isFilt ? filtTempEvents : tempEvents" 
          :permEvents="isFilt ? filtPermEvents : permEvents" 
          :totalItems1="isFilt ? filtTempEvents.length : tempEvents.length" 
          :totalItems2="isFilt ? filtPermEvents.length : permEvents.length"/>
      </div>
    </div>

  </div>

  <Filtr ref="modalFiltr" @show-modal="show = true" @filtered-events="receiveFilteredEvents"/>
</template>

<script>
import Tab1 from '../components/TempEventCards.vue';
import Tab2 from '../components/PermEventCards.vue';
import Filtr from '../components/Filtr.vue';

export default {
  data() {
    return {
      isModalOpen: false,
      parks: [],
      activeTab: 0,
      tabs: [
        { title: 'Временные', content: Tab1 },
        { title: 'Постоянные', content: Tab2 },
      ],
      tempEvents: [],
      permEvents: [],

      isFilt: false,
      filtTempEvents: [],
      filtPermEvents: [],
    };
  },
  components: {
    Tab1,
    Tab2,
    Filtr
  },

  mounted() {
      this.fetchEvents();
  },
  methods: {
    showModal: function () {
        this.$refs.modalFiltr.show = true;
        
      },
      receiveFilteredEvents(filteredEvents) {
          // Здесь можно обработать полученные отфильтрованные события
          console.log("Фильтры", filteredEvents)
          this.isFilt = true;
          this.filtTempEvents = filteredEvents.filter(filteredEvent => filteredEvent.type === 'временное');
          this.filtPermEvents = filteredEvents.filter(filteredEvent => filteredEvent.type === 'постоянное');

          
      },
    async fetchEvents() {
      try {
        const response = await fetch('http://127.0.0.1:8000/api/events?status=одобрено');
        const data = await response.json();

        this.tempEvents = data.data.filter(event => event.type === 'временное');
        this.permEvents = data.data.filter(event => event.type === 'постоянное');

      } catch (error) {
        console.error('Error fetching parks:', error);
      }
    }
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
    height: 100vh;
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
