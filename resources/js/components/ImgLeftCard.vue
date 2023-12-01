<template>
  <div
    class="row border-item-cart"
    @mouseover="addHoverEffect"
    @mouseleave="removeHoverEffect"
  >
    <div class="col-12 col-sm-12">
      <h3 class="title_cart mb-2">{{ title }}</h3>
    </div>
    <div class="col-3 col-sm-4">
      <div class="image-container">
        <!-- <img
          src="/assets/img/Tour/653e4b0293808_1.jpg"
          alt="Tour Image"
          class="img-fluid"
        /> -->
        <img :src="image" :alt="title" style="object-fit: cover" class="img-fluid" />
      </div>
    </div>
    <div class="col-9 col-sm-4">
      <p><font-awesome-icon :icon="['far', 'calendar']" /> departure day: {{ date }}</p>
      <p><font-awesome-icon :icon="['far', 'clock']" /> time: {{ duration }} day</p>
      <p><font-awesome-icon :icon="['fas', 'users']" /> remaining seats: 20</p>
      <p><font-awesome-icon :icon="['fas', 'user']" /> adult slot: {{ adults }}</p>
      <p>
        <font-awesome-icon :icon="['fas', 'child-reaching']" /> children slot:
        {{ children }}
      </p>
    </div>
    <div class="col-3 col-sm-4 d-flex align-items-center justify-content-center">
      {{ price }} USD/1 people
    </div>
  </div>
</template>

<script>
export default {
  props: {
    title: {
      type: String,
      default: null,
    },
    adults: {
      type: Number,
      default: null,
    },
    children: {
      type: Number,
      default: null,
    },
    duration: {
      type: Number,
      default: null,
    },
    price: {
      type: Number,
      default: null,
    },
    date: {
      type: String,
      default: null,
    },
    image: {
      type: String,
      default: null,
    },
  },
  setup() {
    const addHoverEffect = () => {
      document.querySelector(".border-item-cart").classList.add("hover-effect");
    };

    const removeHoverEffect = () => {
      document.querySelector(".border-item-cart").classList.remove("hover-effect");
    };

    return { addHoverEffect, removeHoverEffect };
  },
};
</script>

<style>
.title_cart {
  color: #003c71;
  line-height: 30px;
}
.row.border-item-cart {
  border: 1px solid rgb(139, 159, 190); /* Đặt đường viền mặc định */
  padding: 2px;
  position: relative;
  transition: box-shadow 0.3s ease; /* Thêm hiệu ứng chuyển động cho đổ bóng */
}

.row.border-item-cart.hover-effect {
  box-shadow: 0 0 10px rgba(138, 185, 207, 0.5); /* Đổ bóng khi hover */
}

.image-container {
  position: relative;
  width: 100%;
  height: 180px;
  overflow: hidden;
  margin: -5px -8px -1px -9px;
}

.image-container img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
</style>
