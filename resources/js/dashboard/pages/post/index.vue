<template>
  <a-card title="post List management" style="width: 100%">
    <form @submit.prevent="searchpost" enctype="multipart/form-data" class="shadow">
      <div class="row mb-4">
        <div class="col-12 col-sm-4">
          <label>
            <span>search name</span>
          </label>
          <a-input placeholder="input name tour" allow-clear v-model:value="searchName">
            <template #prefix>
              <font-awesome-icon :icon="['fas', 'file-signature']" />
            </template>
          </a-input>
        </div>

        <div class="col-12 col-sm-3">
          <label>
            <span>place</span>
          </label>
          <a-select
            show-search
            placeholder="place seclect"
            style="width: 100%"
            :options="Places"
            :filter-option="filterplace"
            v-model:value="searchPlace_id"
            allow-clear
          >
            <template #suffixIcon>
              <font-awesome-icon :icon="['fas', 'location-dot']" /> </template
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
        <a-table :dataSource="posts" :columns="columns" :scroll="{ x: 576 }">
          <template #expandedRowRender="{ record }">
            <Descriptions title="introduce">
              <Descriptions>
                <div v-html="record.introduce"></div>
              </Descriptions>
            </Descriptions>
          </template>

          <template #bodyCell="{ column, index, record }">
            <template v-if="column.key === 'index'">
              <span>{{ index + 1 }}</span>
            </template>

            <template v-if="column.key === 'image'">
              <Image :src="record.image" :alt="record.title" width="120px" />
            </template>

            <template v-if="column.key === 'action'">
              <router-link :to="{ name: 'posts-edit', params: { slug: record.slug } }">
                <a-button type="primary" class="me-2 mt-2">
                  <font-awesome-icon :icon="['fas', 'pen-to-square']" />
                </a-button>
              </router-link>

              <a-button
                type="primary"
                danger
                class="mt-2"
                @click="deleteRecord(record.id)"
                ><font-awesome-icon :icon="['fas', 'trash']"
              /></a-button>
              <!-- @click="deleteRecord(record.id)" -->
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
import { message, Descriptions, Image } from "ant-design-vue";
import { useRouter, useRoute } from "vue-router";

export default defineComponent({
  setup() {
    const store = useMenu();
    store.onselectedkey(["post_list"]);

    const $loading = inject("$loading");

    const search = reactive({
      searchName: "",
      searchPlace_id: null,
    });

    const visible = ref(false);
    const visibleStates = ref({});
    const Places = ref([]);
    const posts = ref([]);
    const router = useRouter();

    const columns = [
      {
        title: "Images",
        dataIndex: "image",
        key: "image",
      },
      {
        title: "Name",
        dataIndex: "title",
        key: "title",
      },
      {
        title: "place",
        dataIndex: "PlacesName",
        key: "PlacesName",
      },
      {
        title: "action",
        key: "action",
        fixed: "right",
      },
    ];

    const getTour = () => {
      const loader = $loading.show({});
      axios
        .get(`http://127.0.0.1:8000/api/dashboard/post`)
        .then(function (response) {
          // console.log(response);
          posts.value = response.data.posts;
          Places.value = response.data.Places;
          loader.hide();
        })
        .catch(function (error) {
          console.error(error);
          loader.hide();
        });
    };

    const deleteRecord = (recordId) => {
      const loader = $loading.show({});
      axios
        .post(`http://127.0.0.1:8000/api/dashboard/post/delete/${recordId}`)
        .then(function (response) {
          // console.log(response);
          loader.hide();
          if (response.data.message) {
            message.success(response.data.message);
            router.go();
          }
        })
        .catch(function (error) {
          console.log(error);
          if (error.response.status === 400) {
            message.error(error.response.data.message);
          } else {
            message.error(error.response.data.message);
          }
          loader.hide();
        });
    };

    const filterplace = (input, Places) => {
      return Places.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
    };

    getTour();

    const searchpost = () => {
      const loader = $loading.show({});
      const formData = new FormData();
      if (search.searchName) {
        formData.append("searchName", search.searchName);
      }
      if (search.searchPlace_id) {
        formData.append("searchPlace_id", search.searchPlace_id);
      }
      axios
        .post("http://127.0.0.1:8000/api/dashboard/post/search", formData)
        .then(function (response) {
          // console.log(response);
          posts.value = response.data.posts;
          loader.hide();
        })
        .catch(function (error) {
          console.error(error);
          loader.hide();
        });
    };

    return {
      posts,
      Places,
      ...toRefs(search),
      filterplace,
      searchpost,
      columns,
      getTour,
      deleteRecord,
      visible,
      visibleStates,
    };
  },
  components: {
    Descriptions,
    Image,
  },
});
</script>
<style>
.btn-search {
  margin: 22px 0px 0px 0px;
}
</style>
