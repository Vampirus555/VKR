<template>
   
  <div class="card">
    <div class="card-banner">
      <img :src="event.image" style="width: 430px; height: 200px;" alt="Park Image">
       </div>
    <div class="card-info">
      <div class="row">
        <div class="col-10 offset-1 event-title-holder">
          <h3>{{event.name}}</h3>
        </div>
      </div>
      <div class="row">
        <div class="col-10 offset-1 event-description-holder">
          <p>Филёвская школа саберфайтинга — одна из первых в мире команд, занимающихся подготовкой и демонстрацией постановочных поединков в стилистике «Звёздных Войн» и обучением всех желающих этому направлению арт-фехтования.</p>
        </div>
      </div>
      <div class="row">
        <div class="col-10 offset-1 event-tag-holder">
          <span>#Кружок</span>
          <span>#Обучение </span>
          <span> #Развлекательное </span>
        </div>
      </div>
      <div class="row">
        <div class="col-10 offset-1 event-details-holder">
          <div class="row">
            <div>
              <i :class="[icon ? icon.join(' ') : 'fa-solid fa-tree']"></i>
            </div>
            
            <div class="col-10">
              <p>{{ event.park[0] }}</p>
            </div>
          </div>
          <div v-if="event.type === 'временное'" class="row">
            <div class="col-1">
              <i class="far fa-calendar" aria-hidden="true"></i>
            </div>

            <div  class="col-10">
              <p>25 мая - 23 апреля</p>
            </div>
          </div>
          <div v-if="event.type === 'постоянное'"class="row">
            <div class="col-1">
              <i :class="[icon ? icon.join(' ') : 'fa-solid fa-phone']"></i>
            </div>
            <div class="col-10">
              <p>88005553535</p>
            </div>
          </div>
          <div style="display: flex;">
            <button @click="goToEvent(event.id)" :eventId="event.id">Подробнее</button>
              <span style="margin-left: 170px;" v-if="event.hasNote">
                <i class="fa-solid fa-bookmark"></i>
              </span>
              <span style="margin-left: 170px;" v-else>
                <i class="fa-regular fa-bookmark" @click="showModal"></i>
              </span>
          </div>
          
        </div>
      </div>
    </div>
  </div>

  <AddNote ref="modal" :event_id="event.id"  @show-modal="show = true"/>

  </template>
  
  <script>
    import AddNote from './AddNote.vue';
    import Pag from "./Pagination.vue";
    export default {
      name: 'Card',
      data (){
        return{
          isModalOpen: false,
        }
      },
      props: {
        event: {
                  type: Array,
                  required: true
              },
      },
      methods: {
        goToEvent(params) {
            this.$router.push('/event?id=' + JSON.stringify(params));
        },
        showModal: function () {
          this.$refs.modal.show = true;
        },
      },
      components: {
        AddNote,
        Pag
      }
    }
  </script>
  
  <style scoped>
    .fa-solid:before {
      font-family: "Font Awesome 5 Free";
      font-weight: 900;
    }
    .fa-cube:before {
      content: "\f1b2"; /* Unicode символ для иконки куба */
    }

    .fa-phone:before {
      content: "\f095"; /* Unicode символ для иконки телефона */
    }

    .fa-calendar:before {
      content: "\f073"; /* Unicode символ для иконки календаря */
    }

    .fa-tree:before {
      content: "\f1bb"; /* Unicode символ для иконки дерева */
    }

    .fa-phone:before {
      content: "\f095"; /* Unicode символ для иконки телефона */
    }
  /* Containers */
  /* .card-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 20px;
    
    max-width: 20%;
    
  } */
  
  .card {
    background: black linear-gradient(rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.1));
    outline: 2px solid #e6dddd;
    border-radius: 15px;
    overflow: hidden;
    position: relative;
    cursor: pointer;
    max-width: 300px;
    margin-bottom: 10px;
  }
  
  .card:hover {
    box-shadow: 0px 0px 15px 5px rgba(0, 0, 0, 0.8);
  }
  
  /* Banners */
  .card-banner {
    width: 100%;
    height: 110px;
    overflow: hidden;
    position: relative;
  }
  
  .card-banner-img {
    width: auto;
    height: 110px;
    max-width: 100%;
    object-fit: cover;
  }
  
  /* Event Title */
  .event-title-holder h3 {
    font-weight: 600;
    font-size: 18px;
    color: #d9d9d9;
    text-align: center;
    margin: 10px 0;
  }
  
  /* Event Description */
  .event-description-holder p {
    font-weight: 400;
    font-size: 12px;
    color: #d9d9d9;
    text-align: justify;
    margin: 0;
    /* padding: 0 10px; */
  }
  
  /* Event Tags */
  .event-tag-holder {
    display: flex;
    justify-content: center;
    margin: 5px 0;
  }
  
  .event-tag-holder span {
    font-weight: 600;
    font-size: 11px;
    color: #ffcc66;
    margin-right: 5px;
  }
  
  /* Event Details */
  .event-details-holder {
    display: flex;
    flex-direction: column;
    align-items: start;
    margin-left: 10px;
  }
  
  .event-details-holder p {
    font-weight: 400;
    font-size: 12px;
    color: #d9d9d9;
    margin: 5px 0;
  }
  
  .event-details-holder .row {
    display: flex;
    align-items: center;
    margin: 2px 0;
  }
  
  /* .event-details-holder .row .col-1 {
    flex: 0 0 10%;
  }
  
  .event-details-holder .row .col-10 {
    flex: 0 0 90%;
  } */
  
  .event-details-holder i {
    font-weight: 400;
    font-size: 12px;
    color: #d9d9d9;
    line-height: 1;
    margin-right: 5px;
  }
  
  button {
    border-radius: 15px;
    border: 1px solid #FFCC66;
    background-color: #6633FF;
    color: #FFFFFF;
    font-size: 12px;
    font-weight: bold;
    padding: 2px 10px;
    letter-spacing: 1px;
    text-transform: uppercase;
    transition: transform 80ms ease-in;
    margin-bottom: 10px;
  }
  
  button:hover {
    transform: scale(1.05);
  }
  </style>