<template>
    <div v-if="show" class="modal-shadow" @click.self="closeModal">
        <div class="container" :class="{ 'right-panel-active': isSignUp }">
            <div class="form-container sign-up-container">
                <form v-if="step === 1">
                    <h1>ActivityParks</h1>
                    <div @click="uploadImage" class="circle" :style="{ backgroundImage: `url(${image})` }"></div>
                    <input type="file" ref="fileInput" style="display: none" @change="handleFileUpload">
                    <span style="display: inline-block;">
                        <p style="display: inline-block; color:white; font-size: 18px;">Ваша роль: </p>
                        <select v-model="role" style="text-align: center;">
                            <option value="user">Обычный пользователь</option>
                            <option value="organizer">Организатор</option>
                            <option value="representative">Представитель</option>
                        </select>
                    </span>
                    <button @click="changeStep(2)">
                        Далее
                    </button>
                </form>  
                
                <form v-if="step === 2">
                    <h1>ActivityParks</h1>
                    <input type="email" placeholder="Email" required />
                    <input type="password" placeholder="Пароль" required />
                    <input type="password" placeholder="Повторите пароль" required  />
                    <input type="text" placeholder="Имя" />
                    <input type="text" placeholder="Фамилия" />
                    <input type="tel" placeholder="Номер телефона" />
                    <!-- по условиям надо глянуть -->
                    <button v-if="role==='user'">Зарегистрироваться</button>
                    <button v-else @click="changeStep(3)">Далее</button>
                </form>

                <form v-if="step === 3">
                    <h1>ActivityParks</h1>
                    <div @click="uploadImage" class="circle" :style="{ backgroundImage: `url(${image})` }"></div>
                    <input type="file" ref="fileInput" style="display: none" @change="handleFileUpload">

                    <select v-model="role">
                        <option value="user">Обычный пользователь</option>
                        <option value="organizer">Организатор</option>
                        <option value="representative">Представитель</option>
                    </select>

                    <button>Зарегистрироваться</button>
                </form>  
            </div>
            <div class="form-container sign-in-container">
                <form>
                    <h1>ActivityParks</h1>
                    
                    
                    <input type="email" v-model="authlogin" placeholder="Email" />
                    <input type="password" v-model="authPass" placeholder="Password" />
                    
                    <button @click="authorize(authlogin, authPass)">Авторизоваться</button>
                </form>
            </div>
            <div class="overlay-container">
                <div class="overlay">
                    <div class="overlay-panel overlay-left">
                        <h1>Создал аккаунт?</h1>
                        <p>Тогда авторизуйся и открой новые возможности!</p>
                        <button class="ghost"  @click="toggleSignUp">К авторизации</button>
                    </div>
                    <div class="overlay-panel overlay-right">
                        <h1>Нет аккаунта?</h1>
                        <p>Тогда пройди регистрацию!</p>
                        <button class="ghost" @click="toggleSignUp">К регистрации</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
 
<script>
    import axios from 'axios';
    export default {
        name: "Auth_reg",
       
        data() {
            return {
                isSignUp: false,
                step: 1,
                image: '',
                show: false,
                authlogin: '',
                authPass: '',
                role: 'Выберите роль...',
                reglogin: '',
                name: '',
                surname: '',
                phone: '',
                regPass: '',
            }
        },
        methods: {
            closeModal: function () {
                this.show = false
            },
            toggleSignUp() {
                this.isSignUp = !this.isSignUp;
            },
            uploadImage() {
                this.$refs.fileInput.click();
            },
            handleFileUpload(event) {
                const file = event.target.files[0];
                const reader = new FileReader();
                reader.onload = () => {
                    this.image = reader.result;
                };
                reader.readAsDataURL(file);
                this.image = URL.createObjectURL(file);
            },
            authorize(login, password) {
                this.authlogin = login;
                this.authPass = password;
                
                // Формируем тело запроса
                const data = {
                    email: login,
                    password: password
                };

                // Выполняем AJAX запрос
                axios.post('http://127.0.0.1:8000/api/login', data)
                .then(response => {
                    console.log(response.data); // Выводим ответ от сервера
                    const token = response.data.token;
                    
                    const user = response.data.user;
                    localStorage.setItem('accessToken', token);
                    localStorage.setItem('user', JSON.stringify(user));
                })
                .catch(error => {
                    console.error(error); 
                    
                });
                
                this.$emit('authenticated');
                this.closeModal();
            },
            changeStep(newStep) {
                this.step = newStep;
            },
            registre() {
                this.changeStep(1);
            }
        }
    }
</script>
 
<style scoped lang="scss">
    .modal-shadow {
        position: absolute;
        z-index: 1000;
        top: 0;
        left: 0;
        min-height: 100%;
        width: 100%;
        background: rgba(0, 0, 0, 0.65);
    }
    .circle {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        border: 1px solid #339933;
        background-size: cover;
        background-position: center;
        background-color: #ccc;
        margin-bottom: 20px;
    }

    
* {
	box-sizing: border-box;
}

h1 {
	color: white;
} 


 p {
	font-size: 14px;
	font-weight: 100;
	line-height: 20px;
	letter-spacing: 0.5px;
	margin: 20px 0 30px;
}  

button {
	border-radius: 15px;
	border: 1px solid #FFCC66;
	background-color: #6633FF;
	color: #FFFFFF;
	font-size: 12px;
	font-weight: bold;
	padding: 12px 45px;
	letter-spacing: 1px;
	text-transform: uppercase;
	transition: transform 80ms ease-in;
}

button:active {
	transform: scale(0.95);
}

button:focus {
	outline: none;
}

button.ghost {
	background-color: transparent;
	border-color: #FFFFFF;
}

form {
	background: black linear-gradient(rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.1));
	display: flex;
	align-items: center;
	justify-content: center;
	flex-direction: column;
	padding: 0 50px;
	height: 100%;
	text-align: center;
}

input, select {
	background-color: #eee;
	border: none;
	border-radius: 5px;
	padding: 12px 15px;
	margin: 8px 0;
	width: 100%;
}

.container {
	background-color: black;
	border-radius: 10px;
	outline: 2px solid #FFCC66;
	// position: relative;
	overflow: hidden;
	width: 768px;
	max-width: 100%;
	min-height: 480px;

    position: absolute;
    z-index: 1000;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.form-container {
	position: absolute;
	top: 0;
	height: 100%;
	transition: all 0.6s ease-in-out;
}

.sign-in-container {
	left: 0;
	width: 50%;
	z-index: 2;
}

.container.right-panel-active .sign-in-container {
	transform: translateX(100%);
}

.sign-up-container {
	left: 0;
	width: 50%;
	opacity: 0;
	z-index: 1;
}

.container.right-panel-active .sign-up-container {
	transform: translateX(100%);
	opacity: 1;
	z-index: 5;
	animation: show 0.6s;
}

@keyframes show {
	0%, 49.99% {
		opacity: 0;
		z-index: 1;
	}
	
	50%, 100% {
		opacity: 1;
		z-index: 5;
	}
}

.overlay-container {
	position: absolute;
	top: 0;
	left: 50%;
	width: 50%;
	height: 100%;
	overflow: hidden;
	transition: transform 0.6s ease-in-out;
	z-index: 100;
}

.container.right-panel-active .overlay-container{
	transform: translateX(-100%);
}

.overlay {
	background: #996633 linear-gradient(rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.1));
	background-repeat: no-repeat;
	background-size: cover;
	background-position: 0 0;
	color: #FFFFFF;
	position: relative;
	left: -100%;
	height: 100%;
	width: 200%;
  	transform: translateX(0);
	transition: transform 0.6s ease-in-out;
}

.container.right-panel-active .overlay {
  	transform: translateX(50%);
}

.overlay-panel {
	position: absolute;
	display: flex;
	align-items: center;
	justify-content: center;
	flex-direction: column;
	padding: 0 40px;
	text-align: center;
	top: 0;
	height: 100%;
	width: 50%;
	transform: translateX(0);
	transition: transform 0.6s ease-in-out;
}

.overlay-left {
	transform: translateX(-20%);
}

.container.right-panel-active .overlay-left {
	transform: translateX(0);
}

.overlay-right {
	right: 0;
	transform: translateX(0);
}

.container.right-panel-active .overlay-right {
	transform: translateX(20%);
}



 
    // .modal {
    //     background: #6bd48e;
    //     border-radius: 8px;
    //     padding: 15px;
    //     min-width: 420px;
    //     max-width: 480px;
    //     height: 500px;
    //     position: absolute;
    //     top: 50%;
    //     left: 50%;
    //     transform: translate(-50%, -50%);
 
    //     &-close {
    //         border-radius: 50%;
    //         color: #fff;
    //         background: #2a4cc7;
    //         display: flex;
    //         align-items: center;
    //         justify-content: center;
    //         position: absolute;
    //         top: 7px;
    //         right: 7px;
    //         width: 30px;
    //         height: 30px;
    //         cursor: pointer;
    //     }
 
 
        
    // }

    // .placeholder-form {
    // box-sizing: border-box;
    // width: 320px;
    // margin: 20px auto;
    // }
    // .placeholder-container {
    //     position: relative;
    //     width: 100%;
    //     margin-bottom: 20px;
    // }
    // .placeholder-container input {
    //     background-color: #FFF;
    //     border: 2px solid #BFE2FF;
    //     box-sizing: border-box;
    //     color: #000;
    //     font-size: 16px;
    //     line-height: 16px;  
    //     height: 50px;
    //     outline: 0;
    //     padding: 0 20px;
    //     width: 100%;
    // }
    // .placeholder-container label {
    //     color: #000;
    //     font-family: Verdana, sans-serif;   
    //     background-color: #FFF;
    //     font-size: 16px;
    //     line-height: 16px;
    //     padding: 5px 10px;
    //     pointer-events: none;
    //     position: absolute;
    //     transition: all 200ms;
    //     top: 12px;
    //     left: 10px;
    //     background-color: #FFF;
    // }
    // .placeholder-container input:focus + label,
    // .placeholder-container input:not(:placeholder-shown) + label{
    //     top: -10px;
    //     left: 10px;
    //     font-size: 11px; 
    //     background-color: #BFE2FF;
    //     padding: 2px 10px;
    // }


</style>