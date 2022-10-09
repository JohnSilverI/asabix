import {createStore} from "vuex";
import axiosClient from '../axios';
import {defaultLocale, i18n} from "../i18n";

const store = createStore ({
  state: {
    user: {
      data: sessionStorage.getItem('userData') ? JSON.parse(sessionStorage.getItem('userData')) : {},
      token: sessionStorage.getItem('TOKEN')
    },
    lang: sessionStorage.getItem('lang') ? sessionStorage.getItem('lang') : defaultLocale,
    tags: {
      loading: false,
      links: [],
      data: []
    },
    currentTag: {
      loading: false,
      data: {}
    },
    notification: {
      show: false,
      type: null,
      message: null
    },
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
    },
    getTags({commit}, {url = null} = {}){
      commit("setTagsLoading", true);
      url = url || "/tag";
      return axiosClient.get(url)
        .then((res) => {
          commit("setTagsLoading", false);
          commit("setTags", res.data);
          return res;
        })
    },
    getTag( {commit}, id) {
      commit("setCurrentTagLoading", true);
      return axiosClient
        .get(`/tag/${id}`)
        .then((res) => {
          commit("setCurrentTag", res.data);
          commit("setCurrentTagLoading", false);
          return res;
        })
        .catch((err) => {
          commit("setCurrentTagLoading", false);
          throw err;
        });
    },
    saveTag({commit}, tag){
      let response;
      if (tag.id) {
        response = axiosClient
          .put(`/tag/${tag.id}`, tag)
          .then((res) => {
            commit("setCurrentTag", res.data);
            return res;
          });
      }
      else {
        response = axiosClient.post("/tag", tag).then((res) => {
          commit("setCurrentTag", res.data);
          return res;
        });
      }
      return response;
    },
    deleteTag({commit}, id){
      return axiosClient.delete(`/tag/${id}`);
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
        state.lang = lang;
        sessionStorage.setItem('lang', lang);
        i18n.global.locale.value = lang;
    },
    setTagsLoading: (state, loading) => {
      state.tags.loading = loading;
    },
    setTags: (state, tags) => {
      state.tags.links = tags.meta.links;
      state.tags.data = tags.data;
    },
    setCurrentTagLoading: (state, loading) => {
      state.currentTag.loading = loading;
    },
    setCurrentTag: (state, tag) => {
      state.currentTag.data = tag.data;
    },
    notify: (state, {message, type}) => {
      state.notification.show = true;
      state.notification.type = type;
      state.notification.message = message;
      setTimeout(() => {
        state.notification.show = false;
      }, 3000)
    }
  },
  modules: {}
})

export default store;
