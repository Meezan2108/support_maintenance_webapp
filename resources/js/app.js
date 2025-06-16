import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { ZiggyVue } from 'ziggy-js';
import { createPinia } from "pinia";
import '@fortawesome/fontawesome-free/css/all.css';
import Toast from 'vue-toastification'
import 'vue-toastification/dist/index.css'



import AdminLayout from "./Layouts/AdminLayout.vue";

import bootstrap from "bootstrap";

createInertiaApp({
    title: (title) => `${title} - ST DMS V25.10`,
    progress: {
        // The delay after which the progress bar will appear
        // during navigation, in milliseconds.
        delay: 250,

        // The color of the progress bar.
        color: "#8a2529",

        // Whether the NProgress spinner will be shown.
        showSpinner: false,
    },
    resolve: (name) => {
        let page = require(`./Pages/${name}`).default;
        page.layout ??= AdminLayout;
        return page;
    },
    setup({ el, App, props, plugin }) {
        const pinia = createPinia();

        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, window.Ziggy)
            .use(pinia)
            .use(Toast)
            .mount(el)
    },
});
