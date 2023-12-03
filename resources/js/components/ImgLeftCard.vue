<template>
  <div
    class="card mb-1 border-0 shadow hover-effect"
    v-if="item"
    @click="updateActivePlan"
    :class="{ 'active-background': this.activePlan == this.item.id }"
  >
    <div class="row g-0 p-3 align-items-center ml-1">
      <div class="col-12 col-sm-12" style="margin-left: 10px">
        <h5 class="card-title">{{ item.tour.title }}</h5>
      </div>
      <div class="col-md-4">
        <img
          :src="item.image"
          :alt="item.tour.title"
          class="img-fluid rounded-start"
          style="object-fit: cover; margin-left: 5px"
        />
      </div>
      <div class="col-md-5">
        <div class="card-body">
          <p class="card-text">
            <font-awesome-icon :icon="['far', 'calendar']" /> departure day:
            {{ item.tours_times.date }}
          </p>
          <p class="card-text">
            <font-awesome-icon :icon="['far', 'clock']" /> time:
            {{ item.tour.duration }} day
          </p>
          <p class="card-text">
            <font-awesome-icon :icon="['fas', 'users']" /> remaining seats: 20
          </p>
          <p class="card-text">
            <font-awesome-icon :icon="['fas', 'user']" /> adult slot: {{ item.adults }}
          </p>
          <p class="card-text">
            <font-awesome-icon :icon="['fas', 'child-reaching']" /> children slot:
            {{ item.children }}
          </p>
        </div>
      </div>
      <div class="col-md-3" style="display: flex">
        <div v-if="this.activePlan == this.item.id">
          <font-awesome-icon :icon="['far', 'circle-check']" />
        </div>
        <p class="total-font">Total {{ item.total }} USD</p>
        <div @click="deleteItem(item.id)">
          <font-awesome-icon :icon="['fas', 'trash']" style="color: red" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent, ref, reactive, toRefs, inject } from "vue";
import { message } from "ant-design-vue";

export default defineComponent({
  setup(props) {
    const $loading = inject("$loading");

    const deleteItem = () => {
      const loader = $loading.show({});
      axios
        .post(`http://127.0.0.1:8000/api/bookingtour/delete/${props.item.id}`)
        .then(function (response) {
          console.log(response);
          message.success(response.data.message);
          props.getCart();
          loader.hide();
        })
        .catch(function (error) {
          console.error(error);
          loader.hide();
        });
    };
    return {
      deleteItem,
    };
  },
  props: {
    item: {
      type: Object,
      default: null,
    },
    getCart: {
      type: Function,
      default: null,
    },
    activePlan: {
      type: Number,
      default: null,
    },
  },
  methods: {
    updateActivePlan() {
      this.$emit("onUpdatePlan", this.item.id);
    },
  },
});
</script>

<style>
.hover-effect:hover {
  box-shadow: 0 0 10px rgba(138, 185, 207, 0.5);
  background-color: rgba(77, 161, 138, 0.7);
}
p.total-font {
  padding: 0px 5px;
  font-weight: 700;
}
.active-background {
  background-color: rgba(77, 161, 138, 0.5);
}
</style>
