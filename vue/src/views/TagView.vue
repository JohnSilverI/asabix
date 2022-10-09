<template>
  <PageComponent>
    <template v-slot:header>
      <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900">
          {{ route.params.id ? $t('Edit_Tag') : $t("Create_tag") }}
        </h1>
        <button v-if="route.params.id"
          type="button"
          class="py-2 px-3 text-white bg-red-500 rounded-md hover:bg-red-600"
          @click="deleteTag()"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5 -mt-1 inline-block"
            viewBox="0 0 20 20"
            fill="currentColor"
          >
            <path
              fill-rule="evenodd"
              d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
              clip-rule="evenodd"
            />
          </svg>
          {{$t('buttons.Delete_tag')}}
        </button>
      </div>
    </template>
    <div v-if="tagLoading" class="flex justify-center">{{$t('Loading...')}}</div>
    <form v-else class="animate-fade-in-down" @submit.prevent="saveTag">
      <div class="shadow sn:rounded-md sm:overflow-hidden">
      <!-- Name -->
      <div class="p-5">
        <label for="title" class="block text-sm font-medium text-gray-700">{{$t('fields.Name')}}</label>
        <input
          type="text"
          name="name"
          id="name"
          v-model="model.name"
          autocomplete="tag_name"
          class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
        />
      </div>
      <!--/ Name -->
        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
          <button type="submit"
                  class="
                        inline-flex
                        justify-center
                        py-2
                        px-4
                        border border-transparent
                        shadow-sm
                        text-sm
                        font-medium
                        rounded-md
                        text-white
                        bg-indigo-600 hover:bg-indigo-700
                        focus:outline-none
                        focus:ring-2
                        focus:ring-offset-2
                        focus:ring-indigo-500"
          >{{$t('buttons.Save')}}
          </button>
        </div>
      </div>
    </form>
  </PageComponent>
</template>

<script setup>
import store from "../store";
import { ref, watch, computed } from "vue";
import { useRoute, useRouter } from 'vue-router';
import PageComponent from "../components/PageComponent.vue";
import { useI18n } from 'vue-i18n';

const { t } = useI18n({ useScope: 'global' });
const route = useRoute();
const router = useRouter();

const tagLoading = computed(() => store.state.currentTag.loading);

let model = ref({
  created: "",
  id: "",
  name: "",
  updated: ""
})

watch(
  () => store.state.currentTag.data, (newVal, oldVal) => {
    model.value = {
      ...JSON.parse(JSON.stringify(newVal))
    }
  }
);
if (route.params.id) {
  store.dispatch('getTag', route.params.id);
}

function saveTag(){
  store.dispatch("saveTag", model.value)
    .then(({ data }) => {
      store.commit('notify', {
        type: 'success',
        message: route.params.id ? t('Tag_updated') : t('Tag_created')
      });

      router.push({
        name: "TagView",
        params: {id: data.data.id},
      })
    });
}

function deleteTag(){
  if (confirm(t('Delete_tag'))) {
    store.dispatch("deleteTag", model.value.id)
      .then(
        () => {
          router.push({name: "Tags"});
        }
      )
  }
}
</script>
