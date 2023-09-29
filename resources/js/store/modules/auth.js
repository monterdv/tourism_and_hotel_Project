import axios from "axios";

export const auth = {
    state: {
        auth_status: false,
        auth_token: null,
        auth_info: {
            name: null,
            email: null,
            avatar: null,
            wallet: null,
            department_id: null,
        }
    },
    getters: {        
        GET_AUTH_STATUS(state){
            return state.auth_status;
        },

        GET_AUTH_TOKEN(state){
            return state.auth_token;
        },

        GET_AUTH_INFO(state){
            return state.auth_info;
        },
    },
    actions : {        
        LOGIN(context,formData){
           return new Promise((resolve, reject) => {
            // const formData = new FormData();
            // formData.append("email", login.email);
            // formData.append("password", login.password);
            axios
              .post(`http://127.0.0.1:8000/api/login`, formData)
              .then(function (response) {
                  context.commit('SET_AUTH_TOKEN',response.data.data.access_token);
                  context.commit('SET_AUTH_INFO',response.data.data.user);
                  resolve(response);
              })
              .catch(function (error) {
                reject(error);
              });
           })
        }, 
        REGISTER(context,formData) {
            return new Promise((resolve, reject) => {
                axios
                .post(`http://127.0.0.1:8000/api/register`, formData)
                .then(function (response) {
                    resolve(response);
                })
                .catch(function (error) {
                    reject(error);
                });
            })
        },

        LOGOUT(context) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.auth_token;
            return new Promise((resolve, reject) => {
                axios
                .post(`http://127.0.0.1:8000/api/logout`)
                .then(function (response) {
                    context.commit('SET_AUTH_LOGOUT');
                    resolve(response);
                })
                .catch(function (error) {
                    reject(error);
                });
            })
        }
    },
    mutations: {

        SET_AUTH_TOKEN(state, token)
        {
            state.auth_token = token;
            state.auth_status = true;
        },

        SET_AUTH_INFO(state, info) 
        {
            state.auth_info.name = info.name;
            state.auth_info.email = info.email;
            state.auth_info.avatar = info.avatar;
            state.auth_info.wallet = info.wallet !== null ? info.wallet : 0;
            state.auth_info.department_id = info.department_id;
        },

        SET_AUTH_LOGOUT(state) {
            state.auth_token = null;
            state.auth_status = false;
            state.auth_info = {
                name: null,
                email: null,
                avatar: null,
                wallet: null,
                department_id: null,
            }
        }
    },
}