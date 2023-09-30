<template>
  <form @submit.prevent="updateprofile()" enctype="multipart/form-data">
    <div class="row">
      <div class="col-12 col-sm-12">
        <div class="row mb-4">
          <div class="col-12 col-sm-2 text-start text-sm-start">
            <label>
              <span class="text-danger me-1">*</span>
              <span>avatar:</span>
            </label>
          </div>
          <div class="col-12 col-sm-6">
            <div class="clearfix">
              <Upload
                v-model:file-list="fileList"
                list-type="picture-card"
                @preview="handlePreview"
                action="http://127.0.0.1:8000/api/upload"
              >
                <div v-if="fileList.length < 1">
                  <plus-outlined />
                  <div style="margin-top: 8px">Upload</div>
                </div>
              </Upload>
              <Modal :open="previewVisible" :title="previewTitle" @cancel="handleCancel">
                <img alt="example" style="width: 100%" :src="previewImage" />
              </Modal>
            </div>
          </div>
        </div>
        <div class="row mb-4">
          <div class="col-12 col-sm-2 text-start text-sm-start">
            <label>
              <span class="text-danger me-1">*</span>
              <span>Name:</span>
            </label>
          </div>
          <div class="col-12 col-sm-6">
            <a-input placeholder="input name" allow-clear v-model:value="name" />
            <div class="w-100"></div>
            <small v-if="errors.name" class="text-danger">{{ errors.name[0] }}</small>
          </div>
        </div>
      </div>
    </div>

    <div class="row mb-4">
      <div class="col-12 col-sm-3 text-start text-sm-start">
        <label>
          <span class="text-danger me-1">*</span>
          <span>change password:</span>
        </label>
      </div>
      <div class="col-12 col-sm-6">
        <input type="checkbox" v-model="passwordChange" />
      </div>
    </div>

    <div class="row mt-3">
      <div class="col-12 col-sm-9 d-grid d-sm-flex justify-content-sm-end mx-auto">
        <a-button type="primary" htmlType="submit">
          <span>Save</span>
        </a-button>
      </div>
    </div>
  </form>
</template>

<script>
import { ref, defineComponent, inject, reactive, toRefs } from "vue";
import { Upload, Modal, message } from "ant-design-vue";
import { useRouter, useRoute } from "vue-router";

export default defineComponent({
  setup() {
    const $loading = inject("$loading");

    const router = useRouter();

    const Profile = reactive({
      name: "",
      fileList: ref([]),
      passwordChange: ref(false),
    });

    const getProfile = () => {
      const loader = $loading.show({});
      axios
        .get("http://127.0.0.1:8000/api/profile")
        .then(function (response) {
          console.log(response);
          // Profile.value = response.data.data.user;
          Profile.name = response.data.data.user.name;
          Profile.email = response.data.data.user.email;
          Profile.avatar = response.data.data.user.avatar;
          loader.hide();
        })
        .catch(function (error) {
          loader.hide();
          console.error(error);
        });
    };
    getProfile();

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

    const errors = ref({});

    const updateprofile = () => {
      const loader = $loading.show({});
      const formData = new FormData();
      formData.append("name", Profile.name);
      formData.append("avatar", Profile.avatar);
      formData.append("passwordChange", Profile.passwordChange);
      // formData.append("password", passwordchange.password);
      // formData.append("password_new", passwordchange.password_new);
      // formData.append("password_confirmation", passwordchange.password_confirmation);

      axios
        .post("http://127.0.0.1:8000/api/profile/profileChange", formData)
        .then(function (response) {
          console.log(response);
          if (response.data.message) {
            loader.hide();
            message.success(response.data.message);
          }
        })
        .catch(function (error) {
          console.log(error);
          loader.hide();
          if (error.response.data.errors) {
            errors.value = error.response.data.errors;
          } else {
            message.error(error.response.data.message);
          }
        });
    };

    return {
      ...toRefs(Profile),
      updateprofile,
      errors,
      previewVisible,
      previewImage,
      previewTitle,
      getBase64,
      handleCancel,
      handlePreview,
    };
  },
  components: {
    Upload,
    Modal,
  },
});
</script>

<style></style>
