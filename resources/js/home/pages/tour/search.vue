<template>
  <div class="homeDetail">
    <div class="grid wide">
      <div class="row sm-gutter">
        <div class="col-12 col-sm-3">
          <a-card style="width: 100%">
            <div class="homeDetail-header">
              <h1 class="homeDetail-textHeader" style="margin: 20px 0">Search</h1>
              <form
                @submit.prevent="advancedsearch"
                enctype="multipart/form-data"
                class="col-12"
              >
                <div class="row mb-12">
                  <div class="col-12 col-sm-12 text-start text-sm-start">
                    <label>
                      <span style="font-size: 20px">Tour Name:</span>
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
                      <span style="font-size: 20px">area:</span>
                    </label>
                  </div>
                  <div class="col-12 col-sm-12">
                    <a-select
                      show-search
                      placeholder="area"
                      style="width: 100%"
                      :options="area"
                      :filter-option="filterarea"
                      allow-clear
                      v-model:value="area_id"
                      class="col-12"
                    ></a-select>
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
                      placeholder="country or city"
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
                      <span style="font-size: 20px">category:</span>
                    </label>
                  </div>
                  <div class="col-12 col-sm-12">
                    <a-select
                      show-search
                      placeholder="category tour"
                      style="width: 100%"
                      :options="category"
                      :filter-option="filtercategory"
                      allow-clear
                      v-model:value="category_id"
                      class="col-12"
                    ></a-select>
                  </div>
                </div>
                <div class="row mb-12">
                  <div class="col-12 col-sm-12 text-start text-sm-start">
                    <label>
                      <span style="font-size: 20px">date range:</span>
                    </label>
                  </div>
                  <div class="col-12 col-sm-12">
                    <a-range-picker
                      v-model:value="date_range"
                      format="DD-MM-YYYY"
                      class="col-12 col-sm-12"
                      :bordered="false"
                    />
                  </div>
                </div>
                <div class="row mb-12">
                  <div class="col-12 col-sm-12 text-start text-sm-start">
                    <label>
                      <span style="font-size: 20px">tour duration:</span>
                    </label>
                  </div>
                  <div class="col-12 col-sm-12">
                    <Slider v-model:value="tour_duration" range :min="1" :max="30" />
                  </div>
                </div>
                <div class="row mb-12">
                  <div class="col-12 col-sm-12 text-start text-sm-start">
                    <label>
                      <span style="font-size: 20px">amount of people:</span>
                    </label>
                  </div>
                  <div class="col-12 col-sm-12">
                    <InputNumber
                      v-model:value="amount"
                      style="width: 100%"
                      max="30"
                      min="0"
                      placeholder="amount of people"
                    />
                  </div>
                </div>
                <div class="row mb-12">
                  <div class="col-12 col-sm-12 text-start text-sm-start">
                    <label>
                      <span style="font-size: 20px">price:</span>
                    </label>
                  </div>
                  <div class="col-12 col-sm-12">
                    <Slider v-model:value="price" range :min="1000" :max="10000" />
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
          <!-- Tìm Kiếm -->

          <!-- Star  -->
          <!-- Experience -->
        </div>
        <div class="col-12 col-sm-9">
          <div class="row">
            <div
              class="sm-gutter col-12 col-sm-4"
              v-for="(item, index) in tours.data ? tours.data: tours"
              :key="index"
            >
              <router-link
                class="home__tour-item"
                :to="{ name: 'tour-detail', params: { slug: item.slug } }"
              >
                <Card
                  :title="item.title"
                  :image="item.image"
                  :price="item.price"
                  :duration="item.duration"
                  :category="item.category.name"
                  :placesName="item.placesName"
                />
              </router-link>
            </div>
          </div>

          <!-- pagination -->
          <Bootstrap5Pagination
            :data="tours"
            @pagination-change-page="searchTour"
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
import { message, Slider, InputNumber } from "ant-design-vue";
import Card from "../../../components/Card.vue";
import { Bootstrap5Pagination } from "laravel-vue-pagination";

export default defineComponent({
  setup() {
    const router = useRouter();
    const route = useRoute();
    const $loading = inject("$loading");
    const tours = ref([]);
    const category = ref([]);
    const area = ref([]);
    const places = ref([]);

    const Search = reactive({
      title: null,
      area_id: null,
      Place_id: null,
      category_id: null,
      date_range: ref(),
      tour_duration: ref(),
      amount: null,
      price: ref(),
    });

    watch(
      () => Search.area_id,
      (newarea, oldarea) => {
        if (newarea !== oldarea) {
          getplace();
        }
      }
    );

    const filtercategory = (input, category) => {
      return category.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
    };
    const filterarea = (input, area) => {
      return area.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
    };
    const filterplaces = (input, places) => {
      return places.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
    };
    const disabledDate = (current) => {
      const twentyDaysLater = dayjs().add(5, "day");
      return current && current < twentyDaysLater.endOf("day");
    };

    const getplace = () => {
      const formData = new FormData();
      formData.append("area_id", Search.area_id ? Search.area_id : "");

      axios
        .post(`http://127.0.0.1:8000/api/tour/areaplace/`, formData)
        .then((response) => {
          // console.log(response);
          places.value = response.data.data.places;
        })
        .catch((error) => {
          console.log(error);
        });
    };

    const advancedsearch = () => {
      const loader = $loading.show({});
      const formData = new FormData();
      formData.append("title", Search.title ? Search.title : "");
      // formData.append("area_id", Search.area_id ? Search.area_id : "");
      formData.append("Place_id", Search.Place_id ? Search.Place_id : "");
      formData.append("category_id", Search.category_id ? Search.category_id : "");
      if (Search.date_range) {
        formData.append("date_range_start", Search.date_range[0].format("YYYY-MM-DD"));
        formData.append("date_range_end", Search.date_range[1].format("YYYY-MM-DD"));
      }
      if (Search.tour_duration) {
        formData.append("duration_start", Search.tour_duration[0]);
        formData.append("duration_end", Search.tour_duration[1]);
      }
      // formData.append("tour_duration", Search.tour_duration ? Search.tour_duration : "");
      formData.append("amount", Search.amount ? Search.amount : "");
      if (Search.price) {
        formData.append("price_start", Search.price[0]);
        formData.append("price_end", Search.price[1]);
      }
      axios
        .post(`http://127.0.0.1:8000/api/tour/advancedsearch/`, formData)
        .then((response) => {
          console.log(response);
          tours.value = response.data.data.results;

          loader.hide();
        })
        .catch((error) => {
          console.log(error);
          loader.hide();
        });
    };

    const searchTour = (page = 1) => {
      const loader = $loading.show({});
      axios
        .get(`http://127.0.0.1:8000/api/tour/search/${route.query.search}?page=${page}`)
        .then((response) => {
          // console.log(response);
          tours.value = response.data.data.Tours;
          category.value = response.data.data.category;
          area.value = response.data.data.area;
          places.value = response.data.data.places;
          loader.hide();
        })
        .catch((error) => {
          console.log(error);
          loader.hide();
        });
    };
    searchTour();

    return {
      ...toRefs(Search),
      tours,
      category,
      places,
      area,
      route,
      disabledDate,
      advancedsearch,
      filtercategory,
      filterarea,
      filterplaces,
      searchTour,
    };
  },
  components: {
    Card,
    Bootstrap5Pagination,
    Slider,
    InputNumber,
  },
});
</script>

<style></style>
