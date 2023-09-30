<template>
  <form @submit.prevent="updatepassword()" enctype="multipart/form-data">
    <div class="row">
      <div class="col-12 col-sm-12">
        <div class="row mb-4">
          <div class="col-12 col-sm-3 text-start text-sm-star">
            <label>
              <span class="text-danger me-1">*</span>
              <span>password:</span>
            </label>
          </div>
          <div class="col-12 col-sm-6">
            <a-input-password
              placeholder="input password"
              allow-clear
              v-model:value="password"
            />
            <div class="w-100"></div>
            <small v-if="errors.password" class="text-danger">{{
              errors.password[0]
            }}</small>
          </div>
        </div>

        <div class="row mb-4">
          <div class="col-12 col-sm-3 text-start text-sm-star">
            <label>
              <span class="text-danger me-1">*</span>
              <span>new password:</span>
            </label>
          </div>
          <div class="col-12 col-sm-6">
            <a-input-password
              placeholder="input password"
              allow-clear
              v-model:value="password_new"
            />
            <div class="w-100"></div>
            <small v-if="errors.password_new" class="text-danger">{{
              errors.password_new[0]
            }}</small>
          </div>
        </div>

        <div class="row mb-4">
          <div class="col-12 col-sm-3 text-start text-sm-star">
            <label>
              <span class="text-danger me-1">*</span>
              <span>password confirmation:</span>
            </label>
          </div>
          <div class="col-12 col-sm-6">
            <a-input-password
              placeholder="input password"
              allow-clear
              v-model:value="password_confirmation"
            />
            <div class="w-100"></div>
            <small v-if="errors.password_confirmation" class="text-danger">{{
              errors.password_confirmation[0]
            }}</small>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-3">
      <div class="col-12 col-sm-9 d-grid d-sm-flex justify-content-sm-end mx-auto">
        <a-button type="primary" htmlType="submit">
          <span>Save</span>
        </a-button>
      </div>
    </div>
  </form>
</template>

<script>
import { ref, defineComponent, inject, reactive, toRefs } from "vue";
import { message } from "ant-design-vue";
import { useRouter, useRoute } from "vue-router";

export default defineComponent({
  setup() {
    const $loading = inject("$loading");

    const router = useRouter();

    const passwordchange = reactive({
      password: "",
      password_new: "",
      password_confirmation: "",
    });

    const errors = ref({});

    const updatepassword = () => {
      const loader = $loading.show({});
      const formData = new FormData();
      formData.append("password", passwordchange.password);
      formData.append("password_new", passwordchange.password_new);
      formData.append("password_confirmation", passwordchange.password_confirmation);

      axios
        .post("http://127.0.0.1:8000/api/profile/passwordChange", formData)
        .then(function (response) {
          console.log(response);
          if (response.data.message) {
            loader.hide();
            open.value = false;
            message.success(response.data.message);
          }
        })
        .catch(function (error) {
          console.log(error);
          loader.hide();
          if (error.response.data.errors) {
            errors.value = error.response.data.errors;
          } else {
            message.error(error.response.data.message);
          }
        });
    };

    return {
      ...toRefs(passwordchange),
      updatepassword,
      errors,
    };
  },
});
</script>

<style></style>
