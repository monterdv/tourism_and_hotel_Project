<template>
  <div class="col-4 col-sm-4">
    <div
      class="most__love-img most__love-img-3"
      :style="{ 'background-image': 'url(' + place.image + ')' }"
    >
      <div class="most__love-text">
        <p class="most__text-header">{{ place.country }}</p>
        <p class="most__text-end">600 Khách Sạn</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  setup() {
    return {};
  },
  props: {
    place: {
      type: Object,
      default: null,
    },
  },
};
</script>

<style></style>
