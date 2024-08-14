<template>
    <div v-if="show" class="modal-shadow" @click.self="closeModal">
        <div class="container">
            <header>Добавление мероприятия</header>
            <div class="progress-bar">
            <div v-for="(step, index) in steps" :key="index" class="step">
                <p :class="{ active: currentStep > index }">{{ step }}</p>
                <div class="bullet" :class="{ active: currentStep > index }">
                <span>{{ index + 1 }}</span>
                </div>
                <div class="check fas fa-check" :class="{ active: currentStep > index }"></div>
            </div>
            </div>
            <div class="form-outer">
            <form @submit.prevent="handleSubmit">
                <div class="page slide-page" v-show="currentStep === 0">
                <div class="block">
                    <div @click="uploadImage" class="circle" :style="{ backgroundImage: `url(${image})` }"></div>
                    <input type="file" ref="fileInput" style="display: none" @change="handleFileUpload">
                    <div class="inputs">
                        <input type="text" v-model="nameEvent" placeholder="Название мероприятия" />
                        <select v-model="type" style="text-align: center;">
                            <option value="временное">Временное</option>
                            <option value="постоянное">Постоянное</option>
                        </select>
                        <input v-show="type === 'временное'" v-model="start_data" type="text" ref="dateInput" @focus="showDatePicker" placeholder="Дата начала" />
                        <input v-show="type === 'временное'" v-model="end_data" type="text" ref="dateInput2" @focus="showDatePicker2" placeholder="Дата окончания" />
                        {{ start_data }}

                    </div>
                </div>
                <div class="field">
                    <button class="firstNext next" @click="next">Далее</button>
                </div>
                </div>

                <div class="page" v-show="currentStep === 1">
                <div >
                    <textarea v-model="desc"  placeholder="Описание мероприятия..." ></textarea>
                </div>
                
                <div class="field btns">
                    <button class="prev-1 prev" @click="prev">Назад</button>
                    <button class="next-1 next" @click="next">Далее</button>
                </div>
                </div>

                <div class="page" v-show="currentStep === 2">
                <div class="inputs">
                    <input type="text" v-model="limit_age" placeholder="Возрастное ограничение" />
                    <input type="text" v-model="price" placeholder="Цена" />
                    <input type="text" v-model="phone" placeholder="Телефон"/>
                    <div class="custom-select">
                        <select v-model="selectedCategory" placeholder="Выберите категорию">
                        <option value="" disabled selected>Выберите категорию</option>
                        <option v-for="category in categories" :value="category.id" :key="category.id">{{ category.name }}</option>
                        </select>
                    </div>
                    
                </div>
               
                <div class="field btns">
                    <button class="prev-2 prev" @click="prev">Назад</button>
                    <button class="next-2 next" @click="next">Далее</button>
                </div>
                </div>

                <div class="page" v-show="currentStep === 3">
                <!-- <div class="title">Площадка:</div> -->
                <div class="custom-select">
                    <select v-model="selectedPark">
                    <option value="" disabled selected>Выберите парк</option>
                    <option v-for="park in parks" :value="park.id" :key="park.id">{{ park.name }}</option>
                    </select>
                </div>
                
                <textarea  placeholder="Расписание..." ></textarea>
                <div class="field btns">
                    <button class="prev-3 prev" @click="prev">Назад</button>
                    <button @click="addEvent">Добавить</button>
                </div>
                </div>
            </form>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    

    export default {
        name: "AddEvent",
       
        data() {
            return {
                show: false,
                steps: ["Основное", "Описание", "Сведения", "Площадка"],
                currentStep: 0,
                type: "временное",
                image: '',
                imageS: '',
                nameEvent: '',
                start_date: '',
                end_date: '',
                desc: '',
                schedule: '',
                limit_age: '',
                price: '',
                phone: '',
                park: '',

                categories: [],
                selectedCategory: [],
                parks: [],
                selectedPark: '',
            }
        },
        mounted(){
            this.getCategories();
            this.getParks();
        },
        methods: {
            
            closeModal: function () {
                this.show = false
            },
            showDatePicker() {
                this.$refs.dateInput.type = 'date'; // При фокусе меняем тип на 'date'
            },
            showDatePicker2() {
                this.$refs.dateInput2.type = 'date'; // При фокусе меняем тип на 'date'
            },
            next() {
                if (this.currentStep < this.steps.length) {
                    this.currentStep++;
                }
            },
            prev() {
                if (this.currentStep > 0) {
                    this.currentStep--;
                }
            },
            handleSubmit() {
                if (this.currentStep === this.steps.length) {
                    alert("Your Form Successfully Signed up");
                    location.reload();
                }
            },

            uploadImage() {
                this.$refs.fileInput.click();
            },
            handleFileUpload(event) {
                const file = event.target.files[0];
                const reader = new FileReader();
                reader.onload = () => {
                    this.imageS = reader.result;
                };
                reader.readAsDataURL(file);
                this.image = URL.createObjectURL(file);
            },
            async fetchPArks(){

            },
            async fetchPArks(){
                
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
            getParks(){
                axios.get('http://127.0.0.1:8000/api/parks')
                    .then(response => {
                        console.log('Токен', response.data);
                        this.parks = response.data.data;
                    })
                    .catch(error => {
                        console.error(error); 
                    });
            },

            addEvent(){
                
                const accessToken = localStorage.getItem('accessToken');
                const data = {
                    name: this.nameEvent,
                    park_id: 3,
                    type: this.type,
                    phone: this.phone,
                    category_ids: [1],
                    limit_age: this.limit_age,
                    desc: this.desc,
                    schedule: this.schedule,
                    start_date: this.date_start,
                    end_date: this.date_end,
                    price: this.price,
                    image: this.imageS
                   

                };

                axios.post('http://127.0.0.1:8000/api/add-event', data, {
                headers: {
                    Authorization: `Bearer ${accessToken}`
                }, 
                } )
                .catch(error => {
                    console.error(error); 
                    
                });
                this.closeModal();
               
            }

        }
    }
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

    

    .modal-shadow {
        position: absolute;
        z-index: 1000;
        top: 0;
        left: 0;
        min-height: 100%;
        width: 100%;
        background: rgba(0, 0, 0, 0.65);
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
    .container header {
        font-size: 35px;
        font-weight: 600;
        margin: 0 0 30px 0;
        color: #d9d9d9;
    }
    .container .form-outer {
        width: 100%;
        overflow: hidden;
    }
    .container .form-outer form {
        display: flex;
        width: calc(100% * 4);
    }
    .form-outer form .page {
        width: calc(100% / 4);
        transition: margin-left 0.3s ease-in-out;
    }
    .form-outer form .page .title {
        text-align: left;
        font-size: 25px;
        font-weight: 500;
    }
    .form-outer form .page .field {
        width: 600px;
        height: 45px;
        margin: 45px 0;
        display: flex;
        position: relative;
    }
    form .page .field .label {
        position: absolute;
        top: -30px;
        font-weight: 500;
    }
    form .page .field input {
        box-sizing: border-box;
        height: 100%;
        width: 100%;
        /* border: 1px solid var(--inputBorderColor); */
        border-radius: 5px;
        padding-left: 15px;
        margin: 0 1px;
        font-size: 18px;
        transition: border-color 150ms ease;
    }

    form .page .field select {
        width: 100%;
        padding-left: 10px;
        font-size: 17px;
        font-weight: 500;
    }
    form .page .field button {
        width: 100%;
        height: calc(100% + 5px);
        border-radius: 15px;
        border: 2px solid #FFCC66;
        background-color: #6633FF;
        margin-top: -20px;
        color: #fff;
        cursor: pointer;
        font-size: 18px;
        font-weight: 500;
        letter-spacing: 1px;
        text-transform: uppercase;
        transition: 0.5s ease;
    }
    form .page .field button:hover {
        background: #000;
    }
    form .page .btns button {
        margin-top: -20px !important;
    }
    form .page .btns button.prev {
        margin-right: 3px;
        font-size: 17px;
    }
    form .page .btns button.next {
        margin-left: 3px;
    }
    .container .progress-bar {
        display: flex;
        margin: 20px 0;
        user-select: none;
    }
    .container .progress-bar .step {
        text-align: center;
        width: 100%;
        position: relative;
        color: #d9d9d9;
    }
    .container .progress-bar .step p {
        font-weight: 500;
        font-size: 18px;
        color: #d9d9d9;;
        margin-bottom: 8px;
    }
    .progress-bar .step .bullet {
        height: 25px;
        width: 25px;
        border: 2px solid #339933;
        display: inline-block;
        border-radius: 50%;
        position: relative;
        transition: 0.2s;
        font-weight: 500;
        font-size: 17px;
        line-height: 25px;
    }
    .progress-bar .step .bullet.active {
        border-color: #339933;
        background: #339933;
    }
    .progress-bar .step .bullet span {
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
    }
    .progress-bar .step .bullet.active span {
        display: none;
    }
    .progress-bar .step .bullet:before,
    .progress-bar .step .bullet:after {
        position: absolute;
        content: "";
        bottom: 11px;
        right: -85px;
        height: 3px;
        width: 65px;
        background: #262626;
    }
    .progress-bar .step .bullet.active:after {
        background: #339933;
        transform: scaleX(0);
        transform-origin: left;
        animation: animate 0.3s linear forwards;
    }
    @keyframes animate {
        100% {
            transform: scaleX(1);
        }
    }
    .progress-bar .step:last-child .bullet:before,
    .progress-bar .step:last-child .bullet:after {
        display: none;
    }
    .progress-bar .step p.active {
        color: #333;
        transition: 0.2s linear;
    }
    .progress-bar .step .check {
        position: absolute;
        left: 50%;
        top: 70%;
        font-size: 15px;
        transform: translate(-50%, -50%);
        display: none;
    }
    .progress-bar .step .check.active {
        display: block;
        color: #fff;
    }

    .circle {
            width: 250px;
            height: 150px;
            border-radius: 10px;
            border: 1px solid #339933;
            background-size: cover;
            background-position: center;
            background-color: #ccc;
            margin-bottom: 20px;
        }
    .block {
    display: grid;
        grid-template-columns: 1fr 1fr; /* Разделение на две колонки */
        grid-column-gap: 15px; /* Расстояние между колонками */
    }
    .inputs {
        display: flex;
        flex-direction: column;
    }
    input, select {
        background-color: #eee;
        border: none;
        border-radius: 5px;
        padding: 5px 10px;
        margin-bottom: 5px;
    margin-top: 5px;
        width: 200px;
    }

    textarea {
    background-color: #eee;
    border: none;
    border-radius: 5px;
    padding: 5px 10px;
    /* margin-bottom: 15px; */
    width: 600px;
    height: 150px;
    flex-direction: center;
    resize: none;
    
    }

</style>