<template>
  <div class="container cart">
    <div class="grid wide">
      <div>
        <Steps :current="3" label-placement="vertical" :items="items" />
      </div>
      <div class="row">
        <div class="col-12 col-sm-8">
          <p class="combo__hot">Select a payment method:</p>
          <div class="card fs-4 mb-2">
            <div class="payment_card">
              <div class="row">
                <div class="col-sm-11 col-11">
                  <h1>pay by credit paypal</h1>
                  <p>
                    After booking and paying successfully, the itinerary will send your
                    electronic ticket valuable customer emails
                  </p>
                </div>
                <div
                  class="col-sm-1 col-1 d-flex align-items-center justify-content-center fs-1"
                >
                  <font-awesome-icon :icon="['fas', 'check']" />
                </div>
              </div>
            </div>
          </div>
          <div class="card fs-4 mb-2">
            <div class="payment_card">
              <div class="row">
                <div class="col-sm-11 col-11">
                  <h1>Pay in cash at the office</h1>
                  <p>Please come to the office at XXX to pay and receive tickets</p>
                </div>
                <div
                  class="col-sm-1 col-1 d-flex align-items-center justify-content-center fs-1"
                >
                  <font-awesome-icon :icon="['fas', 'check']" />
                </div>
              </div>
            </div>
          </div>
          <div class="card fs-4 mb-2">
            <div class="payment_card">
              <div class="row">
                <div class="col-sm-11 col-11">
                  <h1>pay by credit card</h1>
                  <p>
                    After booking and paying successfully, the itinerary will send your
                    electronic ticket valuable customer emails
                  </p>
                </div>
                <div
                  class="col-sm-1 col-1 d-flex align-items-center justify-content-center fs-1"
                >
                  <font-awesome-icon :icon="['fas', 'check']" />
                </div>
              </div>
            </div>
          </div>
          <button class="btn-information mt-3">
            <span class="text-white detail__contact-required--text">Continue</span>
          </button>
        </div>
        <div class="col-12 col-sm-4">
          <div class="card">
            <img
              src="/assets/img/Tour/653e4b0293808_1.jpg"
              class="card-img-top"
              :alt="title"
            />
            <div class="card-body fs-4">
              <h3 class="card-title">title</h3>
              <p class="card-text">
                <font-awesome-icon :icon="['far', 'calendar']" /> departure day: 5
              </p>
              <p class="card-text">
                <font-awesome-icon :icon="['far', 'clock']" /> time: 5 day
              </p>
              <p class="card-text">
                <font-awesome-icon :icon="['fas', 'user']" /> adult slot: 5
              </p>
              <p class="card-text">
                <font-awesome-icon :icon="['fas', 'child-reaching']" /> children slot: 5
              </p>
              <p class="card-text fw-bold fs-3">
                total: <span class="total_color">5</span> USD
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent, ref, reactive, toRefs, inject } from "vue";
import { message, Steps } from "ant-design-vue";
import { useRouter, useRoute } from "vue-router";

export default defineComponent({
  setup() {
    const router = useRouter();
    const route = useRoute();
    const $loading = inject("$loading");

    const items = ref([
      {
        title: "Finished",
        description: "login",
      },
      {
        title: "Finished",
        description: "Choose a tour",
      },
      {
        title: "Finished",
        description: "customer information",
      },
      {
        title: "In Progress",
        description: "Select a payment method",
      },
      {
        title: "Waiting",
        description: "pay",
      },
    ]);

    return {
      items,
    };
  },
  components: {
    Steps,
  },
});
</script>

<style>
.payment_card {
  padding: 10px;
  background-color: #e6f7ff;
  cursor: pointer;
}
.payment_card:hover {
  background-color: #7dc4e6;
}
</style>
