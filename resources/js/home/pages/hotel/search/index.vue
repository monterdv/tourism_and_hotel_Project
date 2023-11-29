<template>
  <div class="homeDetail">
    <div class="grid wide">
      <div class="row sm-gutter">
        <div class="col-12 col-sm-3">
          <!-- Tìm Kiếm -->
          <a-card style="width: 100%">
            <div class="homeDetail-header">
              <h1 class="homeDetail-textHeader" style="margin: 20px 0">Search</h1>
              <form
                @submit.prevent="advancedsearch()"
                enctype="multipart/form-data"
                class="col-12"
              >
                <div class="row mb-12">
                  <div class="col-12 col-sm-12 text-start text-sm-start">
                    <label>
                      <span style="font-size: 20px">hotel Name:</span>
                    </label>
                  </div>
                  <div class="col-12 col-sm-12">
                    <a-input
                      placeholder="input Tour Name"
                      allow-clear
                      v-model:value="title"
                    />
                  </div>
                </div>
                <div class="row mb-12">
                  <div class="col-12 col-sm-12 text-start text-sm-start">
                    <label>
                      <span style="font-size: 20px">Star class:</span>
                    </label>
                  </div>
                  <div class="col-12 col-sm-12">
                    <Rate v-model:value="Star" />
                  </div>
                </div>
                <div class="row mb-12">
                  <div class="col-12 col-sm-12 text-start text-sm-start">
                    <label>
                      <span style="font-size: 20px">Places:</span>
                    </label>
                  </div>
                  <div class="col-12 col-sm-12">
                    <a-select
                      show-search
                      placeholder="places"
                      style="width: 100%"
                      :options="places"
                      :filter-option="filterplaces"
                      allow-clear
                      v-model:value="Place_id"
                      class="col-12"
                    ></a-select>
                  </div>
                </div>
                <div class="row mb-12">
                  <div class="col-12 col-sm-12 text-start text-sm-start">
                    <label>
                      <span style="font-size: 20px">convenient hotel:</span>
                    </label>
                  </div>
                  <div class="col-12 col-sm-12">
                    <a-select
                      v-model:value="widgets"
                      mode="multiple"
                      style="width: 100%"
                      placeholder="convenient hotel select"
                      :filter-option="filter"
                      :options="widgetOptions"
                    ></a-select>
                  </div>
                </div>

                <div class="row mb-12">
                  <div class="col-12 col-sm-12 text-start text-sm-start">
                    <label>
                      <span style="font-size: 20px">price:</span>
                    </label>
                  </div>
                  <div class="col-12 col-sm-12">
                    <Slider v-model:value="price" range :min="50" :max="1000" />
                  </div>
                </div>
                <div class="row mt-3">
                  <a-button type="primary" htmlType="submit">
                    <span>Search</span>
                  </a-button>
                </div>
              </form>
            </div>
          </a-card>
        </div>
        <div class="col-12 col-sm-9">
          <div class="row">
            <div
              class="sm-gutter col-12 col-sm-4"
              v-for="(item, index) in hotels.data"
              :key="index"
            >
              <router-link
                class="home__tour-item"
                :to="{ name: 'hotel-detail', params: { slug: item.slug } }"
              >
                <Card
                  style="min-height: 495px"
                  :title="item.title"
                  :image="item.image"
                  :price="item.price"
                  :Ratehotel="item.star_rating"
                  :address="item.address"
                />
              </router-link>
            </div>
          </div>

          <!-- pagination -->
          <Bootstrap5Pagination
            v-if="checkPagination"
            :data="hotels"
            @pagination-change-page="searchHotel"
            class="mt-4 float-end"
          />

          <Bootstrap5Pagination
            v-if="!checkPagination"
            :data="hotels"
            @pagination-change-page="advancedsearch"
            class="mt-4 float-end"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent, ref, reactive, toRefs, inject, watch } from "vue";
import { useRouter, useRoute } from "vue-router";
import { message, Slider, InputNumber, Rate } from "ant-design-vue";
import Card from "../../../../components/Card.vue";
import { Bootstrap5Pagination } from "laravel-vue-pagination";

export default defineComponent({
  setup() {
    const route = useRoute();
    const hotels = ref([]);
    const $loading = inject("$loading");
    const places = ref([]);
    const widgetOptions = ref([]);
    const checkPagination = ref(true);

    const Search = reactive({
      title: null,
      Star: ref(),
      Place_id: null,
      widgets: ref([]),
      price: ref([]),
    });

    const filterplaces = (input, places) => {
      return places.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
    };
    const filter = (input, widgetOptions) => {
      return widgetOptions.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
    };

    const searchHotel = (page = 1) => {
      const loader = $loading.show({});
      axios
        .get(`http://127.0.0.1:8000/api/hotel/search/${route.query.search}?page=${page}`)
        .then((response) => {
          // console.log(response);
          hotels.value = response.data.data.hotels;
          widgetOptions.value = response.data.data.widgetOptions;
          places.value = response.data.data.places;
          checkPagination.value = true;
          loader.hide();
        })
        .catch((error) => {
          console.log(error);
          loader.hide();
        });
    };
    searchHotel();

    const advancedsearch = (page = 1) => {
      const loader = $loading.show({});
      const formData = new FormData();
      formData.append("title", Search.title ? Search.title : "");
      formData.append("Place_id", Search.Place_id ? Search.Place_id : "");
      formData.append("Star", Search.Star ? Search.Star : "");
      // formData.append("category_id", Search.category_id ? Search.category_id : "");
      // if (Search.date_range) {
      //   formData.append("date_range_start", Search.date_range[0].format("YYYY-MM-DD"));
      //   formData.append("date_range_end", Search.date_range[1].format("YYYY-MM-DD"));
      // }
      if (Search.price) {
        formData.append(
          "price_start",
          Search.price[0] && Search.price[1] > 50 ? Search.price[0] : ""
        );
        formData.append(
          "price_end",
          Search.price[1] && Search.price[1] > 50 ? Search.price[1] : ""
        );
      }
      axios
        .post(`http://127.0.0.1:8000/api/hotel/advancedsearch?page=${page}`, formData)
        .then((response) => {
          // console.log(response);
          checkPagination.value = false;
          hotels.value = response.data.data.results;
          loader.hide();
        })
        .catch((error) => {
          console.log(error);
          loader.hide();
        });
    };

    return {
      ...toRefs(Search),
      hotels,
      places,
      widgetOptions,
      checkPagination,
      filterplaces,
      filter,
      searchHotel,
      advancedsearch,
    };
  },
  components: {
    Rate,
    Card,
    Slider,
    InputNumber,
    Bootstrap5Pagination,
  },
});
</script>

<style>
.pagination {
  --bs-pagination-font-size: 12px;
}
</style>
