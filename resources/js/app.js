//require('./echo');

import { createPinia } from 'pinia'
import { createApp, h } from 'vue'
import { registerPlugins } from '@core/utils/plugins'
import { createInertiaApp } from '@inertiajs/vue3'
//import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/index'
import DashboardLayout from '@/App.vue'
import LoginLayout from '@/App-login.vue'
import Toast from "vue-toastification";


// Styles
import '@core-scss/template/index.scss'
import '@styles/styles.scss'
import "vue-toastification/dist/index.css";


createInertiaApp({
  title: title => `لوحة تحكم ترف - ${title}`,
  resolve: name => {

    const pages = import.meta.glob('./pages/**/*.vue', { eager: true })

    let page = pages[`./pages/${name}.vue`]

    if(name.includes("login") || name.includes("register") || name.includes("forgot-password") || name.includes("reset-password")){
      page.default.layout = LoginLayout
    }else {
      page.default.layout = DashboardLayout

    }

    return page

  },
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) });

    const pinia = createPinia()

    registerPlugins(app)
    app.use(plugin)
    app.use(pinia)
    //app.use(ZiggyVue)
    app.mount(el)
    app.use(Toast);

  },
  progress: {
    
    color: '#C4174F',
    showSpinner: true,
  },
})


