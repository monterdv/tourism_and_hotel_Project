<template>
  <div class="container">
    <div class="blog-header">
      <div class="grid wide">
        <div class="row sm-gutter">
          <div class="col-12 col-sm-8">
            <div class="home-blog">
              <div class="home-blog__container">
                <div class="home-blog__img">
                  <img
                    src="https://cdn3.ivivu.com/2023/08/B%E1%BA%A1ch-Th%E1%BB%A7y-H%C3%A0-ivivu-770x375.jpg"
                    alt=""
                    class="home-blog__img-item"
                  />
                  <img
                    src="https://cdn3.ivivu.com/2023/08/Six-Senses-Krabey-Island-ivivu-19-770x375.jpg"
                    alt=""
                    class="home-blog__img-item"
                  />
                  <img
                    src="https://cdn3.ivivu.com/2023/06/Movenpick-Waverly-Phú-Quốc-ivivu-33-770x375.jpg"
                    alt=""
                    class="home-blog__img-item"
                  />
                  <img
                    src="https://cdn3.ivivu.com/2023/08/B%E1%BA%A1ch-Th%E1%BB%A7y-H%C3%A0-ivivu-770x375.jpg"
                    alt=""
                    class="home-blog__img-item"
                  />
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-4">
            <div class="home-blog__top-slidebar">
              <div class="home-blog__slidebar">
                <h2 class="home-blog__slidebar-item">Top 3 Bài Viết Mới Nhất</h2>
              </div>

              <div class="home-blog__sildebar-container">
                <div class="home-blog__sildebar-container-img">
                  <img
                    src="https://cdn3.ivivu.com/2022/09/Tổng-quan-du-lịch-Vũng-Tàu-ivivu-75x75.jpg"
                    alt=""
                    class="blog-slidebar-img"
                  />
                </div>

                <div class="home-blog__sildebar-container-item">
                  <div class="home-blog__sildebar-container-title">
                    <a title="Du lịch Vũng Tàu: Cẩm nang từ A đến Z">
                      Du lịch Vũng Tàu: Cẩm nang từ A đến Z
                    </a>
                  </div>

                  <div class="home-blog__sildebar-container-day">23/09/2023</div>
                </div>
              </div>

              <div class="home-blog__sildebar-container">
                <div class="home-blog__sildebar-container-img">
                  <img
                    src="https://cdn3.ivivu.com/2022/09/Tổng-quan-du-lịch-Vũng-Tàu-ivivu-75x75.jpg"
                    alt=""
                    class="blog-slidebar-img"
                  />
                </div>

                <div class="home-blog__sildebar-container-item">
                  <div class="home-blog__sildebar-container-title">
                    <a href="" title="Du lịch Vũng Tàu: Cẩm nang từ A đến Z">
                      Du lịch Vũng Tàu: Cẩm nang từ A đến Z
                    </a>
                  </div>

                  <div class="home-blog__sildebar-container-day">23/09/2023</div>
                </div>
              </div>

              <div class="home-blog__sildebar-container">
                <div class="home-blog__sildebar-container-img">
                  <img
                    src="https://cdn3.ivivu.com/2022/09/Tổng-quan-du-lịch-Vũng-Tàu-ivivu-75x75.jpg"
                    alt=""
                    class="blog-slidebar-img"
                  />
                </div>

                <div class="home-blog__sildebar-container-item">
                  <div class="home-blog__sildebar-container-title">
                    <a href="" title="Du lịch Vũng Tàu: Cẩm nang từ A đến Z">
                      Du lịch Vũng Tàu: Cẩm nang từ A đến Z
                    </a>
                  </div>

                  <div class="home-blog__sildebar-container-day">23/09/2023</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="blog-body">
      <div class="grid wide">
        <div class="row">
          <div class="col-12 col-sm-12">
            <div class="blog-body__item">
              <h2 class="blog-body-tile">Cẩm Nang Du Lịch</h2>
            </div>
            <router-link :to="{ name: 'blog-detail' }" class="blog-body__content">
              <div class="blog-body__content-img">
                <img
                  src="https://cdn3.ivivu.com/2023/08/ve-may-bay-uc-ivivu-2-370x215.jpg"
                  alt="BAY ĐẾN ÚC CÙNG LOẠT ƯU ĐÃI HẤP 
                                    DẪN TỪ CÁC HÃNG HÀNG KHÔNG NỘI ĐỊA VIỆT NAM"
                  class="blog-body__img"
                />
              </div>
              <div class="blog-body__content-content">
                <h2 class="blog-body__content-title">
                  BAY ĐẾN ÚC CÙNG LOẠT ƯU ĐÃI HẤP DẪN TỪ CÁC HÃNG HÀNG KHÔNG NỘI ĐỊA VIỆT
                  NAM
                </h2>

                <div class="blog-body__content-day">
                  <p class="blog-body__days">20/11/2023</p>
                  <p class="blog-body__author">Trần Hoàng Minh</p>
                </div>

                <p class="blog-body__content-text">
                  Cơ hội khám phá nước Úc vào mùa hoa rực rỡ, tươi mới đang đến gần hơn
                  bạn với hàng loạt ưu đãi hấp dẫn cho giá vé bay từ các hãng hàng không
                  nội địa Việt Nam. Cơ hội khám phá nước Úc vào mùa hoa rực rỡ, tươi mới
                  đang đến gần hơn bạn với hàng loạt ưu đãi hấp dẫn cho giá vé bay từ các
                  hãng hàng không nội địa Việt Nam.
                </p>
              </div>
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  setup() {
    return {};
  },
};
</script>

<style></style>
