<template>
    <div v-if="show" class="modal-shadow" @click.self="closeModal">
        <div class="container">
            <header>Добавление заметки</header>
            <!-- {{ event_id }} -->
            <textarea v-model="addText" placeholder="Введите текст..." rows="6"></textarea>
            <button class="firstNext next" @click="addNote">Добавить</button>
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
                addText: '',
            }
        },
        props: {
            event_id: {
                    type: Number,
                    required: true
                },
        },
        methods: {
            closeModal: function () {
                this.show = false
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
   button {
        width: 300px;
        height: calc(100% + 5px);
        border-radius: 15px;
        border: 2px solid #FFCC66;
        background-color: #6633FF;
        margin: 10px;
        color: #fff;
        cursor: pointer;
        font-size: 16px;
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
        margin-bottom: 15px;
        width: 400px;
        resize: none;
    
    flex-direction: center;
    }

    
</style>