<template>
  <a-card title="bed Room management" style="width: 100%">
    <div class="row">
      <div class="col-12 d-flex justify-content-end me-2">
        <a-button type="primary" @click="showModal">
          <font-awesome-icon :icon="['fas', 'plus']" />
        </a-button>
      </div>
    </div>
    <div class="row mb-4">
      <div class="col-12 col-sm-6">
        <form @submit.prevent="searchbedtype()" enctype="multipart/form-data">
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
          @submit.prevent="checkform ? createbed() : update(ID)"
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
                <div class="col-12 col-sm-9 text-start text-sm-start">
                  <a-input
                    placeholder="enter bed name"
                    allow-clear
                    v-model:value="name"
                  />
                  <div class="w-100"></div>
                  <small v-if="errors.name" class="text-danger">{{
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

    <div class="row mt-5">
      <div class="col-12">
        <a-table :dataSource="bedtypedata" :columns="columns" :scroll="{ x: 576 }">
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
      </div>
    </div>
  </a-card>
</template>

<script>
import { ref, defineComponent, inject, reactive, toRefs } from "vue";
import { useRouter, useRoute } from "vue-router";
import { message, Modal } from "ant-design-vue";
import { useMenu } from "../../../../store/menu";

export default defineComponent({
  setup() {
    const store = useMenu();
    store.onselectedkey(["bed"]);
    const $loading = inject("$loading");
    const router = useRouter();
    const bedtypedata = ref([]);

    const errors = ref({});
    const open = ref(false);
    const checkform = ref(true);
    const modalTitle = ref("Create bed type");

    const form = reactive({
      ID: "",
      name: "",
    });

    const showModal = () => {
      open.value = true;
      form.name = "";
      modalTitle.value = "Create bed type";
      checkform.value = true;
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

    const getbed = () => {
      const loader = $loading.show({});
      axios
        .get(`http://127.0.0.1:8000/api/dashboard/Hotel/bedtype`)
        .then(function (response) {
          // console.log(response);
          bedtypedata.value = response.data.data.bed_type;
          loader.hide();
        })
        .catch(function (error) {
          console.log(error);
          loader.hide();
        });
    };

    const showEdit = (record) => {
      showModal();
      modalTitle.value = "Edit bed type";
      getEdit(record);
      checkform.value = false;
      errors.value = {};
    };

    const createbed = () => {
      const loader = $loading.show({});
      const formData = new FormData();
      formData.append("name", form.name);
      axios
        .post("http://127.0.0.1:8000/api/dashboard/Hotel/bedtype/create", formData)
        .then(function (response) {
          // console.log(response);
          loader.hide();
          if (response.data.message) {
            getbed();
            message.success(response.data.message);
            form.name = "";
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
        .get(`http://127.0.0.1:8000/api/dashboard/Hotel/bedtype/${record}/edit`)
        .then(function (response) {
          // console.log(response);
          form.ID = response.data.data.bed_type.id;
          form.name = response.data.data.bed_type.name;
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
      formData.append("name", form.name);
      axios
        .post(
          `http://127.0.0.1:8000/api/dashboard/Hotel/bedtype/${record}/edit`,
          formData
        )

        .then(function (response) {
          // console.log(response);
          loader.hide();
          if (response.data.message) {
            getbed();
            message.success(response.data.message);
            form.name = "";
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
        .post(`http://127.0.0.1:8000/api/dashboard/Hotel/bedtype/delete/${recordId}`)
        .then(function (response) {
          // console.log(response);
          loader.hide();
          if (response.data.message) {
            message.success(response.data.message);
            getbed();
          }
        })
        .catch(function (error) {
          console.log(error);
          message.error(error.response.data.message);
          loader.hide();
        });
    };
    const search = reactive({
      searchName: "",
    });

    const searchbedtype = () => {
      const loader = $loading.show({});
      const params = {
        name: search.searchName,
      };

      axios
        .get(`http://127.0.0.1:8000/api/dashboard/Hotel/bedtype/search`, { params })
        .then((response) => {
          // console.log(response);
          bedtypedata.value = response.data.data.results;
          loader.hide();
        })
        .catch((error) => {
          console.error(error);
          loader.hide();
        });
    };

    getbed();
    return {
      columns,
      open,
      errors,
      checkform,
      modalTitle,
      bedtypedata,
      searchbedtype,
      ...toRefs(form),
      ...toRefs(search),
      deleteRecord,
      showModal,
      handleCancel,
      createbed,
      showEdit,
      update,
    };
  },
  components: {
    Modal,
  },
});
</script>

<style></style>
