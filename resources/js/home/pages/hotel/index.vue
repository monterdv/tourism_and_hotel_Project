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
          <!-- <div class="slider__search-input">
            <a-input
              placeholder="Bạn Muốn Đi Đâu ?"
              class="slider__search-input-text"
              allow-clear
              v-model:value="search"
            />
          </div> -->
          <div class="slider__menu">
            <div class="silder__calander">
              <div class="slider__list">
                <!-- <DatePicker
                  v-model:value="date"
                  format="DD-MM-YYYY"
                  :disabled-date="disabledDate"
                  class="col-12 col-sm-12"
                ></DatePicker> -->
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
    <!-- End slider -->
    <!-- Begin Body -->
    <div class="grid wide">
      <div class="row sm-gutter">
        <div class="col l-12">
          <div class="combo">
            <p class="combo__hot">ComBo Tốt Nhất Ngày Hôm Nay</p>
            <p class="combo__person">
              <img
                src="https://res.ivivu.com/hotel/img/fire-sale.svg"
                alt="fire_red"
                style="font-size: 1.6rem"
              />
              762 khách đã đặt phòng trong 24h qua
            </p>
          </div>
          <p class="combo__decription">Nhanh tay đặt ngay. Để mai sẽ lỡ</p>
        </div>

        <div class="col-12 col-sm-12">
          <div class="card-image__wrapper">
            <i class="fa-solid fa-chevron-left button" id="card-img__pre"></i>
            <div class="card-image__container">
              <div class="card-image__carousel">
                <!-- <img
                  src="./assects/img/slider/slider1.webp"
                  alt=""
                  class="card-image__img"
                />
                <img
                  src="./assects/img/slider/slider2.webp"
                  alt=""
                  class="card-image__img"
                />
                <img
                  src="./assects/img/slider/slider3.webp"
                  alt=""
                  class="card-image__img"
                />
                <img
                  src="./assects/img/slider/slider4.webp"
                  alt=""
                  class="card-image__img"
                /> -->
              </div>
              <i class="fa-solid fa-chevron-right button" id="card-img__next"></i>
            </div>
            <!-- <a href="" class="card__link">
                                <div class="card__img">
                                    <img src="./assects/img/slider/anlam-nvb-sd.webp" alt=""
                                        class="card__img-decription">
                                </div>
                            </a> -->
          </div>
        </div>

        <!-- Hè Ròi Du Lịch Thoi  -->
        <div class="col l-12">
          <div class="combo">
            <p class="combo__hot">Hè rồi, du lịch thôi !</p>
            <p class="combo__person">Thư giãn - nạp năng lượng - khám phá</p>
          </div>
        </div>

        <div class="home__image">
          <div class="row sm-gutter">
            <div class="col-6 col-sm-3">
              <div class="home__image-p1">
                <div class="image__decription background-1">
                  <div class="image__infor">Quốc Tế</div>
                  <div class="image__end-infor">
                    Khám phá thế giới trong tầm tay - <span>64 khách sạn</span>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-6 col-sm-3">
              <div class="home__image-p1">
                <div class="image__decription background-2">
                  <div class="image__infor">Team X</div>
                  <div class="image__end-infor">
                    Nâng tầm chuyến du lịch của công ty và đội nhóm của bạn !
                  </div>
                </div>
              </div>
            </div>

            <div class="col-6 col-sm-3">
              <div class="home__image-p1">
                <div class="image__decription background-3">
                  <div class="image__infor">Villa</div>
                  <div class="image__end-infor">
                    Chill tại Villa, vui hè thả ga - <span>30 khách sạn</span>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-6 col-sm-3">
              <div class="home__image-p1">
                <div class="image__decription background-4">
                  <div class="image__infor">Gift Voucher</div>
                  <div class="image__end-infor">
                    Lưu giữ khoảnh khắc, trải nghiệm hành trình
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Trong Nước -->
        <div class="col l-12">
          <div class="combo">
            <p class="combo__hot">Điểm đến yêu thích trong nước</p>
            <p class="combo__person">Lên rừng xuống biển. Trọn vẹn Việt Nam</p>
          </div>
        </div>

        <div class="home__love">
          <div class="row sm-gutter">
            <div class="col-8 col-sm-8">
              <div
                class="most__love-img most__love-img-1"
                style="
                  background-image: url(https://cdn1.ivivu.com/iVivu/2023/03/02/10/phuquoc-show.webp);
                "
              >
                <div class="most__love-text">
                  <p class="most__text-header">Phú Quốc</p>
                  <p class="most__text-end">399 Khách Sạn</p>
                </div>
              </div>

              <div class="row sm-gutter">
                <div class="col l-6 m-6 c-6">
                  <div
                    class="most__love-img most__love-img-2"
                    style="
                      background-image: url(https://cdn1.ivivu.com/iVivu/2023/03/02/10/quynhon-show.webp);
                    "
                  >
                    <div class="most__love-text">
                      <p class="most__text-header">Qui Nhơn</p>
                      <p class="most__text-end">499 Khách Sạn</p>
                    </div>
                  </div>
                </div>

                <div class="col l-6 m-6 c-6">
                  <div
                    class="most__love-img most__love-img-3"
                    style="
                      background-image: url(https://cdn1.ivivu.com/iVivu/2023/03/02/10/dalat-show.webp);
                    "
                  >
                    <div class="most__love-text">
                      <p class="most__text-header">Đà Lạt</p>
                      <p class="most__text-end">900 Khách Sạn</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-4 col-sm-4">
              <div
                class="most__love-img most__love-img-4"
                style="
                  background-image: url(https://cdn1.ivivu.com/iVivu/2023/03/02/10/vungtau-show.webp);
                "
              >
                <div class="most__love-text">
                  <p class="most__text-header">Vũng Tàu</p>
                  <p class="most__text-end">600 Khách Sạn</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="home__love">
          <div class="row sm-gutter">
            <div class="col-4 col-sm-4">
              <div
                class="most__love-img most__love-img-4"
                style="
                  background-image: url(https://cdn1.ivivu.com/iVivu/2023/03/02/10/nhatrang-show.webp);
                "
              >
                <div class="most__love-text">
                  <p class="most__text-header">Nha trang</p>
                  <p class="most__text-end">423 Khách Sạn</p>
                </div>
              </div>
            </div>

            <div class="col-8 col-sm-8">
              <div
                class="most__love-img most__love-img-1"
                style="
                  background-image: url(https://cdn1.ivivu.com/iVivu/2023/03/02/10/danang-show.webp);
                "
              >
                <div class="most__love-text">
                  <p class="most__text-header">Đà Nẵng</p>
                  <p class="most__text-end">646 Khách Sạn</p>
                </div>
              </div>

              <div class="row sm-gutter">
                <div class="col l-6 m-6 c-6">
                  <div
                    class="most__love-img most__love-img-3"
                    style="
                      background-image: url(https://cdn1.ivivu.com/iVivu/2023/03/02/10/phanthiet-show.webp);
                    "
                  >
                    <div class="most__love-text">
                      <p class="most__text-header">Phan Thiết</p>
                      <p class="most__text-end">119 Khách Sạn</p>
                    </div>
                  </div>
                </div>

                <div class="col l-6 m-6 c-6">
                  <div
                    class="most__love-img most__love-img-3"
                    style="
                      background-image: url(https://cdn1.ivivu.com/iVivu/2023/03/02/10/phuyen-show.webp);
                    "
                  >
                    <div class="most__love-text">
                      <p class="most__text-header">Phú Yên</p>
                      <p class="most__text-end">19 Khách Sạn</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Nước Ngoài -->
        <div class="col-12">
          <div class="combo">
            <p class="combo__hot">Điểm đến yêu thích nước ngoài</p>
            <p class="combo__person">Bao la thế giới. Bốn bể là nhà</p>
          </div>
        </div>

        <div class="home__love">
          <div class="row sm-gutter">
            <div class="col-8 col-sm-8">
              <div
                class="most__love-img most__love-img-1"
                style="
                  background-image: url(https://cdn1.ivivu.com/iVivu/2023/04/17/11/kl-cr.webp);
                "
              >
                <div class="most__love-text">
                  <p class="most__text-header">Kula Lumpua</p>
                  <p class="most__text-end">1034 Khách Sạn</p>
                </div>
              </div>
            </div>

            <div class="col-4 col-sm-4">
              <div
                class="most__love-img most__love-img-9"
                style="
                  background-image: url(https://cdn1.ivivu.com/iVivu/2023/07/11/18/cruise-terminal-banner-cr.webp);
                "
              >
                <div class="most__love-text">
                  <p class="most__text-header">Singapore</p>
                  <p class="most__text-end">534 Khách Sạn</p>
                </div>
              </div>
            </div>
          </div>

          <div class="row sm-gutter">
            <div class="col-4 col-sm-4">
              <div
                class="most__love-img most__love-img-3"
                style="
                  background-image: url(https://cdn1.ivivu.com/iVivu/2023/07/11/18/image-62d0243c31a586-52735367-cr.webp);
                "
              >
                <div class="most__love-text">
                  <p class="most__text-header">San Francisco</p>
                  <p class="most__text-end">382 Khách Sạn</p>
                </div>
              </div>
            </div>

            <div class="col-4 col-sm-4">
              <div
                class="most__love-img most__love-img-3"
                style="
                  background-image: url(https://cdn1.ivivu.com/iVivu/2023/07/11/18/photo-1624138784614-87fd1b6528f8-cr.webp);
                "
              >
                <div class="most__love-text">
                  <p class="most__text-header">Sydney</p>
                  <p class="most__text-end">290 Khách Sạn</p>
                </div>
              </div>
            </div>

            <div class="col-4 col-sm-4">
              <div
                class="most__love-img most__love-img-3"
                style="
                  background-image: url(https://cdn1.ivivu.com/iVivu/2023/02/13/17/maldives-cr.webp);
                "
              >
                <div class="most__love-text">
                  <p class="most__text-header">Madives</p>
                  <p class="most__text-end">613 Khách Sạn</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--End Body  -->
  </div>
</template>

<script>
import { ref, defineComponent, reactive, toRefs, inject } from "vue";
import { message, AutoComplete } from "ant-design-vue";
// import dayjs from "dayjs";
import { useRouter, useRoute } from "vue-router";

export default defineComponent({
  setup() {
    const router = useRouter();
    const route = useRoute();

    const $loading = inject("$loading");

    const search = ref("");

    const hotel = () => {
      axios
        .get(`http://127.0.0.1:8000/api/hotel/home`)
        .then((response) => {
          console.log(response);
        })
        .catch((error) => {
          console.log(error);
        });
    };
    hotel();

    return { search };
  },
  components: { AutoComplete },
});
</script>

<style></style>
