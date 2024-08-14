<template>
    <div class="about">
      <h1>Введите название парка</h1>
      <div class="search-bar">
        <input type="text" placeholder="Поиск..." v-model="searchQuery" @input="search">
        <button @click="goToPark(selectedPark.id)"  :parkId="selectedPark.id">Search</button>
        <div class="search-results">
          <div class="card" v-for="result in searchResults" :key="result.id" @click="selectResult(result)">
              {{ result.name }}
          </div>
      </div>
        
    
      </div>

      
    </div>
    <Carousel style="margin-top: 300px;"/>
    
  </template>

<script>
import axios from 'axios';
import Tab2 from '../components/Card.vue';
import Carousel from '../components/DetailsCarousel.vue';

export default {
    data() {
          return {
              searchQuery: '',
              searchResults: [],
              selectedPark: []
          };
    },
    methods: {
        search() {
            if (this.searchQuery.length > 0) { // Проверяем, что поле ввода не пустое
                axios.get('http://127.0.0.1:8000/api/live-search', { params: { query: this.searchQuery } })
                    .then(response => {
                        this.searchResults = response.data.data;
                    })
                    .catch(error => {
                        console.error(error);
                    });
            } else {
                this.searchResults = []; // Если поле ввода пустое, очищаем результаты поиска
            }
        },
        selectResult(result) {
            this.searchQuery = result.name;
            this.searchResults = []; 
            this.selectedPark = result;
        },
        goToPark(params) {
            this.$router.push('/park?id=' + JSON.stringify(params));
        }
    },
    components:{
      Tab2,
      Carousel
    }
};
</script>
  
  <style>

.custom-select {
    position: relative;
    }

    .custom-select select {
    
    padding: 10px;
    /* font-size: 16px; */
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #fff;
    appearance: none; /* Убирает стандартный стрелочный селектор */
    overflow-y: auto;
    }

  .about {
    
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }
  
  .search-bar {
    margin-top: 20px;
  }
  
  .search-bar input {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
  }
  
  .search-bar button {
    margin-left: 10px;
    padding: 8px 16px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
  }


  .search-results {
    display: flex;
    flex-direction: column;

    position: absolute;
    top: 22%;
    left: 50%;
    background-color: white;
}

.card {
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 10px;
    margin: 5px;
    cursor: pointer;
}

/* .card:hover {
    background-color: #f0f0f0;
} */

  </style>
  