<template>
  <div class="container cart">
    <div class="grid wide">
      <div>
        <Steps :current="2" label-placement="vertical" :items="items" />
      </div>
      <div class="row">
        <div class="col-12 col-sm-8">
          <a-card style="width: 100%">
            <p class="combo__hot">
              Contact Info:
              <i
                ><span class="Required_Information">
                  Required Information(<span class="text-danger">*</span>)</span
                ></i
              >
            </p>
            <formInfo
              :errors="errors"
              :index="0"
              :nationality="nationality"
              :contactInfoData="contactInfos[0]"
            />
            <div v-if="totalslot !== null">
              <div v-for="item in totalslot - 1" :key="item">
                <hr />
                <p class="combo__hot">customer information #{{ item + 1 }}:</p>
                <formInfo
                  :errors="errors"
                  :index="item"
                  :nationality="nationality"
                  :contactInfoData="contactInfos[item]"
                />
              </div>
            </div>
            <hr />
            <p class="combo__hot">Select a payment method:</p>
            <div v-for="item in payment" :key="item.id">
              <paymentmethod
                :item="item"
                :activePlan="activePlan"
                @onUpdatePlan="updateActivePlan"
              />
            </div>
            <button class="btn-information mt-3" @click="customerInformation">
              <span class="text-white detail__contact-required--text">Continue</span>
            </button>
          </a-card>
        </div>
        <div class="col-12 col-sm-4">
          <div class="card">
            <img :src="image" class="card-img-top" :alt="title" />
            <div class="card-body fs-4">
              <h3 class="card-title">{{ title }}</h3>
              <p class="card-text">
                <font-awesome-icon :icon="['far', 'calendar']" /> tour code:
                {{ tour_code }}
              </p>
              <p class="card-text">
                <font-awesome-icon :icon="['far', 'calendar']" /> departure day:
                {{ date }}
              </p>
              <p class="card-text">
                <font-awesome-icon :icon="['far', 'clock']" /> time: {{ duration }} day
              </p>
              <p class="card-text">
                <font-awesome-icon :icon="['fas', 'user']" /> adult slot: {{ adults }}
              </p>
              <p class="card-text">
                <font-awesome-icon :icon="['fas', 'child-reaching']" /> children slot:
                {{ children }}
              </p>
              <p class="card-text fw-bold fs-3">
                total: <span class="total_color">{{ total }}</span> USD
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent, ref, reactive, toRefs, inject } from "vue";
import { message, Steps } from "ant-design-vue";
import { useRouter, useRoute } from "vue-router";
import formInfo from "../tour/ContactInfo.vue";
// import paymentmethod from "../payment.vue"
import paymentmethod from "../tour/payment.vue"

export default defineComponent({
  setup() {
    const router = useRouter();
    const route = useRoute();
    const $loading = inject("$loading");
    const contactInfos = ref([]);
    const errors = ref({});
    const payment = ref([]);
    const nationality = ref([]);
    const activePlan = ref();

    const booking = reactive({
      title: null,
      date: null,
      image: null,
      adults: null,
      children: null,
      id: null,
      duration: null,
      total: null,
      totalslot: null,
      tour_code: null,
      payment_id: null,
      tour_id: null,
      tours_time_id: null,
      user_id: null,
    });

    const items = ref([
      {
        title: "Finished",
        description: "login",
      },
      {
        title: "Finished",
        description: "Choose a tour",
      },
      {
        title: "In Progress",
        description: "customer information",
      },
      {
        title: "Waiting",
        description: "Select a payment method",
      },
      {
        title: "Waiting",
        description: "pay",
      },
    ]);
    const bookingtour = () => {
      const loader = $loading.show({});
      axios
        .get(
          `http://127.0.0.1:8000/api/bookingtour/booking/${route.query.id}/${route.query.adults}/${route.query.children}`
        )
        .then((response) => {
          // console.log(response);
          booking.id = response.data.data.booking_detail.id;
          booking.image = response.data.data.booking_detail.image;
          booking.tour_id = response.data.data.booking_detail.tour_id;
          booking.tours_time_id = response.data.data.booking_detail.tours_time_id;
          booking.user_id = response.data.data.booking_detail.user_id;
          booking.title = response.data.data.booking_detail.tour.title;
          booking.date = response.data.data.booking_detail.tours_times.date;
          booking.tour_code = response.data.data.booking_detail.tours_times.Time_Code;
          booking.adults = route.query.adults;
          booking.children = route.query.children;
          booking.duration = response.data.data.booking_detail.tour.duration;
          booking.total = response.data.data.total;
          booking.totalslot = response.data.data.totalslot;
          payment.value = response.data.data.payment;
          nationality.value = response.data.data.nationality;
          contactInfos.value = Array.from({ length: booking.totalslot }, (_, index) => {
            return {
              name: null,
              phone: null,
              email: null,
              address: null,
              passport: null,
              nationality: null,
            };
          });

          loader.hide();
          //   console.log(booking);
          //   console.log(contactInfos);
        })
        .catch((error) => {
          console.log(error);
          if (error) {
            message.error(error.response.data.error);
            router.push({
              name: "cart",
            });
          }
          loader.hide();
        });
    };
    bookingtour();

    const prepareFormData = () => {
      const formData = new FormData();
      contactInfos.value.forEach((contactInfoData, index) => {
        formData.append(`customer[${index}][name]`, contactInfoData.name ?? "");
        formData.append(`customer[${index}][phone]`, contactInfoData.phone ?? "");
        formData.append(`customer[${index}][email]`, contactInfoData.email ?? "");
        // formData.append(`customer[${index}][address]`, contactInfoData.address ?? "");
        formData.append(`customer[${index}][passport]`, contactInfoData.passport ?? "");
        formData.append(
          `customer[${index}][nationality]`,
          contactInfoData.nationality ?? ""
        );
        formData.append("payment_id", booking.payment_id ?? "");
        formData.append("adults", booking.adults ?? "");
        formData.append("children", booking.children ?? "");
        formData.append("cart_id", booking.id ?? "");
        formData.append("user_id", booking.user_id ?? "");
      });
      formData.append("totalPrice", booking.total ?? "");
      formData.append("tour_id", booking.tour_id ?? "");
      formData.append("tourTime_id", booking.tours_time_id ?? "");
      return formData;
    };

    const handleApiError = (error) => {
      console.log(error);
      if (error.response) {
        if (error.response.status === 400) {
          message.error(error.response.data.message);
        } else if (error.response.status === 422) {
          handleValidationErrors(error.response.data.errors);
        } else {
          message.error("An unexpected error occurred.");
        }
      } else {
        message.error("An unexpected error occurred.");
      }
      $loading.hide();
    };

    const handleValidationErrors = (validationErrors) => {
      let errorDetails = [];
      if (validationErrors) {
        for (let key in validationErrors) {
          if (
            key.includes("customer") &&
            Array.isArray(validationErrors[key]) &&
            validationErrors[key].length > 0
          ) {
            let customerNumber = parseInt(key.split(".")[1]);
            if (!errorDetails[customerNumber]) {
              errorDetails[customerNumber] = {};
            }
            let fieldName = key.split(".")[2];
            if (!errorDetails[customerNumber][fieldName]) {
              errorDetails[customerNumber][fieldName] = [];
            }
            errorDetails[customerNumber][fieldName].push(validationErrors[key][0]);
          }
        }
      }
      errors.value = errorDetails;
    };

    const customerInformation = () => {
      const loader = $loading.show({});
      const formData = prepareFormData();
      axios
        .post(`http://127.0.0.1:8000/api/bookingtour/customerInformation`, formData)
        .then((response) => {
          // console.log(response);
          if (response.data.message == "1") {
            initiatePayment();
          } else {
            // console.log(response);
            router.replace({
              name: "checkout",
              params: { code: response.data.code },
            });
          }
          loader.hide();
        })
        .catch((error) => {
          loader.hide();
          handleApiError(error);
        });
    };
    const updateActivePlan = (plan) => {
      if (activePlan.value == plan) {
        activePlan.value = null;
        booking.payment_id = null;
      } else {
        activePlan.value = plan;
        booking.payment_id = plan;
      }
    };
    const initiatePayment = () => {
      const loader = $loading.show({});
      const formData = prepareFormData();
      axios
        // Gọi API Laravel để tạo và trả về URL thanh toán PayPal
        .post("http://127.0.0.1:8000/api/paypal/payment/tour", formData)
        .then((response) => {
          // Redirect đến URL thanh toán PayPal
          // console.log(response);
          loader.hide();
          if (response.data.redirect_url) {
            window.location.href = response.data.redirect_url;
          }
        })
        .catch((error) => {
          loader.hide();
          // handleApiError(error);
        });
    };

    return {
      payment,
      contactInfos,
      activePlan,
      items,
      errors,
      nationality,
      updateActivePlan,
      initiatePayment,
      customerInformation,
      ...toRefs(booking),
    };
  },
  components: {
    Steps,
    formInfo,
    paymentmethod,
  },
});
</script>

<style></style>
