import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createStore } from "vuex";
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import Swal from 'sweetalert2';
import axios from 'axios';
import VueAxios from 'vue-axios';

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
})

window.Toast = Toast;

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

// Create a new store instance.
const store = createStore({
    state () {
        return {
            loading: false,
        }
    },

    mutations: {
        setLoading (state, payload) {
            state.loading = payload;
        },
    },

    getters: {
        getLoading: state => {
            return state.loading;
        },
    },
});

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .use(store)
            .use(VueAxios, axios)
            .use(ZiggyVue, Ziggy)
            .mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });
