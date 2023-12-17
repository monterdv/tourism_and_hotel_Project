<template>
  <router-link
    v-if="place && place.country"
    :to="{
      name: 'hotel-search',
      query: { search: place.country },
    }"
  >
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
        <p class="most__text-end">{{ place.total_hotels }} Khách Sạn</p>
      </div>
    </div>
  </router-link>
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
