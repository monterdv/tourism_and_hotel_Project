<template>
  <div class="col l-4 m-6 c-12" v-for="(item, index) in list" :key="index">
    <router-link
      class="home__tour-item"
      :to="{ name: 'tour-detail', params: { slug: item.slug } }"
    >
      <div
        class="home__tour-item-img"
        :style="{ backgroundImage: 'url(' + item.image + ')' }"
      ></div>
      <h4 class="home__tour-name">
        {{ item.title }}
      </h4>
      <!-- <div class="home__tour-mark">
                  <p class="tour__mark">8.3</p>
                  <p class="tour__classification">Tốt</p>
                  <p class="rate">13 đánh giá</p>
                </div> -->
      <ul class="tour__related">
        <!-- <li class="tour__related-item" style="width: 70px">
                    - Gardens by the Bay
                  </li> -->
        <li class="tour__related-item">status: {{ item.status }}</li>
        <li class="tour__related-item">Tour Code: {{ item.tour_Code }}</li>
      </ul>
      <div class="tour__price">
        <span class="tour__price-old"> </span>
        <span class="tour__price-new">{{ item.price }} USD</span>
      </div>
    </router-link>
  </div>
</template>

<script>
export default {
  props: {
    list: {
      type: Object,
      default: null,
    },
  },
  setup() {
    return {};
  },
};
</script>

<style></style>
