<template>
  <a-menu v-model:openKeys="openKeys" v-model:selectedKeys="selectedKeys" mode="inline">
    <a-menu-item key="user">
      <router-link
        :to="{ name: 'users' }"
        style="text-decoration: none; font-size: 20px; display: flex; align-items: center"
      >
        <font-awesome-icon :icon="['fas', 'user']" class="me-2" />
        <span>account management</span>
      </router-link>
    </a-menu-item>
    <a-menu-item key="places">
      <router-link
        :to="{ name: 'places' }"
        style="text-decoration: none; font-size: 20px; display: flex; align-items: center"
      >
        <font-awesome-icon :icon="['fas', 'map-location']" class="me-2" />
        <span>places</span>
      </router-link>
    </a-menu-item>
    <a-menu-item key="category">
      <router-link
        :to="{ name: 'category' }"
        style="text-decoration: none; font-size: 20px; display: flex; align-items: center"
      >
        <font-awesome-icon :icon="['fas', 'grip-vertical']" class="me-2" />
        <span>category</span>
      </router-link>
    </a-menu-item>
    <a-sub-menu key="tour" style="text-decoration: none; font-size: 20px">
      <template #title>
        <font-awesome-icon :icon="['fas', 'plane']" class="me-2" />
        <span>Tours</span>
      </template>
      <a-menu-item key="tour_add"
        ><router-link
          :to="{ name: 'tour-create' }"
          style="text-decoration: none; font-size: 20px"
        >
          <font-awesome-icon :icon="['fas', 'plus']" class="me-2" />
          <span>Add Tour</span>
        </router-link></a-menu-item
      >
      <a-menu-item key="tour_list"
        ><router-link
          :to="{ name: 'tour' }"
          style="text-decoration: none; font-size: 20px"
        >
          <font-awesome-icon :icon="['fas', 'bars']" class="me-2" />
          <span>Tour List</span>
        </router-link></a-menu-item
      >
      <!-- <a-menu-item key="12">Option 12</a-menu-item>
      <a-sub-menu key="sub4" title="Submenu">
        <a-menu-item key="13">Option 11</a-menu-item>
        <a-menu-item key="14">Option 12</a-menu-item>
      </a-sub-menu> -->
    </a-sub-menu>

    <a-sub-menu key="Hotel" style="text-decoration: none; font-size: 20px">
      <template #title>
        <font-awesome-icon :icon="['fas', 'hotel']" class="me-2" />
        <span>Hotels</span>
      </template>
      <a-menu-item key="Hotel-create"
        ><router-link
          :to="{ name: 'hotel-create' }"
          style="text-decoration: none; font-size: 20px"
        >
          <font-awesome-icon :icon="['fas', 'plus']" class="me-2" />
          <span>Add hotel</span>
        </router-link></a-menu-item
      >
      <a-menu-item key="Hotel"
        ><router-link
          :to="{ name: 'hotel' }"
          style="text-decoration: none; font-size: 20px"
        >
          <font-awesome-icon :icon="['fas', 'bars']" class="me-2" />
          <span>Hotels List</span>
        </router-link></a-menu-item
      >
      <a-menu-item key="amenities">
        <router-link
          :to="{ name: 'hotel-amenities' }"
          style="text-decoration: none; font-size: 20px"
        >
          <font-awesome-icon :icon="['fas', 'square-poll-horizontal']" class="me-2" />
          <span>amenities</span></router-link
        ></a-menu-item
      >
    </a-sub-menu>

    <a-sub-menu key="booking" style="text-decoration: none; font-size: 20px">
      <template #title>
        <font-awesome-icon :icon="['fas', 'calendar-week']" class="me-2" />
        <span>booking</span>
      </template>
      <a-menu-item key="booking-tour"
        ><router-link
          :to="{ name: 'booking-tour' }"
          style="text-decoration: none; font-size: 20px"
        >
          <font-awesome-icon :icon="['fas', 'bars']" class="me-2" />
          <span>booking tour</span>
        </router-link></a-menu-item
      >
      <!-- <a-menu-item key="widget">
        <router-link
          :to="{ name: 'hotel-widget' }"
          style="text-decoration: none; font-size: 20px"
        >
          <font-awesome-icon :icon="['fas', 'square-poll-horizontal']" class="me-2" />
          <span>widget</span></router-link
        ></a-menu-item
      > -->
    </a-sub-menu>

    <a-sub-menu key="post" style="text-decoration: none; font-size: 20px">
      <template #title>
        <font-awesome-icon :icon="['fas', 'newspaper']" class="me-2" />
        <span>post management</span>
      </template>
      <a-menu-item key="post_add"
        ><router-link
          :to="{ name: 'posts-create' }"
          style="text-decoration: none; font-size: 20px"
        >
          <font-awesome-icon :icon="['fas', 'plus']" class="me-2" />
          <span>Add post</span>
        </router-link></a-menu-item
      >
      <a-menu-item key="post_list"
        ><router-link
          :to="{ name: 'posts' }"
          style="text-decoration: none; font-size: 20px"
        >
          <font-awesome-icon :icon="['fas', 'bars']" class="me-2" />
          <span>post List</span>
        </router-link></a-menu-item
      >
    </a-sub-menu>
    <a-sub-menu key="sliders" style="text-decoration: none; font-size: 20px">
      <template #title>
        <font-awesome-icon :icon="['fas', 'gear']" class="me-2" />
        <span>slider</span>
      </template>
      <a-menu-item key="slide_tour"
        ><router-link
          :to="{ name: 'slide-tour' }"
          style="text-decoration: none; font-size: 20px"
        >
          <font-awesome-icon :icon="['fas', 'sliders']" class="me-2" />

          <span>home tour</span>
        </router-link></a-menu-item
      >
      <!-- <a-menu-item key="slide-hotel"
        ><router-link
          :to="{ name: 'slide' }"
          style="text-decoration: none; font-size: 20px"
        >
          <font-awesome-icon :icon="['fas', 'sliders']" class="me-2" />

          <span>home hotel</span>
        </router-link></a-menu-item
      > -->
      <!-- <a-menu-item key="slide-hotel"
        ><router-link
          :to="{ name: 'slide' }"
          style="text-decoration: none; font-size: 20px"
        >
          <font-awesome-icon :icon="['far', 'address-card']" class="me-2" />
          <span>About us</span>
        </router-link></a-menu-item
      > -->
      <a-sub-menu key="sub4" title="home tour">
        <a-menu-item key="13">Option 11</a-menu-item>
        <a-menu-item key="14">Option 12</a-menu-item>
      </a-sub-menu>
    </a-sub-menu>
  </a-menu>
</template>

<script>
import { defineComponent } from "vue";
import { useMenu } from "../../../store/menu";
import { storeToRefs } from "pinia";

export default defineComponent({
  setup() {
    const store = useMenu();

    return {
      ...storeToRefs(store),
    };
  },
});
</script>

<style></style>
