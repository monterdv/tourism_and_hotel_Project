<template>
  <vueper-slides :slide-ratio="1 / 4" autoplay fixed-height="480px">
    <vueper-slide
      v-for="(slide, index) in tourImg"
      :key="index"
      :image="slide.path"
    />
  </vueper-slides>
</template>

<script>
import { defineComponent, ref, toRefs, inject, reactive, watch } from "vue";
import { VueperSlides, VueperSlide } from "vueperslides";
import "vueperslides/dist/vueperslides.css";

export default {
  props: {
    tourImg: {
      type: Object,
      required: true,
    },
  },
  setup() {
    return {};
  },
  components: {
    VueperSlides,
    VueperSlide,
  },
};
</script>

<style></style>
