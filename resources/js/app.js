import './bootstrap';
import { createApp } from 'vue';
import { createPinia } from 'pinia'
import {
    message,
    Upload,
    Input,
    Select,
    Avatar,
    Table,
    Card,
    Menu,
    List,
    Drawer,
    Button,
    Statistic,
    Image,

} from 'ant-design-vue';
import 'ant-design-vue/dist/reset.css';
import router from './router/index.js';
import App from './App.vue';
Window.axios = axios;
import axios from 'axios';

import Auth from './router/Auth';
// Vue.prototype.auth = Auth;

// axios.defaults.headers['Authorization'] = `Bearer ${localStorage.getItem('token')}`;

import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { fab } from '@fortawesome/free-brands-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';
library.add(fas, fab, far);

// import Loading from 'vue-loading-overlay';
import { LoadingPlugin } from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/css/index.css';

import 'bootstrap/dist/css/bootstrap.min.css';

import CKEditor from '@ckeditor/ckeditor5-vue';

const pinia = createPinia();

const app = createApp(App);
app.component('font-awesome-icon', FontAwesomeIcon)
app.use(LoadingPlugin, {
    color: '#1BBEDE',
    backgroundColor: '#505050',
    loader: 'Bars',
    height: 200,
    width: 90
});
app.use(router);
app.use(pinia);
app.use(CKEditor);
app.use(message);
app.use(Statistic);
app.use(Menu);
app.use(Upload);
app.use(Input);
app.use(Select);
app.use(Avatar);
app.use(Button);
app.use(Table);
app.use(Card);
app.use(List);
app.use(Drawer);
app.use(Image);
app.mount("#home");

app.config.globalProperties.$message = message;
