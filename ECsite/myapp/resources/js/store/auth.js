import Axios from "axios"
import {OK, CREATED, CSRF_TIMEOUT, UNPROCESSABLE_ENTITY} from "../util"

//データの入れ物
const state = {
  //ログイン済みユーザー
  user: null,
  //ログイン済み管理者
  adminUser: null,
  //apiが成功したか判定(true or false)
  apiStatus: null,
  //バリデーションエラーメッセージ
  //ログイン時
  loginErrorMessages: null,
  //個人情報登録時
  registerErrorMessages: null,
  //ログイン時
  adminLoginErrorMessages: null,
  //管理者情報登録時
  adminRegisterErrorMessages: null,
  //商品登録時
  productRegister: null,
  //商品登録時
  productRegisterErrorMessages: null,
  //お届け先登録時
  deliveryErrorMessages: null,
  //購入チェック(get.vueへの遷移を判断)
  buyCheck: false,
}

//ステートの内容から算出される値
const getters = {
  //ログインチェックに使用(二重否定は昔のブラウザはundefinedに対応していないものがあり一度boolean型に直しそれを否定して元に戻す)
  check: state => !! state.user,
  //ログインユーザーのnameを取得
  username: state => state.user ? state.user.account_name: '',
  //ログインチェックに使用(二重否定は昔のブラウザはundefinedに対応していないものがあり一度boolean型に直しそれを否定して元に戻す)
  admincheck: state => !! state.adminUser,
  //ログイン管理者のlogin_idを取得
  adminusername: state => state.adminUser ? state.adminUser.login_id: '',
  //登録商品情報を取得
  getProductRegister(state){
    return state.productRegister;
  },
  //get.vueがbuy.nueから遷移しているかのチェック
  buyCheck: state => state.buyCheck,
}

//ステートを更新するためのメソッド(同期処理)
const mutations = {
  //ミューテーションの第一引数は必ずステートになる
  setUser(state, user){
    state.user = user;
  },
  setAdminUser(state, adminUser){
    state.adminUser = adminUser;
  },
  setApiStatus(state, status){
    state.apiStatus = status;
  },
  setLoginErrorMessages(state, messages){
    state.loginErrorMessages = messages;
  },
  setRegisterErrorMessages (state, messages) {
    state.registerErrorMessages = messages
  },
  setAdminLoginErrorMessages(state, messages){
    state.adminLoginErrorMessages = messages;
  },
  setAdminRegisterErrorMessages (state, messages) {
    state.adminRegisterErrorMessages = messages
  },
  setProductRegister (state, data) {
    state.productRegister = data
  },
  setProductRegisterErrorMessages (state, messages) {
    state.productRegisterErrorMessages = messages
  },
  setDeliveryErrorMessages (state, messages) {
    state.deliveryErrorMessages = messages
  },
  setBuyCheck (state, check) {
    state.buyCheck = check
  },
}

/*ステートを更新するメソッド(非同期処理)
APIとの通信などの非同期処理を行った後にミューテーションを呼び出してステートを更新する*/
const actions = {
  /*第一引数にはコンテキストオブジェクトが渡される
  コンテキストオブジェクトにはミューテーションを呼び出すための commitメソッドなどが入っている*/
  async register(context, data){
    context.commit('setApiStatus', null);
    const response = await Axios.post('/api/register', data);

    if(response.status === CREATED){
      context.commit('setApiStatus', true);
      context.commit('setUser', response.data);
      return false
    }

    context.commit('setApiStatus', false);

    if(response.status === UNPROCESSABLE_ENTITY){
      context.commit('setRegisterErrorMessages', response.data.errors);
    }else{
      context.commit('error/setCode', response.status, {root: true});
    }
  },
  async login(context, data){
    context.commit('setApiStatus', null);
    const response = await Axios.post('/api/login', data);

    if(response.status === OK){
      context.commit('setApiStatus', true);
      context.commit('setUser', response.data);
      return false;
    }

    context.commit('setApiStatus', false);

    if(response.status === UNPROCESSABLE_ENTITY){
      context.commit('setLoginErrorMessages', response.data.errors);
    }else{
      //あるストアモジュールから別のモジュールのミューテーションを commit する場合は第三引数に { root: true } を追加する
      context.commit('error/setCode', response.status, {root: true});
    }
  },
  async logout(context){
    context.commit('setApiStatus', null);
    const response = await Axios.post('/api/logout');

    if(response.status === OK){
      context.commit('setApiStatus', true);
      context.commit('setUser', null);
      return false;
    }

    context.commit('setApiStatus', false);
    context.commit('error/setCode', response.status, {root: true});
  },
  async adminregister(context, data){
    context.commit('setApiStatus', null);
    const response = await Axios.post('/api/adminRegister', data);

    if(response.status === CREATED){
      context.commit('setApiStatus', true);
      context.commit('setAdminUser', response.data);
      return false
    }

    context.commit('setApiStatus', false);

    if(response.status === UNPROCESSABLE_ENTITY){
      context.commit('setAdminRegisterErrorMessages', response.data.errors);
    }else{
      context.commit('error/setCode', response.status, {root: true});
    }
  },
  async adminlogin(context, data){
    context.commit('setApiStatus', null);
    const response = await Axios.post('/api/adminLogin', data);

    if(response.status === OK){
      context.commit('setApiStatus', true);
      context.commit('setAdminUser', response.data);
      return false;
    }

    context.commit('setApiStatus', false);

    if(response.status === UNPROCESSABLE_ENTITY){
      context.commit('setAdminLoginErrorMessages', response.data.errors);
    }else{
      //あるストアモジュールから別のモジュールのミューテーションを commit する場合は第三引数に { root: true } を追加する
      context.commit('error/setCode', response.status, {root: true});
    }
  },
  async adminLogout(context){
    context.commit('setApiStatus', null);
    const response = await Axios.post('/api/adminLogout');

    if(response.status === OK){
      context.commit('setApiStatus', true);
      context.commit('setAdminUser', null);
      return false;
    }

    context.commit('setApiStatus', false);
    context.commit('error/setCode', response.status, {root: true});
  },
  async currentUser(context){
    context.commit('setApiStatus', null);
    const response = await Axios.get('/api/user');
    const user = response.data || null;

    if(response.status === OK){
      context.commit('setApiStatus', true);
      context.commit('setUser', user);
      return false;
    }

    context.commit('setApiStatus', false);
    context.commit('error/setCode', response.status, {root: true});
  },
  async currentAdminUser(context){
    context.commit('setApiStatus', null);
    const response = await Axios.get('/api/adminUser');
    const user = response.data || null;

    if(response.status === OK){
      context.commit('setApiStatus', true);
      context.commit('setAdminUser', user);
      return false;
    }

    context.commit('setApiStatus', false);
    context.commit('error/setCode', response.status, {root: true});
  },
  async delivery(context, data){
    context.commit('setApiStatus', null);
    const response = await Axios.post('/api/delivery', data);

    if(response.status === OK){
      context.commit('setApiStatus', true);
      return false;
    }

    context.commit('setApiStatus', false);

    if(response.status === UNPROCESSABLE_ENTITY){
      context.commit('setDeliveryErrorMessages', response.data.errors);
    }else{
      context.commit('error/setCode', response.status, {root: true});
    }
  },
  async productregistervalidate(context, data){
    context.commit('setApiStatus', null);
    const response = await Axios.post('/api/productRegisterValidate', data);

    if(response.status === OK){
      context.commit('setApiStatus', true);
      if(!data['imgName']){
        context.commit('setProductRegister', data);
      }
      context.commit('setProductRegisterErrorMessages', "OK");
      return false
    }

    context.commit('setApiStatus', false);

    if(response.status === UNPROCESSABLE_ENTITY){
      context.commit('setProductRegisterErrorMessages', response.data.errors);
    }else{
      context.commit('error/setCode', response.status, {root: true});
    }
  }
}
//アクション→コミットでミューテーション呼び出し→ステート更新というパターンはよく使う

export default{
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}