import '@fortawesome/fontawesome-free/css/all.css';
import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import VueAwesomePaginate from "vue-awesome-paginate";
import "vue-awesome-paginate/dist/style.css";
import moment from 'moment';

const app = createApp(App)

app.use(moment);
app.use(router);
app.use(VueAwesomePaginate);
app.mount('#app')