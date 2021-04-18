<template>
<div>
    <Loading class="loadingblock" v-show="loading" :key="loadingType.id" :item="loadingType" />
    <nav class="navbar">
        <RouterLink class="navbar__title" to="/adminTopPage">
            PORTFOLIO
        </RouterLink>
        <div v-if="isLogin" class="navbar__menu">
            <div class="navbar__item">
                <span class="user_name">
                    LoginId：{{ username }}
                </span>
                <RouterLink class="button button--link buttonLogin admintoppage_button" to="/adminTopPage">
                    <i class="fas fa-home"></i>
                    トップページ
                </RouterLink>
                <RouterLink class="button button--link buttonLogin shipping_button" to="/adminShippingList">
                    <i class="fas fa-truck-moving"></i>
                    発送一覧
                </RouterLink>
                <RouterLink class="button button--link buttonLogin productlists_button" to="/adminProductList">
                    <i class="fas fa-tshirt"></i>
                    商品一覧
                </RouterLink>
                <RouterLink class="button button--link buttonLogin productregister_button" to="/adminproductregister">
                    <i class="fas fa-chalkboard-teacher"></i>
                    新規商品登録
                </RouterLink>
                <RouterLink class="button button--link buttonLogin adminregister_button" to="/adminLogin">
                    <i class="far fa-address-card"></i>
                    管理者登録
                </RouterLink>
                <button class="button button--link button_adminlogout" @click="logout">
                    <i class="fas fa-sign-out-alt"></i>
                    ログアウト
                </button>
                <div class="explanation">
                    <div class="shipping_explanation font_explanation">
                        <h3>購入された商品の一覧を確認、発送処理を行えます</h3>
                    </div>
                    <div class="productlists_explanation font_explanation">
                        <h3>登録された商品の一覧を確認、編集、削除を行えます</h3>
                    </div>
                    <div class="productregister_explanation font_explanation">
                        <h3>新規の商品の登録を行えます</h3>
                    </div>
                    <div class="adminregister_explanation font_explanation">
                        <h3>新規の管理者の登録を行えます</h3>
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
            await this.$store.dispatch('auth/adminLogout');
            this.loading = false;
            if(this.apiStatus){
                this.$router.push('/adminLogin');
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
            return this.$store.getters['auth/admincheck'];
        },
        username(){
            return this.$store.getters['auth/adminusername']
        }
    }
}
</script>