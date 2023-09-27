<template>
  <form @submit.prevent="createWidget()" enctype="multipart/form-data" v-if="addnew">
    <a-card title="Create widget" style="width: 100%">
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
                placeholder="enter the widget name"
                allow-clear
                v-model:value="widget"
              />
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-12 col-sm-10 d-grid d-sm-flex justify-content-sm-end mx-auto">
              <a-button type="primary" htmlType="submit">
                <span>Save</span>
              </a-button>
            </div>
          </div>
        </div>
      </div>
    </a-card>
  </form>
  <a-card title="widget List" style="width: 100%" class="mt-3">
    <div class="row">
      <div class="col-12 d-flex col-sm-6 me-2">
        <form @submit.prevent="getWidget()" enctype="multipart/form-data" class="col-9">
          <a-input-search
            v-model:value="name"
            placeholder="input search"
            enter-button
            allow-clear
          />
        </form>
      </div>

      <div class="col-12 d-flex col-sm-5 justify-content-end me-2">
        <a-button type="primary" @click="toggleAddNew()">
          <font-awesome-icon :icon="['fas', 'plus']" />
        </a-button>
      </div>
    </div>
    <div class="row mt-5">
      <div class="col-12">
        <a-table :dataSource="NameWidget" :columns="columns" :scroll="{ x: 576 }">
          <template #bodyCell="{ column, index, record }">
            <template v-if="column.key === 'index'">
              <span>{{ index + 1 }}</span>
            </template>

            <template v-if="column.key === 'action'">
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
import axios from "axios";
import { useRouter } from "vue-router";
import { defineComponent, ref, inject } from "vue";
import { message } from "ant-design-vue";
import { useMenu } from "../../../../store/menu";

export default defineComponent({
  setup() {
    const store = useMenu();
    store.onselectedkey(["widget"]);

    const $loading = inject("$loading");

    const router = useRouter();

    const addnew = ref(false);
    const widget = ref("");
    const name = ref("");

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
        width: 100,
      },
    ];
    const NameWidget = ref([]);

    const getWidget = () => {
      const loader = $loading.show({});

      axios
        .get(`http://127.0.0.1:8000/api/dashboard/Hotel/widget?name=${name.value}`)
        .then(function (response) {
          // console.log(response);
          NameWidget.value = response.data.data;
          loader.hide();
        })
        .catch(function (error) {
          console.log(error);
          loader.hide();
        });
    };

    getWidget();

    const createWidget = () => {
      const loader = $loading.show({});
      axios
        .post("http://127.0.0.1:8000/api/dashboard/Hotel/widget/create", {
          name: widget.value,
        })
        .then(function (response) {
          // console.log(response);
          loader.hide();

          if (response.data.message) {
            message.success(response.data.message);
            router.go();
            addnew.value = false;
          }
        })
        .catch(function (error) {
          console.log(error);
          loader.hide();
          if (error) {
            message.error(error.response.data.message);
          }
        });
    };
    const toggleAddNew = () => {
      // this.addnew = !addnew;
      addnew.value = !addnew.value;
    };

    const deleteRecord = (recordId) => {
      const loader = $loading.show({});
      axios
        .post(`http://127.0.0.1:8000/api/dashboard/Hotel/widget/delete/${recordId}`)
        .then(function (response) {
          // console.log(response);
          loader.hide();
          if (response.data.message) {
            message.success(response.data.message);
            router.go();
          }
        })
        .catch(function (error) {
          // console.log(error);
          loader.hide();

          message.error(error.response.data.message);
        });
    };

    return {
      createWidget,
      widget,
      columns,
      NameWidget,
      addnew,
      toggleAddNew,
      router,
      name,
      getWidget,
      deleteRecord,
    };
  },
  methods: {},
});
</script>

<style></style>
