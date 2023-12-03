<template>
  <div class="row" v-if="contactInfoData">
    <div class="col-12 col-sm-6 mb-3">
      <div class="row">
        <div class="col-12 col-sm-2 text-start text-sm-end">
          <label>
            <span class="text-danger me-1">*</span>
            <span>Name:</span>
          </label>
        </div>
        <div class="col-12 col-sm-10">
          <a-input
            placeholder="input Tour Name"
            allow-clear
            v-model:value="contactInfoData.name"
          />
          <div class="w-100"></div>
        </div>
      </div>
    </div>
    <div class="col-12 col-sm-6 mb-3">
      <div class="row">
        <div class="col-12 col-sm-3 text-start text-sm-end">
          <label>
            <span class="text-danger me-1">*</span>
            <span>phone:</span>
          </label>
        </div>
        <div class="col-12 col-sm-9">
          <a-input
            placeholder="input phone"
            allow-clear
            v-model:value="contactInfoData.phone"
          />
          <div class="w-100"></div>
        </div>
      </div>
    </div>
    <div class="col-12 col-sm-6 mb-3">
      <div class="row">
        <div class="col-12 col-sm-2 text-start text-sm-end">
          <label>
            <span class="text-danger me-1">*</span>
            <span>Email:</span>
          </label>
        </div>
        <div class="col-12 col-sm-10">
          <a-input
            placeholder="input Email"
            allow-clear
            v-model:value="contactInfoData.email"
          />
          <div class="w-100"></div>
        </div>
      </div>
    </div>
    <div class="col-12 col-sm-6 mb-3">
      <div class="row">
        <div class="col-12 col-sm-3 text-start text-sm-end">
          <label>
            <span class="text-danger me-1">*</span>
            <span>Address:</span>
          </label>
        </div>
        <div class="col-12 col-sm-9">
          <a-input
            placeholder="input Address"
            allow-clear
            v-model:value="contactInfoData.address"
          />
          <div class="w-100"></div>
        </div>
      </div>
    </div>
    <div class="col-12 col-sm-6 mb-3">
      <div class="row">
        <div class="col-12 col-sm-2 text-start text-sm-end">
          <label>
            <span class="text-danger me-1">*</span>
            <span>passport:</span>
          </label>
        </div>
        <div class="col-12 col-sm-10">
          <a-input
            placeholder="input passport"
            allow-clear
            v-model:value="contactInfoData.passport"
          />
          <div class="w-100"></div>
        </div>
      </div>
    </div>
    <div class="col-12 col-sm-6 mb-3">
      <div class="row">
        <div class="col-12 col-sm-3 text-start text-sm-end">
          <label>
            <span class="text-danger me-1">*</span>
            <span>nationality:</span>
          </label>
        </div>
        <div class="col-12 col-sm-9">
          <a-input
            placeholder="input nationality"
            allow-clear
            v-model:value="contactInfoData.nationality"
          />
          <div class="w-100"></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    contactInfoData: Object,
    default: null,
  },
  setup() {
    return {};
  },
};
</script>

<style></style>
