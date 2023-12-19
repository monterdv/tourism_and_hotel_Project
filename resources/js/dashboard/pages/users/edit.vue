<template>
  <form @submit.prevent="updateUsers()" enctype="multipart/form-data">
    <a-card title="Edit Account" style="width: 100%" class="shadow">
      <div class="row">
        <div class="col-12 col-sm-4">
          <div class="row">
            <div class="col-12 d-flex justify-content-center mb-3">
              <a-avatar shape="square" :size="200">
                <template #icon
                  ><UserOutlined />
                  <img
                    :src="avatar !== null ? avatar : '/assets/img/User-avatar.svg.png'"
                    alt="Image"
                  />
                </template>
              </a-avatar>
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
              <!-- <small v-if="errors.status_id" class="text-danger">{{
                errors.status_id[0]
              }}</small> -->
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
              <a-input
                placeholder="input name"
                allow-clear
                disabled
                v-model:value="name"
                readonly
              />
              <div class="w-100"></div>
              <!-- <small v-if="errors.name" class="text-danger">{{
                errors.name[0]
              }}</small> -->
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
              <a-input
                placeholder="input email"
                allow-clear
                v-model:value="email"
                disabled
                readonly
              />
              <div class="w-100"></div>
              <!-- <small v-if="errors.email" class="text-danger">{{
                errors.email[0]
              }}</small> -->
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
              <!-- <small v-if="errors.department_id" class="text-danger">{{
                errors.department_id[0]
              }}</small> -->
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12 col-sm-9 d-grid d-sm-flex justify-content-sm-end mx-auto">
          <router-link :to="{ name: 'users' }">
            <a-button class="me-0 me-sm-2 mb-3 mb-sm-0">
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
import { useRouter, useRoute } from "vue-router";
import { message } from "ant-design-vue";
import { useMenu } from "../../../store/menu";

export default defineComponent({
  setup() {
    useMenu().onselectedkey(["user"]);
    const router = useRouter();
    const route = useRoute();

    const $loading = inject("$loading");

    const user_status = ref([]);
    const departments = ref([]);
    const users = reactive({
      name: "",
      email: "",
      avatar: "",
      department_id: [],
      status_id: [],
    });

    const errors = ref({});

    const getUseredit = () => {
      const loader = $loading.show({});
      axios
        .get(`http://127.0.0.1:8000/api/dashboard/users/${route.params.id}/edit`)
        .then(function (response) {
          // console.log(response);
          users.name = response.data.users.name;
          users.email = response.data.users.email;
          users.avatar = response.data.users.avatar;
          users.department_id = response.data.users.department_id;
          users.status_id = response.data.users.status_id;

          departments.value = response.data.Department;
          user_status.value = response.data.users_status;
          loader.hide();
        })
        .catch(function (error) {
          console.log(error);
          loader.hide();
        });
    };

    const filterOption = (input, option) => {
      return option.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
    };

    const updateUsers = () => {
      const loader = $loading.show({});
      const formData = new FormData();
      formData.append("department_id", users.department_id);
      formData.append("status_id", users.status_id);

      axios
        .post(`http://127.0.0.1:8000/api/dashboard/users/${route.params.id}`, formData)
        .then(function (response) {
          // console.log(response);
          loader.hide();
          if (response.data.message) {
            message.success(response.data.message);
          }
          router.push({ name: "users" });
        })
        .catch(function (error) {
          errors.value = error.response.data.errors;
          loader.hide();
        });
    };

    getUseredit();
    return {
      user_status,
      departments,
      errors,
      ...toRefs(users),
      filterOption,
      updateUsers,
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
