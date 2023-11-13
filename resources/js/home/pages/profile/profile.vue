<template>
  <div class="row">
    <div class="col-lg-12 mb-4 mb-sm-5">
      <div class="card card-style1 border-0">
        <div class="card-body p-1-9 p-sm-2-3 p-md-6 p-lg-7">
          <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0 text-center">
              <div>
                <img
                  v-if="!avatar"
                  src="https://bootdey.com/img/Content/avatar/avatar7.png"
                  alt=""
                />
                <img v-else :src="avatar" alt="" width="400" height="400" />
              </div>
              <div class="mt-2 align-items-center">
                <Upload
                  v-model:file-list="fileList"
                  :before-upload="beforeUpload"
                  name="file"
                  :max-count="1"
                  action="http://127.0.0.1:8000/api/upload"
                >
                  <a-button v-if="fileList.length === 0">
                    <upload-outlined></upload-outlined>
                    Upload Avatar
                  </a-button>
                  <a-button
                    v-if="fileList.length > 0"
                    type="primary"
                    style="margin-top: 16px"
                    @click="updateAvatar"
                    :loading="uploading"
                  >
                    {{ uploading ? "Uploading" : "Start Upload" }}
                  </a-button>
                </Upload>
              </div>
            </div>
            <div class="col-lg-6 px-xl-10">
              <Form
                layout="horizontal"
                :disabled="componentDisabled"
                style="max-width: 600px"
                @submit.prevent="updateprofile()"
                enctype="multipart/form-data"
              >
                <a-form-item label="email">
                  {{ email }}
                </a-form-item>
                <a-form-item label="Name">
                  <a-input placeholder="input name" allow-clear v-model:value="name" />
                  <div class="w-100"></div>
                  <small v-if="errors.name" class="text-danger">{{
                    errors.name[0]
                  }}</small>
                </a-form-item>
                <a-form-item label="phone">
                  <a-input placeholder="input phone" allow-clear v-model:value="phone" />
                </a-form-item>
                <a-form-item label="wallet">
                  {{ wallet }}
                </a-form-item>
                <a-form-item label="passwordChange">
                  <Checkbox v-model:checked="passwordChange"></Checkbox>
                  <!-- <input type="checkbox" v-model="passwordChange" /> -->
                </a-form-item>
                <div v-if="passwordChange">
                  <a-form-item label="password">
                    <a-input-password
                      placeholder="input password"
                      allow-clear
                      v-model:value="password"
                    />
                    <div class="w-100"></div>
                    <small v-if="errors.password" class="text-danger">{{
                      errors.password[0]
                    }}</small>
                  </a-form-item>

                  <a-form-item label="password new">
                    <a-input-password
                      placeholder="input password new"
                      allow-clear
                      v-model:value="password_new"
                    />
                    <div class="w-100"></div>
                    <small v-if="errors.password_new" class="text-danger">{{
                      errors.password_new[0]
                    }}</small>
                  </a-form-item>

                  <a-form-item label="password confirmation">
                    <a-input-password
                      placeholder="input password confirmation"
                      allow-clear
                      v-model:value="password_confirmation"
                    />
                    <div class="w-100"></div>
                    <small v-if="errors.password_confirmation" class="text-danger">{{
                      errors.password_confirmation[0]
                    }}</small>
                  </a-form-item>
                </div>
                <div class="row">
                  <div class="col-12 col-sm-12" v-if="componentDisabled">
                    <button class="header__login-login2" @click="Disabledinput()">
                      update profile
                    </button>
                  </div>
                  <div class="col-12 col-sm-12" v-if="!componentDisabled">
                    <button class="header__login-login2" type="primary" htmlType="submit">
                      update
                    </button>
                  </div>
                </div>
              </Form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, defineComponent, inject, reactive, toRefs } from "vue";
import formprofile from "./formprofile.vue";
import { message, Tabs, Form, Checkbox, Upload } from "ant-design-vue";
import { useRouter, useRoute } from "vue-router";

export default defineComponent({
  setup() {
    const $loading = inject("$loading");
    const router = useRouter();

    const activeKey = ref("1");
    const componentDisabled = ref(true);
    const errors = ref({});
    const uploading = ref(false);
    const Profile = reactive({
      avatar: "",
      fileList: ref([]),
      email: "",
      department_id: "",
      name: "",
      phone: "",
      wallet: "",
      passwordChange: ref(false),
      password: "",
      password_new: "",
      password_confirmation: "",
    });

    const Disabledinput = () => {
      componentDisabled.value = !componentDisabled.value;
    };
    const getProfile = () => {
      const loader = $loading.show({});
      axios
        .get("http://127.0.0.1:8000/api/profile")
        .then(function (response) {
          // console.log(response);
          Profile.name = response.data.data.user.name;
          Profile.email = response.data.data.user.email;
          Profile.phone = response.data.data.user.phone;
          Profile.avatar = response.data.data.user.avatar;
          Profile.department_id = response.data.data.user.department_id;
          Profile.wallet = response.data.data.user.wallet || 0;
          loader.hide();
        })
        .catch(function (error) {
          loader.hide();
          console.error(error);
        });
    };
    getProfile();

    const updateprofile = () => {
      const loader = $loading.show({});
      const formData = new FormData();
      formData.append("name", Profile.name);
      formData.append("phone", Profile.phone);
      formData.append("passwordChange", Profile.passwordChange);
      if (Profile.passwordChange) {
        formData.append("password", Profile.password);
        formData.append("password_new", Profile.password_new);
        formData.append("password_confirmation", Profile.password_confirmation);
      }

      axios
        .post("http://127.0.0.1:8000/api/profile/profileChange", formData)
        .then(function (response) {
          console.log(response);
          if (response.data.message) {
            loader.hide();
            Disabledinput();
            message.success(response.data.message);
            // router.go();
            errors.value = {};
            passwordChange.value = false;
          }
        })
        .catch(function (error) {
          console.log(error);
          loader.hide();
          if (error) {
            if (error.response.data.errors) {
              errors.value = error.response.data.errors;
            }
          }
        });
    };

    const beforeUpload = (file) => {
      fileList.value = [...(fileList.value || []), file];
      return false;
    };

    const updateAvatar = () => {
      const loader = $loading.show({});
      const formData = new FormData();
      formData.append("name", Profile.name);
      if (Profile.fileList.length > 0 && Profile.fileList[0].originFileObj) {
        formData.append("file", Profile.fileList[0].originFileObj);
      }
      axios
        .post("http://127.0.0.1:8000/api/profile/uploadAvatar", formData)
        .then(function (response) {
          console.log(response);
          loader.hide();
          router.go();
        })
        .catch(function (error) {
          console.log(error);
          loader.hide();
        });
    };

    return {
      ...toRefs(Profile),
      activeKey,
      updateprofile,
      componentDisabled,
      Disabledinput,
      errors,
      beforeUpload,
      updateAvatar,
      uploading,
    };
  },
  components: {
    formprofile,
    Tabs,
    Form,
    Checkbox,
    Upload,
  },
});
</script>

<style></style>
