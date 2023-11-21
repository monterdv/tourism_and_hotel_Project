<template>
  <div class="container">
    <SliderSearch :hotel="true" />
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
                <div
                  class="image__decription"
                  style="
                    background: url(https://www.ivivu.com/chu-de/combo-quoc-te) top center /
                      cover no-repeat;
                  "
                >
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
        <div v-if="placesDomestic.length > 0">
          <div class="col l-12">
            <div class="combo">
              <p class="combo__hot">Favorite destination in the country</p>
              <!-- <p class="combo__person">Lên rừng xuống biển. Trọn vẹn Việt Nam</p> -->
            </div>
          </div>

          <div class="home__love">
            <div class="row sm-gutter">
              <!-- col8 -->
              <div class="col-8 col-sm-8">
                <itemCol8 :place="placesDomestic[0]" />

                <div class="row sm-gutter">
                  <itemCol6 :place="placesDomestic[1]" />
                  <itemCol6 :place="placesDomestic[2]" />
                </div>
              </div>
              <itemCol4 :place="placesDomestic[3]" />
            </div>
          </div>

          <div class="home__love">
            <div class="row sm-gutter">
              <itemCol4 :place="placesDomestic[4]" />
              <div class="col-8 col-sm-8">
                <itemCol8 :place="placesDomestic[5]" />

                <div class="row sm-gutter">
                  <itemCol6 :place="placesDomestic[6]" />
                  <itemCol6 :place="placesDomestic[7]" />
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Nước Ngoài -->
        <div v-if="placesInternational.length > 0">
          <div class="col-12">
            <div class="combo">
              <p class="combo__hot">Favorite foreign destination</p>
              <!-- <p class="combo__person">Bao la thế giới. Bốn bể là nhà</p> -->
            </div>
          </div>
          <div class="home__love">
            <div class="row sm-gutter">
              <div class="col-8 col-sm-8">
                <itemCol8 :place="placesInternational[0]" />
              </div>

              <itemCol4 :place="placesInternational[1]" />

              <!-- <div class="col-4 col-sm-4">
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
            </div> -->
            </div>

            <div class="row sm-gutter">
              <itemCol4 :place="placesInternational[2]" />
              <itemCol4 :place="placesInternational[3]" />
              <itemCol4 :place="placesInternational[4]" />

              <!-- <div class="col-4 col-sm-4">
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
            </div> -->
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
import { useRouter, useRoute } from "vue-router";
import itemCol8 from "./item/item_col_8.vue";
import itemCol6 from "./item/item_col_6.vue";
import itemCol4 from "./item/item_col_4.vue";
// import {itemCol8, itemCol6, itemCol4} from "./item";
import SliderSearch from "../../../components/SliderSearch.vue";

export default defineComponent({
  setup() {
    const router = useRouter();
    const route = useRoute();

    const placesDomestic = ref([]);
    const placesInternational = ref([]);

    const $loading = inject("$loading");

    const hotel = () => {
      const loader = $loading.show({});
      axios
        .get(`http://127.0.0.1:8000/api/hotel/home`)
        .then((response) => {
          console.log(response);
          placesDomestic.value = response.data.data.placesDomestic;
          placesInternational.value = response.data.data.placesInternational;
          loader.hide();
        })
        .catch((error) => {
          console.log(error);
          loader.hide();
        });
    };
    hotel();

    return {
      placesDomestic,
      placesInternational,
    };
  },
  components: {
    AutoComplete,
    itemCol8,
    itemCol6,
    itemCol4,
    SliderSearch,
  },
});
</script>

<style>
.background-1 {
  background: url(https://www.ivivu.com/chu-de/combo-quoc-te) top center / cover no-repeat;
}
.background-2 {
  background: url(/assects/img/decripton/tb1.png) top center / cover no-repeat;
}
.background-3 {
  background: url(/assects/img/decripton/chon-3.jpg) top center / cover no-repeat;
}
.background-4 {
  background: url(/assects/img/decripton/dangcap1.webp) top center / cover no-repeat;
}
</style>
