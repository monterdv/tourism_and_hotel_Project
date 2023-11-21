<template>
  <div class="slider-tour">
    <div class="slider__text" style="margin-bottom: -20px">
      <div class="slider__main">
        <p class="slider__text-header--tour">Du Lịch Theo Cá Tính</p>
        <p class="combo__person" style="width: 400px">
          <img
            src="https://res.ivivu.com/hotel/img/fire-sale.svg"
            alt="fire_red"
            style="font-size: 1.6rem"
          />
          762 khách đã đặt phòng trong 24h qua
        </p>
      </div>

      <p class="slider-tour__end-text">Trải Nghiệm Hơn - Giá Phải Chăng</p>
    </div>

    <div class="slider__content">
      <div class="slider__input">
        <div class="slider__menu">
          <div class="silder__calander">
            <div class="slider__list">
              <a-input
                placeholder="Bạn Muốn Đi Đâu ?"
                class="slider__search-input-text"
                allow-clear
                v-model:value="search"
              />
            </div>
          </div>

          <router-link
            v-if="tour"
            :to="{
              name: 'tour-search',
              query: { search: search },
            }"
            class="slider__search"
          >
            <p class="slider__search-text">Tìm</p>
          </router-link>
          <router-link
            v-if="hotel"
            :to="{
              name: 'hotel-search',
              query: { search: search },
            }"
            class="slider__search"
          >
            <p class="slider__search-text">Tìm</p>
          </router-link>
        </div>
      </div>
    </div>
  </div>
  <div class="hotline">
    <div class="grid wide">
      <div class="row">
        <div class="col l-4 m-6 c-12">
          <div class="hotline-main">
            <div class="hotline-icon">
              <i class="fa-solid fa-person-circle-question"></i>
            </div>
            <div class="hotline__text">
              <div class="hotline__text-header">Professional Consulting</div>
              <div class="hotline__text-end">Enthusiastic support, thoughtful care</div>
            </div>
          </div>
        </div>

        <div class="col l-4 m-6 c-12">
          <div class="hotline-main">
            <div class="hotline-icon">
              <i class="fa-solid fa-earth-americas"></i>
            </div>
            <div class="hotline__text">
              <div class="hotline__text-header">Diverse Experiences</div>
              <div class="hotline__text-end">
                Choose the right tour, reasonable tour price
              </div>
            </div>
          </div>
        </div>

        <div class="col l-4 m-6 c-12">
          <div class="hotline-main">
            <div class="hotline-icon">
              <i class="fa-solid fa-comments-dollar"></i>
            </div>
            <div class="hotline__text">
              <div class="hotline__text-header">Secure Payment</div>
              <div class="hotline__text-end">Flexible, Clear, Secure</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, defineComponent } from "vue";
export default defineComponent({
  props: {
    tour: {
      type: Boolean,
      default: false,
    },
    hotel: {
      type: Boolean,
      default: false,
    },
  },
  setup() {
    const search = ref("");
    return {
      search,
    };
  },
});
</script>

<style></style>
