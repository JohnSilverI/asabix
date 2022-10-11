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
    posts: {
      loading: false,
      links: [],
      data: []
    },
    currentTag: {
      loading: false,
      data: {}
    },
    currentPost: {
      loading: false,
      data: {}
    },
    tagsForSelectbox:{
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
    },
    getTagsForSelectbox({commit}) {
      commit("setTagsforSelectboxLoading", true);
      return axiosClient
        .get('/tags_for_selectbox')
        .then(res => {
          commit("setTagsforSelectbox", res);
          commit("setTagsforSelectboxLoading", false);
          return res;
        })
    },
    savePost({commit}, post){
      let response;
      if (post.post_id) {
        response = axiosClient
          .put(`/post/${post.post_id}`, post)
          .then((res) => {
            console.log('edit',res)
            commit("setCurrentPost", res.data);

            return res;
          });
      }
      else {
        response = axiosClient.post("/post", post).then((res) => {
          commit("setCurrentPost", res.data);

          return res;
        });
      }
      return response;
    },
    getPosts({commit}, {url = null} = {}){
      commit("setPostsLoading", true);
      url = url || "/post";
      return axiosClient.get(url)
        .then((res) => {
          commit("setPostsLoading", false);
          commit("setPosts", res.data);
          return res;
        })
    },
    getPost( {commit}, id) {
      commit("setCurrentPostLoading", true);
      return axiosClient
        .get(`/post/${id}`)
        .then((res) => {
          commit("setCurrentPost", res.data);
          commit("setCurrentPostLoading", false);
          return res;
        })
        .catch((err) => {
          commit("setCurrentPostLoading", false);
          throw err;
        });
    },
    deletePost({commit}, id){
      return axiosClient.delete(`/post/${id}`);
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
    setPostsLoading: (state, loading) => {
      state.posts.loading = loading;
    },
    setTags: (state, tags) => {
      state.tags.links = tags.meta.links;
      state.tags.data = tags.data;
    },
    setPosts: (state, posts) => {
      state.posts.links = posts.meta.links;
      state.posts.data = posts.data;
    },
    setCurrentTagLoading: (state, loading) => {
      state.currentTag.loading = loading;
    },
    setCurrentPostLoading: (state, loading) => {
      state.currentPost.loading = loading;
    },
    setCurrentTag: (state, tag) => {
      state.currentTag.data = tag.data;
    },
    setCurrentPost: (state, post) => {
      state.currentPost.data = post;
    },
    setTagsforSelectboxLoading:(state, type) => {
      state.tagsForSelectbox.loading = type;
    },
    setTagsforSelectbox:(state, tags) => {
      state.tagsForSelectbox.data = tags.data;
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
