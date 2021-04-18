<template>
  <div>
    <Loading class="loadingblock" v-show="loading" :key="loadingType.id" :item="loadingType" />
    <div class="pankuzu_list">
      <RouterLink class="pankuzu_link pankuzu_font" to="/topPage">
        トップページ
      </RouterLink>
      <p class="pankuzu_symbol">＞</p>
      <h5 class="pankuzu_font">お気に入りアイテム</h5>
    </div>
    <div class="block_title_block">
      <i class="fas fa-heart fas_icon"></i>
      <h3 class="block_title">{{ username }}様のお気に入り</h3>
    </div>
    <div class="photo-list">
      <div class="grid" v-if="products.length">
        <FavoriteProduct class="grid__item" v-for="product in products" :key="product.id" :item="product" @favorite="onFavoriteClick" />
      </div>
      <div v-else>
        <div class="notProduct_content">
          <h2 class="notProduct_font">お気に入りがありません。</h2>
          <RouterLink type="submit" class="button topPage_button" to="/topPage">ショッピングを続ける</RouterLink>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import FavoriteProduct from '../components/FavoriteProduct.vue'
import Pagination from '../components/Pagination.vue'
import Loading from '../components/Loading.vue'
import { OK } from '../util'

export default{
  components: {
    FavoriteProduct,
    Loading,
  },
  data(){
    return {
      products: [],
      loading: true,
      loadingType: {
        id: 0,
        type: 0
      },
    }
  },
  methods: {
    async fetchProduct(){
      const response = await axios.get('/api/favorite');

      if(response.status !== OK){
        this.$store.commit('error/setCode', response.status);
        return false;
      }

      this.products = response.data;
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

      const responseList = await axios.get('/api/favorite');

      if(responseList.status !== OK){
        this.$store.commit('error/setCode', responseList.status);
        return false;
      }

      this.products = responseList.data;
      this.loading = false;
    },
    onFavoriteClick({id}){
      this.productLike(id);
    }
  },
  computed: {
    username(){
        return this.$store.getters['auth/username']
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