<script setup>
import store from './store';
import {useI18n} from 'vue-i18n';

const {t} = useI18n({useScope: 'global'})
store.dispatch('init');
</script>

<template>

  <router-view></router-view>
</template>

<style scoped>

</style>
