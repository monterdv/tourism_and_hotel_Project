<template>
  <div class="container">
    <div class="tour__detail">
      <div class="grid wide">
        <div class="row">
          <h1 class="tour__header mb-2">
            {{ hotel.title }}
          </h1>

          <div class="col-12 col-sm-8">
            <div class="tour__detail-decription">
              <Carousel arrows dots-class="slick-dots slick-thumb">
                <template #customPaging="props">
                  <a>
                    <img :src="getImgUrl(hotel.hotel_paths[props.i].path)" />
                  </a>
                </template>
                <div v-for="(img, index) in hotel.hotel_paths" :key="index">
                  <img :src="getImgUrl(img.path)" />
                </div>
              </Carousel>
            </div>

            <div class="col l-12 c-12 m-12">
              <div class="tour__detail-p">
                <a-table :dataSource="roomType" :columns="columns">
                  <template #bodyCell="{ column, record }">
                    <template v-if="column.key === 'ImageOfRoom'">
                      <Image :src="record.image" alt="Room" width="100px" />
                    </template>

                    <template v-if="column.key === 'widgetRoom'"> a</template>

                    <template v-if="column.key === 'booking'">
                      <router-link :to="{}">
                        <a-button type="primary" class="me-2"> book </a-button>
                      </router-link>
                    </template>
                  </template>
                </a-table>
              </div>
            </div>

            <div class="tour__detail-detail">
              <div class="tour__detail-detail-decrition" v-html="hotel.introduce"></div>
            </div>
          </div>

          <div class="col-12 col-sm-4">
            <div class="tour__detail-p">
              <div class="tour__detail-detail">
                <div class="tour__detail-detail-decrition">
                  <p>address: {{ hotel.address }}</p>
                  <p>star rating: {{ hotel.star_rating }}sao</p>
                  <p>checkin time: {{ hotel.checkin_time }}</p>
                  <p>checkout time: {{ hotel.checkout_time }}</p>
                  <p>country: {{ hotel.place.country }}</p>
                  <p>area: {{ hotel.place.area }}</p>
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
import { InputNumber, message, Carousel, Image } from "ant-design-vue";
import { useRouter, useRoute } from "vue-router";
import dayjs from "dayjs";

// import "../../../../../public/assets/js/slide";

export default defineComponent({
  setup() {
    const route = useRoute();

    const $loading = inject("$loading");

    const hotel = ref([]);
    const roomType = ref([]);
    const columns = [
      {
        title: "Image of room",
        dataIndex: "ImageOfRoom",
        key: "ImageOfRoom",
      },
      {
        title: "Kind of room",
        dataIndex: "name",
        key: "name",
      },
      {
        title: "widget",
        dataIndex: "widgetRoom",
        key: "widgetRoom",
      },
      {
        title: "price",
        dataIndex: "base_price",
        key: "base_price",
      },
      {
        title: "book room",
        dataIndex: "booking",
        key: "booking",
      },
    ];

    const gethotel = () => {
      const loader = $loading.show({});
      axios
        .get(`http://127.0.0.1:8000/api/hotel/${route.params.slug}`)
        .then(function (response) {
          console.log(response);
          hotel.value = response.data.data.hotel;
          roomType.value = response.data.data.roomType;

          loader.hide();
        })
        .catch(function (error) {
          console.error(error);
          loader.hide();
        });
    };
    gethotel();

    return {
      hotel,
      columns,
      roomType,
    };
  },
  components: {
    Carousel,
    InputNumber,
    Image,
  },
  methods: {
    getImgUrl(path) {
      if (path) {
        return path;
      } else {
        return "default-image.jpg";
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
