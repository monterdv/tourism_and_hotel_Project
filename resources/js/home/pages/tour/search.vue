<template>
  <div class="homeDetail">
    <div class="grid wide">
      <div class="homeDetail-header">
        <h1 class="homeDetail-textHeader">Khách sạn Đà Lạt</h1>
      </div>
      <div class="row sm-gutter">
        <div class="col l-4 c-12 m-12">
          <div class="homeDetail-containerLeft">
            <div class="homeDetail-containerLeft__item">
              <div class="homDetail-container__image">
                <img
                  src="Admin.jpg"
                  alt="admin"
                  class="homDetail-container__image-img"
                />
              </div>
              <div class="homDetail-container__contact">
                <h2 class="homDetail-container__contact-header">Cần Hổ Trợ</h2>
                <h2 class="homDetail-container__contact-header">
                  Tư Vấn : Lê Thị Chương
                </h2>
                <div class="homDetail-container__body">
                  <div class="homDetail-container__item">
                    <p class="homDetail-container__text">HCM</p>
                    <div class="homDetail-container__phone">1900 1870</div>
                  </div>

                  <div class="homDetail-container__item">
                    <p class="homDetail-container__text">HN</p>
                    <div class="homDetail-container__phone">1900 2045</div>
                  </div>

                  <div class="homDetail-container__item">
                    <p class="homDetail-container__text">CT</p>
                    <div class="homDetail-container__phone">1900 2087</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Tìm Kiếm -->

          <div class="homeDetail-header">
            <h1 class="homeDetail-textHeader" style="margin: 20px 0">Tìm Kiếm</h1>
            <div class="homDetail-container__search">
              <input
                type="text"
                placeholder="Tìm Kiếm Hotel ?"
                class="homDetail-container__input-search"
              />
              <div class="homDetail-container__search-icon">
                <i class="fa-solid fa-magnifying-glass-location"></i>
              </div>
            </div>
          </div>

          <!-- Star  -->
          <div class="homeDetail-header">
            <h1 class="homeDetail-textHeader" style="margin: 20px 0">Hạng Sao</h1>
          </div>
          <div class="homDetail-rankContainer">
            <div class="homDetail-container__rank">
              <label for="five star" class="homDetail-container__check">
                <input type="checkbox" name="" id="" />
              </label>
              <div class="homDetail-container__star">
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
              </div>
            </div>

            <div class="homDetail-container__rank">
              <label for="five star" class="homDetail-container__check">
                <input type="checkbox" name="" id="" />
              </label>
              <div class="homDetail-container__star">
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
              </div>
            </div>

            <div class="homDetail-container__rank">
              <label for="five star" class="homDetail-container__check">
                <input type="checkbox" name="" id="" />
              </label>
              <div class="homDetail-container__star">
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
              </div>
            </div>

            <div class="homDetail-container__rank">
              <label for="five star" class="homDetail-container__check">
                <input type="checkbox" name="" id="" />
              </label>
              <div class="homDetail-container__star">
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
              </div>
            </div>

            <div class="homDetail-container__rank">
              <label for="five star" class="homDetail-container__check">
                <input type="checkbox" name="" id="" />
              </label>
              <div class="homDetail-container__star">
                <i class="fa-solid fa-star"></i>
              </div>
            </div>
          </div>

          <!-- Experience -->
          <h1 class="homeDetail-textHeader" style="margin: 20px 0">
            Kinh Nghiệm Du Lịch Đà Lạt
          </h1>
          <div class="homeDetail-experience">
            <div class="homeDetail-experience-body">
              <p class="homeDetail-experience-body__text">
                Ngoài những địa chỉ quen thuộc như hồ Xuân Hương, đỉnh Langbiang, dinh Bảo
                Đại hay thiền viện Trúc Lâm,... bạn có biết Đà Lạt còn nhiều thứ tuyệt vời
                khác nữa không?<br />

                Nhất định bạn phải thưởng thức món nem nướng, sữa đậu nành nóng hay bánh
                ướt lòng gà ngon nức tiếng. Chưa hết đâu, bạn còn phải đến thăm thác Voi -
                nơi có cầu vồng suốt ngày hay bãi Tiên Sa, hồ Đại Ninh nữa nhé. Hãy để
                iVIVU.com mách bạn!
              </p>
              <a href="#" class="homeDetail-experience-body__link">Xem Thêm</a>
            </div>
          </div>
        </div>
        <div class="col l-8 c-12 m-12">
          <div class="homeDetail-containerRight">
            <div class="homeDetail-containerRight-a">
              <a href="#" class="homeDetail-containerRight-link">
                <div class="homeDetail-containerRight-body">
                  <div class="homeDetail-containerRight-body__container">
                    <div class="homeDetail-containerRight-image">
                      <img
                        src="https://cdn1.ivivu.com/iVivu/2022/03/28/16/hinhdaidien-374x280.webp?o=jpg"
                        alt=""
                        class="homeDetail-containerRight-img"
                      />
                    </div>
                    <div class="homeDetail-containerRight-container">
                      <p class="containerRight-text__header">
                        Khu Nghĩ Dưỡng Mecure Đà Lạt
                      </p>
                      <div class="containerRight-block">
                        <div class="containerRight-star">
                          <i class="fa-solid fa-star"></i>
                          <i class="fa-solid fa-star"></i>
                          <i class="fa-solid fa-star"></i>
                          <i class="fa-solid fa-star"></i>
                          <i class="fa-solid fa-star"></i>
                        </div>
                        <p class="containerRight-block__score">9.7</p>
                        <p class="containerRight-block__grade">Tuyệt Vời</p>
                        <p class="containerRight-block__count">71 Đánh Giá</p>
                      </div>

                      <div class="homeDetail-containerRight-location">
                        <div class="homeDetail-location__icon">
                          <i class="fa-solid fa-map-location-dot"></i>
                        </div>
                        <p class="homeDetail-location__text">
                          20 Phan Như Thạch , P1 - Thành Phố Đà Lạt
                        </p>
                      </div>
                    </div>
                    <div class="homeDetail-containerRight-price">
                      <p class="containerRight-price">2.461.000 VND</p>
                    </div>
                  </div>
                </div>
              </a>
            </div>
          </div>

          <a href="#" class="home__tour-item hide-on__laptop">
            <div
              class="home__tour-item-img"
              style="
                background-image: url(https://cdn1.ivivu.com/iVivu/2022/03/28/16/hinhdaidien-374x280.webp?o=jpg);
              "
            ></div>
            <h4 class="home__tour-name">Khu Nghĩ Dưỡng Mecure Đà Lạt</h4>
            <div class="home__tour-mark">
              <p class="tour__mark">9.7</p>
              <p class="tour__classification">Tuyệt Vời</p>
              <p class="rate">71 đánh giá</p>
            </div>

            <div class="tour__price">
              <span class="tour__price-new">2.416.000 VND</span>
              <div class="containerRight-block">
                <div class="containerRight-star">
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                </div>
              </div>
            </div>
            <div class="tour__favourite">
              <i class="fa-solid fa-check"></i>
              <span>
                Còn 14 ngày | Combo 3N2Đ | Vé máy bay + Bữa sáng + Miễn phí 01 trẻ em
              </span>
            </div>
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { useRouter, useRoute } from "vue-router";

export default {
  setup() {
    const route = useRoute();

    const searchHotel = () => {
      axios
        .get(`http://127.0.0.1:8000/api/hotel/search/${route.query.search}`)
        .then((response) => {
          console.log(response);
        })
        .catch((error) => {
          console.log(error);
        });
    };
    searchHotel();
    return {};
  },
  components: {
  },
};
</script>

<style></style>
