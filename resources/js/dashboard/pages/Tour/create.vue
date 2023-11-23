<template>
  <form @submit.prevent="createHotel" enctype="multipart/form-data">
    <a-card title="Create Tour" style="width: 100%">
      <div class="row">
        <div class="col-12 col-sm-12">
          <div class="row mb-4">
            <div class="col-12 col-sm-1 text-start text-sm-end">
              <label>
                <span class="text-danger me-1">*</span>
                <span>Tour Name:</span>
              </label>
            </div>
            <div class="col-12 col-sm-11">
              <a-input placeholder="input Tour Name" allow-clear v-model:value="title" />
              <div class="w-100"></div>
              <small v-if="errors.title" class="text-danger">{{ errors.title[0] }}</small>
            </div>
          </div>
          <div class="col-12 col-sm-12">
            <div class="row mb-4">
              <div class="col-12 col-sm-6">
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
                    <small v-if="errors.place_id" class="text-danger">{{
                      errors.place_id[0]
                    }}</small>
                  </div>
                </div>
              </div>
              <div class="col-12 col-sm-6">
                <div class="row mb-4">
                  <div class="col-12 col-sm-1 text-start text-sm-end">
                    <label>
                      <span class="text-danger me-1">*</span>
                      <span>Status:</span>
                    </label>
                  </div>
                  <div class="col-12 col-sm-11">
                    <a-select
                      show-search
                      placeholder="hotel star number"
                      style="width: 100%"
                      :options="statusOptions"
                      :filter-option="filterOption"
                      allow-clear
                      v-model:value="status"
                      class="col-12"
                    ></a-select>
                    <small v-if="errors.status" class="text-danger">{{
                      errors.status[0]
                    }}</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-12">
            <div class="row mb-4">
              <div class="col-12 col-sm-6">
                <div class="row mb-2">
                  <div class="col-12 col-sm-2 text-start text-sm-end">
                    <label>
                      <span class="text-danger me-1">*</span>
                      <span>category:</span>
                    </label>
                  </div>
                  <div class="col-12 col-sm-10">
                    <a-select
                      show-search
                      placeholder="category"
                      style="width: 100%"
                      :options="category"
                      :filter-option="filtercategory"
                      allow-clear
                      v-model:value="category_id"
                      class="col-12"
                    ></a-select>
                    <small v-if="errors.category_id" class="text-danger">{{
                      errors.category_id[0]
                    }}</small>
                  </div>
                </div>
              </div>
              <div class="col-12 col-sm-6">
                <div class="row mb-4">
                  <div class="col-12 col-sm-2 text-start text-sm-end">
                    <label>
                      <span class="text-danger me-1">*</span>
                      <span>Day number:</span>
                    </label>
                  </div>
                  <div class="col-12 col-sm-10">
                    <InputNumber v-model:value="duration" min="1" style="width: 100%">
                      <template #addonBefore>
                        <font-awesome-icon :icon="['far', 'calendar-days']" />
                      </template>
                    </InputNumber>
                    <small v-if="errors.status" class="text-danger">{{
                      errors.status[0]
                    }}</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- here -->
        <div class="row">
          <a-card title="Image hotel" style="width: 100%">
            <div class="clearfix">
              <Upload
                v-model:file-list="fileList"
                list-type="picture-card"
                @preview="handlePreview"
                action="http://127.0.0.1:8000/api/upload"
              >
                <div v-if="fileList.length < 6">
                  <plus-outlined />
                  <div style="margin-top: 8px">Upload</div>
                </div>
              </Upload>
              <Modal :open="previewVisible" :title="previewTitle" @cancel="handleCancel">
                <img alt="example" style="width: 100%" :src="previewImage" />
              </Modal>
              <div class="w-100"></div>
              <small v-if="errors.image" class="text-danger">{{ errors.image[0] }}</small>
            </div>
          </a-card>
        </div>
      </div>

      <div class="row mb-2">
        <div class="col-12 col-sm-12 text-start text-sm-start">
          <label>
            <span class="text-danger me-1">*</span>
            <span>introduce:</span>
            <div class="w-100"></div>
            <small v-if="errors.introduce" class="text-danger">{{
              errors.introduce[0]
            }}</small>
          </label>
        </div>
      </div>
      <Editor v-model="introduce" />

      <div class="row mt-4">
        <div class="col-12 col-sm-12 text-start text-sm-start">
          <label>
            <span class="text-danger me-1">*</span>
            <span>schedule:</span>
            <div class="w-100"></div>
            <small v-if="errors.schedule" class="text-danger">{{
              errors.schedule[0]
            }}</small>
          </label>
        </div>
      </div>
      <Editor v-model="schedule" />

      <div class="row mt-3">
        <div class="col-12 col-sm-9 d-grid d-sm-flex justify-content-sm-end mx-auto">
          <router-link :to="{ name: 'tour' }">
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
import { Upload, Modal, message, InputNumber } from "ant-design-vue";
import Editor from "../Editor.vue";
import { useRouter } from "vue-router";
import { useMenu } from "../../../store/menu";

export default defineComponent({
  setup() {
    const store = useMenu();
    store.onselectedkey(["tour_add"]);
    const $loading = inject("$loading");
    const router = useRouter();
    const errors = ref({});

    const Tour = reactive({
      title: "",
      place: null,
      category_id: null,
      duration: null,
      introduce: "",
      schedule: "",
      status: null,
      fileList: ref([]),
    });

    const statusOptions = [
      {
        label: "Active",
        value: "active",
      },
      {
        label: "On Hold",
        value: "On Hold",
      },
    ];

    const filterOption = (input, statusOptions) => {
      return statusOptions.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
    };
    const filterplace = (input, Places) => {
      return Places.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
    };

    const filtercategory = (input, category) => {
      return category.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
    };

    const Places = ref([]);
    const category = ref([]);
    const getCreateTour = () => {
      const loader = $loading.show({});
      axios
        .get("http://127.0.0.1:8000/api/dashboard/tour/create")
        .then(function (response) {
          // console.log(response);
          Places.value = response.data.data.places;
          category.value = response.data.data.category;
          loader.hide();
        })
        .catch(function (error) {
          console.error(error);
          loader.hide();
        });
    };
    getCreateTour();

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
      formData.append("title", Tour.title);
      formData.append("place_id", Tour.place ? Tour.place : "");
      formData.append("status", Tour.status ? Tour.status : "");
      formData.append("introduce", Tour.introduce);
      formData.append("schedule", Tour.schedule);
      formData.append("category_id", Tour.category_id ? Tour.category_id : "");
      formData.append("duration", Tour.duration);

      let count = 0;
      Tour.fileList.forEach((file, index) => {
        formData.append(`file_${index}`, file.originFileObj);
        count++;
      });
      formData.append("count", count);

      axios
        .post("http://127.0.0.1:8000/api/dashboard/tour/create", formData)
        .then(function (response) {
          console.log(response);
          if (response) {
            loader.hide();
            message.success(response.data.message);
            router.push({ name: "tour" });
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
      category,
      filterOption,
      filterplace,
      filtercategory,
      errors,
      previewVisible,
      previewImage,
      previewTitle,
      getBase64,
      handleCancel,
      handlePreview,
      ...toRefs(Tour),
      createHotel,
      Image,
      statusOptions,
    };
  },
  components: {
    Editor,
    Upload,
    Modal,
    InputNumber,
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
