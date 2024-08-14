<template>
  
  <div id="support-form-modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <header>Обращение в поддержку</header>
      
      <div class="form-group-lg">
        <label for="email-input">Почта</label>
        <input type="email" class="form-control" id="email-input" placeholder="romvlad2002@gmail.com">
      </div>          
        
      <div class="form-group-lg">
        <label for="name-input">Имя</label>
        <input type="text" class="form-control" id="name-input" placeholder="Романов Владислав">
      </div>
      
      <div class="form-group-lg">
        <label for="product-input">Тип обращения</label>
        <select class="form-control" id="product-input">
          <option></option>
          <option>Вопрос о веб-приложении</option>
          <option>Предложение нового фунционала</option>
          <option>Обнаружена ошибка в работе функционала</option>
        </select>
      </div>
            
      <div class="form-group-lg">
        <label for="inquiry-input">Текст обращения</label>
        <textarea type="text" class="form-control" id="inquiry-input" rows="5" placeholder="Ваше обращение..."></textarea>
      </div>

      <button>Отправить</button>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</template>

<script>
export default {
  name: "Support",
  data() {
    return {
      steps: ["Основное", "Описание", "Сведения", "Площадка"],
      currentStep: 0,
      type: "временное",
      image: '',
      name: '',
      start_data: '',
      end_data: '',
      desc: '',
      schedule: '',
      limit_age: '',
      price: '',
      phone: '',
      park: ''
    };
  },
  methods: {
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
            this.image = reader.result;
        };
        reader.readAsDataURL(file);
        this.image = URL.createObjectURL(file);
    },
  }
};
</script>

<style scoped>
#support-form-modal {
  text-align: center;
  border-radius: 10px;
  margin-left: auto;
  margin-right: auto;
  margin-top: 50px;
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
  outline: 2px solid #d9d9d9;
  
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
  height: 50px;
  font-size: 25px;
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




















<!-- <template>
    <div class="feedback-form">
      <h1>Форма обратной связи</h1>
      <form @submit.prevent="submitForm">
        <label for="name">Имя:</label>
        <input type="text" id="name" v-model="formData.name" required>
        
        <label for="email">Email:</label>
        <input type="email" id="email" v-model="formData.email" required>
        
        <label for="message">Сообщение:</label>
        <textarea id="message" v-model="formData.message" required></textarea>
        
        <button type="submit">Отправить</button>
      </form>
      <div v-if="formSubmitted" class="success-message">
        Спасибо за ваше сообщение! Мы свяжемся с вами в ближайшее время.
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        formData: {
          name: '',
          email: '',
          message: ''
        },
        formSubmitted: false
      };
    },
    methods: {
      submitForm() {
        // Здесь можно добавить логику отправки данных на сервер
        // Например, используя Axios или Fetch API
        // После успешной отправки показываем сообщение об успешной отправке
        this.formSubmitted = true;
        // Очищаем форму
        this.formData.name = '';
        this.formData.email = '';
        this.formData.message = '';
      }
    }
  };
  </script>
  
  <style>
  .feedback-form {
    max-width: 400px;
    margin: 0 auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
  }
  
  label {
    display: block;
    margin-bottom: 5px;
  }
  
  input, textarea, button {
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
  }
  
  button {
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }
  
  .success-message {
    margin-top: 10px;
    padding: 10px;
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
    border-radius: 5px;
  }
  </style> -->