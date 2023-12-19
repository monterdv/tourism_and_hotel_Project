<template>
  <a-button @click="showModal" style="margin-bottom: 8px">Add number rooms</a-button>
  <a-table :data-source="numberRoom" :columns="columns">
    <template #bodyCell="{ column, index, record }">
      <template v-if="column.key === 'index'">
        <span>{{ index + 1 }}</span>
      </template>

      <template v-if="column.key === 'status'">
        <a-tag color="success" v-if="record.status === 'available'">{{
          record.status
        }}</a-tag>
        <a-tag color="warning" v-if="record.status === 'occupied'">{{
          record.status
        }}</a-tag>
        <a-tag color="error" v-if="record.status === 'maintenance'">{{
          record.status
        }}</a-tag>
      </template>
      <template v-if="column.key === 'acction'">
        <a-button type="primary" class="me-2" @click="GetUpdateRecord(index)">
          <font-awesome-icon :icon="['fas', 'pen-to-square']" />
        </a-button>
        <a-button type="primary" danger @click="deleteRecord(index)" class="mt-1"
          ><font-awesome-icon :icon="['fas', 'trash']"
        /></a-button>
      </template>
    </template>
  </a-table>
  <Modal v-model:open="open">
    <!-- <template #title> {{ modalTitle }} </template> -->
    <template #footer>
      <form
        @submit.prevent="checkform ? checkname() : update(index)"
        enctype="multipart/form-data"
      >
        <div class="row">
          <div class="col-12 col-sm-12 mb-4 justify-content-center align-items-center">
            <div class="row">
              <div class="col-12 col-sm-2 text-start text-sm-center">
                <label>
                  <span class="text-danger me-1">*</span>
                  <span>room number:</span>
                </label>
              </div>
              <div class="col-12 col-sm-9 text-start text-sm-start">
                <a-input
                  placeholder="enter number room"
                  allow-clear
                  v-model:value="number_of_rooms"
                />
                <div class="w-100"></div>
                <small v-if="errors && errors.number_of_rooms" class="text-danger">{{
                  errors.number_of_rooms[0]
                }}</small>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-12 mb-4 justify-content-center align-items-center">
            <div class="row mb-4">
              <div class="col-12 col-sm-2 text-start text-sm-center">
                <label>
                  <span class="text-danger me-1">*</span>
                  <span>status:</span>
                </label>
              </div>
              <div class="col-12 col-sm-9 text-start text-sm-start">
                <a-select
                  v-model:value="status"
                  style="width: 100%"
                  placeholder="Please select Status"
                  :options="statusOptions"
                  allow-clear
                ></a-select>
                <div class="w-100"></div>
                <small v-if="errors && errors.status" class="text-danger">{{
                  errors.status[0]
                }}</small>
              </div>
            </div>
          </div>
        </div>

        <a-button key="back" @click="handleCancel">Cancel</a-button>
        <a-button key="submit" htmlType="submit" type="primary">Save</a-button>
      </form>
    </template>
  </Modal>
</template>

<script>
import { defineComponent, ref, reactive, toRefs, inject } from "vue";
import { Modal, message } from "ant-design-vue";
import { useRouter, useRoute } from "vue-router";

export default defineComponent({
  props: {
    numberRoom: {
      type: Object,
      default: null,
    },
  },
  setup(props) {
    const open = ref(false);
    const route = useRoute();
    const $loading = inject("$loading");
    const checkform = ref(true);

    const errors = ref({});

    // console.log("check:", route);
    const item = reactive({
      number_of_rooms: "",
      status: null,
      index: null,
    });

    const statusOptions = [
      {
        label: "available",
        value: "available",
      },
      {
        label: "occupied",
        value: "occupied",
      },
      {
        label: "maintenance",
        value: "maintenance",
      },
    ];

    const columns = [
      {
        title: "#",
        key: "index",
        width: 70,
      },
      {
        title: "number rooms",
        dataIndex: "number_of_rooms",
        width: "30%",
      },
      {
        title: "status",
        key: "status",
      },
      {
        title: "acction",
        key: "acction",
      },
    ];

    const handleCancel = () => {
      open.value = false;
      checkform.value = true;
      item.number_of_rooms = null;
      item.status = null;
    };
    const showModal = () => {
      open.value = true;
      item.number_of_rooms = null;
      item.status = null;
    };

    const checkname = () => {
      const loader = $loading.show({});

      const isRoomNumberUnique = !props.numberRoom.some(
        (room) => room.number_of_rooms === item.number_of_rooms
      );

      if (!isRoomNumberUnique) {
        message.error("Room number must be unique.");
        loader.hide();
        return;
      }
      const params = {
        number_of_rooms: item.number_of_rooms,
        status: item.status,
      };
      axios
        .get(`http://127.0.0.1:8000/api/dashboard/Hotel/${route.params.slug}/check`, {
          params,
        })
        .then(function (response) {
          console.log(response);
          message.success(response.data.message);
          const newRoom = response.data.data;
          props.numberRoom.push(newRoom);
          // console.log(props.numberRoom);

          item.number_of_rooms = null;
          item.status = null;
          handleCancel();
          loader.hide();
        })
        .catch(function (error) {
          console.error(error);
          errors.value = error.response.data.errors;
          loader.hide();
        });
    };
    const deleteRecord = (index) => {
      // console.log(index);
      const loader = $loading.show({});

      // // Tìm chỉ mục của bản ghi với số_phòng đã cho
      // const indexToDelete = props.numberRoom.findIndex((room) => room.id === id);

      // if (indexToDelete !== -1) {
      //   // Nếu tìm thấy, hãy xóa bản ghi khỏi mảng numberRoom

      const deletedRecord = props.numberRoom.splice(index, 1)[0];

      message.success(
        `Record with room number ${deletedRecord.number_of_rooms} deleted successfully.`
      );
      // } else {
      //   message.error("Record not found.");
      // }

      loader.hide();
    };
    const GetUpdateRecord = (index) => {
      // console.log(index);
      showModal();
      checkform.value = false;
      item.number_of_rooms = props.numberRoom[index].number_of_rooms;
      item.status = props.numberRoom[index].status;
      item.index = index;
    };

    const update = (index) => {
      // if (recordIndex !== -1) {
      props.numberRoom[index].number_of_rooms = item.number_of_rooms;
      props.numberRoom[index].status = item.status;
      item.id = null;
      item.number_of_rooms = null;
      item.status = null;
      checkform.value = true;
      handleCancel();
      message.success(`update successfully.`);
      // } else {
      //   message.error("Record not found.");
      // }
    };

    return {
      columns,
      open,
      checkform,
      handleCancel,
      deleteRecord,
      update,
      GetUpdateRecord,
      showModal,
      ...toRefs(item),
      statusOptions,
      checkname,
      errors,
    };
  },
  components: {
    Modal,
  },
});
</script>

<style></style>
