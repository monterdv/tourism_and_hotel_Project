<template>
  <a-card title="Place" style="width: 100%">
    <div class="row">
      <div class="col-12 d-flex justify-content-end me-2">
        <router-link :to="{ name: 'places-create' }">
          <a-button type="primary">
            <font-awesome-icon :icon="['fas', 'plus']" />
          </a-button>
        </router-link>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <a-table :dataSource="Places" :columns="columns" :scroll="{ x: 576 }">
          <template #bodyCell="{ column, index, record }">
            <template v-if="column.key === 'index'">
              <span>{{ index + 1 }}</span>
            </template>

            <template v-if="column.key === 'action'">
              <router-link :to="{ name: 'places-edit', params: { slug: record.slug } }">
                <a-button type="primary" htmlType="submit" class="me-2">
                  <font-awesome-icon :icon="['fas', 'pen-to-square']" />
                </a-button>
              </router-link>

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
import { useMenu } from "../../../store/menu";
import { ref, defineComponent, inject } from "vue";
import { message } from "ant-design-vue";
import { useRouter } from "vue-router";
export default defineComponent({
  setup() {
    const store = useMenu();
    store.onselectedkey(["places"]);

    const $loading = inject("$loading");

    const router = useRouter();

    const Places = ref([]);

    const columns = [
      {
        title: "#",
        key: "index",
        width: 70,
      },
      {
        title: "country",
        dataIndex: "country",
        key: "country",
      },
      {
        title: "area",
        dataIndex: "area",
        key: "area",
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
        .get("http://127.0.0.1:8000/api/dashboard/places")
        .then(function (response) {
          console.log(response);
          Places.value = response.data;
          loader.hide();
        })
        .catch(function (error) {
          console.error(error);
          loader.hide();
        });
    };
    getUser();

    const deleteRecord = (recordId) => {
      const loader = $loading.show({});
      axios
        .post(`http://127.0.0.1:8000/api/dashboard/places/delete/${recordId}`)
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
      columns,
      Places,
      deleteRecord,
    };
  },
});
</script>

<style></style>
