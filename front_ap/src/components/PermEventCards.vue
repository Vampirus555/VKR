<template>
<div class="event-cards-container">
    <div v-for="event in paginatedItems" :key="event.id">
        
        <Card :event="event"/>
    </div>
</div>
<Pag :totalItems="totalItems2" :itemsPerPage="itemsPerPage" @update:currentPage="currentPage = $event" />
</template>
  
<script>
    import Card from '../components/Card.vue';
    import Pag from "./Pagination.vue";
    import { ref } from "vue";
    export default {
        name: 'PermEventCards',
        data() {
            return {
                itemsPerPage: 8,
                currentPage: ref(1)
            };
        },
        props: {
            permEvents: {
                type: Array,
                required: true
            },
            totalItems2:{
                type: Number,
                required: true
            },
        },
        components: {
            Card,
            Pag
        },
        computed: {
            paginatedItems() {
                const startIndex = (this.currentPage - 1) * this.itemsPerPage;
                const endIndex = startIndex + this.itemsPerPage;
                return this.permEvents.slice(startIndex, endIndex);
            }
        },
    };
    
</script>
  
<style scoped>
    .event-cards-container{
        display: flex;
        flex-wrap: wrap; /* Позволяет элементам переноситься на следующую строку */
        justify-content: space-between; /* Распределение элементов по ширине контейнера */
    }
</style>
  