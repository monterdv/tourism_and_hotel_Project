<template>
  <div class="container">
    <div class="grid wide">
      <section class="bg-light"></section>
      <a-tabs v-model:activeKey="activeKey" centered>
        <a-tab-pane key="1" tab="Your profile"><profile /> </a-tab-pane>
        <a-tab-pane key="2" tab="booking tour" force-render
          >Content of Tab Pane 2</a-tab-pane
        >
        <a-tab-pane key="3" tab="booking hotel">Content of Tab Pane 3</a-tab-pane>
      </a-tabs>
    </div>
  </div>
</template>

<script>
import { ref, defineComponent, inject, reactive, toRefs } from "vue";
import { message } from "ant-design-vue";
import profile from "./profile.vue";

export default defineComponent({
  setup() {
    const information = ref(false);

    const $loading = inject("$loading");
    const activeKey = ref("1");

    return {
      activeKey,
    };
  },
  components: {
    profile,
  },
});
</script>

<style>
.card-style1 {
  box-shadow: 0px 0px 10px 0px rgb(89 75 128 / 9%);
}

.container {
  min-height: 100vh;
}
.border-0 {
  border: 0 !important;
}
.col-lg-6.px-xl-10 {
  font-size: 20px;
}
.card {
  position: relative;
  display: flex;
  flex-direction: column;
  min-width: 0;
  word-wrap: break-word;
  background-color: #fff;
  background-clip: border-box;
  border: 1px solid rgba(0, 0, 0, 0.125);
  border-radius: 0.25rem;
}

section {
  padding: 120px 0px 0px 0px;
  overflow: hidden;
  background: #fff;
}
.mb-2-3,
.my-2-3 {
  margin-bottom: 2.3rem;
}

.section-title {
  font-weight: 600;
  letter-spacing: 2px;
  text-transform: uppercase;
  margin-bottom: 10px;
  position: relative;
  display: inline-block;
}
.text-primary {
  color: #ceaa4d !important;
}
.text-secondary {
  color: #15395a !important;
}
.font-weight-600 {
  font-weight: 600;
}
.display-26 {
  font-size: 1.3rem;
}

@media screen and (min-width: 992px) {
  .p-lg-7 {
    padding: 4rem;
  }
}
@media screen and (min-width: 768px) {
  .p-md-6 {
    padding: 3.5rem;
  }
}
@media screen and (min-width: 576px) {
  .p-sm-2-3 {
    padding: 2.3rem;
  }
}
.p-1-9 {
  padding: 1.9rem;
}

.bg-secondary {
  background: #15395a !important;
}
@media screen and (min-width: 576px) {
  .pe-sm-6,
  .px-sm-6 {
    padding-right: 3.5rem;
  }
}
@media screen and (min-width: 576px) {
  .ps-sm-6,
  .px-sm-6 {
    padding-left: 3.5rem;
  }
}
.pe-1-9,
.px-1-9 {
  padding-right: 1.9rem;
}
.ps-1-9,
.px-1-9 {
  padding-left: 1.9rem;
}
.pb-1-9,
.py-1-9 {
  padding-bottom: 1.9rem;
}
.pt-1-9,
.py-1-9 {
  padding-top: 1.9rem;
}
.mb-1-9,
.my-1-9 {
  margin-bottom: 1.9rem;
}
@media (min-width: 992px) {
  .d-lg-inline-block {
    display: inline-block !important;
  }
}
.rounded {
  border-radius: 0.25rem !important;
}
</style>
