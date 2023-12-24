<template>
  <div class="row" v-if="contactInfoData">
    <div class="col-12 col-sm-6 mb-3">
      <div class="row">
        <div class="col-12 col-sm-3 text-start text-sm-end">
          <label>
            <span class="text-danger me-1">*</span>
            <span>Name:</span>
          </label>
        </div>
        <div class="col-12 col-sm-9">
          <a-input
            placeholder="input Tour Name"
            allow-clear
            v-model:value="contactInfoData.name"
          />
          <div class="w-100"></div>
          <div v-if="errors && errors[index] && errors[index].name">
            <small v-if="errors[index].name" class="text-danger">{{
              errors[index].name[0]
            }}</small>
          </div>
        </div>
      </div>
    </div>
    <div class="row" v-if="number == null || index < number">
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
            <div v-if="errors && errors[index] && errors[index].phone">
              <small v-if="errors[index].phone" class="text-danger">{{
                errors[index].phone[0]
              }}</small>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 mb-3">
        <div class="row">
          <div class="col-12 col-sm-3 text-start text-sm-end">
            <label>
              <span class="text-danger me-1">*</span>
              <span>Email:</span>
            </label>
          </div>
          <div class="col-12 col-sm-9">
            <a-input
              placeholder="input Email"
              allow-clear
              v-model:value="contactInfoData.email"
            />
            <div class="w-100"></div>
            <div v-if="errors && errors[index] && errors[index].email">
              <small v-if="errors[index].email" class="text-danger">{{
                errors[index].email[0]
              }}</small>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 mb-3">
        <div class="row">
          <div class="col-12 col-sm-3 text-start text-sm-end">
            <label>
              <span class="text-danger me-1">*</span>
              <span>passport:</span>
            </label>
          </div>
          <div class="col-12 col-sm-9">
            <a-input
              placeholder="input passport"
              allow-clear
              v-model:value="contactInfoData.passport"
            />
            <div class="w-100"></div>
            <div v-if="errors && errors[index] && errors[index].passport">
              <small v-if="errors[index].passport" class="text-danger">{{
                errors[index].passport[0]
              }}</small>
            </div>
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
            <a-select
              show-search
              placeholder="place seclect"
              style="width: 100%"
              :options="nationality"
              :filter-option="filterNationality"
              v-model:value="contactInfoData.nationality"
              allow-clear
            />
            <div class="w-100"></div>
            <div v-if="errors && errors[index] && errors[index].nationality">
              <small v-if="errors[index].nationality" class="text-danger">{{
                errors[index].nationality[0]
              }}</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    contactInfoData: Object,
    errors: {
      type: Object,
      default: null,
    },
    index: {
      type: Number,
      default: null,
    },
    nationality: {
      type: Object,
      default: null,
    },
    number: {
      type: Number,
      default: null,
    },
  },

  setup(props) {
    const nationality = props.nationality;
    const filterNationality = (input, nationality) => {
      return nationality.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
    };
    return {
      filterNationality,
    };
  },
};
</script>

<style></style>
