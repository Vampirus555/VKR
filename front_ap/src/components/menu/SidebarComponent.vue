<template>
    <div class="menu" >
        <img src="/logo.png" alt="Логотип" width="180" height="30">
        
        <div v-for="(section, index) in dynamicMenu" :key="index">
          <div class="divider"></div>
          <div class="label">{{ section.label }}</div>
            <router-link
              v-for="(item, itemIndex) in section.items"
              :key="itemIndex"
              :to="item.path"
              style="text-decoration: none;"
            >
              <NavItem
                :data="item.children"
                :icon="item.icon"
                :label="item.label"
                :depth="0"
              />
            </router-link>
          
        </div>

  

      
    </div>
  </template>
  
  <script>
  import NavItem from './NavItem.vue';
  import Auth_reg from '../AuthRegComponent.vue'
  

  
  export default {
    name: 'SidebarComponent',

    props: {
      isUserLoggedIn: {
                type: Boolean,
                required: true
      },
      userRole: {
                type: Text,
                required: true
      },
    },
    data() {
      return {
        smallMenu: false,
        // isUserLoggedIn: tr,
        // userRole: '',
        menuTree: [

          {
            label: "Основное",
            items: [
              {label: "Главная", icon: ["fas", "fa-home"], path: '/'},
              {label: "Парки", icon: ["fas", "fa-tree"], path: '/parks'},
              {label: "Мероприятия", icon: ["fas", "fa-layer-group"], path: '/events'},
              // {label: "Рекомендации", icon: ["fas", "fa-fire"], path: '/parks', roles: ['user', 'admin'] },
            ]
          },

          {
            label: "Личный кабинет",
            items: [
              {label: "Аккаунт", icon: ["fas", "fa-user"], path: '/parks', roles: ['user', 'organizer', 'delegate', 'admin']},
              {label: "Заметки", icon: ["fas", "fa-bookmark"], path: '/notes', roles: ['user', 'organizer', 'delegate', 'admin']},
              {label: "Мой парк", icon: ["fas", "fa-chart-simple"], path: '/myPark', roles: ['delegate', 'admin']},
              {label: "Мои события", icon: ["fas", "fa-chart-simple"], path: '/myEvents', roles: ['admin', 'organizer']},
              {label: "Диалоги", icon: ["fas", "fa-comments"], path: '/dialogs', roles: ['organizer', 'delegate', 'admin']},
            ]
          },

          {
            label: "Дополнительно",
            items: [
              {label: "Поддержка", icon: ["fas", "fa-circle-info"], path: '/support'},
            ]
          },
          
        ],
      } 
    },
    components: {
      NavItem,
      Auth_reg
    },
    methods: {
      openModal(){
        this.$emit('open-modal')
      }
    },
    mounted() {
      if(this.isUserLoggedIn){
        this.userRole= localStorage.getItem('user');
      }
    },
    computed: {

      dynamicMenu() {
        return this.menuTree.reduce((acc, menuGroup) => {
            const filteredItems = menuGroup.items.filter(item => {
                if (item.roles) {
                    if (this.isUserLoggedIn) {
                        return item.roles.includes(this.userRole);
                    } else {
                        return false; // скрывать пункты с roles для неавторизованного пользователя
                    }
                }
                return true; // отображать пункты без roles для всех пользователей
            });
            if (filteredItems.length > 0) {
                acc.push({ label: menuGroup.label, items: filteredItems });
            }
            return acc;
        }, []);
    }
      
    }

  }
  </script>
  
  <style lang="scss" scoped>
  .menu {
    position: fixed;
    height: 100vh;
    width: 240px;
    padding-right: 1px; 
    background: black linear-gradient(rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.1));
    left: 0;
    top: 0;
    border-right: 1px solid #e6dddd;
    transition: all .3s ease;
    overflow: auto;
    i {
      position: fixed;
      left: 250px;
      font-size: 20px;
      top: 15px;
      user-select: none;
      cursor: pointer;
      transition: all .3s ease;
    }
  }
  .label {
    font-size: 20px;
    font-weight: bold;
    color: #D9D9D9;
    margin-left: 10px;
    margin-top: 10px;
  }
  .divider {
    border-top: 1px solid #e6dddd;
    
  }

  img{
    margin: 10px;
    
  }

 
  </style>