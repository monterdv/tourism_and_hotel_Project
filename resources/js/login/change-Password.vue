<template>
  <div class="main">
    <div></div>
    <form @submit.prevent="forgetPassword" enctype="multipart/form-data" class="form">
      <h3 class="heading">Reset Password</h3>
      <!-- <p class="desc">Welcome back! Log in to your account. ❤️</p> -->

      <div class="spacer"></div>

      <div class="form-group">
        <label for="email" class="form-label">Email Address</label>
        <input
          v-model="forget.email"
          type="text"
          placeholder="Enter your email address"
          class="form-control"
        />
        <span class="form-message"></span>
      </div>

      <button class="form-submit" htmlType="submit">SIGN IN</button>

      <div class="heading form-label mt-4" style="font-size: 15px">
        <p>
          Don't have account?
          <span
            style="cursor: pointer; color: rgb(14, 102, 216)"
            v-on:click="changetabs('register')"
          >
            Create Account
          </span>
        </p>
      </div>
    </form>
  </div>
</template>

<script>
import axios from "axios";
import { defineComponent, ref, reactive, toRefs, inject } from "vue";
import { message } from "ant-design-vue";
import { useRouter } from "vue-router";

import "../../../public/assets/js/login";
export default defineComponent({
  setup() {
    const errors = ref({});
    const router = useRouter();
    const $loading = inject("$loading");

    const forgetPassword = () => {
      const loader = $loading.show({});
      const formData = new FormData();
      formData.append("email", forget.email);

      axios
        .post(`http://127.0.0.1:8000/api/forgetPassword`, formData)
        .then(function (response) {
          // console.log(response);
          loader.hide();
          message.success(response.data.message);
        })
        .catch(function (error) {
          // console.log(error);
          loader.hide();
          if (error.response.status === 422) {
            message.error(error.response.data.message);
          } else {
            message.error(error.response.data.message);
          }
        });
    };

    return {};
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
