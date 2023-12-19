<template>
  <form @submit.prevent="createPlaces" enctype="multipart/form-data">
    <a-card title="Create Places" style="width: 100%" class="shadow">
      <div class="row">
        <div class="col-12 col-sm-12">
          <div class="row mb-4">
            <div class="col-12 col-sm-1 text-start text-sm-end">
              <label>
                <span class="text-danger me-1">*</span>
                <span>country:</span>
              </label>
            </div>
            <div class="col-12 col-sm-7">
              <a-input placeholder="country" allow-clear v-model:value="country" />
              <div class="w-100"></div>
              <small v-if="errors.country" class="text-danger">{{
                errors.country[0]
              }}</small>
            </div>
          </div>
        </div>

        <div class="row mb-4">
          <div class="col-12 col-sm-1 text-start text-sm-end">
            <label>
              <span class="text-danger me-1">*</span>
              <span>area: </span>
            </label>
          </div>
          <div class="col-12 col-sm-7">
            <a-select
              show-search
              placeholder="country or city"
              style="width: 100%"
              :options="areaOption"
              :filter-option="filterOption"
              allow-clear
              v-model:value="area_id"
            ></a-select>
            <div class="w-100"></div>
            <small v-if="errors.area" class="text-danger">{{ errors.area[0] }}</small>
          </div>
        </div>

        <div class="row mb-4">
          <div class="col-12 col-sm-2 text-start text-sm-start">
            <label>
              <span class="text-danger me-1">*</span>
              <span>Image backgroud: </span>
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

      <div class="row mt-3">
        <div class="col-12 col-sm-9 d-grid d-sm-flex justify-content-sm-end mx-auto">
          <router-link :to="{ name: 'places' }">
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
import { Upload, Modal, message } from "ant-design-vue";
import { useRouter } from "vue-router";
import { useMenu } from "../../../store/menu";

export default defineComponent({
  setup() {
    const store = useMenu();
    store.onselectedkey(["places"]);

    const $loading = inject("$loading");

    const router = useRouter();
    const errors = ref({});

    const areaOption = ref([]);

    const places = reactive({
      area_id: 1,
      country: "",
      file: ref([]),
    });

    const getplacesCreate = () => {
      const loader = $loading.show({});
      axios
        .get("http://127.0.0.1:8000/api/dashboard/places/create")
        .then(function (response) {
          // console.log(response);
          areaOption.value = response.data.area;
          loader.hide();
        })
        .catch(function (error) {
          console.error(error);
          loader.hide();
        });
    };

    getplacesCreate();

    const filterOption = (input, option) => {
      return option.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
    };

    const createPlaces = () => {
      const loader = $loading.show({});
      const formData = new FormData();
      formData.append("area_id", places.area_id);
      formData.append("country", places.country);
      // formData.append("file", places.file[0].originFileObj);
      if (places.file.length > 0) {
        formData.append("file", places.file[0].originFileObj);
      }

      axios
        .post("http://127.0.0.1:8000/api/dashboard/places/create", formData)
        .then(function (response) {
          console.log(response);
          loader.hide();

          if (response.data.message) {
            message.success(response.data.message);
            router.push({ name: "places" });
          }
        })
        .catch(function (error) {
          console.log(error);
          loader.hide();
          if (error.response.data.errors) {
            errors.value = error.response.data.errors;
          }
        });
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
      areaOption,
      handlePreview,
      previewVisible,
      previewImage,
      handleCancel,
      getBase64,
      ...toRefs(places),
      filterOption,
      createPlaces,
      errors,
    };
  },
  components: {
    Upload,
    Modal,
  },
});
</script>

<style>
.selec-danger-input {
  border: 1px solid red;
}
</style>
