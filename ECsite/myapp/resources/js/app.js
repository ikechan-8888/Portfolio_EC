import Vue from 'vue'
// ルーティングの定義をインポートする
import router from "./router";
// ルートコンポーネントをインポートする
import App from "./App.vue";
//store(データを入れる箱)をインポートする
import store from './store';
//CSRF対策
import './bootstrap'

const createApp = async()=>{
  const getUrl = location.pathname;
  const checkUrl = getUrl.substr(0, 6);
  if(checkUrl === '/admin'){
    await store.dispatch('auth/currentAdminUser');
  }else{
    await store.dispatch('auth/currentUser');
  }
  new Vue({
    el: '#app',
    router, // ルーティングの定義を読み込む
    store,//データを入れる箱
    components: { App }, // ルートコンポーネントの使用を宣言する
    template: '<App />' // ルートコンポーネントを描画する
  });
}

createApp();