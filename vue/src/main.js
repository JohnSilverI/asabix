import { createApp } from 'vue'
import store from "./store";
import router from "./router/index.js";
import './index.css'
import App from './App.vue'

import { useI18n } from 'vue-i18n';
import {i18n} from "./i18n/index.js";

createApp(App, {
    setup() {
        const {t} = useI18n()
        return {t}
    }
})
  .use(store)
  .use(router)
  .use(i18n)
  .mount('#app')
