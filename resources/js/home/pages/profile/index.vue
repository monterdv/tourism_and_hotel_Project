<template>
  <div class="container">
    <div class="grid wide">
      <div class="profile">
        <div class="row sm-gutter">
          <div class="col-sm-4">
            <!-- here -->
            <div class="profile-user">
              <div class="profile-user__item">
                <div class="profile-user-items">
                  <div class="profile-user-sefl__icon">
                    <i class="fa-regular fa-user"></i>
                  </div>
                  <p class="profile-user-sefl__text">
                    <a href="#"> Hồ Sơ Của tôi </a>
                  </p>
                </div>
                <div class="profile-user-items">
                  <div class="profile-user-sefl__icon">
                    <i class="fa-solid fa-cart-shopping"></i>
                  </div>
                  <p class="profile-user-sefl__text">
                    <a href="#"> Đơn Hàng Của tôi </a>
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-8">
            <div class="profile-user">
              <div class="profile-user__item">
                <div class="profile-user__img">
                  <img
                    v-if="!avatar"
                    src="https://graph.facebook.com/204427495936201/picture?type=large"
                    alt=""
                    class="profile-user-sefl__img"
                  />
                  <img v-else :src="avatar" alt="" class="profile-user-sefl__img" />
                </div>

                <div class="profile-user-name">
                  <p class="profile-user-name__tilte">Full name :</p>

                  <p class="profile-user-name__full">{{ name }}</p>
                </div>

                <div class="profile-user-name">
                  <p class="profile-user-name__tilte">Email :</p>

                  <p class="profile-user-name__full">{{ email }}</p>
                </div>

                <div class="profile-user-name">
                  <p class="profile-user-name__tilte">wallet :</p>

                  <p class="profile-user-name__full">{{ wallet ? wallet : 0 }}</p>
                </div>

                <div class="row">
                  <div class="col-12 col-sm-12">
                    <div class="col-sm-6">
                      <button
                        class="header__login-login1"
                        @click="showDrawerinformation()"
                      >
                        change information
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <a-drawer
    v-model:open="information"
    class="custom-class"
    title="change profile"
    placement="right"
    :width="720"
  >
    <formprofile :Profile="Profile" />
  </a-drawer>
</template>

<script>
import { ref, defineComponent, inject, reactive, toRefs } from "vue";
import formprofile from "./formprofile.vue";
import { message } from "ant-design-vue";

export default defineComponent({
  setup() {
    const information = ref(false);

    const $loading = inject("$loading");

    const showDrawerinformation = () => {
      information.value = true;
    };

    const Profile = reactive({
      avatar: "",
      email: "",
      department_id: "",
      name: "",
      wallet: "",
    });

    // const ProfileUser = ref();

    const getProfile = () => {
      const loader = $loading.show({});
      axios
        .get("http://127.0.0.1:8000/api/profile")
        .then(function (response) {
          console.log(response);
          // ProfileUser.value = response.data.data.user;
          Profile.name = response.data.data.user.name;
          Profile.email = response.data.data.user.email;
          Profile.avatar = response.data.data.user.avatar;
          Profile.department_id = response.data.data.user.department_id;
          Profile.wallet = response.data.data.user.wallet;
          loader.hide();
        })
        .catch(function (error) {
          loader.hide();
          console.error(error);
        });
    };
    getProfile();
    return {
      ...toRefs(Profile),
      showDrawerinformation,
      information,
    };
  },
  components: {
    formprofile,
  },
});
</script>

<style></style>
