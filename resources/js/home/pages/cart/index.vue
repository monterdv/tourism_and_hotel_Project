<template>
  <div class="container" v-if="Cart">
    <div class="grid wide">
      <div class="row">
        <div class="col-12 col-sm-8">
          <a-card title="your Cart" class="cart" style="width: 100%">
            <div class="row">
              <Empty v-if="Cart == 0" />
              <div
                class="col-12 col-sm-12 mt-2"
                v-for="(item, index) in Cart"
                :key="index"
              >
                <ImgLeftCard
                  :item="item"
                  :getCart="getCart"
                  :activePlan="activePlan"
                  @onUpdatePlan="updateActivePlan"
                />
              </div>
            </div>
          </a-card>
        </div>
        <div class="col-12 col-sm-4">
          <a-card title="Detail tour" class="cart" style="width: 100%">
            <div class="row">
              <div class="card-body">
                <InputNumber
                  min="1"
                  :max="maxSlotAdults"
                  v-model:value="adults"
                  placeholder="adult price"
                  class="col-12 col-sm-12"
                ></InputNumber>
                <InputNumber
                  min="0"
                  :max="maxSlotChildren"
                  v-model:value="children"
                  placeholder="Children's price"
                  class="col-12 col-sm-12 mt-3 mb-4"
                ></InputNumber>
                <h2 class="card-title mb-4">
                  your cart total: <span class="total-cart">{{ TotalCart }} USD</span>
                </h2>

                <div v-if="id">
                  <p class="card-text" style="font-size: 16px">
                    Ticket price for adults:
                    <span class="total-cart"
                      >{{ price_adults ? price_adults : 0 }} USD</span
                    >
                  </p>
                  <p class="card-text" style="font-size: 16px">
                    Child ticket price:
                    <span class="total-cart"
                      >{{ price_children ? price_children : 0 }} USD</span
                    >
                  </p>
                  <p class="card-text" style="font-size: 16px">
                    Total cost of selected tour:
                    <span class="total-cart">{{ totalCost }} USD</span>
                  </p>
                </div>
              </div>
              <button v-if="id" class="detail__contact-required mt-3">
                <router-link
                  :to="{
                    name: 'booking',
                    query: { id: id, adults: adults, children: children },
                  }"
                  style="text-decoration: none"
                >
                  <span class="detail__contact-required--text text-white"
                    ><font-awesome-icon
                      :icon="['far', 'credit-card']"
                      class="me-2"
                    />payment process</span
                  >
                </router-link>
              </button>
              <button v-if="!id" class="detail__contact-required mt-3">
                <span class="detail__contact-required--text"
                  ><font-awesome-icon
                    :icon="['far', 'credit-card']"
                    class="me-2"
                  />payment process</span
                >
              </button>
            </div>
          </a-card>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent, ref, reactive, toRefs, inject, computed } from "vue";
import { InputNumber, Empty, message } from "ant-design-vue";
import { useRouter, useRoute } from "vue-router";
import ImgLeftCard from "../../../components/ImgLeftCard.vue";
export default defineComponent({
  setup() {
    const $loading = inject("$loading");
    const Cart = ref([]);
    const TotalCart = ref();
    const activePlan = ref();
    const Detail = reactive({
      id: null,
      adults: null,
      title: null,
      children: null,
      price_adults: null,
      price_children: null,
      slots_remaining: null,
    });

    const getCart = () => {
      const loader = $loading.show({});
      axios
        .get(`http://127.0.0.1:8000/api/bookingtour/`)
        .then(function (response) {
          console.log(response);
          Cart.value = response.data.data.cart;
          TotalCart.value = response.data.data.total;
          loader.hide();
        })
        .catch(function (error) {
          console.error(error);
          loader.hide();
        });
    };
    getCart();

    const updateActivePlan = (plan) => {
      if (activePlan.value == plan) {
        activePlan.value = null;
        Detail.id = null;
        Detail.title = null;
        Detail.adults = null;
        Detail.children = null;
        Detail.price_adults = null;
        Detail.price_children = null;
        Detail.slots_remaining = null;
      } else {
        activePlan.value = plan;
        const selected = Cart.value.find((item) => item.id === activePlan.value);
        if (selected) {
          Detail.id = selected.id;
          Detail.title = selected.tour.title;
          Detail.adults = selected.adults;
          Detail.children = selected.children;
          Detail.price_adults = selected.tours_times.price_adults;
          Detail.price_children = selected.tours_times.price_children;
          Detail.slots_remaining =
            selected.tours_times.slots_remaining - selected.tours_times.slots_booked;
        }
        // console.log(Detail);
      }
    };

    const totalCost = computed(() => {
      if (
        Detail.adults !== null &&
        Detail.price_adults !== null &&
        Detail.children !== null &&
        Detail.price_children !== null
      ) {
        return (
          Detail.adults * Detail.price_adults + Detail.children * Detail.price_children
        );
      } else {
        return 0;
      }
    });

    const maxSlotAdults = computed(() => {
      if (Detail.slots_remaining !== null && Detail.adults !== null) {
        const max = Detail.slots_remaining - Detail.children;
        // console.log("adults max: ", max);
        return max;
      } else {
        return 0;
      }
    });

    const maxSlotChildren = computed(() => {
      if (Detail.slots_remaining !== null && Detail.children !== null) {
        const max = Detail.slots_remaining - Detail.adults;
        // console.log("children max: ", max);
        return max;
      } else {
        return 0;
      }
    });
    return {
      Cart,
      TotalCart,
      activePlan,
      ...toRefs(Detail),
      getCart,
      updateActivePlan,
      totalCost,
      maxSlotAdults,
      maxSlotChildren,
    };
  },
  components: {
    ImgLeftCard,
    Empty,
    InputNumber,
  },
  methods: {},
});
</script>

<style>
.cart {
  min-height: 70vh;
  margin-top: 120px;
}
.total-cart {
  float: right;
}
</style>
