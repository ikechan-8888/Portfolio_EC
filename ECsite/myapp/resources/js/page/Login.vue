<template>
  <div>
    <Loading class="loadingblock" v-show="loading" :key="loadingType.id" :item="loadingType" />
    <div class="pankuzu_list">
      <RouterLink class="pankuzu_link pankuzu_font" to="/topPage">
        トップページ
      </RouterLink>
      <p class="pankuzu_symbol">＞</p>
      <h5 class="pankuzu_font">ログイン</h5>
    </div>
    <div class="container--small">
      <ul class="tab">
        <li class="tab__item" :class="{'tab__item--active': tab === 1 }" @click="tab = 1">
          ログイン
        </li>
        <li class="tab__item" :class="{'tab__item--active': tab === 2 }" @click="tab = 2">
          会員登録
        </li>
      </ul>
      <div class="panel form_panel" v-show="tab === 1">
        <form class="form" @submit.prevent="login">
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
            <button type="submit" class="button form_button">ログイン</button>
          </div>
        </form>
      </div>
      <div class="panel form_panel" v-show="tab === 2">
        <form class="form" @submit.prevent="register">
          <div class="form_block">
            <div>
              <label for="username" class="form_label">名前</label><span class="form_require">必須</span>
            </div>
            <div v-if="registerErrors">
              <ul v-if="registerErrors.account_name" class="errors">
                <li v-for="msg in registerErrors.account_name" :key="msg">{{msg}}</li>
              </ul>
            </div>
          </div>
          <input type="text" class="form__item" id="username" placeholder="例) 池上広軌" v-model="registerForm.account_name">
          <div class="form_block">
            <div>
              <label for="loginid" class="form_label">ログインID</label><span class="form_require">必須</span>
            </div>
            <div v-if="registerErrors">
              <ul v-if="registerErrors.login_id" class="errors">
                <li v-for="msg in registerErrors.login_id" :key="msg">{{msg}}</li>
              </ul>
            </div>
          </div>
          <input type="text" class="form__item" id="loginid" placeholder="例) thefreelance" v-model="registerForm.login_id">
          <div class="form_block">
            <div>
              <label for="password" class="form_label">パスワード</label><span class="form_require">必須</span>
            </div>
            <div v-if="registerErrors">
              <ul v-if="registerErrors.password" class="errors">
                <li v-for="msg in registerErrors.password" :key="msg">{{msg}}</li>
              </ul>
            </div>
          </div>
          <input type="password" class="form__item" id="password" placeholder="例) himitu" v-model="registerForm.password">
          <div class="form__button">
            <button type="submit" class="button form_button">登録</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import Loading from '../components/Loading.vue'
export default{
  components: {
    Loading,
  },
  data(){
    return{
      tab: 1,
      loginForm: {
        login_id: '',
        password: ''
      },
      registerForm: {
        account_name: '',
        login_id: '',
        password: ''
      },
      loading: false,
      loadingType: {
        id: 0,
        type: 1
      }
    }
  },
  methods: {
    //stores/index.js にて Vue.use(Vuex) という記述で Vuex プラグインの使用を宣言したので、this.$store からストアを参照することができる
    //アクションを呼び出すにはdispatchメソッドを使用する(第一引数はアクションの名前)
    async register () {
      this.loading = true;
      /* authストアのresigterアクションを呼び出す
         this.registerFormは登録データ*/
      await this.$store.dispatch('auth/register', this.registerForm);
      this.loading = false;
      //statuscode=200だったら
      if(this.apiStatus){
        // トップページに移動する(resourses/js/router.jsのルートに/が追加される)
        this.$router.push('/topPage');
      }
    },
    async login(){
      this.loading = true;
      await this.$store.dispatch('auth/login', this.loginForm);
      this.loading = false;
      //statuscode=200だったら
      if(this.apiStatus){
        this.$router.push('/topPage');
      }
    },
    clearError(){
      this.$store.commit('auth/setLoginErrorMessages', null);
      this.$store.commit('auth/setRegisterErrorMessages', null);
    }
  },
  computed: {
    apiStatus(){
      return this.$store.state.auth.apiStatus;
    },
    loginErrors(){
      return this.$store.state.auth.loginErrorMessages;
    },
    registerErrors(){
      return this.$store.state.auth.registerErrorMessages;
    }
  },
  created(){
    this.clearError();
  }
}
</script>