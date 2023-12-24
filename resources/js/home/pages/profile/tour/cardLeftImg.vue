<template>
  <div class="card mb-1 border-0 shadow hover-effect" v-if="item">
    <div class="row g-0 p-3 align-items-center ml-1">
      <div class="col-12 col-sm-12" style="margin-left: 10px">
        <h5 class="card-title">{{ item.tour.title }}</h5>
      </div>
      <div class="col-md-4">
        <img
          :src="item.img"
          class="rounded-start w-100"
          style="object-fit: cover; margin-left: 5px"
        />
      </div>
      <div class="col-md-6">
        <div class="card-body">
          <p class="card-text">
            <font-awesome-icon :icon="['fas', 'barcode']" /> code:
            {{ item.bookings_Code }}
          </p>
          <p class="card-text">
            <font-awesome-icon :icon="['far', 'calendar']" /> departure day:
            {{ item.time.date }}
          </p>
          <p class="card-text">
            <font-awesome-icon :icon="['far', 'clock']" /> time:
            {{ item.tour.duration }} day
          </p>
          <p class="card-text">
            <font-awesome-icon :icon="['fas', 'user']" /> adult slot: {{ item.adults }}
          </p>
          <p class="card-text">
            <font-awesome-icon :icon="['fas', 'child-reaching']" /> children slot:
            {{ item.children }}
          </p>
          <p class="card-text">
            <font-awesome-icon :icon="['far', 'credit-card']" /> payment methods:
            {{ item.payments.title }}
          </p>

          <p class="card-text">
            <font-awesome-icon :icon="['fas', 'money-bill']" /> total amount:
            {{ item.total_price }}USD
          </p>
        </div>
      </div>
      <!-- <div class="col-md-2" style="display: flex">
        <div
          :class="{
            'unpaid-color': item.status_payment === 'unpaid',
            'paid-color': item.status_payment === 'paid',
          }"
        >
          <p class="total-font">{{ item.status_payment }}</p>
          <div @click="deleteItem(item.id)">
            <font-awesome-icon :icon="['fas', 'trash']" style="color: red" />
          </div>
        </div>
      </div> -->

      <div
        class="col-md-2"
        style="display: flex"
        :class="{
          'unpaid-color': item.status_payment === 'unpaid',
          'paid-color': item.status_payment === 'paid',
        }"
      >
        <p class="total-font me-2">{{ item.status_payment }}</p>
        <div
          v-if="item.status_payment === 'unpaid' && item.status_booking === 'upcoming'"
        >
          <div @click="deleteItem()">
            <font-awesome-icon :icon="['fas', 'trash']" style="color: red" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent, ref, reactive, toRefs, inject } from "vue";
import { message } from "ant-design-vue";
export default defineComponent({
  props: {
    item: {
      type: Object,
      default: null,
    },
    getbookingtour: {
      type: Function,
      default: null,
    },
  },
  setup(props) {
    const $loading = inject("$loading");

    const deleteItem = () => {
      const loader = $loading.show({});
      axios
        .post(`http://127.0.0.1:8000/api/profile/deletetour/${props.item.id}`)
        .then(function (response) {
          // console.log(response);
          message.success(response.data.message);
          props.getbookingtour();
          loader.hide();
        })
        .catch(function (error) {
          console.error(error);
          loader.hide();
        });
    };

    return { deleteItem };
  },
});
</script>

<style>
.hover-effect:hover {
  box-shadow: 0 0 10px rgba(138, 185, 207, 0.5);
  background-color: rgba(77, 161, 138, 0.7);
}
.unpaid-color {
  color: red;
}
.paid-color {
  color: #e99840;
}
</style>
