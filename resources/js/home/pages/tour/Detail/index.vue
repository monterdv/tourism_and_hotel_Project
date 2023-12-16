<template>
  <div class="container">
    <div class="tour__detail">
      <div class="grid wide">
        <div class="row">
          <h1 class="tour__header mb-2">
            {{ tour.title }}
          </h1>

          <div class="col-12 col-sm-8">
            <div class="tour__detail-decription">
              <slides :Img="tourImg" />
            </div>
            <div class="hotline d-flex justify-content-center">
              <div class="grid wide">
                <div class="row">
                  <div
                    class="col-12 col-sm-4 d-sm-flex justify-content-sm-center mx-auto"
                  >
                    <div class="hotline-main">
                      <div class="hotline-icon">
                        <font-awesome-icon :icon="['fas', 'calendar']" />
                      </div>
                      <div class="hotline__text">
                        <div class="hotline__text-header">duration</div>
                        <div class="hotline__text-end">
                          {{ tour.duration ? tour.duration : "" }} Day
                        </div>
                      </div>
                    </div>
                  </div>

                  <div
                    class="col-12 col-sm-4 d-sm-flex justify-content-sm-center mx-auto"
                  >
                    <div class="hotline-main">
                      <div class="hotline-icon">
                        <font-awesome-icon :icon="['fas', 'location-dot']" />
                      </div>
                      <div class="hotline__text">
                        <div class="hotline__text-header">place</div>
                        <div class="hotline__text-end">
                          {{ tour.place && tour.place.country ? tour.place.country : "" }}
                        </div>
                      </div>
                    </div>
                  </div>

                  <div
                    class="col-12 col-sm-4 d-sm-flex justify-content-sm-center mx-auto"
                  >
                    <div class="hotline-main">
                      <div class="hotline-icon">
                        <font-awesome-icon :icon="['fas', 'truck-plane']" />
                      </div>
                      <div class="hotline__text">
                        <div class="hotline__text-header">vehicle</div>
                        <div class="hotline__text-end">
                          {{ tour.vehicle && tour.vehicle.name ? tour.vehicle.name : "" }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-12 col-sm-12">
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

          <div class="col-12 col-sm-4">
            <!-- <form @submit.prevent="AddTocard" enctype="multipart/form-data"> -->
            <div class="slide-bar__left" style="padding-bottom: 50px">
              <div class="schedue">
                <h1 class="schedule-header">Launch schedule & price</h1>
                <span class="schedule-end">Select departure date:</span>
                <a-select
                  show-search
                  placeholder="status"
                  style="width: 50%"
                  :options="time"
                  v-model:value="time_id"
                  @change="handleChange"
                ></a-select>
              </div>

              <div class="schedue-people">
                <div class="schedue-pepleo__all">
                  <p class="schedue-text">Adult:</p>
                  <p class="schedue-price">{{ priceAdult }}</p>
                  <p class="schedue-slot">
                    <InputNumber min="1" style="width: 50%" v-model:value="Adult">
                    </InputNumber>
                    people
                  </p>
                </div>
              </div>

              <div class="schedue-people">
                <div class="schedue-pepleo__all">
                  <p class="schedue-text">Children:</p>
                  <p class="schedue-price">{{ priceChildren }}</p>
                  <p class="schedue-slot">
                    <InputNumber min="0" style="width: 50%" v-model:value="Children">
                    </InputNumber>
                    people
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
                <p class="slider-price__root">
                  {{ priceAdult * Adult + priceChildren * Children }}
                  <!-- {{ totalPrice }} -->
                </p>
              </div>

              <div class="tours-detail__contact">
                <!-- <div class="detail__contact-support justy">
                  <span class="detail__contact-support--text">Contact</span>
                </div> -->

                <div class="detail__contact-required">
                  <span @click="AddTocard" class="detail__contact-required--text"
                    >add to cart</span
                  >
                </div>
              </div>
            </div>
            <!-- </form> -->
            <!-- tour lien quan -->
            <div class="col-12" v-for="item in tourRelevant" :key="item.id">
              <router-link
                :to="{ name: 'tour-detail', params: { slug: item.slug } }"
                :key="item.slug"
                class="link"
              >
                <Card
                  :title="item.title"
                  :image="item.image"
                  :price="item.price"
                  :status="item.status"
                  :Code="item.tour_Code"
                />
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent, ref, toRefs, inject, reactive, watch } from "vue";
import { InputNumber, message, Carousel } from "ant-design-vue";
import { useRouter, useRoute } from "vue-router";
import Card from "../../../../components/Card.vue";
import slides from "../../../../components/slides.vue";

export default defineComponent({
  setup() {
    const route = useRoute();
    const $loading = inject("$loading");
    const tour = ref([]);
    const time = ref([]);
    const tourImg = ref([]);
    const tourRelevant = ref([]);

    const bookTour = reactive({
      time_id: "",
      tour_id: "",
      Adult: 2,
      Children: 0,
    });

    let price = reactive({
      priceAdult: null,
      priceChildren: null,
    });

    // Watch for changes in 'route.params.slug' and update tour data accordingly
    watch(
      () => route.params.slug,
      (newSlug, oldSlug) => {
        if (newSlug !== oldSlug) {
          getTour();
        }
      }
    );

    const getTour = () => {
      const loader = $loading.show({});
      axios
        .get(`http://127.0.0.1:8000/api/tour/${route.params.slug}`)
        .then(function (response) {
          console.log(response);
          tour.value = response.data.data.tour;
          time.value = response.data.data.tourTime;
          tourImg.value = response.data.data.tour.tour_paths;
          tourRelevant.value = response.data.data.tourRelevant;
          bookTour.tour_id = response.data.data.tour.id;
          bookTour.time_id = response.data.data.tourTime[0].value;

          if (time.value.length > 0) {
            const { price_adults, price_children } = time.value[0];
            price.priceAdult = price_adults;
            price.priceChildren = price_children;
          }
          loader.hide();
        })
        .catch(function (error) {
          console.error(error);
          loader.hide();
        });
    };
    getTour();

    const handleChange = (value) => {
      // Tìm thời gian được chọn dựa trên giá trị 'value'
      const selectedTime = time.value.find((timeItem) => timeItem.value === value);

      if (selectedTime) {
        // Nếu thời gian được chọn tồn tại, cập nhật 'price' dựa trên giá trị của thời gian này
        const { price_adults, price_children } = selectedTime;
        price.priceAdult = price_adults;
        price.priceChildren = price_children;

        // updateTotalPrice();
      }
    };

    const AddTocard = () => {
      const loader = $loading.show({});
      const formData = new FormData();
      formData.append("tour_id", bookTour.tour_id);
      formData.append("time_id", bookTour.time_id);
      formData.append("Adult", bookTour.Adult);
      formData.append("Children", bookTour.Children);
      axios
        .post(`http://127.0.0.1:8000/api/bookingtour/addtocar`, formData)
        .then(function (response) {
          console.log(response);
          if (response.data.status == 1) {
            message.warning(response.data.message);
          } else {
            message.success(response.data.message);
          }
          loader.hide();
        })
        .catch(function (error) {
          // console.error(error);
          message.error(error.response.data.error);
          loader.hide();
        });
    };

    return {
      tour,
      time,
      tourRelevant,
      tourImg,
      ...toRefs(bookTour),
      ...toRefs(price),
      AddTocard,
      handleChange,
    };
  },
  components: {
    Carousel,
    InputNumber,
    Card,
    slides,
  },
  methods: {},
});
</script>

<style>
.thumbnails {
  margin: auto;
  max-width: 300px;
}

.thumbnails .vueperslide {
  box-sizing: border-box;
  border: 1px solid #fff;
  transition: 0.3s ease-in-out;
  opacity: 0.7;
  cursor: pointer;
}

.thumbnails .vueperslide--active {
  box-shadow: 0 0 6px rgba(0, 0, 0, 0.3);
  opacity: 1;
  border-color: #000;
}
.tour__detail-detail img {
  width: 100%;
}
</style>
