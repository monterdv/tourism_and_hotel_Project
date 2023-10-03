<template>
  <div class="container">
    <div class="tour__detail">
      <div class="grid wide">
        <div class="row">
          <h1 class="tour__header mb-2">
            {{ tour.title }}
          </h1>

          <div class="col l-8 m-12 c-12">
            <div class="tour__detail-decription">
              <Carousel arrows dots-class="slick-dots slick-thumb">
                <template #customPaging="props">
                  <a>
                    <img :src="getImgUrl(tour.tour_paths[props.i].path)" />
                  </a>
                </template>
                <div v-for="(img, index) in tour.tour_paths" :key="index">
                  <img :src="getImgUrl(img.path)" />
                </div>
              </Carousel>
            </div>

            <div class="col l-12 c-12 m-12">
              <div class="tour__detail-p">
                <div class="tour__detail-detail">
                  <div
                    class="tour__detail-detail-decrition"
                    v-html="tour.introduce"
                  ></div>
                </div>

                <div class="tour__detail-detail">
                  <div class="tour__detail-detail-decrition" v-html="tour.schedule"></div>
                </div>

                <div class="tour__detail-detail">
                  <h1 class="tour__detail-detail-header">TravelMate®</h1>
                  <p class="tour__detail-detail-decrition">
                    TravelMate® là chuyên viên tư vấn về Du Thuyền của iVIVU, giúp tối ưu
                    hóa hành trình trải nghiệm theo phong cách của quý khách và là bạn
                    đồng hành trực tuyến đáng tin cậy trong suốt chuyến đi.
                    <br />

                    - Trước khi khởi hành, TravelMate® sẽ liên lạc và hỗ trợ quý khách
                    việc chuẩn bị cho chuyến đi Singapore - Malaysia, tư vấn và giải đáp
                    các thắc mắc liên quan đến lịch trình khai báo y tế, di chuyển ra bến
                    cảng và các thông tin cần thiết khác.
                    <br />

                    - Trong suốt hành trình trải nghiệm, TravelMate® luôn kết nối trực
                    tuyến và sẵn sàng cung cấp, tư vấn thông tin tại điểm đến như tham
                    quan, tour trải nghiệm địa phương, ăn uống, đi lại, mua sắm...và các
                    trường hợp khẩn cấp.
                    <br />

                    - TravelMate® tiếp nhận và xử lý các phản hồi sau khi hành trình kết
                    thúc và quý khách đã về đến Việt Nam.
                  </p>
                </div>
              </div>
            </div>
          </div>

          <div class="col l-4 m-0 c-0">
            <div class="slide-bar__left" style="margin-bottom: 10px">
              <div class="schedue">
                <h1 class="schedule-header">Launch schedule & price</h1>
                <span class="schedule-end">Select departure date:</span>
                <!-- <DatePicker
                  v-model:value="value1"
                  :disabled-date="disabledDate"
                  format="DD-MM-YYYY"
                >
                  <template #dateRender="{ current }">
                    <div class="ant-picker-cell-inner" :style="getCurrentStyle(current)">
                      {{ current.date() }}
                    </div>
                    <div v-if="notes[current.date()]" class="note">
                      {{ notes[current.date()] }}
                    </div>
                  </template>
                </DatePicker> -->
                <a-select
                  show-search
                  placeholder="status"
                  style="width: 50%"
                  :options="time"
                  v-model:value="tourid"
                ></a-select>
              </div>

              <div class="schedue-day">
                <!-- <div class="schedue-day__list">
        <div class="schedue-day__item">22/08</div>
      </div> -->

                <!-- <div class="schedue-day__all">
        <div class="schedue-day__icon">
          <i class="fa-solid fa-calendar-days"></i>
        </div>
        <p class="schedue-day__text">Tất Cả</p>
      </div> -->
              </div>

              <div class="schedue-people">
                <div class="schedue-pepleo__all">
                  <p class="schedue-text">Adult:</p>
                  <p class="schedue-price">12.990.000 đ</p>
                  <p class="schedue-text">
                    <InputNumber min="1" style="width: 50%" v-model:value="Adult"> </InputNumber>
                    people
                  </p>
                </div>
              </div>

              <div class="schedue-people">
                <div class="schedue-pepleo__all">
                  <p class="schedue-text">Children:</p>
                  <p class="schedue-price">12.990.000 đ</p>
                  <p class="schedue-text">
                    <InputNumber min="0" style="width: 50%" v-model:value="Children"> </InputNumber> people
                  </p>
                </div>
              </div>

              <div class="slider-note">
                <div class="slider-note__item">
                  <i class="fa-solid fa-circle-info"></i>
                </div>
                <p class="slider-note__text">Contact to confirm</p>
              </div>

              <div class="slider-note">
                <p class="slider-price__total">total</p>
                <p class="slider-price__root">25.980.000VND</p>
              </div>

              <div class="tours-detail__contact">
                <div class="detail__contact-support justy">
                  <p class="detail__contact-support--text">Contact Consulting</p>
                </div>

                <div class="detail__contact-required">
                  <p class="detail__contact-required--text">Order Request</p>
                </div>
              </div>
            </div>

            <div class="slide-bar__left">
              <div class="row">
                <div class="col l-6">
                  <div class="slide__bar-all">
                    <div class="slide-bar__icon">
                      <i class="fa-solid fa-check"></i>
                    </div>
                    <p class="slide-bar__text">5 star cruise</p>
                  </div>
                </div>
                <div class="col l-6">
                  <div class="slide__bar-all">
                    <div class="slide-bar__icon">
                      <i class="fa-solid fa-check"></i>
                    </div>
                    <p class="slide-bar__text">TravelMate®</p>
                  </div>
                </div>
                <div class="col l-6">
                  <div class="slide__bar-all">
                    <div class="slide-bar__icon">
                      <i class="fa-solid fa-check"></i>
                    </div>
                    <p class="slide-bar__text">Shows</p>
                  </div>
                </div>

                <div class="col l-6">
                  <div class="slide__bar-all">
                    <div class="slide-bar__icon">
                      <i class="fa-solid fa-check"></i>
                    </div>
                    <p class="slide-bar__text">Full meal package</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="tour__foreign-country" style="margin-top: 6px">
            <div class="grid wide"></div>
          </div>

          <div class="tour__foreign-country" style="margin-top: 6px">
            <div class="grid wide">
              <div class="row sm-gutter">
                <div class="col l-12">
                  <div class="combo">
                    <p class="combo__hot">Tour Nước Ngoài Cao Cấp</p>
                    <p class="combo__person">Trải Nghiệm Thế Giới, Khám Phá Bản Thân</p>
                  </div>
                </div>
              </div>

              <div class="tours">
                <div class="row sm-gutter">
                  <div class="col l-4 m-6 c-12">
                    <a href="" class="home__tour-item">
                      <div
                        class="home__tour-item-img"
                        style="
                          background-image: url(//cdn2.ivivu.com/2018/09/14/10/ivivu-toa-thap-doi-twin-towers--360x225.jpg);
                        "
                      ></div>
                      <h4 class="home__tour-name">
                        Tour Liên Tuyến Ba Nước 6N5Đ: Singapore - Indonesia - Malaysia
                      </h4>
                      <div class="home__tour-mark">
                        <p class="tour__mark">8.3</p>
                        <p class="tour__classification">Tốt</p>
                        <p class="rate">13 đánh giá</p>
                      </div>
                      <ul class="tour__related">
                        <li class="tour__related-item">- Gardens by the Bay</li>
                        <li class="tour__related-item">- Đảo Ngọc Batam</li>
                        <li class="tour__related-item">- Tàu Cao Tốc Đi Thẳng Ba Nước</li>
                      </ul>
                      <div class="tour__price">
                        <span class="tour__price-old"> </span>
                        <span class="tour__price-new">12.690.000 đ</span>
                      </div>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent, ref, toRefs, inject, reactive } from "vue";
import { InputNumber, message, Carousel } from "ant-design-vue";
import { useRouter, useRoute } from "vue-router";
import dayjs from "dayjs";

// import "../../../../../public/assets/js/slide";

export default defineComponent({
  setup() {
    const route = useRoute();

    const $loading = inject("$loading");

    const tour = ref([]);
    const time = ref([]);

    const bookTour = reactive({
      tourid: "",
      Adult: 2,
      Children: 0,
    });

    const getTour = () => {
      const loader = $loading.show({});
      axios
        .get(`http://127.0.0.1:8000/api/tour/${route.params.slug}`)
        .then(function (response) {
          console.log(response);
          tour.value = response.data.data.tour;
          time.value = response.data.data.tourTime;
          bookTour.tourid = response.data.data.tourTime[0].value;
          loader.hide();
        })
        .catch(function (error) {
          console.error(error);
          loader.hide();
        });
    };
    getTour();

    return { tour, time, ...toRefs(bookTour) };
  },
  components: {
    Carousel,
    InputNumber,
  },
  methods: {
    getImgUrl(path) {
      if (path) {
        return path;
      } else {
        return "default-image.jpg"; // Đường dẫn ảnh mặc định
      }
    },
  },
});
</script>

<style scoped>
:deep(.slick-dots) {
  position: relative;
  height: auto;
}
:deep(.slick-slide img) {
  border: 5px solid #fff;
  display: block;
  margin: auto;
  max-width: 80%;
}
:deep(.slick-arrow) {
  display: none !important;
}
:deep(.slick-thumb) {
  bottom: 0px;
}
:deep(.slick-thumb li) {
  width: 60px;
  height: 45px;
}
:deep(.slick-thumb li img) {
  width: 100%;
  height: 100%;
  filter: grayscale(100%);
  display: block;
}
:deep .slick-thumb li.slick-active img {
  filter: grayscale(0%);
}
</style>
