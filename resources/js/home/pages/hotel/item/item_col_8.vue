<template>
  <div
    v-if="place"
    class="most__love-img most__love-img-1"
    :style="{
      'background-image': 'url(' + place.image + ')',
      'background-size': 'cover',
    }"
  >
    <div class="most__love-text">
      <p class="most__text-header">{{ place.country }}</p>
      <p class="most__text-end">499 Khách Sạn</p>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    place: {
      type: Object,
      default: null,
    },
  },
};
</script>

<style></style>
