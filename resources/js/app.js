import './bootstrap';
import { createApp } from 'vue'
import MainLayout from './layouts/MainLayout.vue'
import axios from 'axios'
import { clickOutside } from './directives/clickOutside'
import './assets/styles/common.css'


const token = document.head.querySelector('meta[name="csrf-token"]')

if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content
} else {
    console.error('CSRF token not found')
}

const app = createApp(MainLayout)
app.mount("#app")

app.directive('click-outside', clickOutside)
