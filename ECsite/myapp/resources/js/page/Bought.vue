<template>
  <div>
    <Loading class="loadingblock" v-show="loading" :key="loadingType.id" :item="loadingType" />
    <div class="pankuzu_list">
      <RouterLink class="pankuzu_link pankuzu_font" to="/topPage">
        トップページ
      </RouterLink>
      <p class="pankuzu_symbol">＞</p>
      <h5 class="pankuzu_font">購入履歴</h5>
    </div>
    <div class="block_title_block">
      <i class="fas fa-chalkboard-teacher fas_icon"></i>
      <h3 class="block_title">{{ username }}様の購入履歴</h3>
    </div>
    <div class="bought-block" v-if="products.length">
      <div class="bought-list">
        <h3 class="bought-count">件数：{{count}}件</h3>
        <BoughtProduct class="bought-lists" v-for="product in products" :key="product.id" :item="product" @loading="onLoading"/>
      </div>
    </div>
    <div v-else>
      <h2>購入した商品がありません。</h2>
    </div>
  </div>
</template>
<script>
import BoughtProduct from '../components/BoughtProduct.vue'
import Loading from '../components/Loading.vue'
import { OK } from '../util'

export default{
  components: {
    BoughtProduct,
    Loading,
  },
  data(){
    return {
      //購入商品
      products: [],
      count: 0,
      loading: true,
      loadingType: {
        id: 0,
        type: 0
      },
    }
  },
  methods: {
    async fetchProduct(){
      const moment = require("moment");
      const response = await axios.get('/api/boughtList');

      if(response.status !== OK){
        this.$store.commit('error/setCode', response.status);
        return false;
      }

      if(response.data.length){
        response.data.forEach(item => {
          item["buyDate"] = moment.utc(item.created_at).local().format("YYYY年MM月DD日");
        });

        this.products = response.data;
        this.count = response.data.length;
      }else{
        this.loading = false;
      }
    },
    onLoading({loadingboolean}){
      this.loading = loadingboolean;
    }
  },
  computed: {
    username(){
        return this.$store.getters['auth/username']
    }
  },
  watch: {
    $route: {
      handler(){
        this.fetchProduct()
      },
      immediate: true
    }
  }
}
</script>