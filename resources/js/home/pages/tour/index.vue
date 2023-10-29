<template>
  <div class="container">
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
              :to="{
                name: 'tour-search',
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
    <!-- End slider -->

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

    <!-- tour nước ngoài cao cấp -->
    <div class="tour__foreign-country mt-3">
      <div class="grid wide">
        <div class="row sm-gutter">
          <div class="col l-12">
            <div class="combo">
              <p class="combo__hot">domestic tourism</p>
              <p class="combo__person">Experience the regions in the country</p>
            </div>
          </div>
        </div>

        <div class="tours">
          <div class="row sm-gutter">
            <item :list="inland" />
          </div>

          <a href="" class="more">
            <button class="more-than">Xem Thêm Tours</button>
          </a>
        </div>
      </div>
    </div>

    <!-- tour japan korea -->
    <div class="tour__foreign-country">
      <div class="grid wide">
        <div class="row sm-gutter">
          <div class="col l-12">
            <div class="combo">
              <p class="combo__hot">Luxury Overseas Tour</p>
              <p class="combo__person">Unique Culture, Wonderful Scenery</p>
            </div>
          </div>
        </div>

        <div class="tours">
          <div class="row sm-gutter">
            <item :list="international" />
          </div>

          <a href="" class="more">
            <button class="more-than">Xem Thêm Tours</button>
          </a>
        </div>
      </div>
    </div>

    <!-- Begin Body -->

    <!--End Body  -->
  </div>
</template>

<script>
import { ref, defineComponent, inject } from "vue";
import item from "./item/item.vue";

export default defineComponent({
  setup() {
    const $loading = inject("$loading");

    const inland = ref([]);
    const international = ref([]);

    const getTour = () => {
      const loader = $loading.show({});
      axios
        .get(`http://127.0.0.1:8000/api/tour`)
        .then(function (response) {
          console.log(response);
          inland.value = response.data.data.inland;
          international.value = response.data.data.international;
          loader.hide();
        })
        .catch(function (error) {
          console.error(error);
          loader.hide();
        });
    };
    getTour();

    const search = ref("");
    return {
      inland,
      international,
      search,
    };
  },
  components: {
    item,
  },
});
</script>

<style></style>
