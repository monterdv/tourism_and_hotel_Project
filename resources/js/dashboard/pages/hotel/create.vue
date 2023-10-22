<template>
  <form @submit.prevent="createHotel" enctype="multipart/form-data">
    <a-card title="Create Hotel" style="width: 100%">
      <div class="row">
        <div class="col-12 col-sm-7">
          <div class="row mb-4">
            <div class="col-12 col-sm-2 text-start text-sm-end">
              <label>
                <span class="text-danger me-1">*</span>
                <span>Hotel:</span>
              </label>
            </div>
            <div class="col-12 col-sm-10">
              <a-input placeholder="input Name Hotel" allow-clear v-model:value="title" />
              <div class="w-100"></div>
              <small v-if="errors.title" class="text-danger">{{ errors.title[0] }}</small>
            </div>
          </div>

          <div class="row mb-4">
            <div class="col-12 col-sm-2 text-start text-sm-end">
              <label>
                <span class="text-danger me-1">*</span>
                <span>Hotel Stars:</span>
              </label>
            </div>
            <div class="col-12 col-sm-10">
              <Rate v-model:value="star" />
              <div class="w-100"></div>
              <small v-if="errors.star_rating" class="text-danger">{{
                errors.star_rating[0]
              }}</small>
            </div>
          </div>

          <div class="row mb-4">
            <div class="col-12 col-sm-2 text-start text-sm-end">
              <label>
                <span class="text-danger me-1">*</span>
                <span>Place:</span>
              </label>
            </div>
            <div class="col-12 col-sm-10">
              <a-select
                show-search
                placeholder="country or city"
                style="width: 100%"
                :options="Places"
                :filter-option="filterplace"
                allow-clear
                v-model:value="place"
                class="col-12"
              ></a-select>
              <div class="w-100"></div>
              <small v-if="errors.place_id" class="text-danger">{{
                errors.place_id[0]
              }}</small>
            </div>
          </div>
          <div class="row mb-4">
            <div class="col-12 col-sm-2 text-start text-sm-end">
              <label>
                <span class="text-danger me-1">*</span>
                <span>address:</span>
              </label>
            </div>
            <div class="col-12 col-sm-10">
              <a-input placeholder="input address" allow-clear v-model:value="address" />
              <div class="w-100"></div>
              <small v-if="errors.address" class="text-danger">{{
                errors.address[0]
              }}</small>
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
            <div class="col-12 col-sm-6">
              <div class="row">
                <div class="col-12 col-sm-4 text-start text-sm-start">
                  <label>
                    <span class="text-danger me-1">*</span>
                    <span style="font-size: small">checkin time:</span>
                  </label>
                </div>
                <div class="col-12 col-sm-8">
                  <TimePicker v-model:value="checkin_time" format="HH:mm" />
                  <div class="w-100"></div>
                  <small v-if="errors.checkin_time" class="text-danger">{{
                    errors.checkin_time[0]
                  }}</small>
                </div>
              </div>
            </div>
            <div class="col-12 col-sm-6">
              <div class="row">
                <div class="col-12 col-sm-5 text-start text-sm-start">
                  <label>
                    <span class="text-danger me-1">*</span>
                    <span style="font-size: small">checkout time:</span>
                  </label>
                </div>
                <div class="col-12 col-sm-7">
                  <TimePicker v-model:value="checkout_time" format="HH:mm" />
                  <div class="w-100"></div>
                  <small v-if="errors.checkout_time" class="text-danger">{{
                    errors.checkout_time[0]
                  }}</small>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- here -->
        <div class="col-12 col-sm-5 mb-4">
          <div class="row">
            <label>
              <span class="text-danger me-1">*</span>
              <span style="font-size: small">Image:</span>
            </label>
            <div class="clearfix">
              <Upload
                v-model:file-list="fileList"
                list-type="picture-card"
                @preview="handlePreview"
                action="http://127.0.0.1:8000/api/upload"
              >
                <div v-if="fileList.length < 6">
                  <!-- <plus-outlined /> -->
                  <div style="margin-top: 8px">Upload</div>
                </div>
              </Upload>
              <Modal :open="previewVisible" :title="previewTitle" @cancel="handleCancel">
                <img alt="example" style="width: 100%" :src="previewImage" />
              </Modal>
              <div class="w-100"></div>
                  <small v-if="errors.image" class="text-danger">{{
                    errors.image[0]
                  }}</small>
            </div>
          </div>
        </div>
      </div>
      <div class="row mb-2">
        <div class="col-12 col-sm-12 text-start text-sm-start">
          <label>
            <span class="text-danger me-1">*</span>
            <span>introduce:</span>
          </label>
          <div class="w-100"></div>
          <small v-if="errors.introduce" class="text-danger">{{
            errors.introduce[0]
          }}</small>
        </div>
      </div>
      <Editor v-model="introduce" />

      <div class="row mt-3">
        <div class="col-12 col-sm-9 d-grid d-sm-flex justify-content-sm-end mx-auto">
          <router-link :to="{ name: 'hotel' }">
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
import { Empty, Upload, Modal, message, TimePicker, Rate } from "ant-design-vue";
import dayjs from "dayjs";
import Editor from "../Editor.vue";
import { useRouter } from "vue-router";
import { useMenu } from "../../../store/menu";

export default defineComponent({
  setup() {
    const store = useMenu();
    store.onselectedkey(["Hotel-create"]);

    const $loading = inject("$loading");

    const router = useRouter();
    const errors = ref({});

    const hotel = reactive({
      title: "",
      place: null,
      address: "",
      star: 5,
      introduce: "",
      status: "active",
      fileList: ref([]),
      checkin_time: ref(dayjs("11:00", "HH:mm")),
      checkout_time: ref(dayjs("15:00", "HH:mm")),
    });

    const options = [
      {
        label: "1 Star",
        value: "1",
      },
      {
        label: "2 Stars",
        value: "2",
      },
      {
        label: "3 Stars",
        value: "3",
      },
      {
        label: "4 Stars",
        value: "4",
      },
      {
        label: "5 Stars",
        value: "5",
      },
    ];

    const statusOptions = [
      {
        label: "Active",
        value: "active",
      },
      {
        label: "Pending",
        value: "pending",
      },
      {
        label: "Inactive",
        value: "inactive",
      },
    ];

    const filterOption = (input, option) => {
      return option.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
    };
    const filterplace = (input, Places) => {
      return Places.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
    };

    const Places = ref([]);
    const getPlaces = () => {
      const loader = $loading.show({});
      axios
        .get("http://127.0.0.1:8000/api/dashboard/Hotel/create")
        .then(function (response) {
          // console.log(response);
          loader.hide();
          Places.value = response.data.data.places;
        })
        .catch(function (error) {
          console.error(error);
          loader.hide();
        });
    };
    getPlaces();

    const previewVisible = ref(false);
    const previewImage = ref("");
    const previewTitle = ref("");

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
      previewTitle.value = "";
    };

    const handlePreview = async (file) => {
      if (!file.url && !file.preview) {
        file.preview = await getBase64(file.originFileObj);
      }
      previewImage.value = file.url || file.preview;
      previewVisible.value = true;
    };

    const createHotel = () => {
      const loader = $loading.show({});
      const formData = new FormData();
      formData.append("title", hotel.title);
      formData.append("place_id", hotel.place ? hotel.place : "");
      formData.append("star_rating", hotel.star);
      formData.append("address", hotel.address);
      formData.append("status", hotel.status);
      formData.append("introduce", hotel.introduce);
      formData.append(
        "checkin_time",
        hotel.checkin_time ? hotel.checkin_time.format("HH:mm") : ""
      );
      formData.append(
        "checkout_time",
        hotel.checkout_time ? hotel.checkout_time.format("HH:mm") : ""
      );

      let count = 0;
      hotel.fileList.forEach((file, index) => {
        formData.append(`file_${index}`, file.originFileObj);
        // console.log(file.originFileObj);
        count++;
      });

      formData.append("count", count);
      // console.log(hotel.fileList);

      axios
        .post("http://127.0.0.1:8000/api/dashboard/Hotel/create", formData)
        .then(function (response) {
          // console.log(response);
          loader.hide();
          if (response) {
            message.success(response.data.message);
            router.push({ name: "hotel" });
          }
        })
        .catch(function (error) {
          console.log(error);
          loader.hide();
          if (error.response.status === 400) {
            message.error(error.response.data.message);
          } else {
            if (error.response.data.errors) {
              errors.value = error.response.data.errors;
            } else {
              message.error(error.response.data.message);
            }
          }
        });
    };

    return {
      Places,
      options,
      filterOption,
      filterplace,
      errors,
      previewVisible,
      previewImage,
      previewTitle,
      getBase64,
      handleCancel,
      handlePreview,
      ...toRefs(hotel),
      createHotel,
      statusOptions,
    };
  },
  components: {
    Empty,
    Editor,
    Upload,
    Modal,
    TimePicker,
    Rate,
  },
});
</script>

<style>
.ant-upload-select-picture-card i {
  font-size: 32px;
  color: #999;
}

.ant-upload-select-picture-card .ant-upload-text {
  margin-top: 8px;
  color: #666;
}

.selec-danger-input {
  border: 1px solid red;
}
</style>
