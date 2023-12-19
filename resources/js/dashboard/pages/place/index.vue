<template>
  <a-card title="Places management" style="width: 100%" class="shadow">
    <div class="row">
      <div class="col-12 d-flex justify-content-end me-2">
        <router-link :to="{ name: 'places-create' }">
          <a-button type="primary">
            <font-awesome-icon :icon="['fas', 'plus']" />
          </a-button>
        </router-link>
      </div>
    </div>

    <form @submit.prevent="searchPlaces" enctype="multipart/form-data">
      <div class="row mb-4">
        <div class="col-12 col-sm-4">
          <label>
            <span>search name</span>
          </label>
          <a-input placeholder="input country" allow-clear v-model:value="searchName">
            <template #prefix>
              <font-awesome-icon :icon="['fas', 'location-dot']" />
            </template>
          </a-input>
        </div>

        <div class="col-12 col-sm-3">
          <label>
            <span>area</span>
          </label>
          <a-select
            placeholder="status seclect"
            style="width: 100%"
            :options="area"
            v-model:value="searchArea"
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
        <a-table :dataSource="Places" :columns="columns" :scroll="{ x: 576 }">
          <template #bodyCell="{ column, index, record }">
            <template v-if="column.key === 'index'">
              <span>{{ index + 1 }}</span>
            </template>

            <template v-if="column.key === 'image'">
              <Image :src="record.image" :alt="record.country" width="150px" />
            </template>

            <template v-if="column.key === 'prominent'">
              <div class="form-check form-switch">
                <input
                  class="form-check-input fs-5"
                  type="checkbox"
                  role="switch"
                  @click="prominentRecord(record.id)"
                  :id="record.prominent"
                  :checked="record.prominent === 1"
                />
              </div>
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
import { ref, defineComponent, inject, reactive, toRefs } from "vue";
import { message, Image, Switch } from "ant-design-vue";
import { useRouter } from "vue-router";
export default defineComponent({
  setup() {
    const store = useMenu();
    store.onselectedkey(["places"]);

    const $loading = inject("$loading");

    const router = useRouter();

    const Places = ref([]);
    const area = ref([]);
    const checked = ref(true);

    const columns = [
      {
        title: "#",
        key: "index",
        width: 70,
      },
      {
        title: "image background",
        dataIndex: "image",
        key: "image",
      },
      {
        title: "country",
        dataIndex: "country",
        key: "country",
      },
      {
        title: "area",
        dataIndex: "areaName",
        key: "areaName",
      },
      {
        title: "prominent",
        key: "prominent",
      },
      {
        title: "action",
        key: "action",
        fixed: "right",
      },
    ];

    const getPlaces = () => {
      const loader = $loading.show({});
      axios
        .get("http://127.0.0.1:8000/api/dashboard/places")
        .then(function (response) {
          // console.log(response);
          Places.value = response.data.Places;
          area.value = response.data.area;
          loader.hide();
        })
        .catch(function (error) {
          console.error(error);
          loader.hide();
        });
    };
    getPlaces();

    const deleteRecord = (recordId) => {
      const loader = $loading.show({});
      axios
        .post(`http://127.0.0.1:8000/api/dashboard/places/delete/${recordId}`)
        .then(function (response) {
          // console.log(response);
          loader.hide();
          if (response.data.message) {
            message.success(response.data.message);
            getPlaces();
          }
        })
        .catch(function (error) {
          // console.log(error);
          loader.hide();
          message.error(error.response.data.message);
        });
    };

    const search = reactive({
      searchName: null,
      searchArea: null,
    });

    const searchPlaces = () => {
      const loader = $loading.show({});
      const formData = new FormData();
      if (search.searchName) {
        formData.append("searchName", search.searchName);
      }
      if (search.searchArea) {
        formData.append("searchArea", search.searchArea);
      }
      axios
        .post("http://127.0.0.1:8000/api/dashboard/places/search", formData)
        .then(function (response) {
          // console.log(response);
          Places.value = response.data.Places;
          loader.hide();
        })
        .catch(function (error) {
          console.error(error);
          loader.hide();
        });
    };

    const prominentRecord = (recordId) => {
      const loader = $loading.show({});
      axios
        .get(`http://127.0.0.1:8000/api/dashboard/places/prominent/${recordId}`)
        .then(function (response) {
          console.log(response);
          loader.hide();
          // if (response.data.message) {
          message.success(response.data.message);
          //   getPlaces();
          // }
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
      checked,
      deleteRecord,
      prominentRecord,
      ...toRefs(search),
      searchPlaces,
      area,
    };
  },
  components: { Image, Switch },
});
</script>

<style>
.btn-search {
  margin: 22px 0px 0px 0px;
}
</style>
