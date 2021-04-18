<template>
  <div>
    <Loading class="loadingblock" v-show="loading" :key="loadingType.id" :item="loadingType" />
    <div class="cart">
      <Pankuzu :key="pankuzu.id" :item="pankuzu" />
      <div v-if="products.length" class="cart--in">
        <div class="cart--left">
          <CartProduct class="grid__item" v-for="product in products" :key="product.id" :item="product" @onOnePlus="onOnePlusClick" @onOneMinus="onOneMinusClick" @onDelete="onDeleteClick" />
        </div>
        <div class="cart--right">
          <div class="total">
            <h4 class="totalmoney">商品合計<span class="cart--price">¥{{totalmoney}}</span></h4>
          </div>
          <div class="animationvar">
          </div>
          <div class="form__button">
              <RouterLink class="button button--link button--buy" to="/buy" v-show="isLogin">
                レジに進む
              </RouterLink>
              <button class="button button--link button--buy" @click="modalCheckDisplay" v-show="!isLogin">
                レジに進む
              </button>
          </div>
        </div>
      </div>
      <div class="cart--no" v-else>
        <div class="notProduct_content">
          <h2 class="notProduct_font">カートに商品がありません。</h2>
          <RouterLink type="submit" class="button topPage_button" to="/topPage">ショッピングを続ける</RouterLink>
        </div>
      </div>
    </div>
    <div class="overlay" v-show="modalCheck">
      <div class="buy_login_modal">
        <div class="buy_login_modal_iclose">
          <i class="fas fa-times close" @click.prevent="modalCheckDisplay"></i>
        </div>
        <div class="buy_login_froms">
          <div class="notLogin_form">
            <h3>ログインせず購入する</h3>
            <div class="notLogin_form_font">
              <p>※ログインせずに購入された場合、購入商品が購入履歴に残りません。</p>
            </div>
            <RouterLink class="button button--link button--buy notLogin_form_button" to="/buy">
                    レジに進む
            </RouterLink>
          </div>
          <div class="login_form">
            <h3>ログインして購入する</h3>
            <form class="form buy_login_form" @submit.prevent="login">
              <div class="form_block">
                <label for="login-id" class="form_label">ログインID</label>
                <div v-if="loginErrors" class="form_errors">
                  <ul v-if="loginErrors.login_id" class="errors">
                    <li v-for="msg in loginErrors.login_id" :key="msg">{{msg}}</li>
                  </ul>
                </div>
              </div>
              <input type="text" class="form__item" id="login-id" v-model="loginForm.login_id">
              <div class="form_block">
                <label for="login-password" class="form_label">パスワード</label>
                <div v-if="loginErrors">
                  <ul v-if="loginErrors.password" class="errors">
                    <li v-for="msg in loginErrors.password" :key="msg">{{msg}}</li>
                  </ul>
                </div>
              </div>
              <input type="text" class="form__item" id="login-password" v-model="loginForm.password">
              <div class="form__button">
                <button type="submit" class="button form_button">レジに進む</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Pankuzu from '../components/Pankuzu.vue'
import CartProduct from '../components/CartProduct.vue'
import Loading from '../components/Loading.vue'
import { OK } from '../util'

export default{
  components: {
    Pankuzu,
    CartProduct,
    Loading,
  },
  data(){
    return {
      pankuzu: {
        id: 0
      },
      buyLoginId: null,
      products: [],
      totalmoney: 0,
      loading: true,
      loadingType: {
        id: 0,
        type: 0
      },
      modalCheck: false,
      loginForm: {
        login_id: '',
        password: ''
      },
    }
  },
  methods: {
    async fetchProduct(){
      const response = await axios.get('/api/buyCartList');

      if(response.status !== OK){
        this.$store.commit('error/setCode', response.status);
        return false;
      }

      let totalmoney = 0;
      if(response.data.length){
        this.buyLoginId = response.data[0].login_id;
        response.data.forEach(item => {
          totalmoney += item.item_number * item.price

          if(item.color === 0){
            item.color = "BLACK";
          }else if(item.color  === 1){
              item.color = "WHITE";
          }else if(item.color  === 2){
              item.color = "GRAY";
          }else if(item.color  === 3){
              item.color = "RED";
          }else if(item.color  === 4){
              item.color = "BLUE";
          }else if(item.color  === 5){
              item.color = "GREEN";
          }else if(item.color  === 6){
              item.color = "YELLOW";
          }
        });
        this.products = response.data;
        this.totalmoney = totalmoney.toLocaleString();
      }

      this.loading = false;
    },
    async login(){
      this.loadingType.type = 1;
      this.loading = true;
      await this.$store.dispatch('auth/login', this.loginForm);
      const data = {
        sessionId: this.buyLoginId
      }
      const response = await axios.put(`/api/buyCartLoginChange`, data);
      if(response.status !== OK){
          this.$store.commit('error/setCode', response.status);
          return false;
      }
      this.loading = false;
      this.$router.push('/buy');
    },
    async onePlus(productId, loginId, itemNumber, itemSize){
      const data = {
        productId: productId,
        loginId: loginId,
        number: itemNumber,
        size: itemSize,
        change: 1
      }
      await axios.put(`/api/buyCartChange`, data);
    },
    async oneMinus(productId, loginId, itemNumber, itemSize){
      const data = {
        productId: productId,
        loginId: loginId,
        number: itemNumber,
        size: itemSize,
        change: 0
      }
      await axios.put(`/api/buyCartChange`, data);
    },
    async onedelete(productId, loginId, itemSize){
      const data = {
        productId: productId,
        loginId: loginId,
        size: itemSize,
      }
      //deleteはbodyを送らない不具合があったのでputで代用(axios)
      await axios.put(`/api/buyCartDelete`, data);
    },
    //購入個数を1増やす
    async onOnePlusClick({productId, loginId, itemNumber, itemSize}){
      this.loadingType.type = 1;
      this.loading = true;
      await this.onePlus(productId, loginId, itemNumber, itemSize);
      await this.fetchProduct();
    },
    //購入個数を1減らす
    async onOneMinusClick({productId, loginId, itemNumber, itemSize}){
      this.loadingType.type = 1;
      this.loading = true;
      await this.oneMinus(productId, loginId, itemNumber, itemSize);
      await this.fetchProduct();
    },
    //購入商品の削除
    async onDeleteClick({productId, loginId, itemSize}){
      this.loadingType.type = 1;
      this.loading = true;
      await this.onedelete(productId, loginId, itemSize);
      await this.fetchProduct();
    },
    //モーダル表示
    modalCheckDisplay(){
      if(this.modalCheck === false){
        this.modalCheck = true;
      }else if(this.modalCheck === true){
        this.modalCheck = false;
      }
      this.clearError();
    },
    clearError(){
      this.$store.commit('auth/setLoginErrorMessages', null);
    }
  },
  computed: {
    isLogin(){
      return this.$store.getters['auth/check'];
    },
    loginErrors(){
      return this.$store.state.auth.loginErrorMessages;
    },
  },
  created(){
    this.clearError();
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