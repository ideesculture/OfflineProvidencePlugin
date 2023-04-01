import { createApp } from 'vue'
import './style.css'
import router from "./router/index"
import { plugin, defaultConfig } from '@formkit/vue'
import App from './App.vue'
import '@formkit/themes/genesis'

createApp(App).use(router).use(plugin, defaultConfig).mount('#app')