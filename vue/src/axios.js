import axios from "axios";
import store from "./store";
import router from "./router";

const axiosClient = axios.create({
  baseURL: 'http://asabix.loc/api'
})

axiosClient.interceptors.request.use(config => {

    if (typeof config.data === "object") {
        config.data = Object.assign(config.data, {lang: store.state.lang})
    }
    else {
        config.data = Object.assign({}, {lang: store.state.lang})
    }

    config.headers.Authorization = `Bearer ${store.state.user.token}`
    return config;
});

axiosClient.interceptors.response.use(response => {
    return response;
}, error => {
    if (error.response.status === 401) {
        sessionStorage.removeItem('TOKEN')
        router.push({name: 'Login'})
    } else if (error.response.status === 404) {
        router.push({name: 'NotFound'})
    }
    throw error;
});

export default axiosClient;
