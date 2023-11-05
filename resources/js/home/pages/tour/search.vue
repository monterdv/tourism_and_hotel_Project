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
              <div class="homDetail-container__text" @click="search(inland.country)">
                {{ inland.country }}
              </div>
            </div>
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
              <div
                class="homDetail-container__text"
                @click="search(international.country)"
              >
                {{ international.country }}
              </div>
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
                        :src="item.image"
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
                      <p class="containerRight-price">{{ item.price }} USD</p>
                    </div>
                  </div>
                </div>
              </router-link>
            </div>
          </div>

          <div v-for="(item, index) in tours" :key="index">
            <router-link
              :to="{ name: 'tour-detail', params: { slug: item.slug } }"
              class="home__tour-item hide-on__laptop"
            >
              <img :src="item.image" alt="" class="home__tour-item-img" />

              <h4 class="home__tour-name">{{ item.title }}</h4>
              <div class="home__tour-mark">
                <p class="tour__mark">9.7</p>
                <p class="tour__classification">Tuyệt Vời</p>
                <p class="rate">71 đánh giá</p>
              </div>

              <div class="tour__price">
                <span class="tour__price-new">{{ item.price }} USD</span>
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
            </router-link>
          </div>
          <!-- pagination -->
          <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-end mt-3" style="font-size: 130px">
              <li
                class="page-item"
                v-bind:class="[{ disabled: !pagination.prev_page_url }]"
                @click="searchTour(pagination.prev_page_url)"
              >
                <a class="page-link" href="#">Previous</a>
              </li>
              <li
                class="page-item"
                v-for="(link, index) in pagination.links"
                :key="index"
                v-bind:class="{ disabled: link.label === pagination.current_page }"
              >
                <a class="page-link" @click="searchTour(link.url)">{{ link.label }}</a>
              </li>
              <li
                class="page-item"
                v-bind:class="[{ disabled: !pagination.next_page_url }]"
                @click="searchTour(pagination.next_page_url)"
              >
                <a class="page-link" href="#">Next</a>
              </li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, defineComponent, inject } from "vue";
import { useRouter, useRoute } from "vue-router";
import { message } from "ant-design-vue";

export default defineComponent({
  setup() {
    const router = useRouter();

    const route = useRoute();
    const $loading = inject("$loading");

    const tours = ref([]);
    const placeInland = ref([]);
    const placeInternational = ref([]);
    const pagination = ref({});

    const makepagination = (current_page, last_page, next_page, prev_page, links) => {
      pagination.value.current_page = current_page;
      pagination.value.last_page = last_page;
      pagination.value.next_page_url = next_page;
      pagination.value.prev_page_url = prev_page;
      pagination.value.links = links.slice(1, -1);
    };

    const searchTour = (page_url) => {
      const loader = $loading.show({});
      page_url =
        page_url || `http://127.0.0.1:8000/api/tour/search/${route.query.search}`;
      axios
        .get(page_url)
        .then((response) => {
          console.log(response);
          tours.value = response.data.data.Tours.data;
          placeInland.value = response.data.data.placeInland;
          placeInternational.value = response.data.data.placeInternational;
          makepagination(
            response.data.data.Tours.current_page,
            response.data.data.Tours.last_page_url,
            response.data.data.Tours.next_page_url,
            response.data.data.Tours.prev_page_url,
            response.data.data.Tours.links
          );
          loader.hide();
        })
        .catch((error) => {
          console.log(error);
          loader.hide();
        });
    };
    searchTour();

    const sendSearchRequest = (country) => {
      const loader = $loading.show({});
      axios
        .get(`http://127.0.0.1:8000/api/tour/search/${country}`)
        .then((response) => {
          // console.log(response);
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

    const search = (country) => {
      router.push({
        name: "tour-search",
        query: { search: country },
      });

      sendSearchRequest(country);
    };
    return {
      tours,
      route,
      placeInland,
      placeInternational,
      pagination,
      searchTour,
      search,
      makepagination,
    };
  },
  components: {},
});
</script>

<style></style>
