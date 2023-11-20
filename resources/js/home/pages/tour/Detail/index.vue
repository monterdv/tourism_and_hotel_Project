<template>
  <div class="container">
    <div class="tour__detail">
      <div class="grid wide">
        <div class="row">
          <h1 class="tour__header mb-2">
            {{ tour.title }}
          </h1>

          <div class="col l-8 m-12 c-12">
            <!-- <vueper-slides fractions progress>
              <vueper-slide v-for="i in tour.tour_paths" :key="i" />
            </vueper-slides> -->
            <div class="tour__detail-decription">
              <vueper-slides :slide-ratio="1 / 4" autoplay fixed-height="480px">
                <vueper-slide
                  v-for="(slide, index) in tour.tour_paths"
                  :key="index"
                  :image="slide.path"
                />
              </vueper-slides>
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
            <form @submit.prevent="initiatePayment" enctype="multipart/form-data">
              <div class="slide-bar__left" style="margin-bottom: 10px">
                <div class="schedue">
                  <h1 class="schedule-header">Launch schedule & price</h1>
                  <span class="schedule-end">Select departure date:</span>
                  <a-select
                    show-search
                    placeholder="status"
                    style="width: 50%"
                    :options="time"
                    v-model:value="tourid"
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
                  <div class="detail__contact-support justy">
                    <div class="detail__contact-support--text">Contact Consulting</div>
                  </div>

                  <div class="detail__contact-required">
                    <a-button htmlType="submit">
                      <p class="detail__contact-required--text">Order Request</p>
                    </a-button>
                  </div>
                </div>
              </div>
            </form>
            <!-- tour lien quan -->
            <div class="col-12" v-for="item in tourRelevant" :key="item.id">
              <router-link
                :to="getDetailLink(item.slug)"
                class="link"
                :key="item.slug"
                @click="getdata(item.slug)"
              >
                <tourRelevant :item="item" />
              </router-link>
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
import tourRelevant from "../item/itemtourRelevant.vue";
import { VueperSlides, VueperSlide } from "vueperslides";
import "vueperslides/dist/vueperslides.css";

export default defineComponent({
  setup() {
    const route = useRoute();

    const $loading = inject("$loading");

    const tour = ref([]);
    const time = ref([]);
    const tourRelevant = ref([]);

    const bookTour = reactive({
      tourid: "",
      Adult: 2,
      Children: 0,
    });

    let price = reactive({
      priceAdult: null,
      priceChildren: null,
    });

    const getTour = () => {
      const loader = $loading.show({});
      axios
        .get(`http://127.0.0.1:8000/api/tour/${route.params.slug}`)
        .then(function (response) {
          console.log(response);
          tour.value = response.data.data.tour;
          time.value = response.data.data.tourTime;
          tourRelevant.value = response.data.data.tourRelevant;
          bookTour.tourid = response.data.data.tourTime[0].value;

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

    const getdata = (slug) => {
      const loader = $loading.show({});
      axios
        .get(`http://127.0.0.1:8000/api/tour/${slug}`)
        .then(function (response) {
          console.log(response);
          tour.value = response.data.data.tour;
          time.value = response.data.data.tourTime;
          tourRelevant.value = response.data.data.tourRelevant;
          bookTour.tourid = response.data.data.tourTime[0].value;

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

    const updateTotalPrice = () => {
      // Tính toán tổng giá trị dựa trên số lượng người lớn và trẻ em
      return price.priceAdult * bookTour.Adult + price.priceChildren * bookTour.Children;
    };

    const initiatePayment = () => {
      const loader = $loading.show({});
      const formData = new FormData();
      formData.append("tourid", bookTour.tourid);
      formData.append("totalPrice", updateTotalPrice());

      formData.append("slug", route.params.slug);

      formData.append("Adult", bookTour.Adult);
      formData.append("Children", bookTour.Children);

      formData.append("price", price.priceAdult);
      formData.append("priceChildren", price.priceChildren);
      axios
        // Gọi API Laravel để tạo và trả về URL thanh toán PayPal
        .post("http://127.0.0.1:8000/api/paypal/payment", formData)
        .then((response) => {
          // Redirect đến URL thanh toán PayPal
          console.log(response);
          loader.hide();
          if (response.data.redirect_url) {
            window.location.href = response.data.redirect_url;
          }
        })
        .catch((error) => {
          console.error("Error initiating payment:", error);
          loader.hide();
        });
    };

    return {
      tour,
      time,
      tourRelevant,
      ...toRefs(bookTour),
      ...toRefs(price),
      handleChange,
      getdata,
      initiatePayment,
    };
  },
  components: {
    Carousel,
    InputNumber,
    tourRelevant,
    VueperSlides,
    VueperSlide,
  },
  methods: {
    // getImgUrl(path) {
    //   if (path) {
    //     return path;
    //   } else {
    //     return "default-image.jpg"; // Đường dẫn ảnh mặc định
    //   }
    // },
    getDetailLink(slug) {
      return {
        name: "tour-detail",
        params: { slug },
      };
    },
  },
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
</style>
