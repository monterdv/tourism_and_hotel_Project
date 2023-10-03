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
            <div class="col l-4 m-6 c-12" v-for="(item, index) in inland" :key="index">
              <router-link
                class="home__tour-item"
                :to="{ name: 'tour-detail', params: { slug: item.slug } }"
              >
                <div
                  class="home__tour-item-img"
                  :style="{ backgroundImage: 'url(' + item.tour_paths[0].path + ')' }"
                ></div>
                <h4 class="home__tour-name">
                  {{ item.title }}
                </h4>
                <div class="home__tour-mark">
                  <p class="tour__mark">8.3</p>
                  <p class="tour__classification">Tốt</p>
                  <p class="rate">13 đánh giá</p>
                </div>
                <ul class="tour__related">
                  <li class="tour__related-item" style="width: 70px">
                    - Gardens by the Bay
                  </li>
                  <li class="tour__related-item">status: {{ item.status }}</li>
                  <li class="tour__related-item">Tour Code: {{ item.tour_Code }}</li>
                </ul>
                <div class="tour__price">
                  <span class="tour__price-old"> </span>
                  <span class="tour__price-new">12.690.000 đ</span>
                </div>
              </router-link>
            </div>

            <!-- <div class="col l-4 m-6 c-12">
              <a href="#" class="home__tour-item">
                <div
                  class="home__tour-item-img"
                  style="
                    background-image: url(//cdn2.ivivu.com/2019/01/11/17/chua-trang-wat-rong-khun-chiangrai-360x225.jpg);
                  "
                ></div>
                <h4 class="home__tour-name">
                  Tour Thái Lan 4N3Đ: Chiang Mai - Chiang Rai - Tam Giác Vàng
                </h4>
                <div class="home__tour-mark">
                  <p class="tour__mark">7.6</p>
                  <p class="tour__classification">Khá Tốt</p>
                  <p class="rate">14 đánh giá</p>
                </div>
                <ul class="tour__related">
                  <li class="tour__related-item">- Tam Giác Vàng</li>
                  <li class="tour__related-item">- Truyền Thống Kantoke</li>
                  <li class="tour__related-item">- Làng Dân Tộc Cổ Dài</li>
                </ul>
                <div class="tour__price">
                  <span class="tour__price-old"> </span>
                  <span class="tour__price-new">8.880.000</span>
                </div>
                <div class="tour__favourite">
                  <i class="fa-solid fa-check"></i>
                  <span>Vietjet | KS 4*</span>
                </div>
              </a>
            </div> -->
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
            <div
              class="col l-4 m-6 c-12"
              v-for="(item1, index) in international"
              :key="index"
            >
              <router-link
                class="home__tour-item"
                :to="{ name: 'tour-detail', params: { slug: item1.slug } }"
              >
                <div
                  class="home__tour-item-img"
                  :style="{ backgroundImage: 'url(' + item1.tour_paths[0].path + ')' }"
                ></div>
                <h4 class="home__tour-name">
                  {{ item1.title }}
                </h4>
                <div class="home__tour-mark">
                  <p class="tour__mark">10.0</p>
                  <p class="tour__classification">Tuyệt Vời</p>
                  <p class="rate">03 đánh giá</p>
                </div>
                <ul class="tour__related">
                  <li class="tour__related-item">- Đặc Sản Hàn Quốc</li>
                  <li class="tour__related-item">- Đảo Nam</li>
                  <li class="tour__related-item">- Hero Show</li>
                </ul>
                <div class="tour__price">
                  <span class="tour__price-old"></span>
                  <span class="tour__price-new">14.990.000 đ</span>
                </div>
                <!-- <div class="tour__favourite">
                  <i class="fa-solid fa-check"></i>
                  <span>Vietjet | KS 4*</span>
                </div> -->
              </router-link>
            </div>
          </div>

          <a href="" class="more">
            <button class="more-than">Xem Thêm Tours</button>
          </a>
        </div>
      </div>
    </div>

    <!-- tour Euro Aus America -->
    <div class="tour__holiday">
      <div class="grid wide">
        <div class="row sm-gutter">
          <div class="col l-12">
            <div class="combo">
              <p class="combo__hot">Tour Du Lịch Châu Âu - Úc - Mỹ</p>
              <p class="combo__person">Trải Nghiệm Đẳng Cấp, Nâng Tầm Cuộc Sống</p>
            </div>
          </div>
        </div>

        <div class="tours">
          <div class="row sm-gutter">
            <div class="col l-4 m-6 c-12">
              <a href="#" class="home__tour-item">
                <div
                  class="home__tour-item-img"
                  style="
                    background-image: url(https://cdn2.ivivu.com/2022/11/11/17/ivivu-kohrong-samloem-bia-360x225.gif);
                  "
                ></div>
                <h4 class="home__tour-name">
                  Tour Campuchia 3N3Đ: Cao Nguyên Bokor - Sihanoukville - Đảo Thiên Đường
                  Kohrong Saloem
                </h4>
                <div class="home__tour-mark">
                  <p class="tour__mark">7.6</p>
                  <p class="tour__classification">Khá Tốt</p>
                  <p class="rate">10 đánh giá</p>
                </div>
                <ul class="tour__related">
                  <li class="tour__related-item">- Đảo Koh Rong Samloem</li>
                  <li class="tour__related-item">- Cao nguyên Bokor</li>
                  <li class="tour__related-item">- Biển ihanoukville</li>
                </ul>
                <div class="tour__price">
                  <span class="tour__price-old">13.990.000đ</span>
                  <span class="tour__price-new">12.110.000đ</span>
                </div>
                <div class="tour__favourite">
                  <i class="fa-solid fa-check"></i>
                  <span>Xe du lịch | KS 3-4*</span>
                </div>
              </a>
            </div>

            <div class="col l-4 m-6 c-12">
              <a href="#" class="home__tour-item">
                <div
                  class="home__tour-item-img"
                  style="
                    background-image: url(https://cdn2.ivivu.com/2022/11/11/17/ivivu-kohrong-samloem-bia-360x225.gif);
                  "
                ></div>
                <h4 class="home__tour-name">
                  Tour Campuchia 3N3Đ: Cao Nguyên Bokor - Sihanoukville - Đảo Thiên Đường
                  Kohrong Saloem
                </h4>
                <div class="home__tour-mark">
                  <p class="tour__mark">7.6</p>
                  <p class="tour__classification">Khá Tốt</p>
                  <p class="rate">10 đánh giá</p>
                </div>
                <ul class="tour__related">
                  <li class="tour__related-item">- Đảo Koh Rong Samloem</li>
                  <li class="tour__related-item">- Cao nguyên Bokor</li>
                  <li class="tour__related-item">- Biển ihanoukville</li>
                </ul>
                <div class="tour__price">
                  <span class="tour__price-old">13.990.000đ</span>
                  <span class="tour__price-new">12.110.000đ</span>
                </div>
                <div class="tour__favourite">
                  <i class="fa-solid fa-check"></i>
                  <span>Xe du lịch | KS 3-4*</span>
                </div>
              </a>
            </div>

            <div class="col l-4 m-6 c-12">
              <a href="#" class="home__tour-item">
                <div
                  class="home__tour-item-img"
                  style="
                    background-image: url(https://cdn2.ivivu.com/2022/11/11/17/ivivu-kohrong-samloem-bia-360x225.gif);
                  "
                ></div>
                <h4 class="home__tour-name">
                  Tour Campuchia 3N3Đ: Cao Nguyên Bokor - Sihanoukville - Đảo Thiên Đường
                  Kohrong Saloem
                </h4>
                <div class="home__tour-mark">
                  <p class="tour__mark">7.6</p>
                  <p class="tour__classification">Khá Tốt</p>
                  <p class="rate">10 đánh giá</p>
                </div>
                <ul class="tour__related">
                  <li class="tour__related-item">- Đảo Koh Rong Samloem</li>
                  <li class="tour__related-item">- Cao nguyên Bokor</li>
                  <li class="tour__related-item">- Biển ihanoukville</li>
                </ul>
                <div class="tour__price">
                  <span class="tour__price-old">13.990.000đ</span>
                  <span class="tour__price-new">12.110.000đ</span>
                </div>
                <div class="tour__favourite">
                  <i class="fa-solid fa-check"></i>
                  <span>Xe du lịch | KS 3-4*</span>
                </div>
              </a>
            </div>

            <div class="col l-4 m-6 c-12">
              <a href="#" class="home__tour-item">
                <div
                  class="home__tour-item-img"
                  style="
                    background-image: url(https://cdn2.ivivu.com/2022/11/11/17/ivivu-kohrong-samloem-bia-360x225.gif);
                  "
                ></div>
                <h4 class="home__tour-name">
                  Tour Campuchia 3N3Đ: Cao Nguyên Bokor - Sihanoukville - Đảo Thiên Đường
                  Kohrong Saloem
                </h4>
                <div class="home__tour-mark">
                  <p class="tour__mark">7.6</p>
                  <p class="tour__classification">Khá Tốt</p>
                  <p class="rate">10 đánh giá</p>
                </div>
                <ul class="tour__related">
                  <li class="tour__related-item">- Đảo Koh Rong Samloem</li>
                  <li class="tour__related-item">- Cao nguyên Bokor</li>
                  <li class="tour__related-item">- Biển ihanoukville</li>
                </ul>
                <div class="tour__price">
                  <span class="tour__price-old">13.990.000đ</span>
                  <span class="tour__price-new">12.110.000đ</span>
                </div>
                <div class="tour__favourite">
                  <i class="fa-solid fa-check"></i>
                  <span>Xe du lịch | KS 3-4*</span>
                </div>
              </a>
            </div>

            <div class="col l-4 m-6 c-12">
              <a href="#" class="home__tour-item">
                <div
                  class="home__tour-item-img"
                  style="
                    background-image: url(https://cdn2.ivivu.com/2022/11/11/17/ivivu-kohrong-samloem-bia-360x225.gif);
                  "
                ></div>
                <h4 class="home__tour-name">
                  Tour Campuchia 3N3Đ: Cao Nguyên Bokor - Sihanoukville - Đảo Thiên Đường
                  Kohrong Saloem
                </h4>
                <div class="home__tour-mark">
                  <p class="tour__mark">7.6</p>
                  <p class="tour__classification">Khá Tốt</p>
                  <p class="rate">10 đánh giá</p>
                </div>
                <ul class="tour__related">
                  <li class="tour__related-item">- Đảo Koh Rong Samloem</li>
                  <li class="tour__related-item">- Cao nguyên Bokor</li>
                  <li class="tour__related-item">- Biển ihanoukville</li>
                </ul>
                <div class="tour__price">
                  <span class="tour__price-old">13.990.000đ</span>
                  <span class="tour__price-new">12.110.000đ</span>
                </div>
                <div class="tour__favourite">
                  <i class="fa-solid fa-check"></i>
                  <span>Xe du lịch | KS 3-4*</span>
                </div>
              </a>
            </div>

            <div class="col l-4 m-6 c-12">
              <a href="#" class="home__tour-item">
                <div
                  class="home__tour-item-img"
                  style="
                    background-image: url(https://cdn2.ivivu.com/2022/11/11/17/ivivu-kohrong-samloem-bia-360x225.gif);
                  "
                ></div>
                <h4 class="home__tour-name">
                  Tour Campuchia 3N3Đ: Cao Nguyên Bokor - Sihanoukville - Đảo Thiên Đường
                  Kohrong Saloem
                </h4>
                <div class="home__tour-mark">
                  <p class="tour__mark">7.6</p>
                  <p class="tour__classification">Khá Tốt</p>
                  <p class="rate">10 đánh giá</p>
                </div>
                <ul class="tour__related">
                  <li class="tour__related-item">- Đảo Koh Rong Samloem</li>
                  <li class="tour__related-item">- Cao nguyên Bokor</li>
                  <li class="tour__related-item">- Biển ihanoukville</li>
                </ul>
                <div class="tour__price">
                  <span class="tour__price-old">13.990.000đ</span>
                  <span class="tour__price-new">12.110.000đ</span>
                </div>
                <div class="tour__favourite">
                  <i class="fa-solid fa-check"></i>
                  <span>Xe du lịch | KS 3-4*</span>
                </div>
              </a>
            </div>
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
});
</script>

<style></style>
