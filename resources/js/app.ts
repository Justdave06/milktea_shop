import '../css/app.css';
import { createApp } from 'vue';
import { initializeTheme } from '@/composables/useAppearance';
import AllProducts from '@/pages/AllProducts.vue';
import Welcome from '@/pages/Welcome.vue';

const normalizedPath = window.location.pathname.replace(/\/+$/, '') || '/';
const page = normalizedPath === '/all-products' ? AllProducts : Welcome;

initializeTheme();
createApp(page).mount('#app');
