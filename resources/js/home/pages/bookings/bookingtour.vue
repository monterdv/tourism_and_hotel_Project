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
            <ContactInfo :contactInfoData="contactInfos[0]" />
            <div v-if="totalslot !== null">
              <div v-for="item in totalslot - 1" :key="item">
                <hr />
                <p class="combo__hot">customer information #{{ item + 1 }}:</p>
                <ContactInfo :contactInfoData="contactInfos[item]" />
              </div>
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
import ContactInfo from "./ContactInfo.vue";

export default defineComponent({
  setup() {
    const router = useRouter();
    const route = useRoute();
    const $loading = inject("$loading");
    const contactInfos = ref([]);

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
          console.log(response);
          booking.id = response.data.data.booking_detail.id;
          booking.image = response.data.data.booking_detail.image;
          booking.title = response.data.data.booking_detail.tour.title;
          booking.date = response.data.data.booking_detail.tours_times.date;
          booking.adults = route.query.adults;
          booking.children = route.query.children;
          booking.duration = response.data.data.booking_detail.tour.duration;
          booking.total = response.data.data.total;
          booking.totalslot = response.data.data.totalslot;

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

    const customerInformation = () => {
      const loader = $loading.show({});
      const formData = new FormData();
      contactInfos.value.forEach((contactInfoData, index) => {
        formData.append(`name[${index}]`, contactInfoData.name);
        formData.append(`phone[${index}]`, contactInfoData.phone);
        formData.append(`email[${index}]`, contactInfoData.email);
        formData.append(`address[${index}]`, contactInfoData.address);
        formData.append(`passport[${index}]`, contactInfoData.passport);
        formData.append(`nationality[${index}]`, contactInfoData.nationality);
      });
      axios
        .post(`http://127.0.0.1:8000/api/bookingtour/customerInformation`, formData)
        .then((response) => {
          console.log(response);

          loader.hide();
        })
        .catch((error) => {
          console.log(error);
          //   message.error(error.response.data.error);
          //   if (error) {
          //     router.push({
          //       name: "cart",
          //     });
          //   }
          loader.hide();
        });
    };

    return {
      contactInfos,
      items,
      customerInformation,
      ...toRefs(booking),
    };
  },
  components: {
    Steps,
    ContactInfo,
  },
});
</script>

<style>

</style>
