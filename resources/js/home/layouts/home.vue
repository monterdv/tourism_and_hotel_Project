<template>
  <div class="wrapper">
    <TheHeader />
    <router-view></router-view>
    <TheFooter />
  </div>
</template>

<script>
import TheHeader from "./partials/TheHeader.vue";
import TheFooter from "./partials/TheFooter.vue";

import "../../../../public/assets/css/main.css";
import "../../../../public/assets/css/base.css";
import "../../../../public/assets/css/gird.css";
import "../../../../public/assets/css/responsive.css";

// import "../../../../public/assets/js/login";
// import "../../../../public/assets/js/slide2";
// import "../../../../public/assets/js/test";
import { ref, defineComponent, inject } from "vue";

export default defineComponent({
  setup() {
    const $loading = inject("$loading");

    const getProfile = () => {
      const loader = $loading.show({});
      axios
        .get("http://127.0.0.1:8000/api/profile")
        .then(function (response) {
          console.log(response);
          // users.value = response.data;
          loader.hide();
        })
        .catch(function (error) {
          loader.hide();
          console.error(error);
        });
    };
    // getProfile();
    return {};
  },
  components: {
    TheHeader,
    TheFooter,
  },
});
</script>

<style></style>
