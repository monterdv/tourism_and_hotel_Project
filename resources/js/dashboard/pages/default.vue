<template>
  <div style="background: #ececec; padding: 30px">
    <div class="row">
      <div class="col-6 d-flex justify-content-center col-sm-2">
        <a-card>
          <a-statistic
            title="Feedback"
            :value="11.28"
            :precision="2"
            suffix="%"
            :value-style="{ color: '#3f8600' }"
            style="margin-right: 50px"
          >
          </a-statistic>
        </a-card>
      </div>
      <div class="col-6 d-flex justify-content-center col-sm-2">
        <a-card>
          <a-statistic
            title="Feedback"
            :value="11.28"
            :precision="2"
            suffix="%"
            :value-style="{ color: '#3f8600' }"
            style="margin-right: 50px"
          >
          </a-statistic>
        </a-card>
      </div>
      <div class="col-6 d-flex justify-content-center col-sm-3">
        <a-card>
          <a-statistic
            title="Idle"
            :value="9.3"
            :precision="2"
            suffix="%"
            class="demo-class"
            :value-style="{ color: '#cf1322' }"
          >
          </a-statistic>
        </a-card>
      </div>
    </div>
  </div>
</template>
<script>
import { ref, defineComponent, inject } from "vue";

export default {
  setup() {
    return {};
  },
  components: {},
};
</script>

<style></style>
