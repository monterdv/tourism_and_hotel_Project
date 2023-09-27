<template>
  <form @submit.prevent="createUsers" enctype="multipart/form-data">
    <a-card title="Create New Account" style="width: 100%">
      <div class="row">
        <div class="col-12 col-sm-4">
          <div class="row">
            <div class="col-12 d-flex justify-content-center mb-3">
              <a-avatar shape="square" :size="200">
                <template #icon
                  ><UserOutlined />
                  <img v-if="thumbnailImage" :src="thumbnailImage" alt="imageName" />
                  <img v-else src="/assets/img/User-avatar.svg.png" alt="a-avatar" />
                </template>
              </a-avatar>
            </div>
            <div class="col-12 d-flex justify-content-center">
              <a-button type="primary">
                <font-awesome-icon :icon="['fas', 'plus']" class="me-2" />
                <input
                  type="file"
                  id="upload"
                  hidden
                  v-on:change="handleThumbnailChange"
                />
                <label for="upload">Choose file</label>
              </a-button>
            </div>
          </div>
        </div>
        <div class="col-12 col-sm-8">
          <div class="row mb-4">
            <div class="col-12 col-sm-4 text-start text-sm-end">
              <label>
                <span class="text-danger me-1">*</span>
                <span> status: </span>
              </label>
            </div>
            <div class="col-12 col-sm-6">
              <a-select
                show-search
                placeholder="status"
                style="width: 100%"
                :options="user_status"
                :filter-option="filterOption"
                v-model:value="status_id"
              ></a-select>

              <div class="w-100"></div>
              <small v-if="errors.status_id" class="text-danger">{{
                errors.status_id[0]
              }}</small>
            </div>
          </div>

          <div class="row mb-4">
            <div class="col-12 col-sm-4 text-start text-sm-end">
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
          <div class="row mb-4">
            <div class="col-12 col-sm-4 text-start text-sm-end">
              <label>
                <span class="text-danger me-1">*</span>
                <span>email:</span>
              </label>
            </div>
            <div class="col-12 col-sm-6">
              <a-input placeholder="input email" allow-clear v-model:value="email" />
              <div class="w-100"></div>
              <small v-if="errors.email" class="text-danger">{{ errors.email[0] }}</small>
            </div>
          </div>
          <div class="row mb-4">
            <div class="col-12 col-sm-4 text-start text-sm-end">
              <label>
                <span class="text-danger me-1">*</span>
                <span> Department: </span>
              </label>
            </div>

            <div class="col-12 col-sm-6">
              <a-select
                show-search
                placeholder="Department"
                style="width: 100%"
                :options="departments"
                :filter-option="filterOption"
                allow-clear
                v-model:value="department_id"
              ></a-select>
              <div class="w-100"></div>
              <small v-if="errors.department_id" class="text-danger">{{
                errors.department_id[0]
              }}</small>
            </div>
          </div>
          <div class="row mb-4">
            <div class="col-12 col-sm-4 text-start text-sm-end">
              <label>
                <span class="text-danger me-1">*</span>
                <span>password:</span>
              </label>
            </div>
            <div class="col-12 col-sm-6">
              <a-input-password
                placeholder="input password"
                allow-clear
                v-model:value="password"
              />
              <div class="w-100"></div>
              <small v-if="errors.password" class="text-danger">{{
                errors.password[0]
              }}</small>
            </div>
          </div>

          <div class="row mb-4">
            <div class="col-12 col-sm-4 text-start text-sm-end">
              <label>
                <span class="text-danger me-1">*</span>
                <span>password confirmation:</span>
              </label>
            </div>
            <div class="col-12 col-sm-6">
              <a-input-password
                placeholder="input password confirmation"
                allow-clear
                v-model:value="password_confirmation"
              />
              <div class="w-100"></div>
              <small v-if="errors.password_confirmation" class="text-danger">{{
                errors.password_confirmation[0]
              }}</small>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12 col-sm-9 d-grid d-sm-flex justify-content-sm-end mx-auto">
          <router-link :to="{ name: 'users' }" class="me-2">
            <a-button class="me-2 me-sm-2 mb-3 mb-sm-0 col-12">
              <span>Cancel</span>
            </a-button>
          </router-link>

          <a-button type="primary" ghost class="ml-2" htmlType="submit">
            <span>Save</span>
          </a-button>
        </div>
      </div>
    </a-card>
  </form>
</template>

<script>
import { defineComponent, ref, reactive, toRefs, inject } from "vue";
import { useRouter } from "vue-router";
import { message } from "ant-design-vue";
import { useMenu } from "../../../store/menu";

export default defineComponent({
  setup() {
    useMenu().onselectedkey(["user"]);
    const router = useRouter();

    const $loading = inject("$loading");

    const user_status = ref([]);
    const departments = ref([]);
    const users = reactive({
      name: "",
      email: "",
      password: "",
      password_confirmation: "",
      avatar: ref([]),
      department_id: 2,
      status_id: 1,
    });

    const errors = ref({});
    const thumbnailImage = ref("");

    const getUsersCreate = () => {
      const loader = $loading.show({});
      axios
        .get("http://127.0.0.1:8000/api/dashboard/users/create")
        .then(function (response) {
          // console.log(response);
          user_status.value = response.data.users_status;
          departments.value = response.data.Department;
          loader.hide();
        })
        .catch(function (error) {
          console.error(error);
          loader.hide();
        });
    };

    const filterOption = (input, option) => {
      return option.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
    };

    const handleThumbnailChange = (event) => {
      const file = event.target.files[0];
      // console.log("Selected file:", file);
      if (!file) {
        return;
      }
      users.avatar = file;
      const reader = new FileReader();

      reader.onload = (e) => {
        const imageData = e.target.result;
        thumbnailImage.value = imageData;
      };

      reader.readAsDataURL(file);
    };

    const createUsers = () => {
      const loader = $loading.show({});
      const formData = new FormData();
      formData.append("name", users.name);
      formData.append("email", users.email);
      formData.append("password", users.password);
      formData.append("password_confirmation", users.password_confirmation);

      const uploadInput = document.getElementById("upload");
      const avatarFile = uploadInput ? uploadInput.files[0] : null;

      if (avatarFile) {
        formData.append("avatar", avatarFile);
      }

      formData.append("department_id", users.department_id);
      formData.append("status_id", users.status_id);

      axios
        .post("http://127.0.0.1:8000/api/dashboard/users/create", formData)
        .then(function (response) {
          // console.log(response);
          if (response.data.message) {
            loader.hide();
            message.success(response.data.message);
            router.push({ name: "users" });
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

    getUsersCreate();
    return {
      handleThumbnailChange,
      user_status,
      departments,
      errors,
      thumbnailImage,
      ...toRefs(users),
      filterOption,
      createUsers,
    };
  },
});
</script>

<style>
.select-danger {
  border: 1px solid red;
}

.input-danger {
  border-color: red;
}
</style>
