<template>
  <a-card title="Tour List" style="width: 100%">
    <div class="row mb-4">
      <div class="col-12 d-flex col-sm-6 me-2 mb-4">
        <form @submit.prevent="getHotel()" enctype="multipart/form-data" class="col-9">
          <a-input-search
            v-model:value="name"
            placeholder="input search"
            enter-button
            allow-clear
          />
        </form>
      </div>

      <!-- <div class="col-12 d-flex col-sm-5 justify-content-end me-2">
        <router-link :to="{ name: 'tour-create' }">
          <a-button type="primary">
            <font-awesome-icon :icon="['fas', 'plus']" class="me-2" />
            <span>Create New Tour</span>
          </a-button>
        </router-link>
      </div> -->
    </div>

    <div class="row">
      <div class="col-12">
        <a-table :dataSource="tours" :columns="columns" :scroll="{ x: 576 }">
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

            <template v-if="column.key === 'images'">
              <a-image
                :preview="{ visible: false }"
                :width="110"
                :src="record.paths[0]"
                @click="visibleStates[record.id] = true"
              />
              <div style="display: none">
                <a-image-preview-group
                  :preview="{
                    visible: visibleStates[record.id] || false,
                    onVisibleChange: (vis) => (visibleStates[record.id] = vis),
                  }"
                >
                  <a-image
                    v-for="(path, index) in record.paths"
                    :key="index"
                    :src="path"
                    class="img-fluid me-1 mb-1"
                    style="width: 100px; height: 70px"
                  />
                </a-image-preview-group>
              </div>
            </template>

            <template v-if="column.key === 'status'">
              <span v-if="record.status === 'active'" class="text-success">{{
                record.status
              }}</span>
              <span v-if="record.status === 'pending'" class="text-primary">{{
                record.status
              }}</span>
              <span v-if="record.status === 'inactive'" class="text-danger">{{
                record.status
              }}</span>
            </template>

            <template v-if="column.key === 'action'">
              <router-link :to="{ name: 'tour-time', params: { slug: record.slug } }">
                <a-button type="primary" class="me-2">
                  <font-awesome-icon :icon="['fas', 'list']" class="me-1" />
                  <span>tour time</span>
                </a-button>
              </router-link>

              <router-link :to="{ name: 'tour-edit', params: { slug: record.slug } }">
                <a-button type="primary" class="me-2 mt-2">
                  <font-awesome-icon :icon="['fas', 'pen-to-square']" />
                </a-button>
              </router-link>

              <a-button type="primary" danger class="mt-2"
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
import { ref, defineComponent, inject } from "vue";
import { message, Descriptions } from "ant-design-vue";
import { useRouter, useRoute } from "vue-router";

export default defineComponent({
  setup() {
    const store = useMenu();
    store.onselectedkey(["tour_list"]);

    const $loading = inject("$loading");

    const name = ref("");

    const router = useRouter();

    const tours = ref([]);
    const columns = [
      {
        title: "tour Code",
        dataIndex: "tour_Code",
        key: "tour_Code",
        width: 100,
      },
      {
        title: "Images",
        dataIndex: "paths",
        key: "images",
      },
      {
        title: "Name Tour",
        dataIndex: "title",
        key: "title",
      },
      {
        title: "status",
        dataIndex: "status",
        key: "status",
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
        .get(`http://127.0.0.1:8000/api/dashboard/tour`)
        .then(function (response) {
          // console.log(response);
          tours.value = response.data.data.tours;
          loader.hide();
        })
        .catch(function (error) {
          console.error(error);
          loader.hide();
        });
    };
    getTour();

    const deleteRecord = (recordId) => {
      const loader = $loading.show({});
      axios
        .post(`http://127.0.0.1:8000/api/Hotel/delete/${recordId}`)
        .then(function (response) {
          // console.log(response);
          loader.hide();
          if (response.data.message) {
            message.success(response.data.message);
            router.go();
          }
          // this.$router.go();
        })
        .catch(function (error) {
          console.log(error);
          loader.hide();
        });
    };

    const visible = ref(false);
    const visibleStates = ref({});

    return {
      tours,
      columns,
      name,
      getTour,
      deleteRecord,
      visible,
      visibleStates,
    };
  },
  components: {
    Descriptions,
  },
});
</script>
