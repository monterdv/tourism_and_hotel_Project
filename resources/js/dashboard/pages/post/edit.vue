<template>
  <form @submit.prevent="updatePost" enctype="multipart/form-data">
    <a-card title="Create new post" style="width: 100%">
      <div class="row">
        <div class="col-12 col-sm-7">
          <div class="row mb-4">
            <div class="col-12 col-sm-2 text-start text-sm-end">
              <label>
                <span class="text-danger me-1">*</span>
                <span>name:</span>
              </label>
            </div>
            <div class="col-12 col-sm-10">
              <a-input placeholder="input Name Hotel" allow-clear v-model:value="title" />
              <div class="w-100"></div>
              <small v-if="errors.title" class="text-danger">{{ errors.title[0] }}</small>
            </div>
          </div>

          <div class="row mb-4">
            <div class="col-12 col-sm-2 text-start text-sm-end">
              <label>
                <span class="text-danger me-1">*</span>
                <span>Place:</span>
              </label>
            </div>
            <div class="col-12 col-sm-10">
              <a-select
                show-search
                placeholder="country or city"
                style="width: 100%"
                :options="Places"
                :filter-option="filterplace"
                allow-clear
                v-model:value="place"
                class="col-12"
              ></a-select>
              <div class="w-100"></div>
              <small v-if="errors.place_id" class="text-danger">{{
                errors.place_id[0]
              }}</small>
            </div>
          </div>
        </div>

        <!-- here -->
        <div class="col-12 col-sm-5 mb-4">
          <div class="row">
            <label>
              <span class="text-danger me-1">*</span>
              <span style="font-size: small">Image:</span>
            </label>
            <div class="clearfix">
              <Upload
                v-model:file-list="file"
                list-type="picture-card"
                @preview="handlePreview"
                action="http://127.0.0.1:8000/api/upload"
              >
                <div v-if="file.length < 1">
                  <!-- <plus-outlined /> -->
                  <div style="margin-top: 8px">Upload</div>
                </div>
              </Upload>
              <Modal :open="previewVisible" :title="previewTitle" @cancel="handleCancel">
                <img alt="example" style="width: 100%" :src="previewImage" />
              </Modal>
              <div class="w-100"></div>
              <small v-if="errors.file" class="text-danger">{{ errors.file[0] }}</small>
            </div>
          </div>
        </div>
      </div>
      <div class="row mb-2">
        <div class="col-12 col-sm-12 text-start text-sm-start">
          <label>
            <span class="text-danger me-1">*</span>
            <span>introduce:</span>
          </label>
          <div class="w-100"></div>
          <small v-if="errors.introduce" class="text-danger">{{
            errors.introduce[0]
          }}</small>
        </div>
      </div>
      <Editor v-model="introduce" />

      <div class="row mt-3">
        <div class="col-12 col-sm-9 d-grid d-sm-flex justify-content-sm-end mx-auto">
          <router-link :to="{ name: 'hotel' }">
            <a-button class="me-0 me-sm-2 mb-3 mb-sm-0">
              <span>Cancel</span>
            </a-button>
          </router-link>

          <a-button type="primary" htmlType="submit">
            <span>Save</span>
          </a-button>
        </div>
      </div>
    </a-card>
  </form>
</template>

<script>
import { defineComponent, ref, reactive, toRefs, inject } from "vue";
import { Empty, Upload, Modal, message, TimePicker, Rate } from "ant-design-vue";
import Editor from "../Editor.vue";
import { useRouter, useRoute } from "vue-router";
import { useMenu } from "../../../store/menu";

export default defineComponent({
  setup() {
    const store = useMenu();
    store.onselectedkey(["post_add"]);

    const $loading = inject("$loading");

    const router = useRouter();
    const route = useRoute();
    const errors = ref({});

    const post = reactive({
      title: "",
      place: null,
      introduce: "",
      file: ref([]),
    });

    const Places = ref([]);
    const getCreatePost = () => {
      const loader = $loading.show({});
      axios
        .get(`http://127.0.0.1:8000/api/dashboard/post/${route.params.slug}/edit`)
        .then(function (response) {
          console.log(response);
          loader.hide();
          post.title = response.data.data.post.title;
          post.place = response.data.data.post.place_id;
          post.introduce = response.data.data.post.introduce;
          post.file = [
            {
              uid: response.data.data.post.id,
              name: response.data.data.post.title,
              status: "done",
              url: response.data.data.post.image,
              thumbUrl: response.data.data.post.image,
            },
          ];
          Places.value = response.data.data.palces;
        })
        .catch(function (error) {
          console.error(error);
          loader.hide();
        });
    };
    getCreatePost();

    const previewVisible = ref(false);
    const previewImage = ref("");
    const previewTitle = ref("");

    function getBase64(file) {
      return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = () => resolve(reader.result);
        reader.onerror = (error) => reject(error);
      });
    }

    const handleCancel = () => {
      previewVisible.value = false;
      previewTitle.value = "";
    };

    const handlePreview = async (file) => {
      if (!file.url && !file.preview) {
        file.preview = await getBase64(file.originFileObj);
      }
      previewImage.value = file.url || file.preview;
      previewVisible.value = true;
    };

    const filterplace = (input, Places) => {
      return Places.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
    };

    const updatePost = () => {
      const loader = $loading.show({});
      const formData = new FormData();
      formData.append("title", post.title);
      formData.append("place_id", post.place ? post.place : "");
      formData.append("introduce", post.introduce);
      if (post.file.length > 0 && post.file[0].originFileObj instanceof File) {
        formData.append("file", post.file[0].originFileObj);
      } else if (post.file.length > 0 && post.file[0].name) {
        formData.append("file", post.file[0].name);
      } else {
        formData.append("file", "");
      }

      axios
        .post(`http://127.0.0.1:8000/api/dashboard/post/${route.params.slug}/edit`, formData)
        .then(function (response) {
          console.log(response);
          loader.hide();
            if (response) {
              message.success(response.data.message);
              router.push({ name: "posts" });
            }
        })
        .catch(function (error) {
          console.log(error);
          loader.hide();
          if (error.response.status === 400) {
            message.error(error.response.data.message);
          } else {
            if (error.response.data.errors) {
              errors.value = error.response.data.errors;
            } else {
              message.error(error.response.data.message);
            }
          }
        });
    };

    return {
      Places,
      errors,
      previewVisible,
      previewImage,
      previewTitle,
      getBase64,
      handleCancel,
      handlePreview,
      ...toRefs(post),
      updatePost,
      filterplace,
    };
  },
  components: {
    Editor,
    Upload,
    Modal,
  },
});
</script>

<style>
.ant-upload-select-picture-card i {
  font-size: 32px;
  color: #999;
}

.ant-upload-select-picture-card .ant-upload-text {
  margin-top: 8px;
  color: #666;
}

.selec-danger-input {
  border: 1px solid red;
}
</style>
