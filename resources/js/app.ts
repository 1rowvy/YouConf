import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import '../css/app.css';

import DefaultLayout from '@/Common/DefaultLayout.vue';

const resolvePageComponent = async (name) => {
    const pages = import.meta.glob('./Pages/**/*.vue');

    const pagePath = `./Pages/${name.split('/').join('/')}.vue`;

    if (pages[pagePath]) {
        const page = await pages[pagePath]();
        return page.default;
    } else {
        console.error(`Component not found: ${pagePath}`);
    }
};

createInertiaApp({
    resolve: async (name) => {
        const page = await resolvePageComponent(name);
        page.layout = page.meta?.layout || DefaultLayout;
        return page;
    },
    setup({ el, app, props, plugin }) {
        createApp({ render: () => h(app, props) })
            .use(plugin)
            .mount(el);
    },
});
