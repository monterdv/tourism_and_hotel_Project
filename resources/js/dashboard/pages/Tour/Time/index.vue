<template>
  <a-card :title="tourName" style="width: 100%">
    <div class="row mb-4">
      <div class="col-12 d-flex justify-content-end me-2">
        <router-link
          :to="{
            name: 'tour-time-create',
            params: { slug: route.params.slug },
          }"
        >
          <a-button type="primary">
            <font-awesome-icon :icon="['fas', 'plus']" class="me-2" />
            <span>Create New</span>
          </a-button>
        </router-link>
      </div>
    </div>

    <form @submit.prevent="searchTime" enctype="multipart/form-data">
      <div class="row mb-4">
        <div class="col-12 col-sm-5">
          <label>
            <span>date</span>
          </label>
          <a-range-picker
            v-model:value="searchDate"
            format="DD-MM-YYYY"
            class="col-12 col-sm-12"
            :bordered="false"
          />
        </div>

        <div class="col-12 col-sm-3">
          <label>
            <span>status</span>
          </label>
          <a-select
            placeholder="status seclect"
            style="width: 100%"
            :options="statusOptions"
            v-model:value="searchStatus"
            allow-clear
          >
            <template #suffixIcon>
              <font-awesome-icon :icon="['fas', 'bookmark']" /> </template
          ></a-select>
        </div>

        <div class="col-12 col-sm-2 btn-sreach">
          <a-button
            type="primary"
            class="ml-2"
            htmlType="submit"
            style="padding: 0px 30px"
          >
            <span>sreach</span>
          </a-button>
        </div>
      </div>
    </form>

    <div class="row">
      <div class="col-12">
        <a-table :dataSource="Times" :columns="columns" :scroll="{ x: 576 }">
          <template #bodyCell="{ column, index, record }">
            <template v-if="column.key === 'index'">
              <span>{{ index + 1 }}</span>
            </template>

            <template v-if="column.key === 'images'">
              <a-image-preview-group>
                <a-image
                  v-for="(path, index) in record.paths"
                  :key="index"
                  :src="path"
                  class="img-fluid me-1 mb-1"
                  style="width: 100px"
                />
              </a-image-preview-group>
            </template>

            <!-- <template v-if="column.key === 'status'">
              <span v-if="record.status === 'available'" class="text-success">{{
                record.status
              }}</span>
              <span v-if="record.status === 'full'" class="text-primary">{{
                record.status
              }}</span>
              <span v-if="record.status === 'pause'" class="text-danger">{{
                record.status
              }}</span>
            </template> -->
            <template v-if="column.key === 'status'">
              <a-tag color="success" v-if="record.status === 'available'">{{
                record.status
              }}</a-tag>
              <a-tag color="processing" v-if="record.status === 'full'">{{
                record.status
              }}</a-tag>
              <a-tag color="error" v-if="record.status === 'pause'">{{
                record.status
              }}</a-tag>
            </template>

            <template v-if="column.key === 'action'">
              <router-link
                :to="{
                  name: 'tour-time-edit',
                  params: { slug: route.params.slug, id: record.id },
                }"
              >
                <a-button type="primary" class="me-1">
                  <font-awesome-icon :icon="['fas', 'pen-to-square']" />
                </a-button>
              </router-link>

              <a-button
                type="primary"
                danger
                class="mt-1"
                @click="deleteRecord(record.id)"
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
import { useMenu } from "../../../../store/menu";
import { ref, defineComponent, inject, reactive, toRefs } from "vue";
import { message } from "ant-design-vue";
import { useRouter, useRoute } from "vue-router";
import dayjs from "dayjs";

export default defineComponent({
  setup() {
    const store = useMenu();
    store.onselectedkey(["tour_list"]);

    const $loading = inject("$loading");

    const dateFormat = "DD-MM-YYYY";

    const name = ref("");

    const router = useRouter();
    const route = useRoute();

    const Times = ref([]);
    const columns = [
      {
        title: "#",
        key: "index",
        width: 90,
      },
      {
        title: "code",
        dataIndex: "Time_Code",
        key: "Time_Code",
      },
      {
        title: "slot",
        dataIndex: "slots_remaining",
        key: "slots_remaining",
      },
      {
        title: "slots booked",
        dataIndex: "slots_booked",
        key: "slots_booked",
      },
      {
        title: "price adults",
        dataIndex: "price_adults",
        key: "price_adults",
      },
      {
        title: "price children",
        dataIndex: "price_children",
        key: "price_children",
      },
      {
        title: "date",
        dataIndex: "date",
        key: "date",
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

    const search = reactive({
      searchDate: ref(),
      searchStatus: null,
    });

    const tourName = ref();

    const getTour = () => {
      const loader = $loading.show({});
      axios
        .get(`http://127.0.0.1:8000/api/dashboard/tour/${route.params.slug}/time`)
        .then(function (response) {
          // console.log(response);
          Times.value = response.data.data.timeTour;
          tourName.value = response.data.data.tour.title;
          loader.hide();
        })
        .catch(function (error) {
          console.error(error);
          loader.hide();

          if (error.response.status === 400) {
            message.error(error.response.data.message);
            router.push({ name: "tour" });
          }
        });
    };
    getTour();

    const deleteRecord = (recordId) => {
      const loader = $loading.show({});
      axios
        .post(
          `http://127.0.0.1:8000/api/dashboard/tour/${route.params.slug}/time/delete/${recordId}`
        )
        .then(function (response) {
          // console.log(response);

          if (response.data.message) {
            loader.hide();
            message.success(response.data.message);
            router.go();
          }
        })
        .catch(function (error) {
          console.log(error);
          loader.hide();
        });
    };

    const statusOptions = [
      {
        label: "Available",
        value: "available",
      },
      {
        label: "Full",
        value: "full",
      },
      {
        label: "Pause",
        value: "pause",
      },
    ];

    const searchTime = () => {
      const loader = $loading.show({});
      const formData = new FormData();
      if (search.searchDate) {
        formData.append("searchDate_start", search.searchDate[0].format("YYYY-MM-DD"));
        formData.append("searchDate_end", search.searchDate[1].format("YYYY-MM-DD"));
      }

      if (search.searchStatus) {
        formData.append("searchStatus", search.searchStatus);
      }
      axios
        .post(
          `http://127.0.0.1:8000/api/dashboard/tour/${route.params.slug}/time/search`,
          formData
        )
        .then(function (response) {
          // console.log(response);
          Times.value = response.data.data.timeTour;
          loader.hide();
        })
        .catch(function (error) {
          console.error(error);
          loader.hide();
        });
    };

    return {
      statusOptions,
      ...toRefs(search),
      searchTime,
      Times,
      columns,
      name,
      route,
      tourName,
      deleteRecord,
    };
  },
});
</script>
<style>
.col-12.col-sm-2.btn-sreach {
  margin-top: 22px;
}
</style>
