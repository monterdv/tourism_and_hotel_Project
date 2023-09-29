<template>
  <div class="main">
    <div></div>
    <form
      @submit.prevent="loginAccount"
      enctype="multipart/form-data"
      class="form"
      v-if="tabSelected === 'login'"
    >
      <h3 class="heading">Login</h3>
      <p class="desc">Welcome back! Log in to your account. ❤️</p>

      <div class="spacer"></div>

      <div class="form-group">
        <label for="email" class="form-label">Email</label>
        <input
          v-model="login.email"
          type="text"
          placeholder="Enter your email address"
          class="form-control"
        />
        <div class="w-100"></div>
        <small v-if="errors.email" class="text-danger form-message">{{
          errors.email[0]
        }}</small>
      </div>

      <div class="form-group">
        <label for="password" class="form-label">Password</label>
        <input
          v-model="login.password"
          type="password"
          placeholder="Enter your password"
          class="form-control"
        />
        <div class="w-100"></div>
        <small v-if="errors.password" class="text-danger form-message">{{
          errors.name[0]
        }}</small>
      </div>
      <div class="form-remenber" style="text-align: left">
        <label for="remenber me" class="form-label">
          <input type="checkbox" style="text-align: left" /> Remenber Me
          <span
            class="float-right"
            style="text-align: right; margin-left: 80px"
            v-on:click="changetabs('forget_Password')"
            >Forgot password?</span
          >
        </label>
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

    <form
      @submit.prevent="RegisterAccount"
      enctype="multipart/form-data"
      class="form"
      v-else-if="tabSelected === 'register'"
    >
      <h3 class="heading">Create Your Account</h3>
      <p class="desc">Enter your personal details to create account</p>

      <div class="spacer"></div>

      <div class="form-group">
        <label for="fullname" class="form-label">Your Name</label>
        <input
          type="text"
          v-model="Register.name"
          placeholder="Enter Your Name"
          class="form-control"
          :class="{ 'selec-danger-input': errors.name }"
        />
        <div class="w-100"></div>
        <small v-if="errors.name" class="text-danger form-message">{{
          errors.name[0]
        }}</small>
      </div>

      <div class="form-group">
        <label for="email" class="form-label">Email</label>
        <input
          v-model="Register.email"
          type="text"
          placeholder="VD: abc@gmail.com"
          class="form-control"
          :class="{ 'selec-danger-input': errors.email }"
        />
        <div class="w-100"></div>
        <small v-if="errors.email" class="text-danger form-message">{{
          errors.email[0]
        }}</small>
      </div>

      <div class="form-group">
        <label for="password" class="form-label">Password</label>
        <input
          v-model="Register.password"
          type="password"
          placeholder="Password"
          class="form-control"
          :class="{ 'selec-danger-input': errors.password }"
        />
        <div class="w-100"></div>
        <small v-if="errors.password" class="text-danger form-message">{{
          errors.password[0]
        }}</small>
      </div>

      <div class="form-group">
        <label for="password_confirmation" class="form-label">Confirm Password</label>
        <input
          v-model="Register.confirm_Password"
          placeholder="Confirm Password"
          type="password"
          class="form-control"
          :class="{ 'selec-danger-input': errors.confirm_Password }"
        />
        <div class="w-100"></div>
        <small v-if="errors.confirm_Password" class="text-danger form-message">{{
          errors.confirm_Password[0]
        }}</small>
      </div>

      <button class="form-submit" htmlType="submit">Register</button>

      <div class="heading form-label mt-4" style="font-size: 15px">
        <p>
          Already have an account?
          <span
            style="cursor: pointer; color: rgb(14, 102, 216)"
            v-on:click="changetabs('login')"
          >
            Sign in
          </span>
        </p>
      </div>
    </form>

    <form
      @submit.prevent="forgetPassword"
      enctype="multipart/form-data"
      class="form"
      v-else
    >
      <h3 class="heading">Forget Password</h3>
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
import store from "../store/index";

import "../../../public/assets/js/login";
export default defineComponent({
  setup() {
    const errors = ref({});
    const router = useRouter();

    const $loading = inject("$loading");

    const tabSelected = ref("login");

    const changetabs = (tab) => {
      tabSelected.value = tab;
    };

    const Register = reactive({
      name: "",
      email: "",
      password: "",
      confirm_Password: "",
    });

    const RegisterAccount = () => {
      const formData = new FormData();
      formData.append("name", Register.name);
      formData.append("email", Register.email);
      formData.append("password", Register.password);
      formData.append("confirm_Password", Register.confirm_Password);

      const loader = $loading.show({});
      // axios.post(`http://127.0.0.1:8000/api/register`, formData)
      store
        .dispatch("REGISTER", formData)
        .then(function (response) {
          // console.log(response);
          if (response) {
            loader.hide();
            message.success(response.data.message);
            router.go();
          }
        })
        .catch(function (error) {
          // console.log(error);
          loader.hide();
          if (error.response.data.errors) {
            errors.value = error.response.data.errors;
          } else {
            message.error(error.response.data.message);
          }
        });
    };

    const login = reactive({
      email: "",
      password: "",
    });

    const loginAccount = () => {
      const loader = $loading.show({});
      const formData = new FormData();
      formData.append("email", login.email);
      formData.append("password", login.password);
      store
        .dispatch("LOGIN", formData)

        // axios.post(`http://127.0.0.1:8000/api/login`, formData)
        .then(function (response) {
          console.log(response);
          if (response) {
            loader.hide();
            localStorage.setItem("token", response.data.data.access_token);
            message.success(response.data.message);
            // router.push({ name: "hotel-home" });
            router.replace({ name: "hotel-home" });
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

    const forget = reactive({
      email: "",
    });

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

    return {
      tabSelected,
      changetabs,
      ...toRefs(Register),
      RegisterAccount,
      loginAccount,
      forgetPassword,
      forget,
      login,
      Register,
      errors,
    };
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
