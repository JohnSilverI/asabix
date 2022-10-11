import {createRouter, createWebHistory} from "vue-router";

import store from "../store/index.js";

import AuthLayout from "../components/AuthLayout.vue";
import Login from "../views/Login.vue";
import Register from "../views/Register.vue";

import DefaultLayout from "../components/DefaultLayout.vue";
import Dashboard from "../views/Dashboard.vue";
import Tags from "../views/Tags.vue";
import Posts from "../views/Posts.vue";
import TagView from "../views/TagView.vue";
import PostView from "../views/PostView.vue";


const routes = [
  {
    path: '/',
    redirect: '/dashboard',
    component: DefaultLayout,
    meta: {requiresAuth: true},
    children: [
      {path: '/dashboard', name: 'Dashboard', component: Dashboard},
      {path: '/tags', name: 'Tags', component: Tags},
      {path: '/tags/create', name: 'TagCreate', component: TagView},
      {path: '/tags/:id', name: 'TagView', component: TagView},
      {path: '/posts', name: 'Posts', component: Posts},
      {path: '/posts/create', name: 'PostCreate', component: PostView},
      {path: '/posts/:id', name: 'PostView', component: PostView},
    ]
  },
  {
    path: '/auth',
    redirect: '/login',
    name: 'Auth',
    component: AuthLayout,
    meta: {isGuest: true},
    children: [
      {path: '/login', name: 'Login', component: Login},
      {path: '/register', name: 'Register', component: Register},
    ]
  },

];


const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach((to,from,next) => {
  if (to.meta.requiresAuth && !store.state.user.token){
    next({name: 'Login'})
  }
  else if (store.state.user.token && to.meta.isGuest){
    next({name: 'Dashboard'});
  }
  else {
    next();
  }
})
export default router;
