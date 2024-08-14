<template>
<!-- <div v-for="park in parks" :key="park.id" class="park-card">
    <div class="park-name">
        <h2>{{ park.name }}</h2>
    
    </div>
    <div class="park-image">
        <img :src="park.image" alt="Park Image">
    
    </div>
    <div class="park-description">
        <p>{{ park.short_desc }}</p>
    
    </div>
    <div class="park-info">
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
        </div>
        <button>Подробнее</button>
    </div> -->
    
<!-- </div> -->


<div class="event-cards-container">
    <div v-for="event in paginatedItems" :key="event.id">
        
        <Cardt :event="event"/>
    </div>
    
</div>
<Pag :totalItems="totalItems1" :itemsPerPage="itemsPerPage" @update:currentPage="currentPage = $event" />
</template>
  
<script>
    import Cardt from '../components/Card.vue';
    import Pag from "./Pagination.vue";
    import { ref } from "vue";
    export default {
        name: 'TempEventCards',
        data() {
            return {
                itemsPerPage: 8,
                currentPage: ref(1)
            };
        },
        props: {
            tempEvents: {
                type: Array,
                required: true
            },
            totalItems1:{
                type: Number,
                required: true
            },
        },
        components: {
            Cardt,
            Pag
        },
        computed: {
            paginatedItems() {
                const startIndex = (this.currentPage - 1) * this.itemsPerPage;
                const endIndex = startIndex + this.itemsPerPage;
                return this.tempEvents.slice(startIndex, endIndex);
            }
        },
    };
    
</script>
  
<style scoped>

    .event-cards-container{
        display: flex;
        flex-wrap: wrap; 
        justify-content: space-between; 
    }
    /* button{
        border-radius: 15px;
        width: 25px;
        color: #339933;
        background-color: #6633FF;
        border: 2px solid #FFCC66;
        color: white;
    }
    .card {
    background-color: black;
    border: 3px solid green;
    border-radius: 15px;
    width: 350px;
    color: #fdfdfd;
}

.image-container {
    position: relative;
    height: 100px;
    background-color: aliceblue;
    overflow: hidden;
    outline: 3px solid #339933;
    border-radius: 15px 15px 0 0;
}

.image-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    
}

.overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 25%;
    background-color: rgba(0, 0, 0, 0.164);
    overflow: hidden;
    
}

.info {
    position: absolute;
    bottom: 0;
   
    color: white;
    display: flex;
    justify-content: space-between;
}
.event-info {
    flex-direction: column;
    align-items: center;
    margin: 5px;
}

.event-title {
    margin-top: 10px;
    color: #ffffff;
}

.details {
    display: flex;
    flex-direction: column;
}

.icon-park, .icon-clock {
    margin-top: 5px;
} */

</style>
  