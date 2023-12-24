<template>
  <div class="row">
    <div class="col-sm-6 col-12">
      <a-card
        title="booking hotel detail"
        style="width: 100%; height: 400px"
        class="shadow"
      >
        <div class="row">
          <p class="col-sm-3 fs-6 fw-bold">name:</p>
          <p class="col-sm-9">{{ bookingDetail.name }}</p>
          <p class="col-sm-3 fs-6 fw-bold">email:</p>
          <p class="col-sm-9">{{ bookingDetail.email }}</p>
          <p class="col-sm-3 fs-6 fw-bold">Check In:</p>
          <p class="col-sm-9">{{ bookingDetail.start_date }}</p>
          <p class="col-sm-3 fs-6 fw-bold">Check Out:</p>
          <p class="col-sm-9">{{ bookingDetail.end_date }}</p>
          <p class="col-sm-3 fs-6 fw-bold">number of days:</p>
          <p class="col-sm-9">{{ bookingDetail.totalDays }} Day</p>
          <p class="col-sm-3 fs-6 fw-bold">status:</p>
          <p class="col-sm-9">{{ bookingDetail.status_booking }}</p>
          <p class="col-sm-3 fs-6 fw-bold">Price:</p>
          <p class="col-sm-9">{{ bookingDetail.price }}</p>
        </div>
      </a-card>
    </div>
    <div class="col-sm-6 col-12">
      <a-card title="User Account" style="width: 100%; height: 400px" class="shadow">
        <div class="row">
          <p class="col-sm-2 fs-6 fw-bold">name:</p>
          <p class="col-sm-9">{{ bookingDetail.user ? bookingDetail.user.name : "" }}</p>
          <p class="col-sm-2 fs-6 fw-bold">email:</p>
          <p class="col-sm-9">{{ bookingDetail.user ? bookingDetail.user.email : "" }}</p>
          <p class="col-sm-2 fs-6 fw-bold">phone:</p>
          <p class="col-sm-9">{{ bookingDetail.user ? bookingDetail.user.phone : "" }}</p>
        </div>
      </a-card>
    </div>
    <div class="col-sm-12 col-12 mt-2">
      <a-card title="Room" style="width: 100%" class="shadow">
        <div class="row">
          <div class="col-sm-4 col-4" v-if="bookingDetail.room_type">
            <img
              :src="bookingDetail.room_type.image"
              :alt="bookingDetail.name"
              width="500"
            />
          </div>
          <div class="col-sm-6 col-6">
            <div class="row">
              <p class="col-sm-3 fs-6 fw-bold">hotel:</p>
              <p class="col-sm-9">
                {{ bookingDetail.hotel ? bookingDetail.hotel.title : "" }}
              </p>
              <p class="col-sm-3 fs-6 fw-bold">star:</p>
              <p class="col-sm-9" v-if="bookingDetail.hotel">
                <!-- {{ bookingDetail.hotel ? bookingDetail.hotel.star_rating : "" }} -->
                <Rate v-model:value="bookingDetail.hotel.star_rating" disabled />
              </p>
              <p class="col-sm-3 fs-6 fw-bold">Room type:</p>
              <p class="col-sm-9">
                {{ bookingDetail.room_type ? bookingDetail.room_type.name : "" }}
              </p>
              <p class="col-sm-3 fs-6 fw-bold">Room Number:</p>
              <p class="col-sm-9">
                {{
                  bookingDetail.number_room
                    ? bookingDetail.number_room.number_of_rooms
                    : ""
                }}
              </p>
            </div>
          </div>
        </div>
      </a-card>
    </div>
  </div>
</template>

<script>
import { ref, defineComponent, inject, reactive, toRefs, watch } from "vue";
import { useMenu } from "../../../../store/menu";
import { useRouter, useRoute } from "vue-router";
import { message, Rate } from "ant-design-vue";
export default defineComponent({
  setup() {
    const $loading = inject("$loading");
    const route = useRoute();
    const router = useRouter();
    // console.log(route);
    const bookingDetail = ref([]);

    const getDetail = () => {
      const loader = $loading.show({});
      axios
        .get(`http://127.0.0.1:8000/api/dashboard/bookinghotel/${route.params.id}/detail`)
        .then(function (response) {
          console.log(response);
          bookingDetail.value = response.data.data.Detailbooking;

          loader.hide();
        })
        .catch(function (error) {
          console.log(error);
          loader.hide();
        });
    };
    getDetail();
    return { bookingDetail };
  },
  components: {
    Rate,
  },
});
</script>

<style></style>
