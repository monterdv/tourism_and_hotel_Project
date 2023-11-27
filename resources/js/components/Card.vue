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
      <!-- <li class="tour__related-item" style="width: 70px">
                    - Gardens by the Bay
                  </li> -->
      <li class="tour__related-item">{{ placesName ? `place: ${placesName}` : "" }}</li>
      <li class="tour__related-item">{{ category ? `category: ${category}` : "" }}</li>
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
export default {
  props: {
    title: {
      type: String,
      required: true,
    },
    image: {
      type: String,
      required: true,
    },
    price: {
      type: Number,
      required: true,
    },
    duration: {
      type: Number,
      default: null,
    },
    category: {
      type: String,
      default: null,
    },
    placesName: {
      type: String,
      default: null,
    },
  },
  setup() {
    return {};
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
