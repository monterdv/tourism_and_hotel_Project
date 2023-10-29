<template>
  <div class="row">
    <div class="col-3 col-sm-3">
      <img :src="item.image" alt="" width="100" height="100" style="object-fit: cover" />
    </div>
    <div class="col-8 col-sm-5">
      <div class="relevant-tour">
        <div class="relevant-title">{{ item.title }}</div>
        <p class="relevant-place">{{ item.tour_Code }}</p>
      </div>
    </div>
    <div class="col-3 col-sm-3">
      <div class="relevant-price">
        <p>{{ item.price }} USD</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    item: {
      type: Object,
      default: null,
    },
  },
  setup() {
    return {};
  },
};
</script>

<style>
.relevant-price{
  line-height: 100px;
  font-size: 16px;
}
.relevant-title {
  font-weight: 600;
}
.relevant-tour {
  margin-left: 16px;
  font-size: 11px;
}
</style>
