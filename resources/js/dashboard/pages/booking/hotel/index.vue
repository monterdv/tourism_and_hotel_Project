<template>
  <a-card title="booking Hotel" style="width: 100%" class="shadow">
    <a-table :dataSource="bookinghotel" :columns="columns" :scroll="{ x: 1300 }">
      <!-- record -->
      <template #bodyCell="{ column, index, record }">
        <template v-if="column.key === 'index'">
          <span>{{ index + 1 }}</span>
        </template>
        <template v-if="column.key === 'RoomNumber'">
          {{ record.number_room.number_of_rooms }}
        </template>
        <template v-if="column.key === 'status_booking'">
          <a-tag color="orange" v-if="record.status_booking === 'upcoming'">{{
            record.status_booking
          }}</a-tag>
          <a-tag color="#2db7f5" v-if="record.status_booking === 'in_progress'">
            in progress </a-tag
          ><a-tag color="green" v-if="record.status_booking === 'completed'">{{
            record.status_booking
          }}</a-tag>
        </template>
        <template v-if="column.key === 'action'">
          <router-link
            :to="{
              name: 'dashboard-booking-hotel-detail',
              params: { id: record.id },
            }"
          >
            <a-button type="primary" htmlType="submit" class="me-2">
              <font-awesome-icon :icon="['fas', 'eye']" />
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
    store.onselectedkey(["booking-hotel"]);
    const $loading = inject("$loading");
    const bookinghotel = ref([]);

    const columns = [
      {
        title: "#",
        key: "index",
        width: 60,
      },
      {
        title: "name",
        dataIndex: "name",
        key: "name",
      },
      {
        title: "email",
        dataIndex: "email",
        key: "email",
      },
      {
        title: "Phone Number",
        dataIndex: "phone_number",
        key: "phone_number",
      },
      {
        title: "Phone Number",
        dataIndex: "phone_number",
        key: "phone_number",
      },
      {
        title: "Room Number",
        key: "RoomNumber",
      },
      {
        title: "Check In",
        dataIndex: "start_date",
        key: "start_date",
      },
      {
        title: "Check Out",
        dataIndex: "end_date",
        key: "end_date",
      },
      {
        title: "price",
        dataIndex: "price",
        key: "price",
      },
      {
        title: "status_booking",
        key: "status_booking",
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
        .get(`http://127.0.0.1:8000/api/dashboard/bookinghotel`)
        .then((response) => {
          // console.log(response);
          bookinghotel.value = response.data.data.bookinghotel;
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
      bookinghotel,
    };
  },
});
</script>

<style></style>
