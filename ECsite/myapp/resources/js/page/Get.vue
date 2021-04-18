<template>
  <div class="get">
    <Pankuzu :key="pankuzu.id" :item="pankuzu" />
    <i class="fas fa-gifts"></i>
    <h3>購入が完了しました!商品が届くまでしばらくお待ちください。</h3>
    <RouterLink class="button button--link button--get" to="/topPage">
      トップページに戻る
    </RouterLink>
  </div>
</template>
<script>
import Pankuzu from '../components/Pankuzu.vue';
export default {
  components: {
    Pankuzu
  },
  data(){
    return {
      modal: true,
      pankuzu: {
        id: 2
      },
    }
  }
}
</script>