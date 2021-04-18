<template>
  <div>
    <Loading class="loadingblock" v-show="loading" :key="loadingType.id" :item="loadingType" />
    <div class="buy--content">
      <Pankuzu :key="pankuzu.id" :item="pankuzu" />
      <div class="buy">
        <div class="buy--left">
          <div class="buy--delivery buy--contents">
            <h2 class="buy--detaildelivery buy--titlename">
                お届け先
            </h2>
            <div class="buy--deliverycontent">
              <h4 class="buy--name fontchange">
                  名前：{{actuallydelivery.name}}
              </h4>
              <div class="buy--addressblock">
                <h4 class="buy--address fontchange">
                    住所：
                </h4>
                <div class="buy--addressdata">
                  <span>〒{{actuallydelivery.code}}</span>
                  <span>{{actuallydelivery.address}}</span>
                </div>
              </div>
              <h4 class="buy--tel fontchange">
                  電話：{{actuallydelivery.tel}}
              </h4>
            </div>
            <div class="buy--deliverychange">
              <button v-if="isLogin && actuallydelivery.id" class="buy--deliverychange--button"  @click.prevent="onmodal">変更</button>
              <button v-else class="buy--deliverychange--button"  @click.prevent="onmodal">入力</button>
            </div>
          </div>
          <div class="buy--pay buy--contents">
            <h2 class="buy--detailpay buy--titlename">
                支払い方法
            </h2>
            <h4 class="buy--fee fontchange">
                代引き
            </h4>
            <h4 class="buy--detailfee fontchange">
                ¥{{fee}}
            </h4>
          </div>
          <div class="buy--product">
            <h2 class="buy--products buy--titlename">
              お届け商品
            </h2>
            <BuyProduct v-for="product in products" :key="product.id" :item="product" />
          </div>
        </div>
        <div class="buy--right">
          <div class="buy--total">
            <div class="buy--totalcontent">
              <h4 class="buy--totalname">商品合計</h4><p class="buy--totalprices">¥{{productTotalmoney}}</p>
            </div>
            <div class="buy--totalcontent">
              <h4 class="buy--totalname">送料</h4><p class="buy--totalprices">¥{{shipping}}</p>
            </div>
            <div class="buy--totalcontent">
              <h4 class="buy--totalname">代引き手数料</h4><p class="buy--totalprices">¥{{fee}}</p>
            </div>
            <div class="buy--totalcontent">
              <h4 class="buy--totalname">支払い合計</h4><p class="buy--totalprices buy--totalprice">¥{{allTotalmoney.toLocaleString()}}</p>
            </div>
          </div>
          <div class="animationvar">
          </div>
          <div class="form__button">
              <button class="button button--link button--bought" :disabled="actuallydelivery.id === ''" :class="{ 'disabled_buy': actuallydelivery.id === '' }" @click.prevent="buy">
                注文を確定する
              </button>
          </div>
        </div>
      </div>
      <div class="overlay" :class="{ 'modaldisplay': modal }">
        <div class="modal">
          <div>
            <div v-if="isLogin && deliverys.length" class="modal--title--login">
              <div class="modal--title--block">
                <h4>お届け先の変更</h4>
                <i class="fas fa-times close" @click.prevent="onmodal(); clearError()"></i>
              </div>
              <Delivery v-for="delivery in deliverys" :key="delivery.id" :item="delivery" @selectDelivery="onSelectDelivery" />
              <div class="form__button">
                <button type="submit" class="button button--modal" @click.prevent="onChengeDelivery(); onmodal()" :disabled="!changeDeliveryAddress" :class="{ 'disabled_modal': !changeDeliveryAddress }">お届け先を変更する</button>
              </div>
              <h4 class="modal--title--add">新しいお届け先を追加する</h4>
            </div>
            <div v-else class="modal--title--guest">
              <h4>お届け先を入力</h4>
              <i class="fas fa-times close" @click.prevent="onmodal"></i>
            </div>
          </div>
          <div class="panel_delivery">
            <form class="form" @submit.prevent="deliveryRegister">
              <!-- delivery_name(名前)　postal_code(郵便番号)　delivery_address(住所)　tel(電話番号) -->
              <div class="form_block">
                <div>
                  <label for="username">名前</label><span class="form_require">必須</span>
                </div>
                <div v-if="deliveryErrors">
                  <ul v-if="deliveryErrors.name" class="errors">
                    <li v-for="msg in deliveryErrors.name" :key="msg">{{msg}}</li>
                  </ul>
                </div>
              </div>
              <input type="text" class="form__item" id="username" placeholder="例) 池上広軌" v-model="deliveryRegisterForm.name">
              <div class="form_block">
                <div>
                  <label for="code">郵便番号</label><span class="form_require">必須</span>
                </div>
                <div v-if="deliveryErrors">
                  <ul v-if="deliveryErrors.code" class="errors">
                    <li v-for="msg in deliveryErrors.code" :key="msg">{{msg}}</li>
                  </ul>
                </div>
              </div>
              <input type="text" class="form__item" id="code" placeholder="例) 100-0000" v-model="deliveryRegisterForm.code">
              <div class="form_block">
                <div>
                  <label for="address">住所</label><span class="form_require">必須</span>
                </div>
                <div v-if="deliveryErrors">
                  <ul v-if="deliveryErrors.address" class="errors">
                    <li v-for="msg in deliveryErrors.address" :key="msg">{{msg}}</li>
                  </ul>
                </div>
              </div>
              <input type="text" class="form__item" id="address" placeholder="例) 東京都西東京市保谷町3町名9番地24 ルチルクォーツB" v-model="deliveryRegisterForm.address">
              <div class="form_block">
                <div>
                  <label for="tel">電話番号</label><span class="form_require">必須</span>
                </div>
                <div v-if="deliveryErrors">
                  <ul v-if="deliveryErrors.tel" class="errors">
                    <li v-for="msg in deliveryErrors.tel" :key="msg">{{msg}}</li>
                  </ul>
                </div>
              </div>
              <input type="tel" class="form__item" id="tel" placeholder="例) 08012345678" v-model="deliveryRegisterForm.tel">
              <div class="form__button">
                <button v-if="isLogin" type="submit" class="button form_button form_button_modal">お届け先を追加する</button>
                <button v-else type="submit" class="button form_button form_button_modal">お届け先を決定する</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="overlay" v-show="notBuyModal">
      <div class="not_buy_modal">
        <div class="not_buy_modal_iclose">
          <p v-if="notBuyProducts.length" class="not_buy_font">
            購入数が販売数を超えてしまっているか
            現在販売していない商品があり購入できません。
            購入できる個数に変更し販売していない商品を削除します。
          </p>
          <p v-else class="not_buy_font">
            現在販売していない商品があり購入できません。
            販売していない商品を削除します。
          </p>
        </div>
        <RouterLink class="button button--link button--buy notBuy_button" to="/cart">
          ショッピングカートに戻る
        </RouterLink>
      </div>
    </div>
  </div>
</template>
<script>
import Pankuzu from '../components/Pankuzu.vue'
import BuyProduct from '../components/BuyProduct.vue'
import Delivery from '../components/Delivery.vue'
import Loading from '../components/Loading.vue'
import { OK } from '../util'

export default{
  components: {
    Pankuzu,
    BuyProduct,
    Delivery,
    Loading,
  },
  data(){
    return {
      modal: true,
      pankuzu: {
        id: 1
      },
      //購入商品
      products: [],
      //購入商品ID
      productIds: [],
      //購入できない商品(商品数)
      notBuyProducts: [],
      //購入できない商品(販売有無)
      notProducts: [],
      //削除された商品チェック
      deleteProducts: false,
      //登録住所
      deliverys: [],
      //商品合計
      productTotalmoney: 0,
      //送料
      shipping: 210,
      //代引き手数料
      fee: 330,
      //支払い金額
      allTotalmoney: 0,
      //住所登録
      deliveryRegisterForm: {
        name: '',
        code: '',
        address: '',
        tel: ''
      },
      //選択住所
      deliveryAddress: {
        id: '',
        name: '',
        code: '',
        address: '',
        tel: ''
      },
      //届け先住所
        actuallydelivery: {
        id: '',
        name: '',
        code: '',
        address: '',
        tel: ''
      },
      //住所を選択しているか
      changeDeliveryAddress: false,
      //購入できないモーダル
      notBuyModal: false,
      //購入ボタン
      authenticity:true,
      loading: true,
      loadingType: {
        id: 0,
        type: 0
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

      if(!response.data.length){
        this.$router.push('/topPage');
      }

      let totalmoney = 0;
      response.data.forEach(item => {
        totalmoney += item.item_number * item.price;

        this.productIds.push({id: item.product_id, size: item.item_size});

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
      const allTotalmoney = totalmoney + this.shipping + this.fee;

      this.products = response.data;
      this.productTotalmoney = totalmoney.toLocaleString();
      this.allTotalmoney = allTotalmoney;
      if(this.isLogin){
        this.actuallyDelivery();
      }else{
        this.loading = false;
      }
    },
    async fetchDelivery(){
      const response = await axios.get('/api/deliveryList');

      if(response.status !== OK){
        this.$store.commit('error/setCode', response.status);
        return false;
      }

      this.deliverys = response.data;
    },
    async actuallyDelivery(){
      const response = await axios.get('/api/actuallyDelivery');

      if(response.status !== OK){
        this.$store.commit('error/setCode', response.status);
        return false;
      }

      if(response.data.length){
        this.actuallydelivery.id = response.data[0].id;
        this.actuallydelivery.name = response.data[0].delivery_name;
        this.actuallydelivery.code = response.data[0].postal_code;
        this.actuallydelivery.address = response.data[0].delivery_address;
        this.actuallydelivery.tel = response.data[0].tel;
        this.authenticity = false;
      }
      this.loading = false;
    },
    async deliveryRegister(){
      this.loadingType.type = 1;
      this.loading = true;

      await this.$store.dispatch('auth/delivery', this.deliveryRegisterForm);
      //statuscode=200だったら
      if(this.apiStatus){
        this.clearError();
        this.deliveryRegisterForm.name = '';
        this.deliveryRegisterForm.code = '';
        this.deliveryRegisterForm.address = '';
        this.deliveryRegisterForm.tel = '';
      }
      if(this.isLogin){
        await this.fetchDelivery();
        this.loading = false;
      }else{
        await this.actuallyDelivery();
        this.loading = false;
        this.onmodal();
      }
    },
    async buy(){
      this.loadingType.type = 1;
      this.loading = true;

      //商品、在工数チェック
      const buyCheckResponse = await axios.post('/api/buyCheck', this.productIds);
      if(buyCheckResponse.status !== OK){
        this.$store.commit('error/setCode', buyCheckResponse.status);
        return false;
      }
      const buyCheckDatas = buyCheckResponse.data;
      this.products.forEach(data => {
        let getSizeStock = null;
        //販売しているか
        let exists = false;
        buyCheckDatas.forEach(getData => {
          if(data.product_id === getData.id){
            if(data.item_size === 'S'){
              getSizeStock = getData.ssize_items;
            }else if(data.item_size === 'M'){
              getSizeStock = getData.msize_items;
            }else if(data.item_size === 'L'){
              getSizeStock = getData.lsize_items;
            }else if(data.item_size === 'XL'){
              getSizeStock = getData.xlsize_items;
            }
            //販売している
            exists = true;
            //購入数が在庫数を超えていないか
            if(data.item_number > getSizeStock){
              this.notBuyProducts.push({id: data.id, stock: getSizeStock});
            }
          }
        })

        if(exists === false){
          this.notProducts.push({id: data.id});
          this.deleteProducts = true;
        }
      });
      if(this.deleteProducts || this.notBuyProducts.length){
        if(this.deleteProducts){
          await this.deleteCart();
        }
        if(this.notBuyProducts.length){
          await this.putCart();
        }
        this.loading = false;
        this.notBuyModal = true;
        return;
      }

      //在工数変更
      let idData = [];
      let sizeData = [];
      let numberData = [];
      this.products.forEach((data)=>{
        idData.push(data.product_id);
        sizeData.push(data.item_size);
        numberData.push(data.item_number);
      });
      const buyStockData = {
        id: idData,
        size: sizeData,
        number: numberData
      }
      const buyStockDataResponse = await axios.post('/api/buyStock', buyStockData);
      if(buyStockDataResponse.status !== OK){
        this.$store.commit('error/setCode', buyStockDataResponse.status);
        return false;
      }

      //購入
      const buyData = {
        delivery: this.actuallydelivery,
        money: this.allTotalmoney
      }
      const buyResponse = await axios.post('/api/buy', buyData);
      if(buyResponse.status !== OK){
        this.$store.commit('error/setCode', buyResponse.status);
        return false;
      }

      //購入商品
      const buyproductData = {
        products: this.products,
        buyId: buyResponse.data.id
      }
      const buyproductResponse = await axios.post('/api/buyproduct', buyproductData);
      if(buyproductResponse.status !== OK){
        this.$store.commit('error/setCode', buyproductResponse.status);
        return false;
      }

      //cartを購入済みにする
      let boughtCartChangeData = [];
      this.products.forEach((data)=>{
        boughtCartChangeData.push(data.id);
      });
      const boughtCartChangeDataResponse = await axios.put('/api/boughtCartChange', boughtCartChangeData);
      if(boughtCartChangeDataResponse.status !== OK){
        this.$store.commit('error/setCode', boughtCartChangeDataResponse.status);
        return false;
      }

      //購入住所
      if(this.isLogin){
        const deliveryChangeData = {
          id: this.actuallydelivery.id
        }
        const deliveryChangeResponse = await axios.put('/api/deliveryChange', deliveryChangeData);
        if(deliveryChangeResponse.status !== OK){
          this.$store.commit('error/setCode', deliveryChangeResponse.status);
          return false;
        }
      }

      this.$store.commit('auth/setBuyCheck', true);

      this.loading = false;

      this.$router.push('/get');

      this.$store.commit('auth/setBuyCheck', false);
    },
    async putCart(){
      const putCartData = {
        products: this.notBuyProducts,
      }
      const putCartResponse = await axios.put('/api/putCart', putCartData);
      if(putCartResponse.status !== OK){
        this.$store.commit('error/setCode', putCartResponse.status);
        return false;
      }
    },
    async deleteCart(){
      const deleteCartData = {
        products: this.notProducts,
      }
      const deleteCartResponse = await axios.put('/api/deleteCart', deleteCartData);
      if(deleteCartResponse.status !== OK){
        this.$store.commit('error/setCode', deleteCartResponse.status);
        return false;
      }
    },
    clearError(){
      this.$store.commit('auth/setDeliveryErrorMessages', null);
    },
    onSelectDelivery({id, name, code, address, tel}){
      this.deliveryAddress.id = id;
      this.deliveryAddress.name = name;
      this.deliveryAddress.code = code;
      this.deliveryAddress.address = address;
      this.deliveryAddress.tel = tel;
      this.changeDeliveryAddress = true;
    },
    onChengeDelivery(){
      this.actuallydelivery.id = this.deliveryAddress.id;
      this.actuallydelivery.name = this.deliveryAddress.name;
      this.actuallydelivery.code = this.deliveryAddress.code;
      this.actuallydelivery.address = this.deliveryAddress.address;
      this.actuallydelivery.tel = this.deliveryAddress.tel;
      this.authenticity = false;
    },
    onmodal(){
      if(this.modal === false){
        this.modal = true;
      }else if(this.modal === true){
        this.modal = false;
      }
    }
  },
  computed: {
    apiStatus(){
      return this.$store.state.auth.apiStatus;
    },
    deliveryErrors(){
      return this.$store.state.auth.deliveryErrorMessages;
    },
    isLogin(){
      return this.$store.getters['auth/check'];
    },
  },
  created(){
    this.clearError();
  },
  watch: {
    $route: {
      handler(){
        this.fetchProduct();
        this.fetchDelivery();
      },
      immediate: true
    }
  }
}
</script>