<template>
  <form @submit.prevent="uploadHotel" enctype="multipart/form-data">
    <a-card :title="`Edit Hotel ` + title" style="width: 100%" class="shadow">
      <div class="row">
        <div class="col-12 col-sm-6">
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
                <span>place:</span>
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
        <div class="col-12 col-sm-6 mb-4">
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
                <div>
                  <div style="margin-top: 8px">Upload</div>
                </div>
              </Upload>
              <Modal :open="previewVisible" :title="previewTitle" @cancel="handleCancel">
                <img alt="example" style="width: 100%" :src="previewImage" />
              </Modal>
              <div class="w-100"></div>
              <small v-if="errors.image" class="text-danger">{{ errors.image[0] }}</small>
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
import { Upload, Modal, message, TimePicker, Rate } from "ant-design-vue";
import Editor from "../Editor.vue";
import dayjs from "dayjs";
import { useRouter, useRoute } from "vue-router";
import { useMenu } from "../../../store/menu";

export default defineComponent({
  setup() {
    const store = useMenu();
    store.onselectedkey(["Hotel"]);

    const $loading = inject("$loading");

    const router = useRouter();
    const route = useRoute();
    const errors = ref({});

    const hotel = reactive({
      title: "",
      place: "",
      address: "",
      star: 0,
      introduce: "",
      status: "",
      fileList: ref([]),
      checkin_time: ref(),
      checkout_time: ref(),
    });

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

    const filterplace = (input, Places) => {
      return Places.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
    };

    const Places = ref([]);
    const getHoteledit = () => {
      const loader = $loading.show({});
      axios
        .get(`http://127.0.0.1:8000/api/dashboard/Hotel/${route.params.slug}/edit`)
        .then(function (response) {
          // console.log(response);
          Places.value = response.data.data.places;
          hotel.title = response.data.data.hotel.title;
          hotel.place = response.data.data.hotel.place_id;
          hotel.address = response.data.data.hotel.address;
          hotel.star = response.data.data.hotel.star_rating;
          hotel.status = response.data.data.hotel.status;
          hotel.introduce = response.data.data.hotel.introduce;
          hotel.checkin_time = dayjs(response.data.data.hotel.checkin_time, "HH:mm");
          hotel.checkout_time = dayjs(response.data.data.hotel.checkout_time, "HH:mm");
          // hotel.fileList = response.data.data.path;

          if (Array.isArray(response.data.data.path)) {
            hotel.fileList = response.data.data.path.map((item, index) => ({
              uid: index,
              name: item.url,
              status: "done",
              url: item.url,
              thumbUrl: item.url,
            }));
          } else {
            // Xử lý trường hợp không phải mảng
            console.error("response.data.data.path is not an array");
          }
          loader.hide();
        })
        .catch(function (error) {
          // console.log(error);
          loader.hide();
          if (error.response.status === 404) {
            message.error(error.response.data.message);
            router.push({ name: "hotel" });
          } else {
            message.error(error.response.data.message);
          }
        });
    };
    getHoteledit();

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
        // console.log(file.preview);
      }
      previewImage.value = file.url || file.preview || (item && item.url);
      previewVisible.value = true;
      // console.log(previewImage);
    };

    const uploadHotel = () => {
      const loader = $loading.show({});
      const formData = new FormData();
      formData.append("title", hotel.title);
      formData.append("place_id", hotel.place ? hotel.place : "");
      formData.append("star_rating", hotel.star);
      formData.append("address", hotel.address);
      formData.append("status", hotel.status ? hotel.status : "");
      formData.append("introduce", hotel.introduce);
      formData.append(
        "checkin_time",
        hotel.checkin_time ? hotel.checkin_time.format("HH:mm") : ""
      );
      formData.append(
        "checkout_time",
        hotel.checkout_time ? hotel.checkout_time.format("HH:mm") : ""
      );

      let countNew = 0;
      let counOld = 0;
      hotel.fileList.forEach((file) => {
        if (file.originFileObj instanceof File) {
          // Nếu phần tử là một tệp (File), thêm nó vào formData
          formData.append(`file_${countNew}`, file.originFileObj);
          countNew++;
        } else {
          formData.append(`Old_${counOld}`, file.name);
          counOld++;
        }
      });

      formData.append("countNew", countNew);
      formData.append("counOld", counOld);

      axios
        .post(
          `http://127.0.0.1:8000/api/dashboard/Hotel/${route.params.slug}/edit`,
          formData
        )
        .then(function (response) {
          // console.log(response);
          if (response) {
            message.success(response.data.message);
            loader.hide();
            router.push({ name: "hotel" });
          }
        })
        .catch(function (error) {
          console.log(error);
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

    return {
      Places,
      statusOptions,
      filterplace,
      errors,
      previewVisible,
      previewImage,
      previewTitle,
      getBase64,
      handleCancel,
      handlePreview,
      ...toRefs(hotel),
      uploadHotel,
    };
  },
  components: {
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
</style>
