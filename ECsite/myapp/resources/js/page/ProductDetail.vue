<template>
    <div>
        <Loading class="loadingblock" v-show="loading" :key="loadingType.id" :item="loadingType" />
        <div class="pankuzu_list">
            <RouterLink class="pankuzu_link pankuzu_font" to="/topPage">
                トップページ
            </RouterLink>
            <p class="pankuzu_symbol">＞</p>
            <h5 class="pankuzu_font">商品詳細</h5>
        </div>
        <div v-if="item" class="photo-detail">
            <figure class="photo-detail__pane photo-detail__image">
                <img :src="`http://localhost/storage/${item.img_name}`" :alt="`Photo by ${item.name}`">
            </figure>
            <div class="photo-detail__pane">
                <div class="photo-detail__block">
                    <h2 class="photo-detail__title">
                        {{item.name}}
                    </h2>
                    <button class="photo-detail__like" :class="{ 'photo-detail__liked': item.login_id }" title="お気に入り" @click="onFavoriteClick" >
                        <i class="icon ion-md-heart"></i>
                    </button>
                </div>
                <h2 class="photo-detail__color">
                    {{item.color}}
                </h2>
                <h2 class="photo-detail__price">
                    ¥{{item.price}}
                </h2>
                <div class="photo-detail__sizeBlock">
                    <span class="photo-detail__size">S / {{item.sstock}}</span>
                    <button v-if="Sstock" class="button button--cart" @click.prevent="cartIn('S')">
                        <i class="fas fa-shopping-cart"></i>カートへ入れる
                    </button>
                    <span v-else class="photo-detail__notsize">完売しました</span>
                </div>
                <div class="photo-detail__sizeBlock">
                    <span class="photo-detail__size">M / {{item.mstock}}</span>
                    <button v-if="Mstock" class="button button--cart" @click.prevent="cartIn('M')">
                        <i class="fas fa-shopping-cart"></i>カートへ入れる
                    </button>
                    <span v-else class="photo-detail__notsize">完売しました</span>
                </div>
                <div class="photo-detail__sizeBlock">
                    <span class="photo-detail__size">L / {{item.lstock}}</span>
                    <button v-if="Lstock" class="button button--cart" @click.prevent="cartIn('L')">
                        <i class="fas fa-shopping-cart"></i>カートへ入れる
                    </button>
                    <span v-else class="photo-detail__notsize">完売しました</span>
                </div>
                <div class="photo-detail__sizeBlock lastBlock">
                    <span class="photo-detail__size">XL / {{item.xlstock}}</span>
                    <button v-if="XLstock" class="button button--cart" @click.prevent="cartIn('XL')">
                        <i class="fas fa-shopping-cart"></i>カートへ入れる
                    </button>
                    <span v-else class="photo-detail__notsize">完売しました</span>
                </div>
                <ul class="tab">
                    <li class="tab__item" :class="{'tab__item--active': tab === 1 }" @click="tab = 1">
                        アイテム説明
                    </li>
                    <li class="tab__item" :class="{'tab__item--active': tab === 2 }" @click="tab = 2">
                        サイズ詳細
                    </li>
                </ul>
                <div class="panel" v-show="tab === 1">
                    <h2 class="photo-detail__content">{{item.content}}</h2>
                </div>
                <div class="panel" v-show="tab === 2">
                    <table v-if="category === 0" class="size_table">
                        <thead>
                            <tr>
                                <th>サイズ</th>
                                <th>身幅</th>
                                <th>肩幅</th>
                                <th>着丈</th>
                                <th>そで丈</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>S</td>
                                <td>{{item.ssize_body}}</td>
                                <td>{{item.ssize_shoulder}}</td>
                                <td>{{item.ssize_length}}</td>
                                <td>{{item.ssize_sleeve}}</td>
                            </tr>
                            <tr>
                                <td>M</td>
                                <td>{{item.msize_body}}</td>
                                <td>{{item.msize_shoulder}}</td>
                                <td>{{item.msize_length}}</td>
                                <td>{{item.msize_sleeve}}</td>
                            </tr>
                            <tr>
                                <td>L</td>
                                <td>{{item.lsize_body}}</td>
                                <td>{{item.lsize_shoulder}}</td>
                                <td>{{item.lsize_length}}</td>
                                <td>{{item.lsize_sleeve}}</td>
                            </tr>
                            <tr>
                                <td>X</td>
                                <td>{{item.xlsize_body}}</td>
                                <td>{{item.xlsize_shoulder}}</td>
                                <td>{{item.xlsize_length}}</td>
                                <td>{{item.xlsize_sleeve}}</td>
                            </tr>
                        </tbody>
                    </table>
                    <table v-else-if="category === 1" class="size_table">
                        <thead>
                            <tr>
                                <th>サイズ</th>
                                <th>ウエスト</th>
                                <th>お尻</th>
                                <th>股上</th>
                                <th>股下</th>
                                <th>太股</th>
                                <th>すそ周り</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>S</td>
                                <td>{{item.ssize_waist}}</td>
                                <td>{{item.ssize_hips}}</td>
                                <td>{{item.ssize_rise}}</td>
                                <td>{{item.ssize_inseam}}</td>
                                <td>{{item.ssize_thigh}}</td>
                                <td>{{item.ssize_hem}}</td>
                            </tr>
                            <tr>
                                <td>M</td>
                                <td>{{item.msize_waist}}</td>
                                <td>{{item.msize_hips}}</td>
                                <td>{{item.msize_rise}}</td>
                                <td>{{item.msize_inseam}}</td>
                                <td>{{item.msize_thigh}}</td>
                                <td>{{item.msize_hem}}</td>
                            </tr>
                            <tr>
                                <td>L</td>
                                <td>{{item.lsize_waist}}</td>
                                <td>{{item.lsize_hips}}</td>
                                <td>{{item.lsize_rise}}</td>
                                <td>{{item.lsize_inseam}}</td>
                                <td>{{item.lsize_thigh}}</td>
                                <td>{{item.lsize_hem}}</td>
                            </tr>
                            <tr>
                                <td>X</td>
                                <td>{{item.xlsize_waist}}</td>
                                <td>{{item.xlsize_hips}}</td>
                                <td>{{item.xlsize_rise}}</td>
                                <td>{{item.xlsize_inseam}}</td>
                                <td>{{item.xlsize_thigh}}</td>
                                <td>{{item.xlsize_hem}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { OK } from '../util';
import Loading from '../components/Loading.vue'

export default {
    components: {
        Loading
    },
    props: {
        id: {
            type: String,
            required: true
        }
    },
    data(){
        return {
            item: null,
            tab: 1,
            Sstock: true,
            Mstock: true,
            Lstock: true,
            XLstock: true,
            category: 0,
            loading: true,
            loadingType: {
                id: 0,
                type: 0
            },
        }
    },
    methods: {
        async fetchProduct(){
            const response = await axios.get(`/api/productDetail/${this.id}`);
            if(response.status !== OK){
                this.$store.commit('error/setCode', response.status);
                return false;
            }

            this.item = response.data[0];

            if(this.item.category_status === 1){
                this.category = 1;
            }

            if(this.item.ssize_items === 0){
                this.item.sstock = "在庫なし";
                this.Sstock = false;
            }else if(this.item.ssize_items <= 10){
                this.item.sstock = "在庫残りわずか";
            }else{
                this.item.sstock = "在庫あり";
            }

            if(this.item.msize_items === 0){
                this.item.mstock = "在庫なし";
                this.Mstock = false;
            }else if(this.item.msize_items <= 10){
                this.item.mstock = "在庫残りわずか";
            }else{
                this.item.mstock = "在庫あり";
            }

            if(this.item.lsize_items === 0){
                this.item.lstock = "在庫なし";
                this.Lstock = false;
            }else if(this.item.lsize_items <= 10){
                this.item.lstock = "在庫残りわずか";
            }else{
                this.item.lstock = "在庫あり";
            }

            if(this.item.xlsize_items === 0){
                this.item.xlstock = "在庫なし";
                this.XLstock = false;
            }else if(this.item.xlsize_items <= 10){
                this.item.xlstock = "在庫残りわずか";
            }else{
                this.item.xlstock = "在庫あり";
            }
            this.loading = false;
        },
        async productLike(){
            this.loadingType.type = 1;
            this.loading = true;
            const response = await axios.put(`/api/product/${this.id}/like`);

            if(response.status !== OK){
                this.$store.commit('error/setCode', response.status);
                return false;
            }

            //お気に入りを反映した商品一覧にする]
            this.item.login_id = response.data.loginId
            this.loading = false;
        },
        async cartIn(size){
            this.loadingType.type = 1;
            this.loading = true;
            const data = {
                id: this.item.id,
                size: size
            }
            const response = await axios.post(`/api/buyCart`, data);
            if(response.status !== OK){
                this.$store.commit('error/setCode', response.status);
                return false;
            }
            this.loading = false;

            this.$router.push('/cart');
        },
        onFavoriteClick(){
            if(! this.isLogin){
                alert('お気に入りをするにはログインしてください');
                return false;
            }else{
                this.productLike();
            }
        }
    },
    computed: {
        isLogin(){
            return this.$store.getters['auth/check'];
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