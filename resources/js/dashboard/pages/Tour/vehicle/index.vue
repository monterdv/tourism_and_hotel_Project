<template>
  <a-card title="vehicle management" style="width: 100%" class="shadow">
    <div class="row mb-4">
      <form @submit.prevent="search" enctype="multipart/form-data">
        <div class="row mb-4">
          <div class="col-12 col-sm-6">
            <a-input placeholder="input name" allow-clear v-model:value="namesearch">
            </a-input>
          </div>
          <div class="col-12 col-sm-1 btn-search">
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
    </div>
    <div class="row">
      <div class="col-12 d-flex justify-content-end me-2">
        <a-button type="primary" @click="showModal">
          <font-awesome-icon :icon="['fas', 'plus']" />
        </a-button>
      </div>
    </div>
    <a-table :dataSource="Vehicle" :columns="columns">
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
  </a-card>
  <Modal v-model:open="open">
    <template #title> {{ modalTitle }} </template>
    <template #footer>
      <form
        @submit.prevent="checkform ? createVehicle() : update(Id)"
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
                <a-input placeholder="enter vehicle" allow-clear v-model:value="name" />
                <div class="w-100"></div>
                <small v-if="errors.name" class="text-danger d-flex">{{
                  errors.name[0]
                }}</small>
              </div>
            </div>
          </div>
        </div>
        <a-button key="back" @click="handleCancel">Cancel</a-button>
        <a-button key="submit" type="primary" htmlType="submit">Save</a-button>
      </form>
    </template>
  </Modal>
</template>

<script>
import { defineComponent, ref, reactive, toRefs, inject } from "vue";
import { Modal, message } from "ant-design-vue";
import { useMenu } from "../../../../store/menu";

export default defineComponent({
  setup() {
    const $loading = inject("$loading");
    const store = useMenu();
    store.onselectedkey(["vehicle"]);
    const errors = ref({});
    const Vehicle = ref([]);
    const checkform = ref(true);

    const open = ref(false);
    const modalTitle = ref("Create vehicle");

    const showModal = () => {
      open.value = true;
      modalTitle.value = "Create vehicle";
      checkform.value = true;
      VehicleForm.name = "";
      errors.value = {};
    };

    const handleCancel = () => {
      open.value = false;
      VehicleForm.name = "";
      VehicleForm.Id = "";
    };

    const showEdit = (record) => {
      showModal();
      modalTitle.value = "Edit Category";
      getEdit(record);
      checkform.value = false;
      errors.value = {};
    };

    const VehicleForm = reactive({
      name: "",
      Id: "",
    });

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
    const getvehicle = () => {
      const loader = $loading.show({});
      axios
        .get(`http://127.0.0.1:8000/api/dashboard/vehicle`)
        .then(function (response) {
          //   console.log(response);
          Vehicle.value = response.data.data.Vehicle;
          loader.hide();
        })
        .catch(function (error) {
          console.log(error);
          loader.hide();
        });
    };
    getvehicle();
    const createVehicle = () => {
      const loader = $loading.show({});
      const formData = new FormData();
      formData.append("name", VehicleForm.name);
      axios
        .post("http://127.0.0.1:8000/api/dashboard/vehicle/create", formData)
        .then(function (response) {
          //   console.log(response);
          loader.hide();
          if (response.data.message) {
            getvehicle();
            message.success(response.data.message);
            VehicleForm.name = "";
            open.value = false;
          }
        })
        .catch(function (error) {
          // console.log(error);
          loader.hide();
          errors.value = error.response.data.errors;
        });
    };

    const getEdit = (record) => {
      const loader = $loading.show({});
      axios
        .get(`http://127.0.0.1:8000/api/dashboard/vehicle/${record}/edit`)
        .then(function (response) {
          //   console.log(response);
          VehicleForm.Id = response.data.data.Vehicle.id;
          VehicleForm.name = response.data.data.Vehicle.name;
          loader.hide();
        })
        .catch(function (error) {
          console.log(error);
          loader.hide();
        });
    };
    const update = (record) => {
      const loader = $loading.show({});
      const formData = new FormData();
      formData.append("name", VehicleForm.name);
      axios
        .post(`http://127.0.0.1:8000/api/dashboard/vehicle/${record}/edit`, formData)

        .then(function (response) {
          // console.log(response);
          loader.hide();
          if (response.data.message) {
            getvehicle();
            message.success(response.data.message);
            VehicleForm.name = "";
            open.value = false;
          }
        })
        .catch(function (error) {
          // console.log(error);
          loader.hide();
          errors.value = error.response.data.errors;
        });
    };
    const deleteRecord = (recordId) => {
      const loader = $loading.show({});
      axios
        .post(`http://127.0.0.1:8000/api/dashboard/vehicle/delete/${recordId}`)
        .then(function (response) {
          // console.log(response);
          loader.hide();
          if (response.data.message) {
            message.success(response.data.message);
            getvehicle();
          }
        })
        .catch(function (error) {
          console.log(error);
          message.error(error.response.data.message);
          loader.hide();
        });
    };
    const searchform = reactive({
      namesearch: "",
    });

    const search = () => {
      const loader = $loading.show({});
      const params = {
        namesearch: searchform.namesearch,
      };

      axios
        .get(`http://127.0.0.1:8000/api/dashboard/vehicle/search`, { params })
        .then((response) => {
        //   console.log(response);
          Vehicle.value = response.data.data.results;
          loader.hide();
        })
        .catch((error) => {
          console.error(error);
          loader.hide();
        });
    };

    return {
      open,
      modalTitle,
      columns,
      Vehicle,
      search,
      showModal,
      handleCancel,
      createVehicle,
      showEdit,
      update,
      deleteRecord,
      errors,
      checkform,
      ...toRefs(VehicleForm),
      ...toRefs(searchform),
    };
  },
  components: {
    Modal,
  },
});
</script>

<style></style>
