<template>
    <div class="block">
        <div class="events">
            <div class="header">Мои мероприятия</div>
            
            <button style="margin-left: 40%; margin-top: 5px;" сlass="show-modal-button" @click="showModal">Добавить</button>
            
            <div class="notes-field">
                

                <div class="note-card" v-for="event in events" :key="event.id" @click="selectEvent(event)">
                    
                    <div class="review-header">
                        <div class="name-group">
                        <div class="initials"><img :src="event.image" style="width: 80px; height: 50px; border-radius: 10px;" alt="Изображение события"></div>
                        <p>{{event.name }} </p>
                        </div>
                    </div>
                    
                    <div class="review-details">
                        <div class="review-date"> <i class="fa-solid fa-tree"></i> {{ event.park[0]}}  <i style="margin-right: 5px; margin-left: 10px;"class="fa-solid fa-square-poll-vertical"></i>{{ event.status}}</div>
                    </div>
                    

                </div>


            </div>
        </div>

        
        <div class="detail">
            
            <div class="field"> 
                <div v-if="selectedEvent">
                   <div class="note-f">
                    <div class="image">
                        <img :src="selectedEvent.image"  alt="Изображение события">
                    </div>
                    <div class="content">
                        <h2>{{ selectedEvent.name }}</h2>
                        {{ selectedEvent.desc }}
                        <div  style="color: #d9d9d9; margin-bottom: 10px; margin-top: 10px" class="info-item">
                            <i style="margin-right: 10px"class="fas fa-tree"></i>
                            <span>{{ selectedEvent.park[0]}}</span>
                        </div>

                        <div style="color: #d9d9d9; margin-bottom: 10px" class="info-item">
                            <i style="margin-right: 10px" class="fas fa-person"></i>
                            <span>{{ selectedEvent.limit_age }}</span>
                        </div>

                        <div  style="color: #d9d9d9; margin-bottom: 10px" class="info-item">
                            <i style="margin-right: 10px" class="fas fa-tags"></i>
                            <span>{{ selectedEvent.price }}</span>
                        </div>

                        <div  style="color: #d9d9d9; margin-bottom: 10px" class="info-item">
                            <i style="margin-right: 10px" class="fas fa-phone"></i>
                            <span>{{ selectedEvent.phone }}</span>
                        </div>

                        <div style="color: #d9d9d9; margin-bottom: 10px"  class="info-item">
                            <i style="margin-right: 10px" class="fas fa-clock"></i>
                            <span>{{ formatDate(selectedEvent.start_date) }} - {{ formatDate(selectedEvent.end_date) }}</span>
                        </div>

                        <div style="color: #d9d9d9; margin-bottom: 10px" class="info-item">
                            <i style="margin-right: 10px" class="fas fa-calendar"></i>
                            <span v-if="!editing">{{ selectedEvent.schedule }}</span>
                            <textarea style="resize: none; width: 300px; " rows="2" v-else v-model="editedText"></textarea>
                        </div>
                        <button @click="editNote">{{ editing ? 'Сохранить' : 'Редактировать' }}</button>
                        <button style="margin-left: 15px;">Удалить</button>  
                    </div>
                   </div> 
                            
                </div>
                <div v-else style="color: #d9d9d9; margin-left: 170px;"> 
                    <p>Выберите мероприятие...</p>
                </div>
            </div>

        </div>
    </div>
    

    
    <Form ref="modalForm"  @show-modal="show = true"/>
</template>

<script>
    import Form from '../components/AddEvent.vue';
    import axios from 'axios';
    import dayjs from 'dayjs';
    import 'dayjs/locale/ru'
    export default {
        name: "MyEvents",
        data() {
            return {
                    isModalOpen: false,
                    events: [],
                    selectedEvent: null,
                    editing: false,
                    editedText: '',
                };
        },
        methods: {
            showModal: function () {
                this.$refs.modalForm.show = true;
            },
            editNote() {
            if (this.editing) {
                // Сохранить изменения
                const selectedEvent = this.selectedEvent;
                selectedEvent.schedule = this.editedText;

                // Отправить изменения на сервер, если необходимо

                this.editing = false;
            } else {
                this.editedText = this.selectedEvent.schedule;
                this.editing = true;
            }
        },

            getEvents(){
                const accessToken = localStorage.getItem('accessToken');
                console.log('Токен', accessToken);

                axios.get('http://127.0.0.1:8000/api/my-eventsE', {
                headers: {
                    Authorization: `Bearer ${accessToken}`
                }
                })
                .then(response => {
                    console.log(response.data); // Выводим ответ от сервера
                    this.events = response.data.data;
                })
                .catch(error => {
                    console.error(error); 
                    
                });
            },
            selectEvent(event) {
                this.selectedEvent = event;
            },
            reviewFormatData(dateString){ 
                dayjs.locale('ru');
                return dayjs(dateString).format('DD MMMM, YYYY');
                },
                formatDate(date) {
                dayjs.locale('ru');
                return dayjs(date).format('D MMM');
                },
        },
        mounted() {
            this.getEvents();
        },
        components: {
            Form
        }
    }
</script>

<style scoped>
   .block {
        width: 90%;
        height: 90%;
        margin: auto; /* Автоматически вычисляет отступ слева */
        border-radius: 10px;
        outline: 2px solid #FFCC66;
        display: flex; /* Используем flexbox для контейнера */
        flex-wrap: wrap;
        

        background: black linear-gradient(rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.1));
    }

    .events {
        width: 40%;
        height: 100%;
        border-radius: 10px;
        outline: 2px solid #FFCC66;
        overflow: hidden;
        /* background: #7B5C47; */
    
    }
    .detail {
        /* width: 60%; */
        height: 100%;
        align-items:flex-start; 
        justify-content:flex-start; /* Добавляем центрирование по горизонтали */
        display: flex;
        margin: auto;

    
        /* outline: 2px solid #e6dddd; */
        

    }
    .header {
        font-size: 25px;
        font-weight: 600;
        color: #d9d9d9;
        width: 100%;
        height: 40px;
        border-bottom: 1px solid white;

        align-items: center; 
        display: flex;
        flex-direction: column;
        
    }

    .notes-field {
        height: 100%;
        align-items: center;
        /* background:#6633FF; */
        display: flex;
        flex-direction: column;
       
        overflow-y: auto;
        &::-webkit-scrollbar {
                width: 20px;
                    background: white;
                }
                
                &::-webkit-scrollbar-thumb {
            background-color: #6633FF;
                }

    }


    .note-card {
        display: flex;
        flex-direction: column;
        justify-content: space-around;
        border: 1px solid #ccc;

        background: #2c2b3f;
        border-radius: 10px;
        width: 450px;
        margin: 10px;
    }

    .image-column {
        flex: 1;
        
    }
    img {
        object-fit: cover;
        width: 650px;
        height: 200px;
    
       
        
    }

    .info-column {
    flex: 2;
    padding: 10px;
    }

    .event-name {
    font-size: 20px;
    font-weight: bold;
    }

    .park-info {
    font-size: 16px;
    }


    .review-header {
	display: flex;
	align-items: center;
	justify-content: space-between;
	color: #ffffff;
	font-weight: bold;
	margin:10px;
}

.name-group {
	display: flex;
	align-items: center;
}

.initials {
	/* display: flex;
	justify-content: center;
	align-items: center;
	width: 48px;
	height: 48px; */
	margin-right: 12px;
	
}

.rating {
	i {
		color: #FFCC66;
	}
}

.review-description {
	color: #ffffff;
	font-weight: 400;
	margin-left: 10px;
	margin-right: 10px;
}

.review-details {
	display: flex;
	justify-content: space-between;
	margin: 10px;
	align-items: center;
	color: #8c8aa7;
}


.field {
    
    width: 550px;
    min-height: 700px;
    background: black linear-gradient(rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.1));
    outline: 2px solid #e6dddd;
    border-radius: 10px;
    margin: auto;
    overflow: hidden;
    align-items:flex-start;
    color: #d9d9d9;
}

.note-f {
    /* display: flex;
    flex-direction: column;
    align-items: center; */
    overflow: hidden;
}

.image {
    width: 950px;
    height: 200px;
    overflow: hidden;
    object-fit: cover;
    margin-bottom: 10px;
    align-items: center;
}

.content {
    text-align: center;
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
</style>