<template>
  <div class="container cart">
    <a-card style="width: 100%">
      <Result status="success">
        <template #title>
          <p>Successfully purchased the tour</p>
        </template>
        <template #subTitle>
          <p>
            {{ checkout.description }} <span v-if="checkout.id == 3"> {{ code }}</span>
          </p>
          <p v-if="checkout.Information">
            {{ checkout.Information }}
          </p>
        </template>
        <template #extra>
          <a-button key="console" type="primary" @click="backHome">Back Home</a-button>
          <a-button key="buy" @click="cart">go to cart</a-button>
        </template>
      </Result>
    </a-card>
  </div>
</template>

<script>
import { defineComponent, ref, reactive, toRefs, inject } from "vue";
import { message, Result } from "ant-design-vue";
import { useRouter, useRoute } from "vue-router";

export default defineComponent({
  setup() {
    const router = useRouter();
    const route = useRoute();
    const $loading = inject("$loading");
    // console.log(router);
    // console.log(route);
    const checkout = ref({});
    const code = ref();
    const data = [
      {
        id: "1",
        description: "Tickets have been sent to your email, please check your email",
      },
      {
        id: "2",
        description: "Please go to XXX address to pay",
        Information: "Payment deadline is 7 days from the date of booking",
      },
      {
        id: "3",
        description: "Please transfer money to XXX account with the syntax:",
        Information: "Payment deadline is 7 days from the date of booking",
      },
    ];
    const addTocheckout = (paymentId) => {
      for (let i = 0; i < data.length; i++) {
        if (data[i].id == paymentId) {
          checkout.value = data[i];
          break;
        }
      }
    };

    const getcheckout = () => {
      const loader = $loading.show({});
      axios
        .get(`http://127.0.0.1:8000/api/bookingtour/checkout/${route.params.code}`)
        .then(function (response) {
          console.log(response);
          addTocheckout(response.data.data.payment.id);
          code.value = response.data.data.code;
          loader.hide();
        })
        .catch(function (error) {
          //   console.error(error);
          if (error.response.status == 404) {
            message.error(error.response.data.error, 10);
            router.replace({
              name: "tour-home",
            });
          }
          loader.hide();
        });
    };
    getcheckout();

    const backHome = () => {
      router.replace({
        name: "tour-home",
      });
    };
    const cart = () => {
      router.replace({
        name: "cart",
      });
    };
    return {
      checkout,
      code,
      backHome,
      cart,
    };
  },
  components: {
    Result,
  },
});
</script>

<style></style>
