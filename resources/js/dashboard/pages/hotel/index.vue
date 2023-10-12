<template>
  <a-card title="Hotel List" style="width: 100%">
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
        <router-link :to="{ name: 'hotel-create' }">
          <a-button type="primary">
            <font-awesome-icon :icon="['fas', 'plus']" class="me-2" />
            <span>Create New Hotel</span>
          </a-button>
        </router-link>
      </div> -->
    </div>
    <div class="row">
      <div class="col-12">
        <a-table :dataSource="Hotel" :columns="columns" :scroll="{ x: 576 }">
          <template #expandedRowRender="{ record }">
            <Descriptions title="information about the hotel">
              <Descriptions label="Name">{{ record.title }}</Descriptions>
              <Descriptions label="star">
                <div class="align-content-center d-flex justify-content-center mb-2">
                  <Rate v-model:value="record.star_rating" disabled />
                </div>
              </Descriptions>
              <Descriptions label="address">{{ record.address }}</Descriptions>
              <Descriptions label="place">{{ record.place.country }}</Descriptions>

              <Descriptions label="check in">{{ record.checkin_time }}</Descriptions>
              <Descriptions label="check out">{{ record.checkout_time }}</Descriptions>
              <Descriptions label="introduce">
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
                    v-for="(img, index) in record.paths"
                    :key="index"
                    :src="img"
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
              <router-link :to="{ name: 'hotel-edit', params: { slug: record.slug } }">
                <a-button type="primary" class="me-1 mt-1">
                  <font-awesome-icon :icon="['fas', 'pen-to-square']" />
                </a-button>
              </router-link>

              <a-button
                type="primary"
                danger
                @click="deleteRecord(record.id)"
                class="mt-1"
                ><font-awesome-icon :icon="['fas', 'trash']"
              /></a-button>
              <router-link :to="{ name: 'hotel-room', params: { slug: record.slug } }">
                <a-button type="primary" class="me-1 mt-1">
                  <font-awesome-icon :icon="['fas', 'list']" class="me-3" />
                  <span>Room</span>
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
import { ref, defineComponent, inject } from "vue";
import { message, Rate, Descriptions } from "ant-design-vue";
import { useRouter, useRoute } from "vue-router";

export default defineComponent({
  setup() {
    const store = useMenu();
    store.onselectedkey(["Hotel"]);

    const $loading = inject("$loading");

    const name = ref("");

    const router = useRouter();

    const Hotel = ref([]);
    const columns = [
      {
        title: "#",
        key: "index",
        width: 70,
      },
      {
        title: "Hotel name",
        dataIndex: "title",
        key: "title",
      },
      {
        title: "Images",
        dataIndex: "paths",
        key: "images",
      },
      {
        title: "address",
        dataIndex: "address",
        key: "address",
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
        width: 160,
      },
    ];

    const getHotel = () => {
      const loader = $loading.show({});
      axios
        .get(`http://127.0.0.1:8000/api/dashboard/Hotel?name=${name.value}`)
        .then(function (response) {
          console.log(response);
          Hotel.value = response.data.data.hotels;
          loader.hide();
        })
        .catch(function (error) {
          console.error(error);
          loader.hide();
        });
    };
    getHotel();

    const deleteRecord = (recordId) => {
      const loader = $loading.show({});
      axios
        .post(`http://127.0.0.1:8000/api/dashboard/Hotel/delete/${recordId}`)
        .then(function (response) {
          // console.log(response);
          if (response.data.message) {
            message.success(response.data.message);
            router.go();
            loader.hide();
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
      Hotel,
      columns,
      name,
      getHotel,
      deleteRecord,
      visible,
      visibleStates,
    };
  },
  components: {
    Rate,
    Descriptions,
  },
});
</script>
