<template>
  <div class="container">
    <div class="grid wide">
      <div class="profile">
        <div class="row sm-gutter">
          <div class="col-sm-4">
            <!-- here -->
            <sliderbar />
          </div>
          <div class="col-12 col-sm-8">
            <div class="profile-user">
              <div class="profile-user__item">
                <div class="profile-user__img">
                  <img
                    src="https://graph.facebook.com/204427495936201/picture?type=large"
                    alt=""
                    class="profile-user-sefl__img"
                  />
                  <div class="profile-user__img-text">Tải Ảnh Lên</div>
                </div>
                <!-- <div class="profile-connect__social">
                  <div class="profile-connect">
                    <div class="profile-connect-items">
                      <p class="profile-connect__social-network">Connect FaceBook</p>
                      <div class="profile-connect__icon">
                        <div class="profile-connect__icons">
                          <i class="fa-brands fa-facebook"></i>
                        </div>
                        <p class="profile-connect__icon-text">FaceBook</p>
                      </div>
                    </div>

                    <div class="proflie-connection">
                      <p class="profile-connected">Đã Kết Nối</p>
                    </div>
                  </div>

                  <div class="profile-connect">
                    <div class="profile-connect-items">
                      <p class="profile-connect__social-network">Connect Google</p>
                      <div
                        class="profile-connect__icon"
                        style="background-color: #d34836"
                      >
                        <div class="profile-connect__icons">
                          <i class="fa-brands fa-google"></i>
                        </div>
                        <p class="profile-connect__icon-text">Google</p>
                      </div>
                    </div>

                    <div class="proflie-connection">
                      <p class="profile-connected">Đã Kết Nối</p>
                    </div>
                  </div>

                  <div class="profile-connect">
                    <div class="profile-connect-items">
                      <p class="profile-connect__social-network">Connect Tap Tap</p>
                      <div
                        class="profile-connect__icon"
                        style="background-color: #f7cd3c"
                      >
                        <div class="profile-connect__icons">
                          <i class="fa-solid fa-t"></i>
                        </div>
                        <p class="profile-connect__icon-text">TapTap</p>
                      </div>
                    </div>

                    <div class="proflie-connection">
                      <p class="profile-connected">Đã Kết Nối</p>
                    </div>
                  </div>
                </div> -->

                <div class="profile-user-name">
                  <p class="profile-user-name__tilte">Full name :</p>

                  <p class="profile-user-name__full">{{ name }}</p>
                </div>

                <!-- <div class="profile-user-name">
                  <p class="profile-user-name__tilte">Số Điện Thoại :</p>

                  <p class="profile-user-name__full">0903113114</p>
                </div> -->

                <div class="profile-user-name">
                  <p class="profile-user-name__tilte">Email :</p>

                  <p class="profile-user-name__full">{{ email }}</p>
                </div>

                <div class="profile-user-name">
                  <p class="profile-user-name__tilte">wallet :</p>

                  <p class="profile-user-name__full">{{ wallet ? wallet : 0}}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, defineComponent, inject, reactive, toRefs } from "vue";
import sliderbar from "./sliderbar.vue";

export default defineComponent({
  setup() {
    const $loading = inject("$loading");

    const Profile = reactive({
      avatar: "",
      email: "",
      department_id: "",
      name: "",
      wallet: "",
    });

    const getProfile = () => {
      const loader = $loading.show({});
      axios
        .get("http://127.0.0.1:8000/api/profile")
        .then(function (response) {
          console.log(response);
          // Profile.value = response.data.data.user;
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
    };
  },
  components: {
    sliderbar,
  },
});
</script>

<style></style>
