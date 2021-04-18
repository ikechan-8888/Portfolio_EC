<template>
    <div class="bought-detail">
        <div class="bought-detail-date">
            <h3>購入日：{{item.buyDate}}</h3>
        </div>
        <div class="bought-detail-list">
            <BoughtProductDetail class="bought-detail-lists" v-for="product in products" :key="product.id" :item="product" />
        </div>
        <div class="bought-detail-content">
            <div class="bought-detail-address">
                <h3>
                    お届け先住所：
                </h3>
                <div class="bought-detail-delivery">
                    <h4>〒{{item.postal_code}}</h4>
                    <h4>{{item.delivery_address}}</h4>
                </div>
            </div>
        </div>
        <div class="bought-detail-price">
            <h3>
                購入金額：¥{{item.buy_price.toLocaleString()}}
            </h3>
        </div>
    </div>
</template>
<script>
import BoughtProductDetail from '../components/BoughtProductDetail.vue'
import { OK } from '../util'

export default {
    components: {
        BoughtProductDetail,
    },
    props: {
        item: {
            type: Object,
            required: true
        }
    },
    data(){
        return {
        //購入商品
        products: [],
        }
    },
    methods: {
        async fetchProduct(){
            const response = await axios.get(`/api/boughtProductList/${this.item.id}`);

            if(response.status !== OK){
                this.$store.commit('error/setCode', response.status);
                return false;
            }

            response.data.forEach(item => {
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
            this.$emit('loading', {
                loadingboolean: false
            })
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