<template>
  <div class="card mb-1 border-0 shadow hover-effect" v-if="item">
    <div class="row g-0 p-3 align-items-center ml-1">
      <div class="col-12 col-sm-12" style="margin-left: 10px">
        <h5 class="card-title">{{ item.hotel ? item.hotel.title : "" }}</h5>
      </div>
      <div class="col-md-4" v-if="item.room_type">
        <img
          :src="item.room_type.image"
          class="rounded-start w-100"
          style="object-fit: cover; margin-left: 5px"
        />
      </div>
      <div class="col-md-7">
        <div class="card-body">
          <p class="card-text">
            <font-awesome-icon :icon="['fas', 'star']" />
            Hotel Star:
            <Rate v-model:value="item.hotel.star_rating" disabled />
          </p>
          <p class="card-text">
            <font-awesome-icon :icon="['fas', 'barcode']" /> Roon type:
            {{ item.room_type ? item.room_type.name : "" }}
          </p>
          <p class="card-text">
            <font-awesome-icon :icon="['fas', 'key']" />
            Room number:
            {{ item.number_room ? item.number_room.number_of_rooms : "" }}
          </p>
          <p class="card-text">
            <font-awesome-icon :icon="['far', 'clock']" />
            Check In:
            {{ item.start_date }}
          </p>
          <p class="card-text">
            <font-awesome-icon :icon="['far', 'clock']" />
            Check Out:
            {{ item.end_date }}
          </p>
          <p class="card-text">
            <font-awesome-icon :icon="['fas', 'calendar-days']" /> Total Day:
            {{ item.totalDays }}
          </p>
          <p class="card-text">
            <font-awesome-icon :icon="['fas', 'sack-dollar']" /> Price:
            {{ item.price }} USD
          </p>
        </div>
      </div>

      <div class="col-md-1" style="display: flex">
        <div>
          <!-- <font-awesome-icon :icon="['fas', 'trash']" style="color: red" /> -->
          <font-awesome-icon :icon="['fas', 'pen-to-square']" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent, ref, reactive, toRefs, inject } from "vue";
import { message, Rate } from "ant-design-vue";
export default defineComponent({
  props: {
    item: {
      type: Object,
      default: null,
    },
  },
  setup(props) {
    const $loading = inject("$loading");

    return {};
  },
  components: {
    Rate,
  },
});
</script>

<style>
.hover-effect:hover {
  box-shadow: 0 0 10px rgba(138, 185, 207, 0.5);
  background-color: rgba(77, 161, 138, 0.7);
}
</style>
