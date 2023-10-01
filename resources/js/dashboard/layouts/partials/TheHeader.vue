<template>
  <div class="containter-fuid">
    <div class="row align-items-center" style="background-color: aqua; padding: 1rem">
      <div class="col-1 d-flex d-sm-none align-items-center justify-content-center">
        <span @click="showDrawer()"><font-awesome-icon :icon="['fas', 'bars']" /></span>
      </div>

      <div
        class="col-10 col-sm-9 d-flex align-items-center justify-content-center justify-content-sm-start"
      >
        <img
          src="../../../../../public/assets/img/logo.jpg"
          alt=""
          height="32"
          width="34"
        />
        <span class="d-none d-sm-flex">Quan tri</span>
      </div>

      <div class="col-sm-3 d-none d-sm-flex align-items-center justify-content-center">
        <router-link :to="{ name: 'login' }" style="text-decoration: none">
          <button class="header__login-login1 align-items-center justify-content-center" >
            <font-awesome-icon :icon="['fas', 'left-long']" class="me-2"/>
            go back home
          </button>
        </router-link>
      </div>
      <!-- <div
        class="col-1 d-flex d-sm-none align-items-center justify-content-center"
      > -->
      <!-- <span @click="showDrawer_user()">x</span> -->
      <!-- </div> -->
    </div>
  </div>

  <a-drawer v-model:open="open" class="custom-class" title="Danh muc" placement="left">
    <Slidebar />
  </a-drawer>

  <!-- <a-drawer
    v-model:open="open_user"
    class="custom-class"
    title="Danh muc"
    placement="right"
  >
    <p>Some contents...</p>
    <p>Some contents...</p>
    <p>Some contents...</p>
  </a-drawer> -->
</template>

<script>
import Slidebar from "./SlidebarMenu.vue";
import { ref } from "vue";
export default {
  setup() {
    const open = ref(false);
    const open_user = ref(false);

    const showDrawer = () => {
      open.value = true;
    };

    const showDrawer_user = () => {
      open_user.value = true;
    };
    return {
      open,
      showDrawer,
      open_user,
      showDrawer_user,
    };
  },
  components: {
    Slidebar,
  },
};
</script>

<style>
.header__login-login1 {
  width: 50%;
  background: #26bed6;
  color: #fff;
  border: none;
  border-radius: 4px;
  padding: 6px;
  margin: 0px 150px;
  text-align: center;
  font-size: 18px;
  font-weight: 400;
  text-align: center;
  display: flex;
  justify-content: center;
  cursor: pointer;
}
.header__login-login1:hover {
  background-color: #42d7ef;
}
</style>
