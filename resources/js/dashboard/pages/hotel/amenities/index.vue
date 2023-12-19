<template>
  <a-card title="amenities management" style="width: 100%" class="shadow">
    <div class="row">
      <div class="col-12 d-flex justify-content-end me-2">
        <a-button type="primary" @click="showModal">
          <font-awesome-icon :icon="['fas', 'plus']" />
        </a-button>
      </div>
    </div>
    <div class="row mb-4">
      <div class="col-12 col-sm-6">
        <form @submit.prevent="searchwidget()" enctype="multipart/form-data">
          <div class="row">
            <a-input-search
              v-model:value="searchName"
              placeholder="input search"
              enter-button
              allow-clear
            />
          </div>
        </form>
      </div>
    </div>
    <Modal v-model:open="open">
      <template #title> {{ modalTitle }} </template>
      <template #footer>
        <form
          @submit.prevent="checkform ? createamenitie() : updateamenitie(ID)"
          enctype="multipart/form-data"
        >
          <div class="row">
            <div class="col-12 col-sm-9 mb-4 justify-content-center align-items-center">
              <div class="row mb-4">
                <div class="col-12 col-sm-2 text-start text-sm-center">
                  <label>
                    <span class="text-danger me-1">*</span>
                    <span>name:</span>
                  </label>
                </div>
                <div class="col-12 col-sm-9">
                  <a-input
                    placeholder="enter the amenitie name"
                    allow-clear
                    v-model:value="name"
                  />
                  <div class="w-100"></div>
                  <!-- <small v-if="errors.name" class="text-danger">{{
                    errors.name[0]
                  }}</small> -->
                </div>
              </div>
            </div>
          </div>
          <a-button key="back" @click="handleCancel">Cancel</a-button>
          <a-button key="submit" type="primary" htmlType="submit">Save</a-button>
        </form>
      </template>
    </Modal>

    <div class="row mt-5">
      <div class="col-12">
        <a-table
          :dataSource="amenitiesdata.data"
          :columns="columns"
          :scroll="{ x: 576 }"
          :pagination="false"
        >
          <template #bodyCell="{ column, index, record }">
            <template v-if="column.key === 'index'">
              <span>{{ index + 1 }}</span>
            </template>

            <template v-if="column.key === 'action'">
              <a-button type="primary" @click="showEdit(record.id)" class="me-2">
                <font-awesome-icon :icon="['fas', 'pen-to-square']" />
              </a-button>
              <a-button type="primary" danger @click="deleteRecord(record.id)"
                ><font-awesome-icon :icon="['fas', 'trash']"
              /></a-button>
            </template>
          </template>
        </a-table>

        <Bootstrap5Pagination
          v-if="checkPagination"
          :data="amenitiesdata"
          @pagination-change-page="getamenities"
          class="mt-4 float-end"
        />
        <Bootstrap5Pagination
          v-if="!checkPagination"
          :data="amenitiesdata"
          @pagination-change-page="searchwidget"
          class="mt-4 float-end"
        />
      </div>
    </div>
  </a-card>
</template>

<script>
import { defineComponent, ref, reactive, toRefs, inject } from "vue";
import { Modal, message } from "ant-design-vue";
import { useMenu } from "../../../../store/menu";
import { Bootstrap5Pagination } from "laravel-vue-pagination";

export default defineComponent({
  setup() {
    const $loading = inject("$loading");
    const store = useMenu();
    store.onselectedkey(["amenities"]);
    const amenitiesdata = ref([]);
    const errors = ref({});
    const open = ref(false);
    const modalTitle = ref("Create amenitie");
    const checkform = ref(true);
    const checkPagination = ref(true);

    const amenitieForm = reactive({
      name: "",
      ID: "",
    });

    const search = reactive({
      searchName: "",
    });

    const showModal = () => {
      open.value = true;
      modalTitle.value = "Create amenitie";
      checkform.value = true;
      amenitieForm.name = "";
      errors.value = {};
    };

    const showEdit = (record) => {
      showModal();
      modalTitle.value = "Edit amenitie";
      getEdit(record);
      checkform.value = false;
      errors.value = {};
    };

    const handleCancel = () => {
      open.value = false;
    };

    const columns = [
      {
        title: "#",
        key: "index",
        width: 70,
      },
      {
        title: "name",
        dataIndex: "name",
        key: "name",
        width: 1000,
      },
      {
        title: "action",
        key: "action",
        fixed: "right",
        width: 150,
      },
    ];

    const getamenities = (page = 1) => {
      const loader = $loading.show({});
      axios
        .get(`http://127.0.0.1:8000/api/dashboard/Hotel/amenitie?page=${page}`)
        .then(function (response) {
          // console.log(response);
          checkPagination.value = true;
          amenitiesdata.value = response.data.data.amenities;
          loader.hide();
        })
        .catch(function (error) {
          console.log(error);
          loader.hide();
        });
    };

    const createamenitie = () => {
      const loader = $loading.show({});
      const formData = new FormData();
      formData.append("name", amenitieForm.name);
      axios
        .post("http://127.0.0.1:8000/api/dashboard/Hotel/amenitie/create", formData)
        .then(function (response) {
          console.log(response);
          loader.hide();
          if (response.data.message) {
            getamenities();
            message.success(response.data.message);
            amenitieForm.name = "";
            search.searchName = "";
            open.value = false;
          }
        })
        .catch(function (error) {
          console.log(error);
          loader.hide();
          errors.value = error.response.data.errors;
        });
    };

    const getEdit = (record) => {
      const loader = $loading.show({});
      axios
        .get(`http://127.0.0.1:8000/api/dashboard/Hotel/amenitie/${record}/edit`)
        .then(function (response) {
          console.log(response);
          amenitieForm.ID = response.data.data.amenities.id;
          amenitieForm.name = response.data.data.amenities.name;
          loader.hide();
        })
        .catch(function (error) {
          console.log(error);
          loader.hide();
        });
    };
    const updateamenitie = (record) => {
      const loader = $loading.show({});
      const formData = new FormData();
      formData.append("name", amenitieForm.name);
      axios
        .post(`http://127.0.0.1:8000/api/dashboard/Hotel/amenitie/${record}/edit`, formData)

        .then(function (response) {
          // console.log(response);
          loader.hide();
          if (response.data.message) {
            search.searchName = "";
            getamenities();
            message.success(response.data.message);
            open.value = false;
          }
        })
        .catch(function (error) {
          // console.log(error);
          loader.hide();
          errors.value = error.response.data.errors;
        });
    };

    const searchwidget = (page = 1) => {
      const loader = $loading.show({});
      const formData = new FormData();

      formData.append("searchName", search.searchName ? search.searchName : "");
      axios
        .post(
          `http://127.0.0.1:8000/api/dashboard/Hotel/amenitie/search?page=${page}`,
          formData
        )
        .then(function (response) {
          // console.log(response);
          if (response) {
            checkPagination.value = false;
            amenitiesdata.value = response.data.data.amenities;
          }
          loader.hide();
        })
        .catch(function (error) {
          console.log(error);
          message.error(error.response.data.message);
          loader.hide();
        });
    };

    const deleteRecord = (recordId) => {
      const loader = $loading.show({});
      axios
        .post(`http://127.0.0.1:8000/api/dashboard/Hotel/amenitie/delete/${recordId}`)
        .then(function (response) {
          // console.log(response);
          loader.hide();
          if (response.data.message) {
            getamenities();
            message.success(response.data.message);
          }
        })
        .catch(function (error) {
          console.log(error);
          message.error(error.response.data.message);
          loader.hide();
        });
    };

    getamenities();
    return {
      amenitiesdata,
      errors,
      open,
      modalTitle,
      columns,
      checkform,
      checkPagination,
      ...toRefs(amenitieForm),
      ...toRefs(search),
      showModal,
      handleCancel,
      showEdit,
      getamenities,
      updateamenitie,
      createamenitie,
      searchwidget,
      deleteRecord,
    };
  },
  components: {
    Modal,
    Bootstrap5Pagination,
  },
});
</script>

<style>
.pagination {
  --bs-pagination-font-size: 12px;
}
</style>
