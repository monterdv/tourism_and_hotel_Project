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
              <slides :Img="hotelImg" />
            </div>

            <div class="col l-12 c-12 m-12 mt-4">
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
                  <h1 class="schedule-header">hotel info</h1>
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

            <div class="tour__detail-p" style="font-size: 30px" v-if="hotelRelevant">
              <div class="hotel_relevant_title">
                <p>hotel Relevant</p>
              </div>

              <div class="col-12" v-for="item in hotelRelevant" :key="item.id">
                <router-link
                  :to="{ name: 'hotel-detail', params: { slug: item.slug } }"
                  :key="item.slug"
                  class="link"
                >
                  <Card
                    :title="item.title"
                    :image="item.image"
                    :price="item.price"
                    :Ratehotel="item.star_rating"
                    :address="item.address"
                  />
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent, ref, toRefs, inject, reactive, watch } from "vue";
import { InputNumber, message, Image, Rate } from "ant-design-vue";
import { useRouter, useRoute } from "vue-router";
import dayjs from "dayjs";
import slides from "../../../../components/slides.vue";
import Card from "../../../../components/Card.vue";

export default defineComponent({
  setup() {
    const route = useRoute();

    const $loading = inject("$loading");

    const hotel = ref([]);
    const roomType = ref([]);
    const hotelImg = ref([]);
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

    watch(
      () => route.params.slug,
      (newSlug, oldSlug) => {
        if (newSlug !== oldSlug) {
          gethotel();
        }
      }
    );

    const gethotel = () => {
      const loader = $loading.show({});
      axios
        .get(`http://127.0.0.1:8000/api/hotel/${route.params.slug}`)
        .then(function (response) {
          console.log(response);
          hotel.value = response.data.data.hotel;
          hotelImg.value = response.data.data.hotel.hotel_paths;
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
      hotelImg,
      columns,
      roomType,
      hotelRelevant,
      formatTimeTo12Hour,
      gethoteldata,
    };
  },
  components: {
    InputNumber,
    Image,
    Rate,
    slides,
    Card,
  },
  methods: {},
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
