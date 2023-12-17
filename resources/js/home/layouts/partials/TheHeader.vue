<template>
  <div class="header">
    <div class="grid wide">
      <div class="header__nav">
        <ul class="header_list">
          <li class="header__header-img">
            <a href="#">
              <img
                src="https://res.ivivu.com/hotel/img/logo-2023n.svg"
                alt=""
                class="header-header-img__image"
              />
            </a>
          </li>
          <li class="header__header-text" style="text-decoration: none">
            <router-link :to="{ name: 'hotel-home' }" class="header__header-text--link">
              <span>Hotel</span>
            </router-link>
          </li>
          <li class="header__header-text">
            <router-link :to="{ name: 'tour-home' }" class="header__header-text--link">
              <span>Tours</span>
            </router-link>
          </li>
          <li class="header__header-text">
            <router-link :to="{ name: 'blog' }" class="header__header-text--link">
              <span>Blog tour</span>
            </router-link>
          </li>
        </ul>

        <ul class="header_list-2">
          <router-link
            :to="{ name: 'login' }"
            v-if="!loggedInStatus"
            style="text-decoration: none"
          >
            <button class="header__login-login1">login</button>
          </router-link>
          <li class="header__header-user">
            <div class="header__header-user--icon" v-if="loggedInStatus">
              <i class="fa-solid fa-user"></i>
              <div class="user__icon">
                <i class="fa-solid fa-chevron-down"></i>
              </div>
            </div>

            <div class="header__login">
              <div class="header__login-heading">
                <div class="header__login-text" v-if="role == 1 || role == 3">
                  <router-link :to="{ name: 'dashboard' }">
                    <span> dashboad </span>
                  </router-link>
                </div>
                <!-- <div class="header__login-text">
                  <a href="#"> Voucher Của Tôi </a>
                </div> -->
                <div class="header__login-text">
                  <router-link :to="{ name: 'profile' }" v-if="loggedInStatus">
                    <span> My profile </span>
                  </router-link>
                </div>
                <div class="header__login-text">
                  <router-link :to="{ name: 'cart' }" v-if="loggedInStatus">
                    <span> Cart </span>
                  </router-link>
                </div>

                <form @submit.prevent="logout" enctype="multipart/form-data">
                  <button class="header__login-login">Log out</button>
                </form>
                <!-- <div class="header__login-text">
                  <a href="#"> Nhận Xét Của Tôi </a>
                </div> -->
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent, inject } from "vue";
import { message } from "ant-design-vue";
import { useRouter } from "vue-router";
import store from "../../../store/index";

export default defineComponent({
  setup() {
    const $loading = inject("$loading");
    const router = useRouter();

    const logout = () => {
      const loader = $loading.show({});
      store
        .dispatch("LOGOUT")
        // axios.post(`http://127.0.0.1:8000/api/logout`)
        .then(function (response) {
          console.log(response);
          if (response) {
            loader.hide();
            message.success(response.data.message);
            localStorage.removeItem("token");
            router.push({ name: "hotel-home" });
          }
        })
        .catch(function (error) {
          // console.log(error);
          loader.hide();
          if (error.response.data.errors) {
            message.error = error.response.data.errors;
          } else {
            message.error(error.response.data.message);
          }
        });
    };
    return { logout };
  },
  components: {},
  computed: {
    loggedInStatus() {
      return this.$store.getters.GET_AUTH_STATUS;
    },
    role() {
      return this.$store.getters.GET_AUTH_INFO.department_id;
    },
  },
});
</script>

<style></style>
