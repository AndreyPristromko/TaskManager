import './bootstrap';
import { createApp } from 'vue'
import MainLayout from './layouts/MainLayout.vue'
import axios from 'axios'

// Настройка axios
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
const token = document.head.querySelector('meta[name="csrf-token"]')
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content
}

const app = createApp(MainLayout)
app.mount("#app")
