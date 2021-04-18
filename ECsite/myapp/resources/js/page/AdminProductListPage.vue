<template>
  <div>
    <Loading class="loadingblock" v-show="loading" :key="loadingType.id" :item="loadingType" />
    <AdminProductDelete class="overlay" v-show="checkDelete" :key="deleteProduct.id" :item="deleteProduct" @close="onclose" @update="onUpdate" />
    <div class="admin_new_register">
        <h2 class="admin_title">商品一覧</h2>
        <!-- <RouterLink class="button admin_new_registerButton" :to="`/adminproductregister`">新規商品登録</RouterLink> -->
    </div>
    <div class="admin_block">
      <div class="admin_product_list_header">
        <h2 class="admin_header_font admin_header_buy">販売状況</h2>
        <h2 class="admin_header_font admin_header_id">商品ID</h2>
        <h2 class="admin_header_font admin_header_name">商品名</h2>
        <div>
          <button class="button admin_informationChenge_button admin_button_content" :class="{'select_display_butoon': display === 0}" @click="display = 0">商品内容</button><button class="button admin_informationChenge_button  admin_button_stock" :class="{'select_display_butoon': display === 1}" @click="display = 1">在庫数</button><button class="button admin_informationChenge_button  admin_button_day" :class="{'select_display_butoon': display === 2}" @click="display = 2">日付</button>
        </div>
      </div>
      <div>
        <AdminProduct v-for="product in products" :key="product.id" :item="product" :display="display" @deleteModal="onDelete" />
      </div>
    </div>
  </div>
</template>
<script>
import AdminProduct from '../components/AdminProduct.vue'
import AdminProductDelete from '../components/AdminProductDelete.vue'
import Loading from '../components/Loading.vue'
import { OK } from '../util'

export default{
  components: {
    AdminProduct,
    AdminProductDelete,
    Loading
  },
  data(){
    return {
      products: [],
      display: 0,
      deleteProduct: {
        id: 999,
        productId: null,
        status: null,
        name: null,
        img: null,
      },
      checkDelete: false,
      loading: true,
      loadingType: {
        id: 0,
        type: 0
      }
    }
  },
  methods: {
    async fetchProduct(status){
      if(status){
        this.loading = true;
        this.loadingType.type = 1;
      }
      const response = await axios.get(`/api/productList`);

      if(response.status !== OK){
        this.$store.commit('error/setCode', response.status);
        return false;
      }

      this.products = response.data;

      if(status){
        this.checkDelete = false;
      }
      this.loading = false;
    },
    onDelete({productId, productDisplayStatus, productName, productImg}){
      this.deleteProduct.productId = productId;
      this.deleteProduct.status = productDisplayStatus;
      this.deleteProduct.name = productName;
      this.deleteProduct.img = productImg;
      this.checkDelete = true;
    },
    async onUpdate(){
      await this.fetchProduct(true);
    },
    onclose({display}){
      this.checkDelete = display;
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