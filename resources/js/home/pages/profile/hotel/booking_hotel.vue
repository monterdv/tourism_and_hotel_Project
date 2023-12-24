<template>
  <a-tabs v-model:activeKey="activeKey" :tab-position="tabPosition" animated>
    <a-tab-pane key="upcoming" tab="Upcoming hotel">
      <Empty v-if="!HotelUpcoming.length" />
      <div v-else v-for="item in HotelUpcoming" :key="item.id">
        <cardLeftImg :item="item" />
      </div>
    </a-tab-pane>
    <a-tab-pane key="in-progress" tab="hotel in Progress" force-render>
      <Empty v-if="!HotelInProgress.length" />
      <div v-else v-for="item in HotelInProgress" :key="item.id">
        <cardLeftImg :item="item" />
      </div>
    </a-tab-pane>
    <a-tab-pane key="completed" tab="Completed hotel">
      <Empty v-if="!HotelCompleted.length" />
      <div v-else v-for="item in HotelCompleted" :key="item.id">
        <cardLeftImg :item="item" />
      </div>
    </a-tab-pane>
  </a-tabs>
</template>

<script>
import { ref, defineComponent, inject, reactive, toRefs } from "vue";
import cardLeftImg from "../hotel/cardLeftImg.vue";
import { Empty, message } from "ant-design-vue";

export default defineComponent({
  setup() {
    const activeKey = ref("upcoming");
    const tabPosition = ref("left");
    const $loading = inject("$loading");

    const HotelCompleted = ref([]);
    const HotelInProgress = ref([]);
    const HotelUpcoming = ref([]);

    const getbookinghotel = () => {
      const loader = $loading.show({});
      axios
        .get("http://127.0.0.1:8000/api/profile/bookinghotel")
        .then(function (response) {
          // console.log(response);
          HotelCompleted.value = response.data.data.HotelCompleted;
          HotelInProgress.value = response.data.data.HotelInProgress;
          HotelUpcoming.value = response.data.data.HotelUpcoming;
          loader.hide();
        })
        .catch(function (error) {
          loader.hide();
          console.error(error.response);
        });
    };
    getbookinghotel();

    return {
      activeKey,
      tabPosition,
      HotelCompleted,
      HotelInProgress,
      HotelUpcoming,
    };
  },
  components: {
    cardLeftImg,
    Empty,
  },
});
</script>

<style></style>
