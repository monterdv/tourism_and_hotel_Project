<template>
  <div class="row">
    <div class="col-12 col-sm-6">
      <a-card style="width: 100%; height: 450px">
        <template #title>
          <div class="row">
            <div class="col-12 col-sm-12 align-items-center">
              tour booking information
            </div>
          </div>
        </template>
        <div class="row" v-if="booking">
          <p class="col-sm-3 fs-6 fw-bold">tour:</p>
          <p class="col-sm-9">{{ booking.tour ? booking.tour.title : "" }}</p>
          <p class="col-sm-3 fs-6 fw-bold">time:</p>
          <p class="col-sm-9">{{ booking.time ? booking.time.date : "" }}</p>
          <p class="col-sm-3 fs-6 fw-bold">pay:</p>
          <p class="col-sm-9">
            {{ booking.payments ? booking.payments.title : "" }}
          </p>
          <p class="col-sm-3 fs-6 fw-bold">status booking:</p>
          <p class="col-sm-9">{{ booking.status_booking }}</p>
          <p class="col-sm-3 fs-6 fw-bold">status payment:</p>
          <p class="col-sm-9">{{ booking.status_payment }}</p>
          <p class="col-sm-3 fs-6 fw-bold">slot:</p>
          <p class="col-sm-9">{{ booking.adults + booking.children }}</p>
          <p class="col-sm-3 fs-6 fw-bold">total price:</p>
          <p class="col-sm-9">{{ booking.total_price }}</p>
        </div>

        <div class="row">
          <div class="col-12 col-sm-12">
            <button
              v-if="booking.status_payment == 'unpaid'"
              type="button"
              class="btn btn-success me-2"
              @click="confirmBooking(booking.id)"
            >
              confirm
            </button>
            <button
              type="button"
              class="btn btn-success me-2"
              v-if="booking.status_payment != 'unpaid'"
            >
              confirmed
            </button>
            <button
              v-if="booking.status_payment == 'unpaid'"
              type="button"
              class="btn btn-danger"
              @click="abortBooking(booking.id)"
            >
              abort order
            </button>
          </div>
        </div>
      </a-card>
    </div>
    <div class="col-12 col-sm-6">
      <a-card style="width: 100%" class="mb-2">
        <template #title>Date of payment: {{ day }}</template></a-card
      >

      <a-card title="customer information" style="width: 100%; height: 390px">
        <img :src="user.avatar" :alt="user.name" width="250" class="border-1" />
        <div class="row">
          <p class="col-sm-2 fs-6 fw-bold">name:</p>
          <p class="col-sm-9">{{ user.name }}</p>
          <p class="col-sm-2 fs-6 fw-bold">email:</p>
          <p class="col-sm-9">{{ user.email }}</p>
          <p class="col-sm-2 fs-6 fw-bold">phone:</p>
          <p class="col-sm-9">{{ user.phone }}</p>
        </div>
      </a-card>
    </div>
    <div class="col-12 col-sm-12 mt-3">
      <a-card title="booking tour slot" style="width: 100%">
        <a-table :dataSource="slot" :columns="columns" :scroll="{ x: 576 }">
          <template #bodyCell="{ column, index }">
            <template v-if="column.key === 'index'">
              <span>{{ index + 1 }}</span>
            </template>
          </template>
        </a-table>

        <div class="row">
          <div class="col-12 col-sm-12 mt-2">
            <button type="button" class="btn btn-primary me-2">update</button>
            <button type="button" class="btn btn-success">
              <font-awesome-icon :icon="['fas', 'download']" class="me-2" />bill
            </button>
          </div>
        </div>
      </a-card>
    </div>
  </div>
</template>

<script>
import { ref, defineComponent, inject, reactive, toRefs } from "vue";
import { useMenu } from "../../../../store/menu";
import { useRouter, useRoute } from "vue-router";
import { message } from "ant-design-vue";
import dayjs from "dayjs";

export default defineComponent({
  setup() {
    const store = useMenu();
    store.onselectedkey(["booking-tour"]);
    const $loading = inject("$loading");
    const route = useRoute();
    const router = useRouter();

    const booking = ref([]);
    const slot = ref([]);
    const user = ref([]);
    const day = ref();

    const columns = [
      {
        title: "#",
        key: "index",
        width: 70,
      },
      {
        title: "name",
        dataIndex: "name",
        key: "name",
      },
      {
        title: "Email",
        dataIndex: "email",
        key: "email",
      },
      {
        title: "phone",
        dataIndex: "phone",
        key: "phone",
      },
      {
        title: "nationality",
        dataIndex: "nationality",
        key: "nationality",
      },
      {
        title: "passport",
        key: "passport",
        fixed: "passport",
      },
      {
        title: "type",
        dataIndex: "type",
        key: "type",
      },
    ];

    const getdetail = () => {
      const loader = $loading.show({});
      axios
        .get(
          `http://127.0.0.1:8000/api/dashboard/bookingtour/${route.params.code}/detail`
        )
        .then((response) => {
          // console.log(response);
          booking.value = response.data.data.booking;
          slot.value = response.data.data.slot;
          user.value = response.data.data.user;
          day.value = dayjs(response.data.data.booking.created_at, "YYYY-MM-DD");
          loader.hide();
        })
        .catch((error) => {
          console.error(error);
          loader.hide();
        });
    };

    const confirmBooking = (id) => {
      const loader = $loading.show({});
      axios
        .get(`http://127.0.0.1:8000/api/dashboard/bookingtour/confirm/${id}`)
        .then((response) => {
          // console.log(response);
          message.success(response.data.message);
          getdetail();
          loader.hide();
        })
        .catch((error) => {
          console.error(error);
          loader.hide();
        });
    };

    const abortBooking = (id) => {
      const loader = $loading.show({});
      axios
        .get(`http://127.0.0.1:8000/api/dashboard/bookingtour/abort/${id}`)
        .then((response) => {
          console.log(response);
          message.success(response.data.message);
          router.push({ name: "booking-tour" });
          loader.hide();
        })
        .catch((error) => {
          console.error(error);
          loader.hide();
        });
    };

    getdetail();
    return {
      columns,
      booking,
      slot,
      user,
      day,
      confirmBooking,
      abortBooking,
    };
  },
});
</script>

<style></style>
