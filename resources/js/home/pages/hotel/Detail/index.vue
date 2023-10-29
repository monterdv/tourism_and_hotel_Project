<template>
  <div class="container">
    <div class="tour__detail">
      <div class="grid wide">
        <div class="row" v-if="hotel">
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
                <div class="hotel_info">
                  <p>hotel info</p>
                </div>
                <div class="tour__detail-detail-decrition">
                  <p>
                    <span class="detailed_information"
                      ><font-awesome-icon
                        :icon="['fas', 'location-dot']"
                        class="me-2"
                      />address:</span
                    >{{ hotel.address }}
                  </p>
                  <p>
                    <span class="detailed_information">Star Rating:</span>
                    <Rate v-model:value="hotel.star_rating" disabled />
                  </p>
                  <p>
                    <span class="detailed_information">Check-in Time:</span
                    >{{ formatTimeTo12Hour(hotel.checkin_time) }}
                  </p>
                  <p>
                    <span class="detailed_information">Check-out Time:</span>
                    {{ formatTimeTo12Hour(hotel.checkout_time) }}
                  </p>
                </div>
              </div>
            </div>

            <div class="tour__detail-p" style="font-size: 30px">
              <div class="hotel_relevant_title">
                <p>hotel Relevant</p>
              </div>
              <div class="hotel_relevant_item">
                <div class="homeDetail-containerRight" v-if="hotelRelevant">
                  <div
                    class="homeDetail-containerRight-a"
                    v-for="item in hotelRelevant"
                    :key="item.id"
                  >
                    <router-link
                      :to="getHotelDetailLink(item.slug)"
                      class="link"
                      :key="item.slug"
                      @click="gethoteldata(item.slug)"
                    >
                      <!-- <div class="row item-relevant">
                        <div class="col-12 col-sm-3">
                          <img
                            :src="item.image"
                            :alt="item.title"
                            class="homeDetail-containerRight-img"
                          />
                        </div>
                        <div class="col-12 col-sm-9">
                          <div class="title-relevant">
                            {{ item.title }}
                            <div>
                              <Rate v-model:value="item.star_rating" disabled />
                            </div>
                            <div>
                              {{ item.address }}
                            </div>
                          </div>
                        </div>
                      </div> -->

                      <div class="row" style="height: 100px">
                        <div class="col-3 col-sm-3">
                          <img
                            :src="item.image"
                            alt=""
                            width="100"
                            height="100"
                            style="object-fit: cover"
                          />
                        </div>
                        <div class="col-7 col-sm-7">
                          <div class="relevant-tour">
                            <div class="relevant-title">{{ item.title }}</div>
                            <p class="relevant-place">address: {{ item.address }}</p>
                          </div>
                        </div>
                        <div class="col-2 col-sm-2">
                          <div class="relevant-price">
                            <p>{{ item.price }} USD</p>
                          </div>
                        </div>
                      </div>
                    </router-link>
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
import { InputNumber, message, Carousel, Image, Rate } from "ant-design-vue";
import { useRouter, useRoute } from "vue-router";
import dayjs from "dayjs";

// import "../../../../../public/assets/js/slide";

export default defineComponent({
  setup() {
    const route = useRoute();

    const $loading = inject("$loading");

    const hotel = ref([]);
    const roomType = ref([]);
    const hotelRelevant = ref([]);
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
          hotelRelevant.value = response.data.data.hotelRelevant;
          loader.hide();
        })
        .catch(function (error) {
          console.error(error);
          loader.hide();
        });
    };
    gethotel();

    function formatTimeTo12Hour(time) {
      return dayjs(time, "HH:mm:ss").format("HH:mm A");
    }

    const gethoteldata = (slug) => {
      const loader = $loading.show({});
      axios
        .get(`http://127.0.0.1:8000/api/hotel/${slug}`)
        .then(function (response) {
          console.log(response);
          hotel.value = response.data.data.hotel;
          roomType.value = response.data.data.roomType;
          hotelRelevant.value = response.data.data.hotelRelevant;
          loader.hide();
        })
        .catch(function (error) {
          console.error(error);
          loader.hide();
        });
    };

    return {
      hotel,
      gethoteldata,
      columns,
      roomType,
      hotelRelevant,
      formatTimeTo12Hour,
    };
  },
  components: {
    Carousel,
    InputNumber,
    Image,
    Rate,
  },
  methods: {
    getImgUrl(path) {
      if (path) {
        return path;
      } else {
        return "default-image.jpg";
      }
    },

    getHotelDetailLink(slug) {
      return {
        name: "hotel-detail", // Name of the route
        params: { slug }, // Pass the selected slug as the parameter
      };
    },
  },
});
</script>

<style scoped>
.relevant-price {
  line-height: 100px;
  font-size: 10px;
}
.relevant-title {
  font-weight: 600;
}
.relevant-tour {
  margin-left: 16px;
  font-size: 12px;
}
span.detailed_information {
  font-size: 16px;
  margin-right: 10px;
  font-weight: 600;
}
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
