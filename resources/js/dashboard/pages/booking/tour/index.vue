<template>
  <a-card title="booking tour" style="width: 100%">
    <div class="row mb-4">
      <form @submit.prevent="searchHotel" enctype="multipart/form-data">
        <div class="row mb-4">
          <div class="col-12 col-sm-7">
            <a-input
              placeholder="input name customer"
              allow-clear
              v-model:value="searchName"
            >
            </a-input>
          </div>
          <div class="col-12 col-sm-3">
            <a-select
              show-search
              placeholder="stasus"
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
import { ref, defineComponent, inject, reactive, toRefs } from "vue";
import { useMenu } from "../../../../store/menu";
import { useRouter, useRoute } from "vue-router";

export default defineComponent({
  setup() {
    const store = useMenu();
    store.onselectedkey(["booking-tour"]);
    const $loading = inject("$loading");
    const bookingtour = ref([]);

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
          console.log(response);
          bookingtour.value = response.data.data.bookingtour;
          loader.hide();
        })
        .catch((error) => {
          console.error(error);
          loader.hide();
        });
    };

    getbooking();
    return {
      columns,
      bookingtour,
    };
  },
});
</script>

<style></style>
