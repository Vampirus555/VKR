<template>
    <div v-if="show" class="modal-shadow" @click.self="closeModal">
        <div id="support-form-modal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <header>Фильтрация мероприятий</header>
                
                <div class="form-group-lg">
                    <label for="email-input">Парк</label>
                    <div class="custom-select">
                        <select v-model="selectedPark">
                        <option value="" disabled selected>Выберите парк</option>
                        <option v-for="park in parks" :value="park.id" :key="park.id">{{ park.name }}</option>
                        </select>
                    </div>
                </div>          
                    
                <div class="form-group-lg">
                    <label for="name-input">Тип мероприятия</label>
                    <div class="custom-select">
                        <select v-model="selectedType">
                        <option value="" disabled selected>Выберите тип</option>
                        <option  :value="временное" >временное</option>
                        <option  :value="постоянное" >постоянное</option>
                        </select>
                    </div>
                </div>

                <div class="form-group-lg">
                    <label for="inquiry-input">Дата проведения</label>
                    <input type="date" style="width: 390px;" class="form-control" id="inquiry-input" ></input>
                </div>
                
                <div class="form-group-lg">
                    <label for="product-input">Выбрать категорию</label>
                    <div class="custom-select">
                        <select v-model="selectedCategory">
                        <option value="" disabled selected>Выберите категорию</option>
                        <option v-for="category in categories" :value="category.id" :key="category.id">{{ category.name }}</option>
                        </select>
                    </div>
                </div>
                        
                

                <button @click="getFilteredEvents()">Применить</button>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
    </div>
</template>

<script>
    import axios from 'axios';
    export default {
        name: "AddEvent",
       
        data() {
            return {
                show: false,
                parks: [],
                categories: [],
                selectedPark: '',
                selectedCategory: '',
                selectedType: '',


                filtered: []
            }
        },
        props: {
            event_id: {
                    type: Number,
                    required: true
                },
        },
        mounted() {
            this.getParks();
            this.getCategories();
        },
        methods: {
            closeModal: function () {
                this.show = false
            },
            getFilteredEvents(){
                // console.log(this.selectedPark);
                axios.get('http://127.0.0.1:8000/api/events', {
                params: {
                    park_id: this.selectedPark,
                // Другие параметры фильтрации, если необходимо
                }
                })
                .then(response => {
                    
                    this.filtered = response.data.data;
                    this.$emit('filtered-events', response.data.data);
                    console.log(response.data.data);
                })
                .catch(error => {
                    console.error('Ошибка при получении данных:', error);
                });
                this.closeModal();

            },
            getParks(){
                axios.get('http://127.0.0.1:8000/api/parks')
                    .then(response => {
                        this.parks = response.data.data;
                    })
                    .catch(error => {
                        console.error(error); 
                    });
            },
            getCategories(){
                axios.get('http://127.0.0.1:8000/api/categories')
                    .then(response => {
                        console.log('Токен', response.data);
                        this.categories = response.data.data;
                    })
                    .catch(error => {
                        console.error(error); 
                    });
            },
            addNote(){
                const accessToken = localStorage.getItem('accessToken');
                console.log('Токен', accessToken);

                const data = {
                    event_id: this.event_id,
                    text: this.addText,
                    
                };

                axios.post('http://127.0.0.1:8000/api/note', data, {
                headers: {
                    Authorization: `Bearer ${accessToken}`
                }
                })
                .catch(error => {
                    console.error(error); 
                    
                });

                this.closeModal();
            },
           

        }
    }
</script>

<style scoped>

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
    .modal-shadow {
        position: absolute;
        z-index: 1000;
        top: 0;
        left: 0;
        min-height: 100%;
        width: 100%;
        background: rgba(0, 0, 0, 0.7);
    }
    .container {
        width: 600px;
        background: black linear-gradient(rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.1));
        text-align: center;
        border-radius: 15px;
        border: 2px solid #FFCC66;
        padding: 50px 35px 10px 35px;

        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    #support-form-modal {
  text-align: center;
  border-radius: 10px;
  /* margin-left: auto;
  margin-right: auto;
  margin-top: 50px; */

  position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
}

.modal-dialog {
  display: inline-block;
}

.modal-content {
  background: black linear-gradient(rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.1));
  padding: 20px;
  display: inline-block;
  text-align: left;
  border-radius: 10px;
  outline: 2px solid #FFCC66;
  
}

header {
  font-size: 35px;
  font-weight: 600;
  margin: 0 0 30px 0;
  color: #d9d9d9;
}

.form-group-lg {
  margin: 10px 0;
}

label {
  color: #d9d9d9;
  display: block; /* Добавляем этот стиль для лейблов */
}

input,
select,
textarea {
  width: 90%;
  padding: 8px;
  margin-top: 5px;
  
}

button {
  border-radius: 15px;
  width: 100%;
  max-width: 300px; /* Ширина кнопки не более 300px */
  height: 40px;
  font-size: 20px;
  border: 2px solid #FFCC66;
  background-color: #6633FF;
  color: #fff;
  margin: auto;
}

button:hover {
  background-color: #34495e;
}

textarea {
  resize: none;
}

    
</style>