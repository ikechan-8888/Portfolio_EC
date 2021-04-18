<template>
  <div>
    <header>
      <Navber v-if="url === 1" />
      <AdminNavbar v-else-if="url === 0" />
    </header>
    <main>
      <div class="container">
        <RouterView />
      </div>
    </main>
    <Footer v-if="url === 1" />
    <AdminFooter v-else-if="url === 0" />
  </div>
</template>

<script>
import Navber from './components/Navbar.vue'
import Footer from './components/Footer.vue'
import AdminNavbar from './components/AdminNavbar.vue'
import AdminFooter from './components/AdminFooter.vue'
import { INTERNAL_SERVER_ERROR, CSRF_TIMEOUT, UNAUTHHORIZED } from './util'

export default{
  components: {
    Navber,
    Footer,
    AdminNavbar,
    AdminFooter,
  },
  computed: {
    errorCode(){
      return this.$store.state.error.code
    }
  },
  data(){
    return{
      url: '',
    }
  },
  methods: {
    fetchProduct(){
      const getUrl = this.$route.path;
      const checkUrl = getUrl.substr(0, 6);
      if(checkUrl === '/admin'){
        this.url = 0;
      }else{
        this.url = 1;
      }
    }
  },
  watch: {
    errorCode: {
      async handler(val){
        const getUrl = this.$route.path;
        const checkUrl = getUrl.substr(0, 6);

        if(val == INTERNAL_SERVER_ERROR){
          if(checkUrl === '/admin'){
            this.$router.push('/admin500');
          }else{
            this.$router.push('/500');
          }
        }else if(val == CSRF_TIMEOUT){
          // トークンをリフレッシュ
          await axios.get('/api/refresh-token');
          // ストアのuserをクリア
          this.$store.commit('auth/setUser', null);
          alert('しばらくログインされなかったので再度ログインしてください');
          if(checkUrl === '/admin'){
            this.$router.push('/adminLogin')
          }else{
            this.$router.push('/login')
          }
        }else if(val === UNAUTHHORIZED){
          // トークンをリフレッシュ
          await axios.get('/api/refresh-token');
          // ストアのuserをクリア
          this.$store.commit('auth/setUser', null);
          if(checkUrl === '/admin'){
            this.$router.push('/adminLogin')
          }else{
            this.$router.push('/login')
          }
        }
        this.$store.commit('error/setCode', null);
      },
      immediate: true
    },
    $route(){
      this.$store.commit('error/setCode', null);
    },
    $route: {
        async handler(){
            await this.fetchProduct();
        },
        immediate: true
    }
  }
}
</script>