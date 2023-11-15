<template>
  <div class="main">
    <div></div>
    <form @submit.prevent="forgetPassword" enctype="multipart/form-data" class="form">
      <h3 class="heading">Reset Password</h3>

      <div class="spacer"></div>

      <div class="form-group">
        <label for="password" class="form-label">Password</label>
        <!-- <input
          type="password"
          placeholder="Password"
          class="form-control"
          :class="{ 'selec-danger-input': errors.password }"
        /> -->
        <a-input-password
          placeholder="input Password"
          allow-clear
          v-model:value="password"
          :class="{ 'selec-danger-input': errors.password }"
        />
        <div class="w-100"></div>
        <small v-if="errors.password" class="text-danger form-message">{{
          errors.password[0]
        }}</small>
      </div>

      <div class="form-group">
        <label for="password_confirmation" class="form-label">Confirm Password</label>
        <a-input-password
          placeholder="input Password"
          allow-clear
          v-model:value="confirm_Password"
          :class="{ 'selec-danger-input': errors.confirm_Password }"
        />
        <div class="w-100"></div>
        <small v-if="errors.confirm_Password" class="text-danger form-message">{{
          errors.confirm_Password[0]
        }}</small>
      </div>

      <button class="form-submit" htmlType="submit">Reset Password</button>
    </form>
  </div>
</template>

<script>
import axios from "axios";
import { defineComponent, ref, reactive, toRefs, inject } from "vue";
import { message } from "ant-design-vue";
import { useRouter, useRoute } from "vue-router";

import "../../../public/assets/js/login";
export default defineComponent({
  setup() {
    const errors = ref({});
    const router = useRouter();
    const route = useRoute();
    const $loading = inject("$loading");

    console.log(route);

    const check = () => {
      const loader = $loading.show({});
      axios
        .get(
          `http://127.0.0.1:8000/api/change-Password/${route.params.user}/${route.params.token}`
        )
        .then(function (response) {
          loader.hide();
          console.log(response);
        })
        .catch(function (error) {
          loader.hide();
          if (error.response.status === 404) {
            router.push({ name: "login" });
          }
          console.log(error);
        });
    };

    check();

    return { errors };
  },
  methods: {},
});
</script>

<style>
.ant-upload-select-picture-card i {
  font-size: 32px;
  color: #999;
}

.ant-upload-select-picture-card .ant-upload-text {
  margin-top: 8px;
  color: #666;
}

.selec-danger-input {
  border: 1px solid red;
}
</style>
