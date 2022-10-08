import {createStore} from "vuex";
import axiosClient from '../axios';
import {defaultLocale, i18n} from "../i18n";

const store = createStore ({
  state: {
    user: {
      data: sessionStorage.getItem('userData') ? JSON.parse(sessionStorage.getItem('userData')) : {},
      token: sessionStorage.getItem('TOKEN')
    },
    lang: sessionStorage.getItem('lang') ? sessionStorage.getItem('lang') : defaultLocale
  },
  getters: {},
  actions: {
    init(){
        i18n.global.locale.value =  this.state.lang
    },
    register({commit}, user) {
      return axiosClient.post('/register',user)
        .then(({data}) => {
          commit('setUser', data)
          return data;
        })
    },

    login({commit}, user){
      return axiosClient.post('/login',user)
        .then(({data}) => {
          commit('setUser', data)
          return data;
        })
    },
    logout({commit}){
      return axiosClient.post('/logout')
        .then(response =>{
          commit('logout')
          return response;
        })
    },
    languageChange({commit}, lang){
        commit('setLang', lang)
    }

  },
  mutations: {
    logout: state => {
      state.user.data = {};
      state.user.token = null;
      sessionStorage.removeItem("userData");
      sessionStorage.removeItem("TOKEN");
    },
    setUser: (state, userData) => {
      state.user.token = userData.token;
      state.user.data = userData.user;
      sessionStorage.setItem('userData', JSON.stringify(userData.user));
      sessionStorage.setItem('TOKEN', userData.token);
    },
    setLang: (state, lang) => {
        sessionStorage.setItem('lang', lang);
        i18n.global.locale.value = lang
    }
  },
  modules: {}
})

export default store;
