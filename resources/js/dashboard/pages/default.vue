<template>
  <div class="row">
    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                Total price
              </div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">$1,140,000</div>
            </div>
            <div class="col-auto">
              <!-- <i class="fas fa-calendar fa-2x text-gray-300"></i> -->
              <font-awesome-icon
                :icon="['fas', 'sack-dollar']"
                class="fa-2x text-gray-300"
              />
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                Tour
              </div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">75</div>
            </div>
            <div class="col-auto">
              <font-awesome-icon :icon="['fas', 'plane']" class="fa-2x text-gray-300" />
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                Hotel
              </div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">180</div>
            </div>
            <div class="col-auto">
              <font-awesome-icon :icon="['fas', 'hotel']" class="fa-2x text-gray-300" />
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Pending Requests Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                USER
              </div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">1,800</div>
            </div>
            <div class="col-auto">
              <font-awesome-icon :icon="['fas', 'user']" class="fa-2x text-gray-300" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-4 col-12">
      <a-card title="doughnutChart" style="width: 100%;" class="shadow h-100">
        <doughnutChart />
      </a-card>
    </div>
    <div class="col-sm-8 col-12 mt-2">
      <a-card title="BarChart" style="width: 100%" class="shadow">
        <BarChart />
      </a-card>
    </div>
    <div class="col-sm-12 col-12 mt-2">
      <a-card title="LineChart" style="width: 100%" class="shadow">
        <LineChart />
      </a-card>
    </div>
    <div class="col-sm-4 col-12 mt-2">
      <a-card title="Calendar" style="width: 100%" class="shadow">test </a-card>
    </div>
  </div>
</template>
<script>
import { ref, defineComponent, inject, reactive, toRefs } from "vue";
import { message } from "ant-design-vue";
import doughnutChart from "./Chart/doughnutChart.vue";
import BarChart from "./Chart/BarChart.vue";
import LineChart from "./Chart/LineChart.vue";
export default defineComponent({
  setup() {
    return {};
  },
  components: { doughnutChart, BarChart, LineChart },
});
</script>

<style>
.border-left-primary {
  border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
  border-left: 0.25rem solid #1cc88a !important;
}
.border-left-warning {
  border-left: 0.25rem solid #f6c23e !important;
}
.border-left-info {
  border-left: 0.25rem solid #36b9cc !important;
}
</style>
