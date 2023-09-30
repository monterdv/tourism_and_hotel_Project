<template>
  <div>
    <Checkbox v-model="checked">Checkbox</Checkbox>
  </div>
</template>

<script>
import { ref } from "vue";
import { message, Checkbox } from "ant-design-vue";

export default {
  setup() {
    const checked = ref(false);

    return { checked };
  },
  components: { Checkbox },
};
</script>

<style></style>
