<template>
  <div>
    <Loading class="loadingblock" v-show="loading" :key="loadingType.id" :item="loadingType" />
    <div class="block_title_block">
      <h3 class="block_title">アイテム一覧</h3>
    </div>
    <div class="grid">
      <Product class="grid__item" v-for="product in products" :key="product.id" :item="product" @favorite="onFavoriteClick" />
    </div>
    <Pagination :current-page="currentPage" :last-page="lastPage" />
  </div>
</template>
<script>
import Product from '../components/Product.vue'
import Pagination from '../components/Pagination.vue'
import Loading from '../components/Loading.vue'
import { OK } from '../util'

export default{
  components: {
    Product,
    Pagination,
    Loading
  },
  data(){
    return {
      products: [],
      currentPage: 0,
      lastPage: 0,
      loading: false,
      loadingType: {
        id: 0,
        type: 0
      },
    }
  },
  methods: {
    async fetchProduct(){
      const url = new URL(window.location.href);
      const params = url.searchParams;
      if(params.get('page')){
        this.loadingType.type = 1;
      }else{
        this.loadingType.type = 0;
      }
      this.loading = true;
      const response = await axios.get(`/api/product/?page=${this.page}`);

      if(response.status !== OK){
        this.$store.commit('error/setCode', response.status);
        return false;
      }

      this.products = response.data.data;
      this.currentPage = response.data.current_page;
      this.lastPage = response.data.last_page;
      this.loading = false;
    },
    async productLike(id){
      this.loadingType.type = 1;
      this.loading = true;
      const response = await axios.put(`/api/product/${id}/like`);

      if(response.status !== OK){
        this.$store.commit('error/setCode', response.status);
        return false;
      }

      //お気に入りを反映した商品一覧にする
      this.products = this.products.map(product => {
        if(product.id == response.data.id){
          //対象の商品のlogin_idに値を入れることでお気に入り判定される
          product.login_id = response.data.loginId;
        }
        return product;
      });

      this.loading = false;
    },
    onFavoriteClick({id}){
      if(! this.isLogin){
        alert('お気に入りをするにはログインしてください');
        return false;
      }else{
        this.productLike(id);
      }
    }
  },
  computed: {
      isLogin(){
          return this.$store.getters['auth/check'];
      },
      apiStatus(){
          return this.$store.state.auth.apiStatus;
      },
  },
  watch: {
    $route: {
      async handler(){
        await this.fetchProduct();
      },
      immediate: true
    }
  },
  props: {
    page: {
      type: Number,
      required: false,
      default: 1
    }
  }
}
</script>