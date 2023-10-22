<template>
  <a-card :title="`Hotel ` + hotelName + ' Room List'" style="width: 100%">
    <div class="row mb-4">
      <div class="col-12 d-flex col-sm-12 justify-content-end me-2">
        <router-link
          :to="{
            name: 'hotel-room-create',
            params: { slug: route.params.slug },
          }"
        >
          <a-button type="primary">
            <font-awesome-icon :icon="['fas', 'plus']" class="me-2" />
            <span>Create Room</span>
          </a-button>
        </router-link>
      </div>
    </div>
    <form @submit.prevent="searchRoom" enctype="multipart/form-data">
      <div class="row mb-4">
        <div class="col-12 col-sm-4">
          <label>
            <span>search name room</span>
          </label>
          <a-input placeholder="input name room" allow-clear v-model:value="searchName">
            <template #prefix>
              <font-awesome-icon :icon="['fas', 'location-dot']" />
            </template>
          </a-input>
        </div>

        <div class="col-12 col-sm-3">
          <label>
            <span>status</span>
          </label>
          <a-select
            show-search
            placeholder="place seclect"
            style="width: 100%"
            :options="statusOptions"
            v-model:value="searchStatus"
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
        <a-table :dataSource="rooms" :columns="columns" :scroll="{ x: 576 }">
          <template #bodyCell="{ column, index, record }">
            <template v-if="column.key === 'index'">
              <span>{{ index + 1 }}</span>
            </template>

            <template v-if="column.key === 'image'">
              <!-- <a-avatar shape="square" :size="100">
                <template #icon>
                  <img :src="record.image" alt="Room" />
                </template>
              </a-avatar> -->
              <Image :src="record.image" alt="Room" />
            </template>
            <template v-if="column.key === 'people'">
              <p>{{ record.max_adults }} adults and {{ record.max_children }} children</p>
            </template>
            <template v-if="column.key === 'status'">
              <span v-if="record.status === 'available'" class="text-success">{{
                record.status
              }}</span>
              <span v-if="record.status === 'occupied'" class="text-info">{{
                record.status
              }}</span>
              <span v-if="record.status === 'reserved'" class="text-warning">{{
                record.status
              }}</span
              ><span v-if="record.status === 'maintenance'" class="text-danger">{{
                record.status
              }}</span>
            </template>

            <template v-if="column.key === 'action'">
              <router-link
                :to="{
                  name: 'hotel-room-edit',
                  params: { slug: route.params.slug, slugRoom: record.slug },
                }"
              >
                <a-button type="primary" class="me-2">
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
            </template>
          </template>
        </a-table>
      </div>
    </div>
  </a-card>
</template>

<script>
import { ref, defineComponent, inject, reactive, toRefs } from "vue";
import { useMenu } from "../../../../store/menu";
import { useRouter, useRoute } from "vue-router";
import { message, Image } from "ant-design-vue";

export default defineComponent({
  setup() {
    const route = useRoute();
    const router = useRouter();

    const $loading = inject("$loading");

    const store = useMenu();
    store.onselectedkey(["Hotel"]);

    const rooms = ref([]);

    const columns = [
      {
        title: "#",
        key: "index",
        width: 70,
      },
      {
        title: "image",
        dataIndex: "image",
        key: "image",
      },
      {
        title: "Room Count",
        dataIndex: "room_count",
        key: "room_count",
      },
      {
        title: "Room name",
        dataIndex: "name",
        key: "name",
      },
      {
        title: "Base Price",
        dataIndex: "base_price",
        key: "base_price",
      },
      {
        title: "Max people",
        key: "people",
      },
      {
        title: "Status",
        dataIndex: "status",
        key: "status",
      },

      {
        title: "Action",
        key: "action",
        fixed: "right",
      },
    ];

    const statusOptions = [
      {
        label: "Available",
        value: "available",
      },
      {
        label: "Occupied",
        value: "occupied",
      },
      {
        label: "Reserved",
        value: "reserved",
      },
      {
        label: "Maintenance",
        value: "maintenance",
      },
    ];

    const search = reactive({
      searchName: "",
      searchStatus: null,
    });

    const filteroption = (input, option) => {
      return option.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
    };

    const hotelName = ref("");

    const getRoom = () => {
      const loader = $loading.show({});
      axios
        .get(`http://127.0.0.1:8000/api/dashboard/Hotel/${route.params.slug}/room`)
        .then(function (response) {
          // console.log(response);
          rooms.value = response.data.data.rooms;
          hotelName.value = response.data.data.hotel.title;
          loader.hide();
        })
        .catch(function (error) {
          // console.error(error);
          if (error.response.status === 404) {
            message.error(error.response.data.message);
            // router.push({ name: "404" });
            router.push({ name: "hotel" });
          }
          loader.hide();
        });
    };
    getRoom();

    const deleteRecord = (recordId) => {
      const loader = $loading.show({});
      axios
        .post(
          `http://127.0.0.1:8000/api/dashboard/Hotel/${route.params.slug}/room/delete/${recordId}`
        )
        .then(function (response) {
          // console.log(response);
          loader.hide();
          if (response.data.error) {
            message.error(response.data.error);
            message.error(response.data.errors);
          }
          if (response.data.message) {
            message.success(response.data.message);
          }
          router.go();
        })
        .catch(function (error) {
          console.log(error);
          loader.hide();
        });
    };

    const searchRoom = () => {
      const loader = $loading.show({});
      const formData = new FormData();
      if (search.searchName) {
        formData.append("searchName", search.searchName);
      }
      if (search.searchStatus) {
        formData.append("searchStatus", search.searchStatus);
      }
      axios
        .post(`http://127.0.0.1:8000/api/dashboard/Hotel/${route.params.slug}/room/search`, formData)
        .then(function (response) {
          // console.log(response);
          rooms.value = response.data.data.rooms;
          loader.hide();
        })
        .catch(function (error) {
          console.error(error);
          loader.hide();
        });
    };

    return {
      columns,
      rooms,
      route,
      deleteRecord,
      hotelName,
      statusOptions,
      filteroption,
      ...toRefs(search),
      searchRoom,
    };
  },
  components: { Image },
});
</script>
<style>
.btn-search {
  margin: 22px 0px 0px 0px;
}
</style>
