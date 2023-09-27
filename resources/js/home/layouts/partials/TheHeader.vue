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
          <li class="header__header-text">
            <router-link :to="{ name: 'hotel-home' }">
              <a class="header__header-text--link">Khách Sạn</a>
            </router-link>
          </li>
          <li class="header__header-text">
            <router-link :to="{ name: 'tour-home' }">
              <a class="header__header-text--link">Tours</a>
            </router-link>
          </li>
          <li class="header__header-text">
            <router-link :to="{ name: 'blog' }">
              <a class="header__header-text--link">Cẩm Nang Du Lịch </a>
            </router-link>
          </li>
        </ul>

        <ul class="header_list-2">
          <li class="header__header-user">
            <button class="btnLogin-popup">
              <a style="text-decoration: none">Login</a>
            </button>
            <router-link :to="{ name: 'profile' }"
              ><div class="header__header-user--icon">
                <i class="fa-solid fa-user"></i>
                <div class="user__icon">
                  <i class="fa-solid fa-chevron-down"></i>
                </div></div
            ></router-link>

            <div class="header__login">
              <div class="header__login-heading">
                <div class="header__login-text">
                  <a href="#"> Kỳ Nghỉ Của Tôi </a>
                </div>
                <div class="header__login-text">
                  <a href="#"> Voucher Của Tôi </a>
                </div>
                <div class="header__login-text">
                  <a href="#"> iViVu Point </a>
                </div>
                <div class="header__login-text">
                  <a href="#"> Hồ Sơ Của Tôi </a>
                </div>
                <div class="header__login-text">
                  <a href="#"> Nhận Xét Của Tôi </a>
                </div>
                <form @submit.prevent="logout" enctype="multipart/form-data" class="form">
                  <button class="header__login-login">Log out</button>
                </form>
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

export default defineComponent({
  setup() {
    const $loading = inject("$loading");
    const router = useRouter();
    
    const logout = () => {
      const loader = $loading.show({});
      axios
        .post(`http://127.0.0.1:8000/api/logout`)
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
});
</script>

<style></style>
