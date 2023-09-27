<template>
  <form @submit.prevent="createRoom" enctype="multipart/form-data">
    <a-card :title="`Hotel ` + hotelName + ' Create Room'" style="width: 100%">
      <div class="row">
        <div class="col-12 col-sm-6">
          <div class="row mb-4">
            <div class="col-12 col-sm-2 text-start text-sm-end">
              <label>
                <span class="text-danger me-1">*</span>
                <span>Name Room:</span>
              </label>
            </div>
            <div class="col-12 col-sm-10">
              <a-input
                placeholder="input Room Name"
                allow-clear
                v-model:value="name"
                :class="{ 'selec-danger-input': errors.name }"
              />
              <div class="w-100"></div>
              <small v-if="errors.name" class="text-danger">{{ errors.name[0] }}</small>
            </div>
          </div>
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
                <span>room count:</span>
              </label>
            </div>
            <div class="col-12 col-sm-10">
              <InputNumber
                min="0"
                v-model:value="room_count"
                placeholder="Room number"
                :class="{ 'selec-danger-input': errors.room_count }"
                class="col-12 col-sm-12"
              ></InputNumber>
              <!-- <a-input
                allow-clear
                v-model:value="room_count"
                :class="{ 'selec-danger-input': errors.room_count }"
              /> -->
              <div class="w-100"></div>
              <small v-if="errors.room_count" class="text-danger">{{
                errors.room_count[0]
              }}</small>
            </div>
          </div>
          <div class="row mb-4">
            <div class="col-12 col-sm-2 text-start text-sm-end">
              <label>
                <span class="text-danger me-1">*</span>
                <span>base price:</span>
              </label>
            </div>
            <div class="col-12 col-sm-10">
              <InputNumber
                min="1"
                v-model:value="base_price"
                addon-after="$"
                :class="{ 'selec-danger-input': errors.base_price }"
              ></InputNumber>
              <div class="w-100"></div>
              <small v-if="errors.base_price" class="text-danger">{{
                errors.base_price[0]
              }}</small>
            </div>
          </div>
          <div class="row mb-4">
            <div class="col-12 col-sm-2 text-start text-sm-end">
              <label>
                <span class="text-danger me-1">*</span>
                <span>widget:</span>
              </label>
            </div>
            <div class="col-12 col-sm-10">
              <a-select
                v-model:value="widgets"
                mode="multiple"
                style="width: 100%"
                placeholder="Please select"
                :filter-option="filter"
                :options="widgetOptions"
                :class="{ 'selec-danger-input': errors.widgets }"
              ></a-select>
              <div class="w-100"></div>
              <small v-if="errors.widgets" class="text-danger">{{
                errors.widgets[0]
              }}</small>
            </div>
          </div>

          <div class="row mb-4">
            <div class="col-12 col-sm-6">
              <div class="row">
                <div class="col-12 col-sm-4 text-start text-sm-end">
                  <label>
                    <span class="text-danger me-1">*</span>
                    <span style="font-size: small">adults:</span>
                  </label>
                </div>
                <div class="col-12 col-sm-8">
                  <InputNumber
                    v-model:value="max_adults"
                    min="2"
                    max="6"
                    style="width: 100%"
                    :class="{ 'selec-danger-input': errors.max_adults }"
                  >
                    <template #addonBefore>
                      <font-awesome-icon :icon="['fas', 'user']" />
                    </template>
                  </InputNumber>
                  <div class="w-100"></div>
                  <small v-if="errors.max_adults" class="text-danger">{{
                    errors.max_adults[0]
                  }}</small>
                </div>
              </div>
            </div>
            <div class="col-12 col-sm-6">
              <div class="row">
                <div class="col-12 col-sm-3 text-start text-sm-start">
                  <label>
                    <span class="text-danger me-1">*</span>
                    <span style="font-size: small">children:</span>
                  </label>
                </div>
                <div class="col-12 col-sm-9">
                  <InputNumber
                    v-model:value="max_children"
                    min="0"
                    style="width: 100%"
                    :class="{ 'selec-danger-input': errors.max_children }"
                  >
                    <template #addonBefore>
                      <font-awesome-icon :icon="['fas', 'child-reaching']" />
                    </template>
                  </InputNumber>
                  <div class="w-100"></div>
                  <small v-if="errors.max_children" class="text-danger">{{
                    errors.max_children[0]
                  }}</small>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- here -->
        <div class="col-12 col-sm-6 mb-4">
          <a-card title="Image hotel" style="width: 100%">
            <small v-if="errors.file" class="text-danger">{{ errors.file[0] }}</small>
            <div
              class="col-12 d-flex justify-content-center align-items-center mb-1"
              :class="{ 'selec-danger-input': errors.file }"
            >
              <img
                v-if="thumbnailImage"
                :src="thumbnailImage"
                alt="imageName"
                style="width: 100%; height: 100%; object-fit: cover"
              />
              <Empty v-else style="width: 100%; height: 200px" />
            </div>
            <div
              class="col-12 col-sm-12 d-flex justify-content-center align-items-center"
            >
              <a-button>
                <font-awesome-icon :icon="['fas', 'upload']" class="me-2" />
                <input
                  type="file"
                  id="upload"
                  hidden
                  v-on:change="handleThumbnailChange"
                />
                <label for="upload">Choose image</label>
              </a-button>
            </div>
          </a-card>
        </div>
      </div>

      <div class="row mt-3">
        <div class="col-12 col-sm-9 d-grid d-sm-flex justify-content-sm-end mx-auto">
          <router-link :to="{ name: 'hotel-room', params: { slug: route.params.slug } }">
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
import { Empty, Upload, Modal, InputNumber, message } from "ant-design-vue";
import { useRouter, useRoute } from "vue-router";
import { useMenu } from "../../../../store/menu";

import Editor from "../../Editor.vue";
export default defineComponent({
  setup() {
    const store = useMenu();
    store.onselectedkey(["Hotel"]);

    const $loading = inject("$loading");

    const route = useRoute();
    const router = useRouter();

    const errors = ref({});

    const room = reactive({
      name: "",
      status: "Available",
      base_price: 100,
      widgets: ref([]),
      max_adults: 2,
      max_children: 0,
      room_count: "",
    });

    const statusOptions = [
      {
        label: "Available",
        value: "available",
      },
      {
        label: "Occupied",
        value: "occupied",
      },
      {
        label: "Reserved",
        value: "reserved",
      },
      {
        label: "Maintenance",
        value: "maintenance",
      },
    ];

    // const simpleImage = ref("");
    const thumbnailImage = ref("");
    const handleThumbnailChange = (event) => {
      const file = event.target.files[0];
      // console.log("Selected file:", file);
      if (!file) {
        return;
      }
      const reader = new FileReader();

      reader.onload = (e) => {
        const imageData = e.target.result;
        thumbnailImage.value = imageData;
      };

      reader.readAsDataURL(file);
    };

    const widgetOptions = ref([]);
    const hotelName = ref([]);

    const getCreateRoom = () => {
      const loader = $loading.show({});
      axios
        .get(`http://127.0.0.1:8000/api/dashboard/Hotel/${route.params.slug}/room/create`)
        .then(function (response) {
          // console.log(response);
          widgetOptions.value = response.data.data.Widget;
          hotelName.value = response.data.data.hotel.title;
          loader.hide();
        })
        .catch(function (error) {
          console.error(error);
          if (error.response.status === 404) {
            message.error(error.response.data.message);
            // router.push({ name: "404" });
            router.push({ name: "hotel" });
          }
          loader.hide();
        });
    };
    getCreateRoom();

    const errorName = ref("");

    const createRoom = () => {
      const loader = $loading.show({});
      const formData = new FormData();
      formData.append("name", room.name);
      formData.append("status", room.status);
      formData.append("base_price", room.base_price);
      formData.append("max_adults", room.max_adults);
      formData.append("max_children", room.max_children);
      formData.append("room_count", room.room_count);
      formData.append("widgets", room.widgets);

      const uploadInput = document.getElementById("upload");
      const ImgFile = uploadInput ? uploadInput.files[0] : null;

      if (ImgFile) {
        formData.append("file", ImgFile);
      }

      axios
        .post(
          `http://127.0.0.1:8000/api/dashboard/Hotel/${route.params.slug}/room/create`,
          formData
        )
        .then(function (response) {
          // console.log(response);
          loader.hide();
          if (response) {
            message.success(response.data.message);
            router.push({
              name: "hotel-room",
              params: { slug: route.params.slug },
            });
          }
        })
        .catch(function (error) {
          // console.log(error);
          if (error.response.status === 404) {
            message.error(error.response.data.message);
            router.push({ name: "hotel" });
          } else {
            if (error.response.data.errors) {
              errors.value = error.response.data.errors;
            } else {
              message.error(error.response.data.message);
            }
          }
          loader.hide();
        });
    };

    const filter = (input, statusOptions) => {
      return statusOptions.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
    };

    return {
      ...toRefs(room),
      errors,
      statusOptions,
      thumbnailImage,
      handleThumbnailChange,
      widgetOptions,
      createRoom,
      errorName,
      route,
      filter,
      hotelName,
    };
  },
  components: {
    Empty,
    Editor,
    Upload,
    Modal,
    InputNumber,
  },
});
</script>

<style>
.selec-danger-input {
  border: 1px solid red;
}
</style>
