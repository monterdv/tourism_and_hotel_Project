<template>
  <div class="card mb-1 border-0 shadow hover-effect">
    <div class="row g-0 p-3 align-items-center ml-1">
      <div class="col-12 col-sm-12" style="margin-left: 10px">
        <h5 class="card-title">title</h5>
      </div>
      <div class="col-md-4">
        <img
          src="/assets/img/Tour/653e4b0293808_1.jpg"
          class="img-fluid rounded-start"
          style="object-fit: cover; margin-left: 5px"
        />
      </div>
      <div class="col-md-6">
        <div class="card-body">
          <p class="card-text">
            <font-awesome-icon :icon="['fas', 'barcode']" /> code: a
          </p>
          <p class="card-text">
            <font-awesome-icon :icon="['far', 'calendar']" /> departure day: a
          </p>
          <p class="card-text">
            <font-awesome-icon :icon="['far', 'clock']" /> time: 5 day
          </p>
          <p class="card-text">
            <font-awesome-icon :icon="['fas', 'user']" /> adult slot: 5
          </p>
          <p class="card-text">
            <font-awesome-icon :icon="['fas', 'child-reaching']" /> children slot: 5
          </p>
          <p class="card-text">
            <font-awesome-icon :icon="['far', 'credit-card']" /> payment methods: 20
          </p>
          <p class="card-text">
            <font-awesome-icon :icon="['fas', 'calendar-check']" /> Date of payment: 20
          </p>
          <p class="card-text">
            <font-awesome-icon :icon="['fas', 'money-bill']" /> total amount: 20
          </p>
        </div>
      </div>
      <div class="col-md-2" style="display: flex">
        <div>already paid</div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  setup() {
    return {};
  },
};
</script>

<style></style>
