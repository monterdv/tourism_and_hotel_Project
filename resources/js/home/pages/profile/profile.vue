<template>
  <div class="row">
    <div class="col-lg-12 mb-4 mb-sm-5">
      <div class="card card-style1 border-0">
        <div class="card-body p-1-9 p-sm-2-3 p-md-6 p-lg-7">
          <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
              <!-- <img
                        src="https://bootdey.com/img/Content/avatar/avatar7.png"
                        alt="..."
                      /> -->
              <img
                v-if="!avatar"
                src="https://bootdey.com/img/Content/avatar/avatar7.png"
                alt=""
              />
              <img v-else :src="avatar" alt="" width="400" height="400"/>
            </div>
            <div class="col-lg-6 px-xl-10">
              <!-- <div
                        class="bg-secondary d-lg-inline-block py-1-9 px-1-9 px-sm-6 mb-1-9 rounded"
                      >
                        <h3 class="h2 text-white mb-0">{{ name }}</h3>
                      </div> -->
              <ul class="list-unstyled mb-1-9">
                <li class="mb-2 mb-xl-3 display-28">
                  <span class="display-26 text-secondary me-2 font-weight-600"
                    >name:</span
                  >
                  {{ name }}
                </li>
                <li class="mb-2 mb-xl-3 display-28">
                  <span class="display-26 text-secondary me-2 font-weight-600"
                    >Email:</span
                  >
                  {{ email }}
                </li>
                <li class="mb-2 mb-xl-3 display-28">
                  <span class="display-26 text-secondary me-2 font-weight-600"
                    >wallet:</span
                  >
                  {{ wallet ? wallet : 0 }}
                </li>

                <!-- <li class="display-28">
                          <span class="display-26 text-secondary me-2 font-weight-600"
                            >Phone:</span
                          >
                          507 - 541 - 4567
                        </li> -->
              </ul>
              <ul class="social-icon-style1 list-unstyled mb-0 ps-0">
                <li>
                  <a href="#!"><i class="ti-twitter-alt"></i></a>
                </li>
                <li>
                  <a href="#!"><i class="ti-facebook"></i></a>
                </li>
                <li>
                  <a href="#!"><i class="ti-pinterest"></i></a>
                </li>
                <li>
                  <a href="#!"><i class="ti-instagram"></i></a>
                </li>
              </ul>
              <div class="row">
                <div class="col-12 col-sm-12">
                  <div class="col-sm-12">
                    <button class="header__login-login1" @click="showDrawerinformation()">
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
import { message, Tabs } from "ant-design-vue";

export default defineComponent({
  setup() {
    const information = ref(false);

    const $loading = inject("$loading");
    const activeKey = ref("1");

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
      activeKey,
    };
  },
  components: {
    formprofile,
    Tabs,
  },
});
</script>

<style></style>
