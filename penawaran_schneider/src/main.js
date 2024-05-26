import "./assets/output.css";
import "./assets/navbar.css";
import "./assets/icon.css";
// Import Vue.js and LoginPage component
import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import axios from "axios";
axios.defaults.baseURL = "http://127.0.0.1:8000";
axios.defaults.headers.common["Authorization"] = 'Bearer ' + localStorage.getItem("sessionToken");
createApp(App).use(router).mount('#app');

