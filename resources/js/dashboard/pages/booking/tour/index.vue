<template>
  <a-card title="booking tour" style="width: 100%">
    <div class="row mb-4">
      <form @submit.prevent="searchHotel" enctype="multipart/form-data">
        <div class="row mb-4">
          <div class="col-12 col-sm-7">
            <a-input
              placeholder="input name customer"
              allow-clear
              v-model:value="searchName"
            >
            </a-input>
          </div>
          <div class="col-12 col-sm-3">
            <a-select
              show-search
              placeholder="stasus"
              style="width: 100%"
              :options="statusOptions"
              v-model:value="searchStatus"
              allow-clear
            >
              <template #suffixIcon>
                <font-awesome-icon :icon="['fas', 'bookmark']" /> </template
            ></a-select>
          </div>
          <div class="col-12 col-sm-2 btn-search">
            <a-button
              type="primary"
              class="ml-2"
              htmlType="submit"
              style="padding: 0px 30px"
            >
              <span>search</span>
            </a-button>
          </div>
        </div>
      </form>
    </div>
    <!-- :dataSource="Hotel" -->
    <a-table :columns="columns" :scroll="{ x: 576 }"> </a-table>
  </a-card>
</template>

<script>
import { ref, defineComponent, inject, reactive, toRefs } from "vue";
import { useMenu } from "../../../../store/menu";
import { useRouter, useRoute } from "vue-router";

export default defineComponent({
  setup() {
    const store = useMenu();
    store.onselectedkey(["booking-tour"]);
    const columns = [
      {
        title: "#",
        key: "index",
        width: 70,
      },
      {
        title: "customer",
        dataIndex: "customer",
        key: "customer",
      },
      {
        title: "tour",
        dataIndex: "tour",
        key: "tour",
      },
      {
        title: "slot",
        dataIndex: "slot",
        key: "slot",
      },
      {
        title: "status",
        dataIndex: "status",
        key: "status",
      },

      {
        title: "action",
        key: "action",
        fixed: "right",
        width: 160,
      },
    ];
    return { columns };
  },
});
</script>

<style></style>
