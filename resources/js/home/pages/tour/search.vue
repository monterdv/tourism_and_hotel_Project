<template>
  <div class="homeDetail">
    <div class="grid wide">
      <div class="row sm-gutter">
        <div class="col l-4 c-12 m-12">
          <!-- Tìm Kiếm -->

          <div class="homeDetail-header">
            <h1 class="homeDetail-textHeader" style="margin: 20px 0">Search</h1>
            <form
              @submit.prevent="getHotel()"
              enctype="multipart/form-data"
              class="col-12"
            >
              <a-input-search placeholder="input Tour Name" enter-button allow-clear />
            </form>
          </div>

          <!-- Star  -->
          <div class="homeDetail-header">
            <h1 class="homeDetail-textHeader" style="margin: 20px 0">
              domestic tourist destination
            </h1>
          </div>
          <div class="homDetail-rankContainer">
            <div
              class="homDetail-container__rank"
              v-for="inland in placeInland"
              :key="inland.id"
            >
              <Checkbox>{{ inland.country }}</Checkbox>
            </div>
            <!-- <div class="homDetail-container__rank">
              <label for="five star" class="homDetail-container__check">
                <input type="checkbox" name="" id="" />
              </label>
              <div class="homDetail-container__star">
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
              </div>
            </div> -->
          </div>

          <div class="homeDetail-header">
            <h1 class="homeDetail-textHeader" style="margin: 20px 0">
              international tourist destination
            </h1>
          </div>
          <div class="homDetail-rankContainer">
            <div
              class="homDetail-container__rank"
              v-for="international in placeInternational"
              :key="international.id"
            >
              <Checkbox>{{ international.country }}</Checkbox>
            </div>
          </div>

          <!-- Experience -->
        </div>
        <div class="col l-8 c-12 m-12">
          <div class="homeDetail-containerRight">
            <div
              class="homeDetail-containerRight-a"
              v-for="(item, index) in tours"
              :key="index"
            >
              <router-link
                :to="{ name: 'tour-detail', params: { slug: item.slug } }"
                class="homeDetail-containerRight-link"
              >
                <div class="homeDetail-containerRight-body">
                  <div class="homeDetail-containerRight-body__container">
                    <div class="homeDetail-containerRight-image">
                      <img
                        :src="item.tour_paths[0].path"
                        alt=""
                        class="homeDetail-containerRight-img"
                      />
                    </div>
                    <div class="homeDetail-containerRight-container">
                      <p class="containerRight-text__header">
                        {{ item.title }}
                      </p>
                      <div class="homeDetail-containerRight-location">
                        <div class="homeDetail-location__icon">
                          <i class="fa-solid fa-map-location-dot"></i>
                        </div>
                        <p class="homeDetail-location__text">
                          {{ item.place.country }}
                        </p>
                      </div>
                      <div class="homeDetail-containerRight-location">
                        <div class="homeDetail-location__icon">Code:</div>
                        <p class="homeDetail-location__text">
                          {{ item.tour_Code }}
                        </p>
                      </div>
                    </div>
                    <div class="homeDetail-containerRight-price">
                      <p class="containerRight-price">2.461.000 VND</p>
                    </div>
                  </div>
                </div>
              </router-link>
            </div>
          </div>

          <a href="#" class="home__tour-item hide-on__laptop">
            <div
              class="home__tour-item-img"
              style="
                background-image: url(https://cdn1.ivivu.com/iVivu/2022/03/28/16/hinhdaidien-374x280.webp?o=jpg);
              "
            ></div>
            <h4 class="home__tour-name">Khu Nghĩ Dưỡng Mecure Đà Lạt</h4>
            <div class="home__tour-mark">
              <p class="tour__mark">9.7</p>
              <p class="tour__classification">Tuyệt Vời</p>
              <p class="rate">71 đánh giá</p>
            </div>

            <div class="tour__price">
              <span class="tour__price-new">2.416.000 VND</span>
              <div class="containerRight-block">
                <div class="containerRight-star">
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                </div>
              </div>
            </div>
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, defineComponent, inject } from "vue";
import { useRouter, useRoute } from "vue-router";
import { Checkbox, message } from "ant-design-vue";

export default defineComponent({
  setup() {
    const route = useRoute();
    const $loading = inject("$loading");

    const tours = ref([]);
    const placeInland = ref([]);
    const placeInternational = ref([]);

    const searchTour = () => {
      const loader = $loading.show({});
      axios
        .get(`http://127.0.0.1:8000/api/tour/search/${route.query.search}`)
        .then((response) => {
          console.log(response);
          tours.value = response.data.data.Tours;
          placeInland.value = response.data.data.placeInland;
          placeInternational.value = response.data.data.placeInternational;
          loader.hide();
        })
        .catch((error) => {
          console.log(error);
          loader.hide();
        });
    };
    searchTour();
    return { tours, route, placeInland, placeInternational };
  },
  components: { Checkbox },
});
</script>

<style></style>
