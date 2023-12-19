<template>
  <div>
    <TheHeader />
    <div class="container-fluid mt-3">
      <div class="row">
        <div class="col-sm-3 d-none d-sm-flex">
          <a-list bordered style="width: 100%" class="shadow">
            <template #header
              ><span style="font-weight: bolder; font-size: 20px">management</span></template
            >
            <Slidebar />
          </a-list>
        </div>
        <div class="col-12 col-sm-9">
          <router-view></router-view>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import TheHeader from "./partials/TheHeader.vue";
import Slidebar from "./partials/SlidebarMenu.vue";

export default {
  setup() {
    return {};
  },
  components: {
    TheHeader,
    Slidebar,
  },
};
</script>

<style></style>
