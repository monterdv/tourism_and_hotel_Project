<template>
  <div class="container">
    <div class="tour__detail">
      <div class="row">
        <div class="col-sm-3 d-none d-sm-flex">
          <a-list bordered style="width: 100%">
            <template #header
              ><span style="font-weight: bolder; font-size: 20px"
                >Khách sạn</span
              ></template
            >
            <Slidebar />
          </a-list>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Slidebar from "./searchSliderbar.vue";
import { useRouter, useRoute } from "vue-router";

export default {
  setup() {
    const route = useRoute();

    const searchHotel = () => {
      axios
        .get(`http://127.0.0.1:8000/api/hotel/search/${route.query.search}`)
        .then((response) => {
          console.log(response);
        })
        .catch((error) => {
          console.log(error);
        });
    };
    searchHotel();
    return {};
  },
  components: {
    Slidebar,
  },
};
</script>

<style></style>
