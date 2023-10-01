<template>
  <div class="slide-bar__left" style="margin-bottom: 10px">
    <div class="schedue">
      <h1 class="schedule-header">Launch schedule & price</h1>
      <span class="schedule-end">Select departure date:</span>
      <DatePicker v-model:value="value1" :disabled-date="disabledDate">
        <template #dateRender="{ current }">
          <div class="ant-picker-cell-inner" :style="getCurrentStyle(current)">
            {{ current.date() }}
          </div>
          <div v-if="notes[current.date()]" class="note">
            {{ notes[current.date()] }}
          </div>
        </template>
      </DatePicker>
    </div>

    <div class="schedue-day">
      <!-- <div class="schedue-day__list">
        <div class="schedue-day__item">22/08</div>
      </div> -->

      <!-- <div class="schedue-day__all">
        <div class="schedue-day__icon">
          <i class="fa-solid fa-calendar-days"></i>
        </div>
        <p class="schedue-day__text">Tất Cả</p>
      </div> -->
    </div>

    <div class="schedue-people">
      <div class="schedue-pepleo__all">
        <p class="schedue-text">Adult:</p>
        <p class="schedue-price">12.990.000 đ</p>
        <p class="schedue-text">
          <InputNumber min="2" style="width: 50%"> </InputNumber>
          people
        </p>
      </div>
    </div>

    <div class="schedue-people">
      <div class="schedue-pepleo__all">
        <p class="schedue-text">Children:</p>
        <p class="schedue-price">12.990.000 đ</p>
        <p class="schedue-text">
          <InputNumber min="0" style="width: 50%"> </InputNumber> people
        </p>
      </div>
    </div>

    <div class="slider-note">
      <div class="slider-note__item">
        <i class="fa-solid fa-circle-info"></i>
      </div>
      <p class="slider-note__text">Contact to confirm</p>
    </div>

    <div class="slider-note">
      <p class="slider-price__total">total</p>
      <p class="slider-price__root">25.980.000VND</p>
    </div>

    <div class="tours-detail__contact">
      <div class="detail__contact-support justy">
        <p class="detail__contact-support--text">Contact Consulting</p>
      </div>

      <div class="detail__contact-required">
        <p class="detail__contact-required--text">Order Request</p>
      </div>
    </div>
  </div>

  <div class="slide-bar__left">
    <div class="row">
      <div class="col l-6">
        <div class="slide__bar-all">
          <div class="slide-bar__icon">
            <i class="fa-solid fa-check"></i>
          </div>
          <p class="slide-bar__text">5 star cruise</p>
        </div>
      </div>
      <div class="col l-6">
        <div class="slide__bar-all">
          <div class="slide-bar__icon">
            <i class="fa-solid fa-check"></i>
          </div>
          <p class="slide-bar__text">TravelMate®</p>
        </div>
      </div>
      <div class="col l-6">
        <div class="slide__bar-all">
          <div class="slide-bar__icon">
            <i class="fa-solid fa-check"></i>
          </div>
          <p class="slide-bar__text">Shows</p>
        </div>
      </div>

      <div class="col l-6">
        <div class="slide__bar-all">
          <div class="slide-bar__icon">
            <i class="fa-solid fa-check"></i>
          </div>
          <p class="slide-bar__text">Full meal package</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent, ref, toRefs, inject, onMounted } from "vue";
import { DatePicker, InputNumber, message } from "ant-design-vue";
import dayjs from "dayjs";

export default defineComponent({
  setup() {
    const value1 = ref();

    const notes = ref({
      1: "1000",
      5: "giá",
      // Thêm các ghi chú khác nếu cần
    });
    const getCurrentStyle = (current) => {
      const style = {};
      const currentDate = current.date();

      if (notes.value[currentDate]) {
        style.border = "1px solid #0085CA";
        style.borderRadius = "50%";
        style.backgroundColor = "#1890ff"; // Màu nền
        style.color = "#fff"; // Màu chữ
        style.textAlign = "center"; // Canh giữa chữ
        // style.lineHeight = "30px"; // Đặt độ cao của dòng cho chữ
        style.fontWeight = "bold"; // Đậm
        // style.position = "relative"; // Đặt vị trí tương đối để căn giữa chữ
        // style.zIndex = "1"; // Đặt chỉ số sắp xếp để đảm bảo chữ nằm trên cùng
      }

      return style;
    };

    const disabledDate = (current) => {
      // Tạo một bản sao của current để so sánh
      const clonedCurrent = current.clone();

      // Lấy kiểu dáng của ngày hiện tại
      const currentStyle = getCurrentStyle(clonedCurrent);

      if (currentStyle.border) {
        // Nếu có thuộc tính border trong kiểu dáng của ngày hiện tại không bị vô hiệu hóa
        return false;
      }

      // Nếu không có thuộc tính border, kiểu dáng không phù hợp,sẽ bị vô hiệu hóa
      return true;
    };

    return { value1, getCurrentStyle, disabledDate, notes };
  },

  components: {
    InputNumber,
    DatePicker,
  },
});
</script>

<style></style>
