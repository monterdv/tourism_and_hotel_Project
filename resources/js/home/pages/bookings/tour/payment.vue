<template>
  <div
    class="card fs-4 mb-2"
    :class="{ 'active-background': this.activePlan == this.item.id }"
    v-if="item"
    @click="updateActivePlan"
  >
    <div class="payment_card">
      <div class="row">
        <div class="col-sm-11 col-11">
          <h1>{{ item.title }}</h1>
          <p v-if="this.activePlan == this.item.id">
            After booking and paying successfully, the itinerary will send your electronic
            ticket valuable customer emails
          </p>
        </div>
        <div
          v-if="this.activePlan == this.item.id"
          class="col-sm-1 col-1 d-flex align-items-center justify-content-center fs-1"
        >
          <font-awesome-icon :icon="['fas', 'check']" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    item: {
      type: Object,
      default: null,
    },
    activePlan: {
      type: Number,
      default: null,
    },
  },
  setup() {
    return {};
  },
  methods: {
    updateActivePlan() {
      this.$emit("onUpdatePlan", this.item.id);
    },
  },
};
</script>

<style>
.payment_card {
  padding: 10px;
  background-color: #e6f7ff;
  cursor: pointer;
}
.payment_card:hover {
  background-color: #7dc4e6;
}
.active-background {
  background-color: rgba(85, 174, 226, 0.5);
}
</style>
