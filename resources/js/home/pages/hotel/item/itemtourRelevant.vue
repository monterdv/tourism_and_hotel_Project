<template>
  <div class="row mb-3" style="border: 1px">
    <div class="col-3 col-sm-3">
      <img :src="item.image" alt="" width="90" height="90" style="object-fit: cover" />
    </div>
    <div class="col-8 col-sm-9">
      <div class="relevant-tour">
        <div class="relevant-title">{{ item.title }}</div>
        <p class="relevant-place">{{ item.tour_Code }}</p>
        <Rate v-model:value="item.star_rating" disabled />
        <div>
          <span class="detailed_information"
            ><font-awesome-icon
              :icon="['fas', 'location-dot']"
              class="me-2"
            />address: </span
          >{{ item.address }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { message, Rate } from "ant-design-vue";

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
  components: { Rate },
};
</script>

<style>
p.relevant-place {
    margin-bottom: -3px;
}

.relevant-price {
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
