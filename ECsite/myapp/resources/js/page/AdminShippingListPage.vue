<template>
  <div>
    <Loading class="loadingblock" v-show="loading" :key="loadingType.id" :item="loadingType" />
    <div class="admin_new_register">
        <h2 class="admin_title">発送一覧</h2>
        <!-- <RouterLink class="button admin_new_registerButton" :to="`/adminproductregister`">新規商品登録</RouterLink> -->
    </div>
    <div class="admin_block">
      <div class="admin_product_list_header">
        <h2 class="admin_header_font admin_header_buy">発送状況</h2>
        <h2 class="admin_header_font admin_header_day">購入日</h2>
        <h2 class="admin_header_font admin_header_sday">発送日</h2>
        <h2 class="admin_header_font admin_header_id">購入ID</h2>
        <h2 class="admin_header_font admin_header_loginid">ログインID</h2>
        <h2 class="admin_header_font admin_header_product">商品数</h2>
        <h2 class="admin_header_font admin_header_delivery">発送住所</h2>
      </div>
      <div>
        <AdminShipping v-for="product in products" :key="product.id" :item="product" />
      </div>
    </div>
  </div>
</template>
<script>
import AdminShipping from '../components/AdminShipping.vue'
import Loading from '../components/Loading.vue'
import { OK } from '../util'

export default{
  components: {
    AdminShipping,
    Loading
  },
  data(){
    return {
      products: [],
      loading: true,
      loadingType: {
        id: 0,
        type: 0
      }
    }
  },
  methods: {
    async fetchProduct(){
      const response = await axios.get(`/api/shippingList`);

      if(response.status !== OK){
        this.$store.commit('error/setCode', response.status);
        return false;
      }

      this.products = response.data;

      this.loading = false;
    }
  },
  watch: {
    $route: {
      async handler(){
        await this.fetchProduct();
      },
      immediate: true
    }
  }
}
</script>