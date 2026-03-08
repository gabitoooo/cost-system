import { createApp } from 'vue'
import App from './app/App.vue'
import router from './app/router'
import pinia from './app/store'
import axiosPlugin from './app/plugins/axios'
import './assets/styles/main.css'

createApp(App).use(pinia).use(router).use(axiosPlugin).mount('#app')
