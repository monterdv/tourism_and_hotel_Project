<template>
  <div class="container cart">
    <div class="grid wide">
      <div class="row">
        <div class="col-12 col-sm-7">
          <div class="card mb-4 border shadow rounded-3 fs-4">
            <div class="card-body">
              <h1 class="bookingPayment">
                person information
                <br />
                <i
                  ><span class="Required_Information fs-3">
                    Please fill in all information</span
                  ></i
                >
              </h1>
              <div class="row mb-4">
                <div class="col-12 col-sm-2 text-start text-sm-end">
                  <label>
                    <span class="text-danger me-1">*</span>
                    <span>Name:</span>
                  </label>
                </div>
                <div class="col-12 col-sm-10">
                  <a-input placeholder="input Name" allow-clear v-model:value="name" />
                  <div class="w-100"></div>
                  <small v-if="errors.name" class="text-danger">{{
                    errors.name[0]
                  }}</small>
                </div>
              </div>
              <div class="row mb-4">
                <div class="col-12 col-sm-2 text-start text-sm-end">
                  <label>
                    <span class="text-danger me-1">*</span>
                    <span>SDT:</span>
                  </label>
                </div>
                <div class="col-12 col-sm-10">
                  <a-input
                    placeholder="input phone number"
                    allow-clear
                    v-model:value="phoneNumber"
                  />
                  <div class="w-100"></div>
                  <small v-if="errors.phoneNumber" class="text-danger">{{
                    errors.phoneNumber[0]
                  }}</small>
                </div>
              </div>
              <div class="row mb-4">
                <div class="col-12 col-sm-2 text-start text-sm-end">
                  <label>
                    <span class="text-danger me-1">*</span>
                    <span>email:</span>
                  </label>
                </div>
                <div class="col-12 col-sm-10">
                  <a-input
                    placeholder="input Tour Name"
                    allow-clear
                    v-model:value="email"
                  />
                  <div class="w-100"></div>
                  <small v-if="errors.email" class="text-danger">{{
                    errors.email[0]
                  }}</small>
                </div>
              </div>
              <div class="row mb-4">
                <div class="col-12 col-sm-2 text-start text-sm-end">
                  <label>
                    <span class="text-danger me-1">*</span>
                    <span>date:</span>
                  </label>
                </div>
                <div class="col-12 col-sm-10">
                  <a-range-picker
                    v-model:value="bookingDate"
                    :disabled-date="disabledDate"
                    format="DD-MM-YYYY"
                    class="col-12 col-sm-12"
                    :bordered="false"
                    @change="handleprice"
                  />
                  <div class="w-100"></div>
                  <div class="row">
                    <div class="col-sm-6">
                      <small v-if="errors.Date_end" class="text-danger me-5">{{
                        errors.Date_end[0]
                      }}</small>
                    </div>
                    <div class="col-sm-6">
                      <small v-if="errors.Date_start" class="text-danger">{{
                        errors.Date_start[0]
                      }}</small>
                    </div>
                  </div>
                </div>
              </div>
              <hr />
              <button class="btn-information mt-3" @click="customerInformation">
                <span class="text-white detail__contact-required--text"
                  ><font-awesome-icon :icon="['fab', 'paypal']" class="me-2" />
                  payment</span
                >
              </button>
            </div>
          </div>
        </div>
        <div class="col-12 col-sm-5">
          <div class="card mb-4 border shadow rounded-3 fs-4">
            <div class="card-body">
              <h3 class="bookingPayment">{{ hotelName }}</h3>
              <hr />
              <img :src="image" class="card-img-top" :alt="hotelName" />

              <div class="badge rounded-pill text-dark fs-5 mb-3 d-flex">
                <Rate v-model:value="star" disabled />
              </div>
              <div class="badge rounded-pill text-dark fs-5 mb-3 d-flex">
                <font-awesome-icon :icon="['fas', 'earth-africa']" class="me-2" />
                Room type : {{ room_type }}
              </div>
              <div class="badge rounded-pill text-dark fs-5 mb-3 d-flex">
                <font-awesome-icon :icon="['fas', 'location-dot']" class="me-2" />
                Address : {{ address }}
              </div>
              <p class="badge rounded-pill text-dark fs-5 mb-3 d-flex">
                <font-awesome-icon :icon="['fas', 'users']" class="me-2" />
                {{ adults }} adults and {{ children }} children
              </p>
              <div class="badge rounded-pill text-dark fs-5 mb-3">
                <font-awesome-icon :icon="['fas', 'bed']" class="me-2" />
                bed type : {{ bed_type }}
              </div>
              <div class="badge rounded-pill text-dark fs-5 mb-3">
                <font-awesome-icon :icon="['far', 'clock']" class="me-2" />
                From {{ formatTimeTo12Hour(checkIn) }} to
                {{ formatTimeTo12Hour(checkOut) }} you will be counted as 1 day
              </div>
              <br />
              <div v-if="totalDays" class="badge rounded-pill text-dark fs-5 mb-3">
                <font-awesome-icon :icon="['fas', 'calendar-days']" class="me-2" />
                Total {{ totalDays }} Day
              </div>
              <hr />
              <p class="bookingPayment">Total payment</p>
              <div class="row">
                <div class="col-sm-6">
                  <i><span class="Required_Information fs-3">Total amount</span></i>
                </div>
                <div v-if="!totalprice" class="col-sm-6 d-flex justify-content-end">
                  <span class="totalPrice">{{ price }} USD/Day</span>
                </div>
                <div v-if="totalprice" class="col-sm-6 d-flex justify-content-end">
                  <span class="totalPrice">{{ totalprice }} USD</span>
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
import { defineComponent, ref, reactive, toRefs, inject } from "vue";
import { useRouter, useRoute } from "vue-router";
import { message, Rate } from "ant-design-vue";
import dayjs from "dayjs";

export default defineComponent({
  setup() {
    const router = useRouter();
    const route = useRoute();
    const $loading = inject("$loading");
    const contactInfos = reactive({
      roomId: null,
      name: null,
      phoneNumber: null,
      email: null,
      bookingDate: [],
      totalprice: null,
      totalDays: null,
    });

    const errors = ref({});

    const room = reactive({
      room_Id: null,
      hotelName: null,
      room_type: null,
      bed_type: null,
      address: null,
      star: null,
      adults: null,
      children: null,
      price: null,
      image: null,
      checkIn: null,
      checkOut: null,
    });

    const getbooling = () => {
      const loader = $loading.show({});
      axios
        .get(
          `http://127.0.0.1:8000/api/bookinghotel/${route.params.slug}/${route.params.hotelid}`
        )
        .then((response) => {
          console.log(response);
          room.room_Id = response.data.data.room.id;
          room.hotelName = response.data.data.room.hotel.title;
          room.room_type = response.data.data.room.name;
          room.bed_type = response.data.data.room.bedtype.name;
          room.address = response.data.data.room.hotel.address;
          room.star = response.data.data.room.hotel.star_rating;
          room.adults = response.data.data.room.max_adults;
          room.children = response.data.data.room.max_children;
          room.price = response.data.data.room.price;
          room.checkIn = response.data.data.room.hotel.checkin_time;
          room.checkOut = response.data.data.room.hotel.checkout_time;
          room.image = response.data.data.room.image;
          // star
          loader.hide();
        })
        .catch((error) => {
          console.log(error);
          loader.hide();
        });
    };
    getbooling();

    const disabledDate = (current) => {
      return current && current < dayjs().endOf("day");
    };

    function formatTimeTo12Hour(time) {
      return dayjs(time, "HH:mm:ss").format("HH:mm A");
    }
    const handleprice = () => {
      const loader = $loading.show({});
      if (
        contactInfos.bookingDate &&
        contactInfos.bookingDate[0] &&
        contactInfos.bookingDate[1]
      ) {
        const Date_start = dayjs(contactInfos.bookingDate[0]);
        const Date_end = dayjs(contactInfos.bookingDate[1]);

        const totalDays = Date_end.diff(Date_start, "day");
        contactInfos.totalprice = totalDays * room.price;
        contactInfos.totalDays = totalDays;
      } else {
        contactInfos.totalprice = 0;
        contactInfos.totalDays = 0;
        loader.hide();
      }
      loader.hide();
    };

    const customerFormData = () => {
      const formData = new FormData();
      formData.append(`name`, contactInfos.name ?? "");
      formData.append(`phoneNumber`, contactInfos.phoneNumber ?? "");
      formData.append(`email`, contactInfos.email ?? "");
      formData.append(`totalprice`, contactInfos.totalprice ?? "");
      formData.append(`totalDays`, contactInfos.totalDays ?? "");
      if (contactInfos.bookingDate) {
        formData.append("Date_start", contactInfos.bookingDate[0].format("YYYY-MM-DD"));
        formData.append("Date_end", contactInfos.bookingDate[1].format("YYYY-MM-DD"));
      }
      return formData;
    };

    const customerInformation = () => {
      const loader = $loading.show({});
      const formData = customerFormData();
      axios
        .post(`http://127.0.0.1:8000/api/bookinghotel/checkInformation`, formData)
        .then((response) => {
          console.log(response);
          // if (response.data.message == "1") {
          //   initiatePayment();
          // } else {
          //   // console.log(response);
          //   router.replace({
          //     name: "checkout",
          //     params: { code: response.data.code },
          //   });
          // }
          loader.hide();
        })
        .catch((error) => {
          console.log(error);
          loader.hide();
          if (error.response.data.errors) {
            errors.value = error.response.data.errors;
          } else {
            message.error(error.response.data.message);
          }
        });
    };
    return {
      ...toRefs(contactInfos),
      ...toRefs(room),
      formatTimeTo12Hour,
      customerInformation,
      handleprice,
      errors,
      disabledDate,
    };
  },
  components: {
    Rate,
  },
});
</script>

<style>
.totalPrice {
  font-weight: bold;
  color: #26bed6;
}
.bookingPayment {
  /* font-size: 21px; */
  font-weight: bold;
  line-height: 1.5;
  color: #003c71;
}
</style>
