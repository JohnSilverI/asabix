<template>
  <PageComponent>
    <template v-slot:header>
      <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900">
          {{ route.params.id ? model.title : "Create a Post" }}
        </h1>
        <button v-if="route.params.id"
                type="button"
                class="py-2 px-3 text-white bg-red-500 rounded-md hover:bg-red-600"
                @click="deletePost()"
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
          Delete Post
        </button>
      </div>
    </template>
    <div v-if="postLoading" class="flex justify-center">{{$t('Loading...')}}</div>
    <form v-else class="animate-fade-in-down" @submit.prevent="savePost">
      <div class="shadow sn:rounded-md sm:overflow-hidden p-3">
        <!-- Tags -->
        <div class="mt-3 col-span-3">
          <label for="tag_id" class="block text-sm font-medium text-gray-700"
          >Tag</label>
          <select
            id="tag_id"
            name="tag_id"
            v-model="model.tag_id"
            :disabled="tagsLoading"
            class="
          mt-1
          block
          w-full
          py-2
          px-3
          border border-gray-300
          bg-white
          rounded-md
          shadow-sm
          focus:outline-none focus:ring-indigo-500 focus:border-indigo-500
          sm:text-sm
        "
          >
            <option v-for="tag in tagsForSelectbox" :key="tag.id" :value="tag.id">
              {{ tag.name }}
            </option>
          </select>
        </div>
        <!--/ Tags -->

        <!-- Title UA-->
        <div>
          <label for="title_ua" class="block text-sm font-medium text-gray-700"
          >Title UA</label
          >
          <input
            type="text"
            name="title_ua"
            id="title_ua"
            v-model="model.title_ua"
            autocomplete="post_title_ua"
            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
          />
        </div>
        <!--/ Title UA-->
        <!-- Title EN-->
        <div>
          <label for="title_en" class="block text-sm font-medium text-gray-700"
          >Title EN</label
          >
          <input
            type="text"
            name="title_en"
            id="title_en"
            v-model="model.title_en"
            autocomplete="post_title_en"
            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
          />
        </div>
        <!--/ Title EN-->
        <!-- Title RU-->
        <div>
          <label for="title_ru" class="block text-sm font-medium text-gray-700"
          >Title RU</label
          >
          <input
            type="text"
            name="title_ru"
            id="title_ru"
            v-model="model.title_ru"
            autocomplete="post_title_ru"
            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
          />
        </div>
        <!--/ Title RU-->

        <!-- Description UA-->
        <div>
          <label for="description_ua" class="block text-sm font-medium text-gray-700">
            Description UA
          </label>
          <div class="mt-1">
              <textarea
                id="description_ua"
                name="description_ua"
                rows="3"
                v-model="model.description_ua"
                autocomplete="post_description_ua"
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"
                placeholder="Describe your post"
              />
          </div>
        </div>
        <!-- / Description UA-->
        <!-- Description EN-->
        <div>
          <label for="description_en" class="block text-sm font-medium text-gray-700">
            Description EN
          </label>
          <div class="mt-1">
              <textarea
                id="description_en"
                name="description_en"
                rows="3"
                v-model="model.description_en"
                autocomplete="post_description_en"
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"
                placeholder="Describe your post"
              />
          </div>
        </div>
        <!--/ Description EN-->
        <!-- Description RU-->
        <div>
          <label for="description_ru" class="block text-sm font-medium text-gray-700">
            Description RU
          </label>
          <div class="mt-1">
              <textarea
                id="description_ru"
                name="description_ru"
                rows="3"
                v-model="model.description_ru"
                autocomplete="post_description_ru"
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"
                placeholder="Describe your post"
              />
          </div>
        </div>
        <!--/ Description RU-->
        <!-- Content UA-->
        <div>
          <label for="content_ua" class="block text-sm font-medium text-gray-700">
            Content UA
          </label>
          <div class="mt-1">
              <textarea
                id="content_ua"
                name="content_ua"
                rows="3"
                v-model="model.content_ua"
                autocomplete="post_content_ua"
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"
              />
          </div>
        </div>
        <!--/ Content UA-->
        <!-- Content EN-->
        <div>
          <label for="content_en" class="block text-sm font-medium text-gray-700">
            Content EN
          </label>
          <div class="mt-1">
              <textarea
                id="content_en"
                name="content_en"
                rows="3"
                v-model="model.content_en"
                autocomplete="post_content_en"
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"
              />
          </div>
        </div>
        <!--/ Content EN-->
        <!-- Content RU-->
        <div>
          <label for="content_ru" class="block text-sm font-medium text-gray-700">
            Content RU
          </label>
          <div class="mt-1">
              <textarea
                id="content_ru"
                name="content_ru"
                rows="3"
                v-model="model.content_ru"
                autocomplete="post_content_ru"
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"
              />
          </div>
        </div>
        <!--/ Content RU-->
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
import PageComponent from "../components/PageComponent.vue";
import { ref, watch, computed } from "vue";
import store from "../store";
import { useRoute, useRouter } from 'vue-router'

const route = useRoute();
const router = useRouter();

const postLoading = computed(() => store.state.currentPost.loading);
const tagsLoading = computed(() => store.state.tagsForSelectbox.loading);

let model = ref({
  post_id: null,
  tag_id: null,
  title_ua: "",
  title_en: "",
  title_ru: "",
  description_ua: "",
  description_en: "",
  description_ru: "",
  content_ua: "",
  content_en: "",
  content_ru: ""
})

watch(
  () => store.state.currentPost.data, (newVal, oldVal) => {
    model.value = {
      ...JSON.parse(JSON.stringify(newVal))
    }
  }
);
console.log('1getPost',route.params.id)
if (route.params.id) {
  console.log('2getPost',route.params.id)
  store.dispatch('getPost', route.params.id);
}
store.dispatch('getTagsForSelectbox');

const tagsForSelectbox = computed(() => store.state.tagsForSelectbox.data);

function savePost(){
  store.dispatch("savePost", model.value)
    .then(({ data }) => {
      store.commit('notify', {
        type: 'success',
        message: 'Post was successfully created'
      });
      router.push({
        name: "PostView",
        params: {id: data.id},
      })
    });
}

function deletePost(){

}
</script>
