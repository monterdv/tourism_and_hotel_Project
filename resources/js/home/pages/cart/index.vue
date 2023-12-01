<template>
  <div class="container">
    <div class="grid wide">
      <div class="row">
        <div class="col-12 col-sm-7">
          <a-card title="Cart" class="cart" style="width: 100%">
            <div class="row">
              <div
                class="col-12 col-sm-12 mt-2"
                v-for="(item, index) in Cart"
                :key="index"
              >
                <ImgLeftCard
                  :title="item.tour.title"
                  :duration="item.tour.duration"
                  :adults="item.adults"
                  :children="item.children"
                  :date="item.tours_times.date"
                  :price="item.tours_times.price_adults"
                  :image="item.image"
                />
              </div>
            </div>
          </a-card>
        </div>
        <div class="col-12 col-sm-4">
          <a-card title="Detail" class="cart" style="width: 100%"
            ><div class="row">Test</div></a-card
          >
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent, ref, reactive, toRefs, inject } from "vue";
import ImgLeftCard from "../../../components/ImgLeftCard.vue";
export default defineComponent({
  setup() {
    const $loading = inject("$loading");
    const Cart = ref([]);

    const getCart = () => {
      const loader = $loading.show({});
      axios
        .get(`http://127.0.0.1:8000/api/bookingtour/`)
        .then(function (response) {
          console.log(response);
          Cart.value = response.data.data.cart;
          loader.hide();
        })
        .catch(function (error) {
          console.error(error);
          loader.hide();
        });
    };
    getCart();
    return {
      Cart,
    };
  },
  components: {
    ImgLeftCard,
  },
});
</script>

<style>
.cart {
  min-height: 70vh;
  margin-top: 120px;
}
</style>
