<template>
  <!-- ,edfdfdfd
  {{ event }} -->
  <div class="block">
    <div class="event">
      <div class="field">
        <div class="park-image">
            <img :src="event.image" alt="Event Image">
        
        </div>
        <div class="head" style="color: #d9d9d9; align-items: center; width: 360px; ">
          <h1 >{{ event.name }}</h1>
          <div style="display: flex; justify-content: space-between;">
              <div style="color: #d9d9d9; margin-bottom: 10px" class="info-item">
                <i style="color: #FFCC66; margin-right: 10px" class="fa-solid fa-eye"></i>
                <span>123</span>
              </div>
              <div style="color: #d9d9d9; margin-bottom: 10px" class="info-item">
                <i style="color: #FFCC66; margin-right: 10px"class="fa-solid fa-star"></i>
                <span>{{event.average_rating}}</span>
              </div>
          </div>
         
          
        </div>
        
       
        <div class="tab">
          <div class="tab-container">
            <ul class="tab-nav">
              <li v-for="(tab, index) in tabs" :key="index" :class="{ 'active': tab.active }" @click="switchTab(index)">
                {{ tab.label }}
              </li>
            </ul>
            <div v-for="(tab, index) in tabs" :key="index" :class="{ 'active': tab.active }" v-bind:id="'tab-body-' + index">
              <div v-if="tab.active">
                <div style="color: #d9d9d9; margin-bottom: 10px; padding-left: 65px; padding-right: 65px;" v-if="tab.type === 'text'">
                  {{ event.desc }}
                </div>
                <div style="color: #d9d9d9; margin-bottom: 10px"   v-else-if="tab.type === 'mapl'">
                  <div  style="color: #d9d9d9; margin-bottom: 10px" class="info-item">
                    <i style="margin-right: 10px"class="fas fa-tree"></i>
                    <span>{{ event.park[0]}}</span>
                  </div>

                  <div style="color: #d9d9d9; margin-bottom: 10px" class="info-item">
                    <i style="margin-right: 10px" class="fas fa-person"></i>
                    <span>{{ event.limit_age }}</span>
                  </div>

                  <div  style="color: #d9d9d9; margin-bottom: 10px" class="info-item">
                    <i style="margin-right: 10px" class="fas fa-tags"></i>
                    <span>{{ event.price }}</span>
                  </div>

                  <div  style="color: #d9d9d9; margin-bottom: 10px" class="info-item">
                    <i style="margin-right: 10px" class="fas fa-phone"></i>
                    <span>{{ event.phone }}</span>
                  </div>

                  <div style="color: #d9d9d9; margin-bottom: 10px" v-if="event.type === 'временное'" class="info-item">
                    <i style="margin-right: 10px" class="fas fa-clock"></i>
                    <span>{{ formatDate(event.start_date) }} - {{ formatDate(event.end_date) }}</span>
                  </div>

                  <div style="color: #d9d9d9; margin-bottom: 10px" class="info-item">
                    <i style="margin-right: 10px" class="fas fa-calendar"></i>
                    <span>{{ event.schedule }}</span>
                  </div>
                  <!--
                  <div class="info-item">
                    <i class="fas fa-phone"></i>
                    <span>{{ park.phone }}</span>
                  </div>

                  <div class="info-item">
                    <i class="fas fa-globe"></i>
                    <span>{{ park.website }}</span>
                  </div>

                  <div class="info-item">
                    <i class="fas fa-clock"></i>
                    <span>{{ park.work_time }}</span>
                  </div> -->
                </div>
              </div>
              
            </div>
          </div>
          
        </div>
        <!-- <button>Назад</button> -->

      

        <!-- <div class="tabs">
          <input type="radio" name="tab-group" id="tab-1" checked>
          <label for="tab-1">Tab 1</label>
          <div class="tab-content">
            <p>Content for Tab 1</p>
          </div>

          <input type="radio" name="tab-group" id="tab-2">
          <label for="tab-2">Tab 2</label>
          <div class="tab-content">
            <p>Content for Tab 2</p>
          </div>

          <input type="radio" name="tab-group" id="tab-3">
          <label for="tab-3">Tab 3</label>
          <div class="tab-content">
            <p>Content for Tab 3</p>
          </div>
        </div> -->

        
      </div>
    </div>

    <div class="review" >
      <div class="header">Отзывы</div>
      <div class="review-field">

        <div class="review-card" v-for="review in reviews" :key="review.id">
          <div class="review-header">
            <div class="name-group">
              <div class="initials"><img :src="review.user.image" style="width: 48px; height: 48px; border-radius: 50%;" alt="Аватар"></div>
              <p>{{ review.user.surname }} {{ review.user.name }}</p>
            </div>
            <div class="rating">
              <i v-for="i in 5" :key="i" :class="{'fas fa-star': i <= review.rating, 'far fa-star': i > review.rating}"></i>
            </div>
          </div>
          <div class="review-description">
            {{review.text}}
          </div>
          <div class="review-details">
            <div class="review-date">{{ reviewFormatData(review.time)}}</div>
          </div>
        </div>
      </div>

      <div class="message-input" style="align-items: center;">
        <div class="wrap" style="align-items: center;">
          <textarea v-model="message" placeholder="Ваш отзыв..." rows="3"></textarea>
          <i class="fa fa-star attachment" aria-hidden="true" @click="showRatingSelection = true"></i>
          <button class="submit" @click="addReview">
            <i class="fa fa-paper-plane" aria-hidden="true"></i>
          </button>
        </div>
      </div>
      <div v-if="showRatingSelection" class="rating-selection">
        <select v-model="newReviewRating" @change="closeRatingSelection">
          <option value="1">★</option>
          <option value="2">★★</option>
          <option value="3">★★★</option>
          <option value="4">★★★★</option>
          <option value="5">★★★★★</option>
        </select>
      </div>
     

    </div>
  </div>
  
</template>

<script>
     
      import axios from 'axios';
      import moment from 'moment';
      import dayjs from 'dayjs';
      import 'dayjs/locale/ru'
      
      

      export default {
      name: 'EventView',
      data() {
        return {
          eventId: null,
          event: [],
          reviews: [],
          message: "",
          showRatingSelection: false,
          newReviewRating: null,
          tabs: [
            { label: 'Описание', type: 'text', active: true },
            { label: 'Дополнительная информация', type: 'mapl', active: false }
          ],
        }
      },
      computed: {
          roundedNumber(number) {
            const rating = number.toString(); // Преобразование в строку
            return parseFloat(rating).toFixed(1);
        },
      },

      mounted() {
          this.eventId = this.$route.query.id;
          this.fetchEvent();
          this.getReviews();
      },
      methods: {
        reviewFormatData(dateString){ 
          dayjs.locale('ru');
          return dayjs(dateString).format('DD MMMM, YYYY');
        },
        formatDate(date) {
          dayjs.locale('ru');
          return dayjs(date).format('D MMM');
        },
        async fetchEvent() {
          try {
            const response = await fetch(`http://127.0.0.1:8000/api/events/${this.eventId}`);
            const data = await response.json();
            this.event = data.data;
          } catch (error) {
            console.error('Error fetching parks:', error);
          }
        },
        
        switchTab(index) {
          this.tabs.forEach((tab, i) => {
            tab.active = i === index;
          });
          this.activeTabIndex = index;
        },
        closeRatingSelection() {
          this.showRatingSelection = false;
        },

        getReviews(){
          axios.get(`http://127.0.0.1:8000/api/reviews/${this.eventId}`)
            .then(response => {
                console.log(response.data); // Выводим ответ от сервера
                this.reviews = response.data.data;
            })
            .catch(error => {
                console.error(error); 
                
            });
        },

        addReview() {
            const accessToken = localStorage.getItem('accessToken'); 
            const config = {
              headers: { Authorization: `Bearer ${accessToken}` }
            };
            const data = {
                event_id: this.event.id,
                rating: this.newReviewRating,
                text: this.message
            };

            // Выполняем AJAX запрос
            axios.post('http://127.0.0.1:8000/api/review', data, config)
              .catch(error => {
                  console.error(error); 
                  
              });
            
              this.message =  "";
              this.newReviewRating =  null;
        },

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
    overflow: hidden;

    background: black linear-gradient(rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.1));
  }
  .event {
    width: 60%;
    height: 100%;
 
    /* outline: 2px solid #e6dddd; */
    

  }
  .review {
    width: 40%;
    height: 100%;
    border-radius: 10px;
    outline: 2px solid #FFCC66;
    overflow: hidden;
    /* background: #7B5C47; */
 
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
  button{
    background-color: red;

    border-radius: 15px;
    margin-top: 20px;
    
    max-width: 450px; /* Ширина кнопки не более 300px */
    height: 35px;
    font-size: 16px;
    border: 2px solid #FFCC66;
    background-color: #6633FF;
    color: #fff;
    
  }

  .field {
    flex-direction: column;
    width: 90%;
    height: 100%;
    margin-left: auto;
    margin-right: auto;
    /* background: black linear-gradient(rgba(255, 255, 255, 0.11), rgba(255, 255, 255, 0.11)); */
  }

  .park-image {
      text-align: center;
      
      overflow: hidden;
  }
  .park-image img {
      height: 350px;
      width: 100%;
      object-fit: cover;
  }



.new-review {
  margin-top: 20px;
}

.head {
  width: 90%;
  align-items: center;
  margin: auto;
}

.tab {
  width: 90%;
  background: black linear-gradient(rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.1));
  display: flex; justify-content: center;
  align-items: center;
  margin: auto;
}

.tab-container {
  max-width: 1200px; height: 600px;

}

.tab-nav {
  display: flex;
  width: fit-content;
  margin: 0 auto;
  margin-bottom: 30px;
  list-style: none;
  border-bottom: 1px solid #999;
  position: relative;
}

.tab-nav li {
  padding: 20px 30px;
  color: #333;
  font-size: 20px;
  cursor: pointer;
  transition: all 0.3s;
}

.tab-nav li.active {
  color: #339933;
  transform: scale(1.2);
}

.tab-nav li:not(:last-child) {
  margin-right: 70px;
}

.tab-body {
  color: #d9d9d9;
   
    display: none;
/*     align-items: center; */
    animation: fade 0.8s;
  }
  
  .tab-body.active { display: flex; color: #d9d9d9; }
  
  @keyframes fade {
    from {
      opacity: 0;
      transform: translateX(-20px);
    }
  
    to {
      opacity: 1;
      transform: translateX(0);
    }
  }
  
  .tab-col-left {
  float: left;
  width: 50%;
}

.tab-col-right {
  float: left;
  width: 50%;
}
  

  .tab-col-right p {
    color: white;
  }



  .review-field {
    height: 80%;
    align-items: center;
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
  /* Панель ввода отзыва */
  .message-input {
			position: absolute;
			bottom: 1;
			width: 450px;
      margin-left: 70px;
      margin-top: 30px;
      background-color: #333;
			
			
			.wrap {
				position: relative;
			
				textarea {
					font-family: "proxima-nova",  "Source Sans Pro", sans-serif;
					float: left;
					border: none;
					width: calc(100% - 90px);
					padding: 11px 32px 10px 8px;
					font-size: 0.8em;
					color: black;
          resize: none;
					/* @media screen and (max-width: $break) {
						padding: 15px 32px 16px 8px;
					} */

					&:focus {
						outline: none;
					}
				}
				
				.attachment {
					position: absolute;
					right: 60px;
					z-index: 3;
					margin-top: 10px;
					font-size: 1.1em;
					color: gray;
					opacity: .5;
					cursor: pointer;
				
					
					&:hover {
						opacity: 1;
					}
				}

				button {
					float: right;
					border: none;
					width: 50px;
					padding: 11px 0;
					cursor: pointer;
					background: #6633FF;
					color: white;
					/* @media screen and (max-width: $break) {
						padding: 16px 0;
					} */
					
					/* &:hover {
						background: $light;
					} */
					
					&:focus {
						outline: none;
					}
				}
			}
		}
    .rating-selection {
      position: absolute;
      background: white;
      border: 1px solid #ccc;
      padding: 10px;
    }


    .review-card {
	display: flex;
	flex-direction: column;
	justify-content: space-around;
	width: 450px;
	min-height: 150px;
	background: #2c2b3f;
	border-radius: 10px;
	margin: 10px 0px;
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
	display: flex;
	justify-content: center;
	align-items: center;
	width: 48px;
	height: 48px;
	margin-right: 12px;
	border-radius: 50%;
	background: #d56a6a;
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
  
</style>