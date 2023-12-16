<template>
  <a-tabs v-model:activeKey="activeKey" :tab-position="tabPosition" animated>
    <a-tab-pane key="upcoming" tab="Upcoming Tour">
      <Empty v-if="!TourUpcoming.length" />
      <div v-else v-for="item in TourUpcoming" :key="item.id">
        <cardLeftImg :item="item" :getbookingtour="getbookingtour" />
      </div>
    </a-tab-pane>
    <a-tab-pane key="in-progress" tab="Tour in Progress" force-render>
      <Empty v-if="!TourInProgress.length" />
      <div v-else v-for="item in TourInProgress" :key="item.id">
        <cardLeftImg :item="item" />
      </div>
    </a-tab-pane>
    <a-tab-pane key="completed" tab="Completed Tour">
      <Empty v-if="!TourCompleted.length" />
      <div v-else v-for="item in TourCompleted" :key="item.id">
        <cardLeftImg :item="item" />
      </div>
    </a-tab-pane>
  </a-tabs>
</template>

<script>
import { ref, defineComponent, inject, reactive, toRefs } from "vue";
import cardLeftImg from "./cardLeftImg.vue";
import { Empty, message } from "ant-design-vue";

export default defineComponent({
  setup() {
    const activeKey = ref("upcoming");
    const tabPosition = ref("left");
    const $loading = inject("$loading");
    const TourCompleted = ref([]);
    const TourInProgress = ref([]);
    const TourUpcoming = ref([]);

    const getbookingtour = () => {
      const loader = $loading.show({});
      axios
        .get("http://127.0.0.1:8000/api/profile/bookingtour")
        .then(function (response) {
          console.log(response);
          TourCompleted.value = response.data.data.TourCompleted;
          TourInProgress.value = response.data.data.TourInProgress;
          TourUpcoming.value = response.data.data.TourUpcoming;
          loader.hide();
        })
        .catch(function (error) {
          loader.hide();
          console.error(error.response);
        });
    };
    getbookingtour();

    return {
      activeKey,
      tabPosition,
      TourCompleted,
      TourInProgress,
      TourUpcoming,
      getbookingtour,
    };
  },
  components: {
    cardLeftImg,
    Empty,
  },
});
</script>

<style></style>
