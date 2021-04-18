<template>
    <div>
        <Loading class="loadingblock" v-show="loading" :key="loadingType.id" :item="loadingType" />
        <nav class="navbar">
            <RouterLink class="navbar__title" to="/topPage">
                PORTFOLIO
            </RouterLink>
            <div class="navbar__menu">
                <div class="navbar__item">
                    <span v-if="isLogin" class="user_name">
                        {{ username }}さま
                    </span>
                    <span v-else class="user_name">
                        ゲストさま
                    </span>
                    <RouterLink class="button button--link favorite_button" to="/favorite">
                        <i class="fas fa-heart"></i>
                    </RouterLink>
                    <RouterLink class="button button--link cart_button" to="/cart">
                        <i class="fas fa-shopping-cart"></i>
                    </RouterLink>
                    <RouterLink class="button button--link bought_button" to="/bought">
                        <i class="fas fa-gift"></i>
                    </RouterLink>
                    <RouterLink class="button button--link shop_button" to="/shop">
                        <i class="fas fa-store-alt"></i>
                    </RouterLink>
                    <button v-if="isLogin" class="button button--link button_logout" @click="logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                    <RouterLink v-else class="button button--link buttonLogin" to="/login">
                        ログイン / 会員登録
                    </RouterLink>
                    <div class="explanation">
                        <div class="favorite_explanation font_explanation">
                            <h3>お気に入りした商品を確認できます</h3>
                        </div>
                        <div class="cart_explanation font_explanation">
                            <h3>カートに追加された商品を確認できます</h3>
                        </div>
                        <div class="bought_explanation font_explanation">
                            <h3>購入した商品を確認できます</h3>
                        </div>
                        <div class="shop_explanation font_explanation">
                            <h3>ショップ情報を確認できます</h3>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</template>
<script>
import Loading from '../components/Loading.vue'

export default {
    components: {
        Loading
    },
    data(){
        return {
        loading: false,
        loadingType: {
            id: 0,
            type: 0
        }
        }
    },
    methods: {
        async logout(){
            this.loading = true;
            this.loadingType.type = 1;
            await this.$store.dispatch('auth/logout');
            this.loading = false;
            if(this.apiStatus){
                const getUrl = this.$route.path;
                const checkUrl = getUrl.substr(0, 4);
                if(checkUrl === '/top'){
                    this.$router.go({path: '/topPage', force: true});
                }else{
                    this.$router.push('/topPage');
                }
            }
        }
    },
    //computedプロパティは関数内の変数に変更があるときだけ再計算される
    //記述方法はcomputedプロパティ内の関数名と処理内容と戻り値を定義するだけ
    //データに何かしらの処理を加えてから表示させたい際はcomputedプロパティを用いる
    //watchプロパティとの使い分けは次に該当する場合、watchプロパティの方が良い(computedプロパティでは処理できない非同期通信などの複雑な処理を行う場合,更新前と更新後の値を使う場合,処理を実行しても、データは返さない場合(computedはreturnが必要))
    computed: {
        apiStatus(){
            return this.$store.state.auth.apiStatus;
        },
        isLogin(){
            return this.$store.getters['auth/check'];
        },
        username(){
            return this.$store.getters['auth/username']
        }
    }
}
</script>