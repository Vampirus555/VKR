<template>
  <div id="app">
    <SidebarComponent :isUserLoggedIn="isUserAuthenticated" :userRole="userRole"/>
    <div class="containerM">
      <!-- Хедер для входа/выхода учетной записи пользователя -->
      <div class="header">
        <div v-if="!isUserAuthenticated" style="margin-right: 10px;">
          <button class="show-modal-button" @click="showModal"><i class="fa-solid fa-arrow-right-to-bracket"></i>Войти</button>
        </div>
        <div v-else class="panel">
          <img :src="img" alt="Аватар">
          <div class="userData" >
              {{ name }} {{ surname }}
          </div>
          <button @click="logout"><i class="fa-solid fa-arrow-right-from-bracket"></i>Выйти</button>
        </div>
      </div>

    
      <Auth_reg ref="modal" @authenticated="handleAuthentication" @show-modal="show = true"/>
      <router-view></router-view>
    </div>
  </div>
</template>

<script>
  import HeaderComponent from './components/HeaderComponent.vue';
  import SidebarComponent from './components/menu/SidebarComponent.vue';
  import Auth_reg from './components/AuthRegComponent.vue';
  import axios from 'axios';

  export default {
  name: 'App',
  components: {
    HeaderComponent,
    SidebarComponent,
    Auth_reg
  },
  // переменные, используемые в текущем компоненте
  data() {
    return {
      isModalOpen: false,
      isUserAuthenticated: false,
      img: '',
      name: '',
      surname: '',
      userRole: ''
    };
  },
  methods: {
      showModal: function () {
        this.$refs.modal.show = true;
      },
      logout() {
        const accessToken = localStorage.getItem('accessToken');
        // localStorage.removeItem('accessToken');
        console.log('Токен', accessToken)

  // Проверяем, есть ли доступный токен
        if (!accessToken) {
          console.error('Нет доступа. Пользователь не авторизован.');
          return;
        }

        // Добавляем заголовок Authorization с Bearer токеном
        const config = {
          headers: { Authorization: `Bearer ${accessToken}` }
        };

        // Выполняем запрос на выход пользователя с установленным заголовком
        axios.post('http://127.0.0.1:8000/api/logout', null, config)
          .then(response => {
            console.log(response.data); // Выводим ответ от сервера
            // Очищаем токен после успешного выхода
            localStorage.removeItem('accessToken');
            localStorage.removeItem('user');
            this.isUserAuthenticated = false;
            this.name = '';
            this.surname = ''; 
            this.img = '';
          
            // Добавьте здесь дополнительную логику после выхода
          })
          .catch(error => {
            console.error(error); // Отображаем ошибку, если запрос не удался
            // Добавьте здесь логику обработки ошибки выхода
          });
      },
      handleAuthentication() {
            this.isUserAuthenticated = true;
            
        }
    },
    mounted() {
        const token = localStorage.getItem('accessToken');
        const user = localStorage.getItem('user');
      
        if (token) {
            const userData = JSON.parse(user);
            this.isUserAuthenticated = true;
            console.log(userData);
            if(userData.role[0] === "Обычный пользователь") {this.userRole="user"};
            if(userData.role[0] === "Организатор мероприятий") {this.userRole="organizer"};
            if(userData.role[0] === "Представитель парка") {this.userRole="delegate"};
            // this.userRole = user;
            this.name = userData.name;
            this.surname = userData.surname; 
            this.img = userData.image;
        }
    }
};
</script>

<style scoped>

  #app {
    display: flex;
    background: #996633 linear-gradient(rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.1));
    height: 100vh;
    margin-top: 0;
    overflow: hidden;
    font-family: 'Open Sans', sans-serif;
  }
  .header {
    display: flex;
    justify-content: flex-end;
  }
  .userData {
    text-align: center; 
    color: #D9D9D9;
    
    
  }

  .containerM {
    display: flex;
    flex-direction: column;
    flex: 1;
    margin-left: 240px;
    margin-top: 0;
    overflow: hidden;

  }

  .panel {
    margin: 10px;
    background: black linear-gradient(rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.1));
    /* width: 400px; */
    height: 40px;
    display: flex;
    justify-content: flex-end;
    border-radius: 10px;
    outline: 2px solid #d9d9d9;
    align-items: center;
  }

  button {
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

  button:hover {
    background: #deedff;
    cursor: pointer;
  }

  i {
    margin-right: 15px;
  }
  img {
    border-radius: 10px;
    height: 40px;
    width: 40px;
    margin-right: 15px;
  }


</style>
