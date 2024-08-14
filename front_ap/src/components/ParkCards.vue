<template>
<div class="park-cards-container">
    <!-- <div v-for="park in parks" :key="park.id" class="park-card"> -->
    <div v-for="park in paginatedItems" :key="park.id" class="park-card">
        <div class="park-name">
            <h3>{{ park.name }}</h3>
        
        </div>
        <div class="park-image">
            <img :src="park.image" alt="Park Image">
        
        </div>
        <div class="park-description">
            {{ park.short_desc }}
        
        </div>
        <div class="park-info" style="margin-left: 5px;">
            <div class="info-item">
                <i class="fas fa-map-marker-alt"></i>
                <span>{{ park.address }}</span>
            </div>
            <div class="info-item">
                <i class="fas fa-m"></i>
                <span>{{ park.metrostations.join(', ') }}</span>
            </div>
            <div class="info-item">
                <i class="fas fa-phone"></i>
                <span>{{ park.phone }}</span>
            </div>
            <div class="info-item">
                <i class="fas fa-globe"></i>
                <span>{{ park.website }}</span>
                <!-- <a :href="'http://' + park.website" style="text-decoration: none; color: white;" target="_blank">{{ park.website }}</a> -->
            </div>
            <button @click="goToPark(park.id)" :parkId="park.id">Подробнее</button>
        </div>
    </div>
</div>
<Pag :totalItems="totalItems" :itemsPerPage="itemsPerPage" @update:currentPage="currentPage = $event" style="margin-left: 25px;"/>


</template>
  
<script>
    import { ref } from "vue";
    import Pag from "./Pagination.vue";
    export default {
        name: 'ParkCards',
       
        data() {
          return {
            itemsPerPage: 6,
            currentPage: ref(1)
          };
        },
        props: {
            parks: {
                type: Array,
                required: true
            },
            totalItems:{
                type: Number,
                required: true
            },
        },
        components: {
          Pag
        },
        computed: {
            paginatedItems() {
                const startIndex = (this.currentPage - 1) * this.itemsPerPage;
                const endIndex = startIndex + this.itemsPerPage;
                return this.parks.slice(startIndex, endIndex);
            }
        },
        methods: {
            goToPark(params) {
                this.$router.push('/park?id=' + JSON.stringify(params));
            }
        }
    };
</script>
  
<style scoped>
i {
  margin: 10px;
}
.park-cards-container{
    display: flex;
    flex-wrap: wrap; /* Позволяет элементам переноситься на следующую строку */
    justify-content: space-between; /* Распределение элементов по ширине контейнера */
}

/* .park-cards-container:hover > :not(:hover) {
    opacity: 0.4;
} */
.park-card:hover {
    box-shadow: 0px 0px 15px 5px rgba(0, 0, 0, 0.8);
  }

    .park-card {
        max-width: 500px;
        margin: 25px;
        background: black linear-gradient(rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.1));
        outline: 2px solid #e6dddd;
        border-radius: 15px;
        color: white;
        font-size: 16px;
        display: grid;
        grid-template-columns: 1fr 1fr; /* Делим на 2 столбца */
        grid-template-rows: auto auto; /* Делим на 2 строки */
        padding: 0;

        transition: opacity 0.6s ease;
    }

    .park-name {
        text-align:center;
        align-items: center;
        grid-row: 1 / 2; /* Расположение в 1 строке */
        grid-column: 1 / 2; /* Расположение в 1 столбце */
        height: 120px;
        color: #d9d9d9;
    }

    .park-image {
        text-align: center;
        grid-row: 1 / 2; /* Расположение в 1 строке */
        grid-column: 2 / 3; /* Расположение во 2 столбце */
        outline: 2px solid #FFCC66;
        border-radius: 15px;
        height: 120px;
        overflow: hidden;
    }

    .park-image img {
        height: 120px;
        width: 100%;
        object-fit: cover;
    }

    .park-description {
        outline: 2px solid #FFCC66;
      
        border-radius: 15px;
        padding: 15px;
        grid-row: 2 / 3; /* Расположение во 2 строке */
        grid-column: 1 / 2; /* Расположение в 1 столбце */
        max-height: 220px;
        overflow: hidden;
        text-overflow: ellipsis; 
        font-size: 12px;
    }
    

    .park-info {
        display: grid;
        grid-template-columns: 1fr; /* Один столбец */
        grid-template-rows: repeat(3, auto); /* Три строки */
        grid-row: 2 / 3; /* Расположение во 2 строке */
        grid-column: 2 / 3; /* Расположение во 2 столбце */
        max-height: 220px;
        font-size: 12px;
    }

    

    button {
        background-color: #6633FF;
        border: 2px solid #FFCC66;
        color: white;
        margin: 10px;
        border-radius: 20px;
    }
    button:hover {
    background-color: #34495e;
  }

</style>
  