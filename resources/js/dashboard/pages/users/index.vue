<template>
  <a-card title="Account" style="width: 100%">
    <div class="row mb-3">
      <div class="col-12 d-flex justify-content-end me-2">
        <router-link :to="{ name: 'users-create' }">
          <a-button type="primary">
            <font-awesome-icon :icon="['fas', 'plus']" />
          </a-button>
        </router-link>
      </div>
    </div>
    <form @submit.prevent="searchUser" enctype="multipart/form-data">
      <div class="row mb-4">
        <div class="col-12 col-sm-4">
          <label>
            <span>name search</span>
          </label>
          <a-input placeholder="input name" allow-clear v-model:value="searchName">
            <template #prefix>
              <font-awesome-icon :icon="['fas', 'user']" />
            </template>
          </a-input>
        </div>

        <div class="col-12 col-sm-3">
          <label>
            <span>Department</span>
          </label>
          <a-select
            placeholder="Department seclect"
            style="width: 100%"
            :options="departments"
            allow-clear
            v-model:value="searchDepartment_id"
          >
            <template #suffixIcon>
              <font-awesome-icon :icon="['fas', 'bookmark']" />
            </template>
          </a-select>
        </div>

        <div class="col-12 col-sm-3">
          <label>
            <span>status</span>
          </label>
          <a-select
            placeholder="status seclect"
            style="width: 100%"
            :options="user_status"
            v-model:value="searchStatus_id"
            allow-clear
          >
            <template #suffixIcon>
              <font-awesome-icon :icon="['fas', 'bookmark']" /> </template
          ></a-select>
        </div>
        <div class="col-12 col-sm-2 btn-search">
          <a-button
            type="primary"
            class="ml-2"
            htmlType="submit"
            style="padding: 0px 30px"
          >
            <span>search</span>
          </a-button>
        </div>
      </div>
    </form>

    <div class="row">
      <div class="col-12">
        <a-table :dataSource="users" :columns="columns" :scroll="{ x: 576 }">
          <template #bodyCell="{ column, index, record }">
            <template v-if="column.key === 'index'">
              <span>{{ index + 1 }}</span>
            </template>

            <template v-if="column.key === 'avatar'">
              <a-avatar shape="square" :size="100">
                <template #icon>
                  <img v-if="record.avatar !== null" :src="record.avatar" alt="avatar" />
                  <img v-else src="/assets/img/User-avatar.svg.png" alt="a-avatar" />
                </template>
              </a-avatar>
            </template>

            <template v-if="column.key === 'status'">
              <span v-if="record.status_id == 1" class="text-success">{{
                record.status
              }}</span>
              <span v-if="record.status_id == 2" class="text-danger">{{
                record.status
              }}</span>
            </template>

            <template v-if="column.key === 'action'">
              <router-link
                :to="{
                  name: 'users-edit',
                  params: { id: record.id },
                  query: { title: record.name },
                }"
              >
                <a-button type="primary" htmlType="submit" class="me-2">
                  <font-awesome-icon :icon="['fas', 'pen-to-square']" />
                </a-button>
              </router-link>
            </template>
          </template>
        </a-table>
      </div>
    </div>
  </a-card>
</template>

<script>
import axios from "axios";
import { useMenu } from "../../../store/menu";
import { ref, defineComponent, inject, reactive, toRefs } from "vue";
import { message } from "ant-design-vue";

export default defineComponent({
  setup() {
    const store = useMenu();
    store.onselectedkey(["user"]);

    const $loading = inject("$loading");

    const users = ref([]);

    const user_status = ref([]);
    const departments = ref([]);

    const search = reactive({
      searchName: "",
      searchDepartment_id: null,
      searchStatus_id: null,
    });

    const columns = [
      {
        title: "#",
        key: "index",
        width: 100,
      },
      {
        title: "Avatar",
        dataIndex: "avatar",
        key: "avatar",
      },
      {
        title: "email",
        dataIndex: "email",
        key: "email",
      },
      {
        title: "Account name",
        dataIndex: "name",
        key: "name",
      },
      {
        title: "department",
        dataIndex: "department",
        key: "department",
      },
      {
        title: "account status",
        dataIndex: "status.name",
        key: "status",
      },
      {
        title: "action",
        key: "action",
        fixed: "right",
      },
    ];

    const getUser = () => {
      const loader = $loading.show({});
      axios
        .get("http://127.0.0.1:8000/api/dashboard/users")
        .then(function (response) {
          console.log(response);
          users.value = response.data.users;
          user_status.value = response.data.users_status;
          departments.value = response.data.Department;
          loader.hide();
        })
        .catch(function (error) {
          console.error(error);
          loader.hide();
        });
    };
    getUser();

    const searchUser = () => {
      const loader = $loading.show({});
      const formData = new FormData();
      if (search.searchName) {
        formData.append("searchName", search.searchName);
      }
      if (search.searchDepartment_id) {
        formData.append("searchDepartment", search.searchDepartment_id);
      }
      if (search.searchStatus_id) {
        formData.append("searchStatus", search.searchStatus_id);
      }
      axios
        .post("http://127.0.0.1:8000/api/dashboard/users/search", formData)
        .then(function (response) {
          // console.log(response);
          users.value = response.data.users;
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

    return {
      users,
      ...toRefs(search),
      columns,
      user_status,
      departments,
      filterOption,
      getUser,
      searchUser,
    };
  },
  methods: {},
  components: {},
});
</script>

<style>
.col-12.col-sm-2.btn-search {
  margin: 22px 0px 0px 0px;
}
</style>
