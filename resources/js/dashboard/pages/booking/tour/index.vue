<template>
  <a-card title="booking tour" style="width: 100%">
    <div class="row mb-4">
      <form @submit.prevent="searchbooking" enctype="multipart/form-data">
        <div class="row mb-4">
          <div class="col-12 col-sm-2">
            <a-input placeholder="input code" allow-clear v-model:value="code"> </a-input>
          </div>
          <div class="col-12 col-sm-5">
            <a-select
              show-search
              placeholder="select tour"
              style="width: 100%"
              :options="tourOptions"
              v-model:value="tour"
              :filter-option="filtertour"
              allow-clear
            >
              <template #suffixIcon>
                <font-awesome-icon :icon="['fas', 'bookmark']" /> </template
            ></a-select>
          </div>
          <div class="col-12 col-sm-2">
            <a-select
              show-search
              placeholder="select date"
              style="width: 100%"
              :options="timeOptions"
              :filter-option="filtertime"
              v-model:value="date"
              allow-clear
            >
              <template #suffixIcon>
                <font-awesome-icon :icon="['fas', 'calendar-days']" />
              </template>
            </a-select>
          </div>
          <div class="col-12 col-sm-2">
            <a-select
              show-search
              placeholder="stasus"
              style="width: 100%"
              :options="statusOptions"
              v-model:value="status"
              :filter-option="filterstatus"
              allow-clear
            >
              <template #suffixIcon>
                <font-awesome-icon :icon="['fas', 'bookmark']" /> </template
            ></a-select>
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
    <a-table :dataSource="bookingtour" :columns="columns" :scroll="{ x: 1200 }">
      <!-- record -->
      <template #bodyCell="{ column, index, record }">
        <template v-if="column.key === 'index'">
          <span>{{ index + 1 }}</span>
        </template>
        <template v-if="column.key === 'tour'">
          <h6>
            {{ record.tour.title }}
          </h6>
          <div>date: {{ record.tour.duration }} day</div>
        </template>
        <template v-if="column.key === 'departure_date'">
          {{ record.time.date }}
        </template>
        <template v-if="column.key === 'status_payment'">
          <span v-if="record.status_payment == 'unpaid'" class="text-warning">{{
            record.status_payment
          }}</span>
          <span v-if="record.status_payment == 'paid'" class="text-success">{{
            record.status_payment
          }}</span>
        </template>
        <template v-if="column.key === 'status_booking'">
          <span v-if="record.status_booking == 'upcoming'" class="text-warning">{{
            record.status_booking
          }}</span>
          <span v-if="record.status_booking == 'in_progress'" class="text-primary">{{
            record.status_booking
          }}</span>
          <span v-if="record.status_booking == 'completed'" class="text-success">{{
            record.status_booking
          }}</span>
        </template>
        <template v-if="column.key === 'slot'">
          {{ record.adults }} adults and {{ record.children }} children
        </template>
        <template v-if="column.key === 'action'">
          <router-link
            :to="{ name: 'booking-detail-tour', params: { code: record.bookings_Code } }"
          >
            <a-button type="primary" htmlType="submit" class="me-2">
              <font-awesome-icon :icon="['fas', 'pen-to-square']" />
            </a-button>
          </router-link>
        </template>
      </template>
    </a-table>
  </a-card>
</template>

<script>
import { ref, defineComponent, inject, reactive, toRefs, watch } from "vue";
import { useMenu } from "../../../../store/menu";
import { useRouter, useRoute } from "vue-router";

export default defineComponent({
  setup() {
    const store = useMenu();
    store.onselectedkey(["booking-tour"]);
    const $loading = inject("$loading");
    const bookingtour = ref([]);
    const tourOptions = ref([]);
    const timeOptions = ref([]);
    const statusOptions = ref([]);

    const search = reactive({
      code: null,
      tour: null,
      date: null,
      status: null,
    });

    const columns = [
      {
        title: "#",
        key: "index",
        width: 60,
      },
      {
        title: "Code",
        dataIndex: "bookings_Code",
        key: "bookings_Code",
        width: 100,
      },
      {
        title: "tour",
        key: "tour",
      },
      {
        title: "departure date",
        key: "departure_date",
        width: 140,
      },
      {
        title: "slot",
        key: "slot",
        width: 140,
      },
      {
        title: "payment",
        key: "status_payment",
        width: 140,
      },
      {
        title: "status",
        key: "status_booking",
        width: 120,
      },

      {
        title: "action",
        key: "action",
        fixed: "right",
        width: 90,
      },
    ];

    const getbooking = () => {
      const loader = $loading.show({});
      axios
        .get(`http://127.0.0.1:8000/api/dashboard/bookingtour`)
        .then((response) => {
          // console.log(response);
          bookingtour.value = response.data.data.bookingtour;
          tourOptions.value = response.data.data.Tour;
          timeOptions.value = response.data.data.time;
          statusOptions.value = response.data.data.status;
          loader.hide();
        })
        .catch((error) => {
          console.error(error);
          loader.hide();
        });
    };

    const searchbooking = () => {
      const loader = $loading.show({});
      const params = {
        code: search.code,
        tour: search.tour,
        date: search.date,
        status: search.status,
      };

      axios
        .get(`http://127.0.0.1:8000/api/dashboard/bookingtour/search`, { params })
        .then((response) => {
          // console.log(response);
          bookingtour.value = response.data.data.results;
          loader.hide();
        })
        .catch((error) => {
          console.error(error);
          loader.hide();
        });
    };
    const filtertour = (input, tourOptions) => {
      return tourOptions.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
    };
    const filtertime = (input, timeOptions) => {
      return timeOptions.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
    };
    const filterstatus = (input, statusOptions) => {
      return statusOptions.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
    };

    const checkdate = (id) => {
      const loader = $loading.show({});
      axios
        .get(`http://127.0.0.1:8000/api/dashboard/bookingtour/checkdate/${id}`)
        .then((response) => {
          console.log(response);
          timeOptions.value = response.data.data.time;
          loader.hide();
        })
        .catch((error) => {
          console.error(error);
          loader.hide();
        });
    };

    watch(
      () => search.tour,
      (newSlug, oldSlug) => {
        if (newSlug !== oldSlug) {
          checkdate(search.tour);
        }
      }
    );

    getbooking();
    return {
      ...toRefs(search),
      columns,
      bookingtour,
      tourOptions,
      timeOptions,
      statusOptions,
      filterstatus,
      filtertour,
      filtertime,
      getbooking,
      searchbooking,
    };
  },
});
</script>

<style></style>
