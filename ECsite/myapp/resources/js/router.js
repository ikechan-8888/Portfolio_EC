import Vue from "vue";
import VueRouter from "vue-router";

//ページコンポネートをインポートする
import TopPage from "./page/TopPage.vue";
import favorite from "./page/Favorite.vue";
import cart from "./page/Cart.vue";
import buy from "./page/Buy.vue";
import bought from "./page/Bought.vue";
import Login from "./page/Login.vue";
import ProductDetail from "./page/ProductDetail.vue";
import Get from "./page/Get.vue";
import Shop from "./page/Shop.vue";
import AdminLogin from "./page/AdminLogin.vue";
import AdminTopPage from "./page/AdminTopPage.vue";
import AdminProductListPage from "./page/AdminProductListPage.vue";
import AdminShippingListPage from "./page/AdminShippingListPage.vue";
import AdminShippingEdit from "./page/AdminShippingEdit.vue";
import AdminProductRegister from "./page/AdminProductRegister.vue";
import AdminProductEdit from "./page/AdminProductEdit.vue";
import store from "./store";
import SystemError from "./page/errors/System.vue"
import AdminSystemError from "./page/errors/AdminSystem.vue"

// VueRouterプラグインを使用する
// これによって<RouterView />コンポーネントなどを使うことができる
Vue.use(VueRouter);

// パスとコンポーネントのマッピング

const routes = [
    {
        path: "/topPage",
        component: TopPage,
        props: route => {
            const page = route.query.page;
            return {page: /^[1-9][0-9]*$/.test(page) ? page * 1 : 1};
        }
    },
    {
        path: "/login",
        component: Login,
        beforeEnter(to, from, next){
            if(store.getters['auth/check']){
                next('/topPage');
            }else{
                next();
            }
        }
    },
    {
        path: "/favorite",
        component: favorite,
        beforeEnter(to, from, next){
            if(store.getters['auth/check']){
                next();
            }else{
                next('/login');
            }
        }
    },
    {
        path: "/cart",
        component: cart
    },
    {
        path: "/buy",
        component: buy
    },
    {
        path: "/bought",
        component: bought,
        beforeEnter(to, from, next){
            if(store.getters['auth/check']){
                next();
            }else{
                next('/login');
            }
        }
    },
    {
        path: "/product/:id",
        component: ProductDetail,
        //propsをtrueに設定していると:値がpropsとして渡される
        props: true
    },
    {
        path: "/get",
        component: Get,
        beforeEnter(to, from, next){
            if(store.getters['auth/buyCheck']){
                next();
            }else{
                next('/topPage');
            }
        }
    },
    {
        path: "/shop",
        component: Shop
    },
    {
        path: "/adminLogin",
        component: AdminLogin,
    },
    {
        path: "/adminTopPage",
        component: AdminTopPage,
        beforeEnter(to, from, next){
            if(store.getters['auth/admincheck']){
                next();
            }else{
                next('/adminLogin');
            }
        }
    },
    {
        path: "/adminProductList",
        component: AdminProductListPage,
        beforeEnter(to, from, next){
            if(store.getters['auth/admincheck']){
                next();
            }else{
                next('/adminLogin');
            }
        }
    },
    {
        path: "/adminShippingList",
        component: AdminShippingListPage,
        beforeEnter(to, from, next){
            if(store.getters['auth/admincheck']){
                next();
            }else{
                next('/adminLogin');
            }
        }
    },
    {
        path: "/adminShippingEdit/:id",
        component: AdminShippingEdit,
        props: true,
        beforeEnter(to, from, next){
            if(store.getters['auth/admincheck']){
                next();
            }else{
                next('/adminLogin');
            }
        }
    },
    {
        path: "/adminproductregister",
        component: AdminProductRegister,
        beforeEnter(to, from, next){
            if(store.getters['auth/admincheck']){
                next();
            }else{
                next('/adminLogin');
            }
        }
    },
    {
        path: "/adminProductEdit/:id",
        component: AdminProductEdit,
        props: true,
        beforeEnter(to, from, next){
            if(store.getters['auth/admincheck']){
                next();
            }else{
                next('/adminLogin');
            }
        }
    },
    {
        path: "/500",
        component: SystemError,
    },
    {
        path: "/admin500",
        component: AdminSystemError,
    }
]

// VueRouterインスタンスを作成する
const router = new VueRouter({
    mode: 'history',
    //scrollBehaviorでxとyの値をセットするとページ送りした時に表示されるページの位置を決めれる
    scrollBehavior(){
        return {x: 0, y: 0}
    },
    routes
});

// VueRouterインスタンスをエクスポートする
// app.jsでインポートするため
export default router;