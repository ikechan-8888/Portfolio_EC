<template>
  <div>
    <Loading class="loadingblock" v-show="loading" :key="loadingType.id" :item="loadingType" />
    <div class="admin_new_register">
        <h2 class="admin_title">詳細情報</h2>
    </div>
    <div class="admin_shipping_block">
      <div class="shipping_top_block">
        <div class="shipping_left_block">
          <div class="shipping_blocks">
            <h2 class="shipping_blocks_font">発送状態</h2>
            <div class="shipping_block">
              <h3 class="button admin_buy_button select_start_butoon" v-show="information.shipping_flag === 1">発送済</h3>
              <h3 class="button admin_buy_button select_stop_butoon" v-show="information.shipping_flag === 0">未発送</h3>
            </div>
          </div>
          <div class="shipping_blocks">
            <h2 class="shipping_blocks_font">購入及び発送日時</h2>
            <div class="shipping_block">
              <p>購入日：{{information.created_at}}</p>
              <p>発送日：{{information.updated_at}}</p>
            </div>
          </div>
          <div class="shipping_blocks">
            <h2 class="shipping_blocks_font">購入者情報</h2>
            <div class="shipping_block">
              <p>ログインID：{{information.login_id}}</p>
              <p>購入ID：{{information.id}}</p>
              <p>購入金額：¥{{Number(information.buy_price).toLocaleString()}}</p>
            </div>
          </div>
          <div class="shipping_blocks">
            <h2 class="shipping_blocks_font">発送先住所情報</h2>
            <div class="shipping_block">
              <p>郵便番号：〒{{information.postal_code}}</p>
              <p>住所：{{information.delivery_address}}</p>
              <p>氏名：{{information.buy_name}}</p>
              <p>電話番号：{{information.tel}}</p>
            </div>
          </div>
        </div>
        <div class="shipping_right_block">
          <div class="shipping_not_block" v-show="information.shipping_flag === 0">
            <div class="shipping_not_sentence">
              <h4 class="shipping_not_font">発送完了ボタンをクリックして<br>発送を完了してください</h4>
            </div>
            <div class="animationvar_s">
            </div>
            <div class="form__button shipping__button">
                <button class="button button--link button--buy" @click="shippingDone">
                  発送完了
                </button>
            </div>
          </div>
          <div class="shipping_yes_block" v-show="information.shipping_flag === 1">
             <div class="shipping_not_sentence">
              <h4 class="shipping_yes_font">THE FREELANCEから発送済</h4>
            </div>
            <div class="animationvar_s">
            </div>
            <div class="form__button shipping__button">
                <button class="button button--link button--shipping">
                  <i class="fas fa-house-user"></i>
                </button>
            </div>
          </div>
        </div>
      </div>
      <div class="shipping_center_block">
        <h2>発送対象商品　合計発送数量：{{totalItemNumber}}個</h2>
        <AdminProductShipping v-for="product in products" :key="product.product_id" :item="product" />
      </div>
    </div>
  </div>
</template>

<script>
import Loading from '../components/Loading.vue'
import AdminProductShipping from '../components/AdminProductShipping.vue'
import { OK } from '../util'

export default{
  components: {
    Loading,
    AdminProductShipping,
  },
  props: {
    id: {
        type: String,
        required: true
    }
  },
  data(){
    return{
      products: [],
      information: [],
      totalItemNumber: 0,
      loading: true,
      loadingType: {
        id: 0,
        type: 0
      }
    }
  },
  methods: {
    async fetchProduct(){
      const moment = require("moment");
      const response = await axios.get(`/api/editShipping/${this.id}`);

      const data = response.data;
      this.products = data;
      this.information = data[0];

      let totalNumber = 0;
      data.forEach(item => {
        totalNumber += item.item_number;
      });
      this.totalItemNumber = totalNumber;

      this.information.created_at = moment.utc(this.information.created_at).local().format("YYYY年MM月DD日HH時mm分ss秒");
      if(this.information.shipping_flag === 1){
        this.information.updated_at = moment.utc(this.information.updated_at).local().format("YYYY年MM月DD日HH時mm分ss秒");
      }else if(this.information.shipping_flag === 0){
        this.information.updated_at = '-'
      }

      this.loading = false;
    },
    async shippingDone(){
      this.loadingType.type = 1;
      this.loading = true;

      const data = {
        id: this.information.id
      };
      this.$store.commit('error/setTestData1', data);
      const response = await axios.put('/api/shippingDoneEdit', data);
      if(response.status !== OK){
          this.$store.commit('error/setCode', response.status);
          return false;
      }

      this.loading = false;
      this.$router.push('/adminShippingList');
    },
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