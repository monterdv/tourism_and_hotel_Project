<template>
  <div class="home__tour-item">
    <img
      :src="image"
      :alt="title"
      style="width: 100%; height: 260px; object-fit: cover"
    />
    <h4 class="home__tour-name">
      {{ title }}
    </h4>
    <div class="home__tour-mark">
      <p class="tour__mark">8.3</p>
      <p class="tour__classification">Tốt</p>
      <p class="rate">13 đánh giá</p>
    </div>
    <ul class="tour__related">
      <li class="tour__related-item" v-if="Ratehotel">
        <Rate :value="Ratehotel" :disabled="true" @update:value="updateRate" />
      </li>
      <li class="tour__related-item">
        <template v-if="address">
          <font-awesome-icon :icon="['fas', 'location-dot']" />
          {{ address }}
        </template>
      </li>
      <li class="tour__related-item">{{ placesName ? `Place : ${placesName}` : "" }}</li>
      <li class="tour__related-item">{{ category ? `category : ${category}` : "" }}</li>
      <li class="tour__related-item">
        {{ duration ? `duration: ${duration} Day` : "" }}
      </li>
    </ul>
    <div class="tour__price">
      <span class="tour__price-old">1000 USD</span>
      <span class="tour__price-new">{{ price ? `${price} USD` : "" }}</span>
    </div>
  </div>
</template>

<script>
import { Rate } from "ant-design-vue";

export default {
  props: {
    title: {
      type: String,
      required: null,
    },
    image: {
      type: String,
      required: null,
    },
    price: {
      type: Number,
      required: null,
    },
    duration: {
      type: Number,
      default: null,
    },
    category: {
      type: String,
      default: null,
    },
    address: {
      type: String,
      default: null,
    },
    placesName: {
      type: String,
      default: null,
    },
    Ratehotel: {
      type: Number,
      default: null,
    },
  },
  setup() {
    return {};
  },
  components: {
    Rate,
  },
  methods: {
    updateRate(newValue) {
      this.$emit("update:Ratehotel", newValue);
    },
  },
};
</script>

<style>
.homeDetail-containerRight-body {
  background-color: var(--white-color);
  margin: 0 2px;
}
.homeDetail-containerRight-body__container {
  display: flex;
  height: 160px;
  /* padding: 3px; */
}
.homeDetail-containerRight-image {
  max-width: 170px;
  max-height: 200px;
}
.homeDetail-containerRight-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
</style>
