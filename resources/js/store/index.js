import { createStore } from "vuex";
import createPersistedState from "vuex-persistedstate";
import { auth } from './modules/auth';

export default createStore({
    state: {

    },
    getters: {        
    },
    actions : {        
    },
    mutations: {
    },
    modules: {
        auth
    },

    plugins: [createPersistedState({
        paths: ['auth']
    })],
})