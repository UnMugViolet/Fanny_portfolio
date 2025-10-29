import './bootstrap';
import { createApp } from 'vue';
import App from './App.vue';
import VueMatomo from 'vue-matomo';
import router from './router';

const app = createApp(App);
app.use(VueMatomo, {
	host: 'https://matomo.rocketegg.systems/',
	siteId: 3,
});

app.use(router);
app.mount('#app');

// Track initial page view
window._paq.push(['trackPageView']);
