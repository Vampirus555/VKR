<template>
    <div class="header">
      <button сlass="show-modal-button" @click="showModal">Авторизация/регистрация</button>
      <Auth_reg ref="modal" @show-modal="show = true"/>
    </div>
</template>
  
<script>
  import Auth_reg from './AuthRegComponent.vue'
  export default {
    name: 'Header',
    components: {
      Auth_reg
    },
    methods: {
      showModal: function () {
        this.$refs.modal.show = true;
      }
    },
  }
</script>
  
<style scoped>
    .header {
        background-color: #000000; /* Цвет фона */
        color: white;
        width: 100%; /* Ширина на всю страницу */
        height: 60px; /* Высота хедера */
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 15px;
        /* margin-left: 240px; */
    }
</style>