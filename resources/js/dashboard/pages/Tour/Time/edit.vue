<template>
  <form @submit.prevent="update" enctype="multipart/form-data">
    <a-card :title="'Edit tour Time'" style="width: 100%">
      <div class="row">
        <div class="col-12 col-sm-8">
          <div class="row mb-4">
            <div class="col-12 col-sm-2 text-start text-sm-end">
              <label>
                <span class="text-danger me-1">*</span>
                <span>Status:</span>
              </label>
            </div>
            <div class="col-12 col-sm-10">
              <a-select
                show-search
                placeholder="hotel star number"
                style="width: 100%"
                :options="statusOptions"
                allow-clear
                v-model:value="status"
                class="col-12"
                :class="{ 'selec-danger-input': errors.status }"
              ></a-select>
              <div class="w-100"></div>
              <small v-if="errors.status" class="text-danger">{{
                errors.status[0]
              }}</small>
            </div>
          </div>
          <div class="row mb-4">
            <div class="col-12 col-sm-2 text-start text-sm-end">
              <label>
                <span class="text-danger me-1">*</span>
                <span>Slot:</span>
              </label>
            </div>
            <div class="col-12 col-sm-10">
              <InputNumber
                min="15"
                v-model:value="slots_remaining"
                placeholder="Room number"
                :class="{ 'selec-danger-input': errors.slots_remaining }"
                class="col-12 col-sm-12"
              ></InputNumber>
              <div class="w-100"></div>
              <small v-if="errors.slots_remaining" class="text-danger">{{
                errors.slots_remaining[0]
              }}</small>
            </div>
          </div>
          <div class="row mb-4">
            <div class="col-12 col-sm-2 text-start text-sm-end">
              <label>
                <span class="text-danger me-1">*</span>
                <span>date:</span>
              </label>
            </div>
            <div class="col-12 col-sm-10">
              <DatePicker
                v-model:value="date"
                format="YYYY-MM-DD"
                :disabled-date="disabledDate"
                class="col-12 col-sm-12"
              ></DatePicker>
              <div class="w-100"></div>
              <small v-if="errors.date" class="text-danger">{{ errors.date[0] }}</small>
            </div>
          </div>
          <div class="row mb-4">
            <div class="col-12 col-sm-6">
              <div class="row">
                <div class="col-12 col-sm-4 text-start text-sm-end">
                  <label>
                    <span class="text-danger me-1">*</span>
                    <span style="font-size: small">adult price:</span>
                  </label>
                </div>
                <div class="col-12 col-sm-8">
                  <InputNumber
                    v-model:value="price_adults"
                    style="width: 100%"
                    :class="{ 'selec-danger-input': errors.price_adults }"
                  >
                    <template #addonBefore>
                      <font-awesome-icon :icon="['fas', 'user']" />
                    </template>
                  </InputNumber>
                  <div class="w-100"></div>
                  <small v-if="errors.price_adults" class="text-danger">{{
                    errors.price_adults[0]
                  }}</small>
                </div>
              </div>
            </div>
            <div class="col-12 col-sm-6">
              <div class="row">
                <div class="col-12 col-sm-4 text-start text-sm-start">
                  <label>
                    <span class="text-danger me-1">*</span>
                    <span style="font-size: small">Children's price:</span>
                  </label>
                </div>
                <div class="col-12 col-sm-8">
                  <InputNumber
                    v-model:value="price_children"
                    min="0"
                    style="width: 100%"
                    :class="{ 'selec-danger-input': errors.price_children }"
                  >
                    <template #addonBefore>
                      <font-awesome-icon :icon="['fas', 'child-reaching']" />
                    </template>
                  </InputNumber>
                  <div class="w-100"></div>
                  <small v-if="errors.price_children" class="text-danger">{{
                    errors.price_children[0]
                  }}</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-3">
        <div class="col-12 col-sm-9 d-grid d-sm-flex justify-content-sm-end mx-auto">
          <router-link :to="{ name: 'tour-time', params: { slug: route.params.slug } }">
            <a-button class="me-0 me-sm-2 mb-3 mb-sm-0">
              <span>Cancel</span>
            </a-button>
          </router-link>

          <a-button type="primary" htmlType="submit">
            <span>Save</span>
          </a-button>
        </div>
      </div>
    </a-card>
  </form>
</template>

<script>
import { defineComponent, ref, reactive, toRefs, inject } from "vue";
import { DatePicker, InputNumber, message } from "ant-design-vue";
import { useRouter, useRoute } from "vue-router";
import dayjs from "dayjs";
import { useMenu } from "../../../../store/menu";

export default defineComponent({
  setup() {
    const store = useMenu();
    store.onselectedkey(["tour_list"]);

    const $loading = inject("$loading");

    const route = useRoute();
    const router = useRouter();

    const errors = ref({});

    const details_tour = reactive({
      status: "",
      slots_remaining: "",
      price_adults: "",
      price_children: "",
      date: ref(),
    });

    const statusOptions = [
      {
        label: "Available",
        value: "available",
      },
      {
        label: "Full",
        value: "full",
      },
      {
        label: "Pause",
        value: "pause",
      },
    ];

    // const timeName = ref();

    const getEdit = () => {
      const loader = $loading.show({});
      axios
        .get(
          `http://127.0.0.1:8000/api/dashboard/tour/${route.params.slug}/time/${route.params.id}/edit`
        )
        .then(function (response) {
          // console.log(response);
          // timeName.value = response.data.data.tour.title;
          details_tour.status = response.data.data.time.status;
          details_tour.slots_remaining = response.data.data.time.slots_remaining;
          details_tour.price_children = response.data.data.time.price_children;
          details_tour.price_adults = response.data.data.time.price_adults;
          details_tour.date = dayjs(response.data.data.time.date, "YYYY-MM-DD");
          loader.hide();
        })
        .catch(function (error) {
          console.error(error);
          loader.hide();
          if (error.response.status === 400) {
            message.error(error.response.data.message);
            router.push({ name: "tour" });
          }
        });
    };
    getEdit();

    const update = () => {
      const loader = $loading.show({});
      const formData = new FormData();
      formData.append("status", details_tour.status);
      formData.append("slots_remaining", details_tour.slots_remaining);
      formData.append("price_adults", details_tour.price_adults);
      formData.append("price_children", details_tour.price_children);
      formData.append(
        "date",
        details_tour.date ? details_tour.date.format("YYYY-MM-DD") : ""
      );

      axios
        .post(
          `http://127.0.0.1:8000/api/dashboard/tour/${route.params.slug}/time/${route.params.id}/edit`,
          formData
        )
        .then(function (response) {
          console.log(response);
          loader.hide();
          if (response) {
            message.success(response.data.message);
            router.push({
              name: "tour-time",
              params: { slug: route.params.slug },
            });
          }
        })
        .catch(function (error) {
          console.log(error);
          loader.hide();
          if (error.response.status === 404) {
            message.error(error.response.data.message);
            router.push({ name: "tour-time" });
          } else {
            if (error.response.data.errors) {
              errors.value = error.response.data.errors;
            } else {
              message.error(error.response.data.message);
            }
          }
        });
    };

    const disabledDate = (current) => {
      const twentyDaysLater = dayjs().add(20, "day");
      return current && current < twentyDaysLater.endOf("day");
    };

    return {
      ...toRefs(details_tour),
      statusOptions,
      errors,
      update,
      route,
      disabledDate,
    };
  },
  components: {
    InputNumber,
    DatePicker,
  },
});
</script>

<style>
.selec-danger-input {
  border: 1px solid red;
}
</style>
