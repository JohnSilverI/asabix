<template>
    <label for="countries" class="block text-sm font-medium text-gray-900 dark:text-gray-400">{{$t('Choose_language')}}</label>
    <select @change.prevent="languageChange" id="countries"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
        <option value="en" :selected="store.state.lang === 'en'">EN</option>
        <option value="ua" :selected="store.state.lang === 'ua'">UA</option>
        <option value="ru" :selected="store.state.lang === 'ru'">RU</option>
    </select>
</template>

<script setup>
import store from "../store";

function languageChange(ev){
    store.dispatch('languageChange', ev.target.value)
}
</script>
