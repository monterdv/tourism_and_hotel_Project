<template>
  <div class="row">
    <div class="col-12 col-sm-6">
      <a-card style="width: 100%">
        <template #title>
          <div class="row">
            <div class="col-12 col-sm-12 align-items-center">
              tour booking information
              <button type="button" class="btn btn-primary me-2 float-end">
                pay deposit
              </button>
            </div>
          </div>
        </template>
        <p>
          <span style="w-25">tour:</span>
          <span>HCMC TOURISM [SAIGON HUNDRED-YEAR HERITAGE]</span>
        </p>
        <p><span style="w-25">time:</span> 22-10-2023</p>
        <p><span style="w-25">pay:</span> paypal</p>
        <p><span style="w-25">Status:</span> unpaid</p>
        <p><span style="w-25">slot:</span> 3</p>
        <p><span style="w-25">total price:</span> 3000 USD</p>
        <p><span style="w-25">paid:</span> 3000 USD</p>
        <div class="row">
          <div class="col-12 col-sm-12">
            <button type="button" class="btn btn-success me-2">confirm</button>
            <button type="button" class="btn btn-danger">abort order</button>
          </div>
        </div>
      </a-card>
    </div>
    <div class="col-12 col-sm-6">
      <a-card style="width: 100%" class="mb-2">
        <template #title>day tour: 22/10/23</template></a-card
      >

      <a-card title="customer information" style="width: 100%">
        <p>name: Tlee</p>
        <p>email: Tlee@gmail.com</p>
        <p>phone: 0905028073</p>
      </a-card>
    </div>
    <div class="col-12 col-sm-12 mt-3">
      <a-card title="booking tour slot" style="width: 100%">
        <a-table :columns="columns" :scroll="{ x: 576 }"> </a-table>
        <div class="row">
          <div class="col-12 col-sm-12 mt-2">
            <button type="button" class="btn btn-primary me-2">update</button>
            <button type="button" class="btn btn-success">
              <font-awesome-icon :icon="['fas', 'download']" class="me-2" />bill
            </button>
          </div>
        </div>
      </a-card>
    </div>
  </div>
</template>

<script>
export default {
  setup() {
    const columns = [
      {
        title: "#",
        key: "index",
        width: 70,
      },
      {
        title: "name",
        dataIndex: "name",
        key: "name",
      },
      {
        title: "Email",
        dataIndex: "Email",
        key: "Email",
      },
      {
        title: "phone",
        dataIndex: "phone",
        key: "phone",
      },
      {
        title: "nationality",
        dataIndex: "nationality",
        key: "nationality",
      },
      {
        title: "passport",
        key: "passport",
        fixed: "passport",
      },
      {
        title: "type",
        dataIndex: "type",
        key: "type",
      },
    ];
    return { columns };
  },
};
</script>

<style></style>
