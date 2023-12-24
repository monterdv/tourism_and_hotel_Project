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
            <div class="mt-4 mb-4 fs-4">
              <div class="card-body">
                <div class="col-12 mt-4">
                  <div class="tour__detail-p">
                    <a-table
                      :dataSource="roomType"
                      :columns="columns"
                      :pagination="false"
                      bordered
                      :scroll="{ x: 800 }"
                    >
                      <template #bodyCell="{ column, record }">
                        <template v-if="column.key === 'KindOfRoom'">
                          <h3 class="room__title">{{ record.name }}</h3>
                          <Image :src="record.image" alt="Room" width="200px" />
                          <p class="rounded-pill bg-light text-dark fs-4 mb-3">
                            <font-awesome-icon
                              :icon="['fas', 'users']"
                              class="me-2 mt-2"
                            />
                            Maximum {{ record.max_adults }} adults &
                            {{ record.max_children }} children
                          </p>
                          <span class="rounded-pill bg-light text-dark fs-4 mb-3">
                            <font-awesome-icon :icon="['fas', 'bed']" class="me-2 mt-2" />
                            {{ record.bedtype.name }}
                          </span>
                        </template>
                        <template v-if="column.key === 'amenities'">
                          <span v-for="item in record.amenities" :key="item.id">
                            <a-tag :bordered="false" class="mb-2">{{ item.name }}</a-tag>
                          </span>
                        </template>
                        <template v-if="column.key === 'price'">
                          <p class="room__title">{{ record.price }} USD</p>
                        </template>
                        <template v-if="column.key === 'KeepRoom'">
                          <router-link
                            :to="{
                              name: 'booking-hotel',
                              params: { slug: record.slug, hotelid: record.hotel_id },
                            }"
                          >
                            <a-button type="primary" class="me-2"> book </a-button>
                          </router-link>
                        </template>
                      </template>
                    </a-table>
                  </div>
                  <div class="tour__detail-detail">
                    <!-- hotel information {{  }} -->
                    <div
                      class="tour__detail-detail-decrition"
                      v-html="hotel.introduce"
                    ></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12 col-sm-4">
            <div class="card mb-4 border shadow rounded-3 fs-4">
              <div class="card-body">
                <h3>hotel info</h3>
                <hr />
                <p>
                  <Rate v-model:value="hotel.star_rating" disabled />
                </p>
                <span class="badge rounded-pill bg-light text-dark fs-5 mb-3">
                  <font-awesome-icon :icon="['fas', 'earth-africa']" class="me-2" />
                  {{ hotel.place ? hotel.place.country : "" }}
                </span>
                <span class="badge rounded-pill bg-light text-dark fs-5 mb-3">
                  <font-awesome-icon
                    :icon="['fas', 'location-dot']"
                    class="me-2"
                  />address : {{ hotel.address }}
                </span>
                <span class="badge rounded-pill bg-light text-dark text-wrap fs-5 mb-3">
                  <font-awesome-icon :icon="['far', 'clock']" class="me-2" />
                  Check-in Time: {{ formatTimeTo12Hour(hotel.checkin_time) }}
                </span>
                <span class="badge rounded-pill bg-light text-dark text-wrap fs-5 mb-3">
                  <font-awesome-icon :icon="['far', 'clock']" class="me-2" />
                  Check-out Time: {{ formatTimeTo12Hour(hotel.checkout_time) }}
                </span>
              </div>
            </div>
            <div class="card mb-4 border-0 shadow-0 rounded-3 fs-4" v-if="hotelRelevant">
              <div class="card-body">
                <h3>hotel relevant</h3>
                <hr />
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
        title: "Kind of room",
        key: "KindOfRoom",
        width: 250,
      },
      {
        title: "amenities",
        key: "amenities",
      },
      {
        title: "price",
        key: "price",
        width: 105,
      },
      {
        title: "Keep room",
        key: "KeepRoom",
        fixed: "right",
        width: 105,
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
          // console.log(response);
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

    return {
      hotel,
      hotelImg,
      columns,
      roomType,
      hotelRelevant,
      formatTimeTo12Hour,
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
.room__title {
  color: #26bed6;
  font-weight: 600;
  font-size: 18px;
  line-height: 1.3;
  text-transform: capitalize;
}
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
