<template>
    <div class="block">
        <div class="notes">
            <div class="header">Мои заметки</div>
            <div class="notes-field">
               
                <div class="note-card" v-for="note in notes" :key="note.id" @click="selectNote(note)">
                    <div class="review-header">
                        <div class="name-group">
                        <div class="initials"><img :src="note.event.image" style="width: 80px; height: 50px; border-radius: 10px;" alt="Изображение события"></div>
                        <p>{{ note.event.name }} </p>
                        </div>
                    </div>
                    <div class="review-details">
                        <div class="review-date"> <i style="margin-right: 5px;"class="fa-solid fa-tree"></i> {{ note.event.park[0]}}  <i style="margin-right: 5px; margin-left: 10px;" class="fa-solid fa-square-poll-vertical"></i>{{ note.event.type}}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="detail">
            <div class="field"> 
                <div v-if="selectedNote">
                   <div class="note-f">
                    <div class="image">
                        <img :src="selectedNote.event.image"  alt="Изображение события">
                    </div>
                    <div class="content">
                        <h2>{{ selectedNote.event.name }}</h2>
                        <p v-if="!editing">{{ selectedNote.text }}</p>
                        <textarea style="resize: none; width: 400px; " rows="8" v-else v-model="editedText"></textarea>
                        <button @click="editNote">{{ editing ? 'Сохранить' : 'Редактировать' }}</button>
                        <button style="margin-left: 15px;" @click="deleteNote">Удалить</button>
                    </div>
                   </div>          
                </div>
                <div v-else> 
                    <p style="margin-left: 150px;">Выберите заметку...</p>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import axios from 'axios';
  
    export default {
      name: 'Notes',
      data() {
        return {
            notes: [],
            selectedNote: null,
            editedText: '',
            editing: false

        }
      },
      methods: {
        getNotes(){
            const accessToken = localStorage.getItem('accessToken');
            console.log('Токен', accessToken);

            axios.get('http://127.0.0.1:8000/api/my-notes', {
            headers: {
                Authorization: `Bearer ${accessToken}`
            }
            })
            .then(response => {
                console.log(response.data); // Выводим ответ от сервера
                this.notes = response.data.data;
            })
            .catch(error => {
                console.error(error); 
                
            });
        },
        selectNote(note) {
            this.selectedNote = note;
        },
        editNote() {
            if (this.editing) {
                // Сохранить изменения
                const selectedNote = this.selectedNote;
                selectedNote.text = this.editedText;

                // Отправить изменения на сервер, если необходимо

                this.editing = false;
            } else {
                this.editedText = this.selectedNote.text;
                this.editing = true;
            }
        },
        deleteNote() {
            const index = this.notes.findIndex(note => note === this.selectedNote);
            if (index !== -1) {
                this.notes.splice(index, 1); // Удаляем заметку из массива notes
                this.selectedNote = null; // Сбрасываем selectedNote
            }
        },
      },
      mounted() {
          this.getNotes();
      },
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

    .notes {
        width: 40%;
        height: 100%;
        border-radius: 10px;
        outline: 2px solid #FFCC66;
        overflow: hidden;
        /* background: #7B5C47; */
    
    }
    .detail {
        width: 60%;
        height: 100%;
        align-items: center; 
        justify-content: center; /* Добавляем центрирование по горизонтали */
        display: flex;

        

    
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
        width: 450px;
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
    
    width: 450px;
    min-height: 500px;
    background: black linear-gradient(rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.1));
    outline: 2px solid #e6dddd;
    border-radius: 10px;
    margin: auto;
    overflow: hidden;
    color: #d9d9d9;
}

.note-f {
    display: flex;
    flex-direction: column;
    align-items: center;
    overflow: hidden;
}

.image {
    width: 450px;
    height: 200px;
    overflow: hidden;
    object-fit: cover;
    margin-bottom: 10px;
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