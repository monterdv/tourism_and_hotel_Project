<template>
  <div class="homeDetail">
    <div class="grid wide">
      <div class="row sm-gutter">
        <div class="col l-4 c-12 m-12">
          <!-- Tìm Kiếm -->
          <div class="homeDetail-header">
            <h1 class="homeDetail-textHeader" style="margin: 20px 0">Tìm Kiếm</h1>
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
            <h1 class="homeDetail-textHeader" style="margin: 20px 0">Hạng Sao</h1>
          </div>
          <div class="homDetail-rankContainer">
            <div class="homDetail-container__rank">
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
            </div>

            <div class="homDetail-container__rank">
              <label for="five star" class="homDetail-container__check">
                <input type="checkbox" name="" id="" />
              </label>
              <div class="homDetail-container__star">
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
              </div>
            </div>

            <div class="homDetail-container__rank">
              <label for="five star" class="homDetail-container__check">
                <input type="checkbox" name="" id="" />
              </label>
              <div class="homDetail-container__star">
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
              </div>
            </div>

            <div class="homDetail-container__rank">
              <label for="five star" class="homDetail-container__check">
                <input type="checkbox" name="" id="" />
              </label>
              <div class="homDetail-container__star">
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
              </div>
            </div>

            <div class="homDetail-container__rank">
              <label for="five star" class="homDetail-container__check">
                <input type="checkbox" name="" id="" />
              </label>
              <div class="homDetail-container__star">
                <i class="fa-solid fa-star"></i>
              </div>
            </div>
          </div>
        </div>
        <div class="col l-8 c-12 m-12">
          <div class="homeDetail-containerRight" v-if="hotels">
            <div
              class="homeDetail-containerRight-a"
              v-for="item in hotels"
              :key="item.id"
            >
              <router-link
                :to="{ name: 'hotel-detail', params: { slug: item.slug } }"
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
                      <div class="containerRight-block">
                        <Rate v-model:value="item.star_rating" disabled />
                        <p class="containerRight-block__count">
                          {{ item.place.country }}
                        </p>
                      </div>

                      <div class="homeDetail-containerRight-location">
                        <div class="homeDetail-location__icon">
                          <i class="fa-solid fa-map-location-dot"></i>
                        </div>
                        <p class="homeDetail-location__text">
                          {{ item.address }}
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
            <div class="tour__favourite">
              <i class="fa-solid fa-check"></i>
              <span>
                Còn 14 ngày | Combo 3N2Đ | Vé máy bay + Bữa sáng + Miễn phí 01 trẻ em
              </span>
            </div>
          </a>
          <!-- pagination -->
          <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-end mt-3" style="font-size: 130px">
              <li
                class="page-item"
                v-bind:class="[{ disabled: !pagination.prev_page_url }]"
                @click="searchHotel(pagination.prev_page_url)"
              >
                <a class="page-link" href="#">Previous</a>
              </li>
              <li
                class="page-item"
                v-for="(link, index) in pagination.links"
                :key="index"
                v-bind:class="{ disabled: link.label === pagination.current_page }"
              >
                <a class="page-link" @click="searchHotel(link.url)">{{ link.label }}</a>
              </li>
              <li
                class="page-item"
                v-bind:class="[{ disabled: !pagination.next_page_url }]"
                @click="searchHotel(pagination.next_page_url)"
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
import { ref, defineComponent, inject, computed } from "vue";
import { useRouter, useRoute } from "vue-router";
import { message, Rate } from "ant-design-vue";

export default defineComponent({
  setup() {
    const route = useRoute();
    const hotels = ref([]);
    const pagination = ref({});
    const $loading = inject("$loading");

    const makepagination = (current_page, last_page, next_page, prev_page, links) => {
      pagination.value.current_page = current_page;
      pagination.value.last_page = last_page;
      pagination.value.next_page_url = next_page;
      pagination.value.prev_page_url = prev_page;
      pagination.value.links = links.slice(1, -1);
    };

    const searchHotel = (page_url) => {
      const loader = $loading.show({});
      page_url =
        page_url || `http://127.0.0.1:8000/api/hotel/search/${route.query.search}`;
      axios
        .get(page_url)
        .then((response) => {
          console.log(response);
          hotels.value = response.data.data.hotels.data;
          makepagination(
            response.data.data.hotels.current_page,
            response.data.data.hotels.last_page_url,
            response.data.data.hotels.next_page_url,
            response.data.data.hotels.prev_page_url,
            response.data.data.hotels.links
          );
          loader.hide();
        })
        .catch((error) => {
          console.log(error);
          loader.hide();
        });
    };
    searchHotel();

    const displayedLinks = computed(() => {
      const maxDisplayed = 3;

      const currentPage = pagination.current_page;
      const lastPage = pagination.last_page;
      const links = pagination.links;

      let start = currentPage - Math.floor(maxDisplayed / 2);
      let end = currentPage + Math.ceil(maxDisplayed / 2);

      if (start < 1) {
        start = 1;
        end = Math.min(maxDisplayed, lastPage);
      }

      if (end > lastPage) {
        end = lastPage;
        start = Math.max(lastPage - maxDisplayed + 1, 1);
      }

      const displayed = [];

      for (let i = start; i <= end; i++) {
        displayed.push({
          url: links[i - 1].url,
          label: links[i - 1].label,
        });
      }

      return displayed;
    });

    return {
      hotels,
      pagination,
      searchHotel,
      displayedLinks,
    };
  },
  components: { Rate },
});
</script>

<style></style>
