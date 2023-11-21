<template>
  <div class="container">
    <SliderSearch :tour="true" />
    <!-- End slider -->

    <!-- tour nước ngoài cao cấp -->
    <div class="tour__foreign-country mt-3">
      <div class="grid wide">
        <div class="row sm-gutter">
          <div class="col l-12">
            <div class="combo">
              <p class="combo__hot">domestic tourism</p>
              <p class="combo__person">Experience the regions in the country</p>
            </div>
          </div>
        </div>

        <div class="tours">
          <div class="row">
            <div class="sm-gutter col-12 col-sm-4" v-for="item in inland" :key="item.id">
              <router-link
                class="home__tour-item"
                :to="{ name: 'tour-detail', params: { slug: item.slug } }"
              >
                <Card
                  :title="item.title"
                  :image="item.image"
                  :price="item.price"
                  :status="item.status"
                  :Code="item.tour_Code"
                  :placesName="item.placesName"
                />
              </router-link>
            </div>
          </div>

          <a href="" class="more">
            <button class="more-than">See More Tours</button>
          </a>
        </div>
      </div>
    </div>

    <!-- tour japan korea -->
    <div class="tour__foreign-country">
      <div class="grid wide">
        <div class="row sm-gutter">
          <div class="col l-12">
            <div class="combo">
              <p class="combo__hot">Luxury Overseas Tour</p>
              <p class="combo__person">Unique Culture, Wonderful Scenery</p>
            </div>
          </div>
        </div>

        <div class="tours">
          <div class="row">
            <div
              class="sm-gutter col-12 col-sm-4"
              v-for="item in international"
              :key="item.id"
            >
              <router-link
                class="home__tour-item"
                :to="{ name: 'tour-detail', params: { slug: item.slug } }"
              >
                <Card
                  :title="item.title"
                  :image="item.image"
                  :price="item.price"
                  :status="item.status"
                  :Code="item.tour_Code"
                  :placesName="item.placesName"
                />
              </router-link>
            </div>
          </div>
          <a href="" class="more">
            <button class="more-than">See More Tours</button>
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, defineComponent, inject } from "vue";
import Card from "../../../components/Card.vue";
import SliderSearch from "../../../components/SliderSearch.vue";

export default defineComponent({
  setup() {
    const $loading = inject("$loading");

    const inland = ref([]);
    const international = ref([]);

    const getTour = () => {
      const loader = $loading.show({});
      axios
        .get(`http://127.0.0.1:8000/api/tour`)
        .then(function (response) {
          console.log(response);
          inland.value = response.data.data.inland;
          international.value = response.data.data.international;
          loader.hide();
        })
        .catch(function (error) {
          console.error(error);
          loader.hide();
        });
    };
    getTour();

    return {
      inland,
      international,
    };
  },
  components: {
    Card,
    SliderSearch,
  },
});
</script>

<style></style>
