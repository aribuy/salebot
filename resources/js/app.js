import "./bootstrap";
import { createApp } from "vue";
import App from "./src/App.vue";
import helper from "./src/mixins/helper.js";
import $ from 'jquery';
import 'select2';
import 'select2/dist/css/select2.css';

import router from './src/router';
import VuePlyr from 'vue-plyr'
import { createPinia } from 'pinia'
const pinia = createPinia()

const app = createApp(App);
// app.directive('select2', select2Directive);

app.mixin(helper).use(router).use(pinia).use(VuePlyr, {
    plyr: {}
}).mount("#app");