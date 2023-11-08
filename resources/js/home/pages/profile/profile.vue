<template>
  <div class="row">
    <div class="col-lg-12 mb-4 mb-sm-5">
      <div class="card card-style1 border-0">
        <div class="card-body p-1-9 p-sm-2-3 p-md-6 p-lg-7">
          <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
              <img
                v-if="!avatar"
                src="https://bootdey.com/img/Content/avatar/avatar7.png"
                alt=""
              />
              <img v-else :src="avatar" alt="" width="400" height="400" />
            </div>
            <div class="col-lg-6 px-xl-10">
              <Form
                :label-col="labelCol"
                :wrapper-col="wrapperCol"
                layout="horizontal"
                :disabled="componentDisabled"
                style="max-width: 600px"
                @submit.prevent="updateprofile()"
                enctype="multipart/form-data"
              >
                <a-form-item label="Name">
                  <a-input placeholder="input name" allow-clear v-model:value="name" />
                </a-form-item>
                <a-form-item label="email">
                  <a-input placeholder="input email" allow-clear v-model:value="email" disabled/>
                </a-form-item>
                <a-form-item label="wallet">
                  <a-input allow-clear v-model:value="wallet" disabled />
                </a-form-item>

                <a-form-item label="passwordChange">
                  <Checkbox v-model:checked="passwordChange"></Checkbox>
                </a-form-item>
                <div v-if="passwordChange">
                  <a-form-item label="password">
                    <a-input-password
                      placeholder="input password"
                      allow-clear
                      v-model:value="password"
                    />
                  </a-form-item>

                  <a-form-item label="password new">
                    <a-input-password
                      placeholder="input password new"
                      allow-clear
                      v-model:value="password_new"
                    />
                  </a-form-item>

                  <a-form-item label="password confirmation">
                    <a-input-password
                      placeholder="input password confirmation"
                      allow-clear
                      v-model:value="password_confirmation"
                    />
                  </a-form-item>
                </div>
              </Form>
              <div class="row">
                <div class="col-6 col-sm-6">
                  <button class="header__login-login1" @click="Disabledinput()">
                    update profile
                  </button>
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
import formprofile from "./formprofile.vue";
import { message, Tabs, Form, Checkbox } from "ant-design-vue";

export default defineComponent({
  setup() {
    const $loading = inject("$loading");
    const activeKey = ref("1");
    const componentDisabled = ref(true);

    const Profile = reactive({
      avatar: "",
      email: "",
      department_id: "",
      name: "",
      wallet: "",
      passwordChange: ref(false),
      password: "",
      password_new: "",
      password_confirmation: "",
    });

    const Disabledinput = () => {
      componentDisabled.value = !componentDisabled.value;
      if (componentDisabled == false) {
        passwordChange.value = false;
      }
    };
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
          Profile.wallet = response.data.data.user.wallet
            ? response.data.data.user.wallet
            : 0;
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
      activeKey,
      componentDisabled,
      Disabledinput,
    };
  },
  components: {
    formprofile,
    Tabs,
    Form,
    Checkbox,
  },
});
</script>

<style></style>
