<template>
  <div class="col l-6 m-6 c-6">
    <div
      class="most__love-img most__love-img-2"
      :style="{ 'background-image': 'url(' + place.image + ')' }"
    >
      <div class="most__love-text">
        <p class="most__text-header">{{ place.country }}</p>

        <p class="most__text-end">{{ place.total_hotels }} Khách Sạn</p>
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
