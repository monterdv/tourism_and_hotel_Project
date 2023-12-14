<template>
  <div class="row">
    <div class="col-12 col-sm-6">
      <a-card style="width: 100%">
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
            <button type="button" class="btn btn-success me-2">confirm</button>
            <button type="button" class="btn btn-danger">abort order</button>
          </div>
        </div>
      </a-card>
    </div>
    <div class="col-12 col-sm-6">
      <a-card style="width: 100%" class="mb-2">
        <template #title>day tour: {{ booking.updated_at }}</template></a-card
      >

      <a-card title="customer information" style="width: 100%">
        <img :src="user.avatar" alt="" width="250" class="border-1" />
        <p>name: {{ user.name }}</p>
        <p>email: {{ user.email }}</p>
        <p>phone: {{ user.phone }}</p>
      </a-card>
    </div>
    <div class="col-12 col-sm-12 mt-3">
      <a-card title="booking tour slot" style="width: 100%">
        <a-table :columns="columns" :scroll="{ x: 576 }"> </a-table>
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

export default defineComponent({
  setup() {
    const store = useMenu();
    store.onselectedkey(["booking-tour"]);
    const $loading = inject("$loading");
    const route = useRoute();

    const booking = ref([]);
    const slot = ref([]);
    const user = ref([]);

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
        dataIndex: "Email",
        key: "Email",
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
          console.log(response);
          booking.value = response.data.data.booking;
          slot.value = response.data.data.slot;
          user.value = response.data.data.user;
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
    };
  },
});
</script>

<style></style>
