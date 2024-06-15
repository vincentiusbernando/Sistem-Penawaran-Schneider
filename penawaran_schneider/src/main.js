import "./assets/output.css";
import "./assets/navbar.css";
import "./assets/icon.css";
// Import Vue.js and LoginPage component
import ToastPlugin from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-bootstrap.css';
import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import axios from "axios";
axios.defaults.headers.common["Authorization"] = 'Bearer ' + localStorage.getItem("sessionToken");
createApp(App).use(router).use(ToastPlugin).mount('#app');

