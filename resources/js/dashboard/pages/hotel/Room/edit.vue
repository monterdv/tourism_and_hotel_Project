<template>
  <form @submit.prevent="createRoom" enctype="multipart/form-data">
    <a-card :title="'Hotel ' + hotelName + ' Edit Room ' + name" style="width: 100%">
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
              <a-input placeholder="input Room Name" allow-clear v-model:value="name" />
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
                class="col-12 col-sm-12"
              ></InputNumber>
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
                  <InputNumber v-model:value="max_adults" min="2" style="width: 100%">
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
                  <InputNumber v-model:value="max_children" min="0" style="width: 100%">
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
          <div class="row mb-4">
            <div class="col-12 col-sm-2 text-start text-sm-start">
              <label>
                <span class="text-danger me-1">*</span>
                <span>Image: </span>
              </label>
            </div>
            <div class="col-12 col-sm-7 text-sm-start">
              <div class="clearfix">
                <Upload
                  v-model:file-list="file"
                  list-type="picture-card"
                  @preview="handlePreview"
                  action="http://127.0.0.1:8000/api/upload"
                >
                  <div v-if="file.length < 1">
                    <plus-outlined />
                    <div style="margin-top: 8px">Upload</div>
                  </div>
                </Upload>
                <Modal :open="previewVisible" @cancel="handleCancel">
                  <img alt="example" style="width: 100%" :src="previewImage" />
                </Modal>
              </div>
              <div class="w-100"></div>
              <small v-if="errors.file" class="text-danger">{{ errors.file[0] }}</small>
            </div>
          </div>
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
import { Upload, Modal, InputNumber, message } from "ant-design-vue";
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
      description: "",
      widgets: ref([""]),
      max_adults: 2,
      max_children: 0,
      room_count: "",
      file: ref([]),
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

    const widgetOptions = ref([]);
    const hotelName = ref([]);

    const getEditRoom = () => {
      const loader = $loading.show({});
      axios
        .get(
          `http://127.0.0.1:8000/api/dashboard/Hotel/${route.params.slug}/room/${route.params.slugRoom}/edit`
        )
        .then(function (response) {
          console.log(response);
          widgetOptions.value = response.data.data.widgetOptions;
          room.name = response.data.data.room.name;
          room.status = response.data.data.room.status;
          room.room_count = response.data.data.room.room_count;
          room.base_price = response.data.data.room.base_price;
          room.max_adults = response.data.data.room.max_adults;
          room.max_children = response.data.data.room.max_children;
          room.widgets = response.data.data.widgets.map((widget) => widget.value);
          hotelName.value = response.data.data.hotel;
          room.file = [
            {
              uid: response.data.data.room.id,
              name: response.data.data.room.image,
              status: "done",
              url: response.data.data.room.image,
              thumbUrl: response.data.data.room.image,
            },
          ];
          loader.hide();
        })
        .catch(function (error) {
          // console.error(error);
          if (error.response.status === 404) {
            message.error(error.response.data.message);
            router.push({
              name: "hotel-room",
              params: { slug: route.params.slug },
            });
            // router.push({ name: "404" });
          } else {
            message.error(error.response.data.message);
          }
          loader.hide();
        });
    };
    getEditRoom();

    const createRoom = () => {
      const loader = $loading.show({});

      const formData = new FormData();
      formData.append("name", room.name);
      formData.append("status", room.status ? room.status : "");
      formData.append("base_price", room.base_price);
      formData.append("max_adults", room.max_adults);
      formData.append("max_children", room.max_children);
      formData.append("room_count", room.room_count);

      formData.append("widgets", room.widgets);

      if (room.file.length > 0 && room.file[0].originFileObj instanceof File) {
        formData.append("file", room.file[0].originFileObj);
      } else if (room.file.length > 0 && room.file[0].name) {
        formData.append("file", room.file[0].name);
      } else {
        formData.append("file", "");
      }

      axios
        .post(
          `http://127.0.0.1:8000/api/dashboard/Hotel/${route.params.slug}/room/${route.params.slugRoom}/edit`,
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
          console.log(error);
          if (error.response.data.errors) {
            errors.value = error.response.data.errors;
          } else {
            message.error(error.response.data.message);
          }
          loader.hide();
        });
    };

    const filter = (input, statusOptions) => {
      return statusOptions.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
    };

    const previewVisible = ref(false);
    const previewImage = ref("");

    function getBase64(file) {
      return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = () => resolve(reader.result);
        reader.onerror = (error) => reject(error);
      });
    }

    const handleCancel = () => {
      previewVisible.value = false;
    };

    const handlePreview = async (file) => {
      if (!file.url && !file.preview) {
        file.preview = await getBase64(file.originFileObj);
      }
      previewImage.value = file.url || file.preview;
      previewVisible.value = true;
    };

    return {
      ...toRefs(room),
      handlePreview,
      handleCancel,
      getBase64,
      previewVisible,
      previewImage,
      errors,
      statusOptions,
      widgetOptions,
      createRoom,
      route,
      filter,
      hotelName,
    };
  },
  components: {
    Editor,
    Upload,
    Modal,
    InputNumber,
  },
  methods: {},
});
</script>

<style>
.selec-danger-input {
  border: 1px solid red;
}
</style>
