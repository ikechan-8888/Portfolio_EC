<template>
    <div class="pankuzu">
      <div class="pankuzuname">
        <i v-if="item.id === 0" class="fas fa-shopping-cart pankuzunameicon"></i>
        <i v-else-if="item.id === 1" class="fas fa-list-alt pankuzunameicon"></i>
        <i v-else-if="item.id === 2" class="fas fa-thumbs-up pankuzunameicon"></i>
        <h2>{{ name }}</h2>
      </div>
      <div class="pankuzuicons">
        <RouterLink v-if="item.id === 1" class="pankuzulist" :class="{'pankuzuiconselect': item.id === 0}" to="/cart">
          <i class="fas fa-edit pankuzuicon"></i>
          <p>注文情報確認</p>
        </RouterLink>
        <div v-else class="pankuzulist" :class="{'pankuzuiconselect': item.id === 0}" to="/cart">
          <i class="fas fa-edit pankuzuicon"></i>
          <p>注文情報確認</p>
        </div>
        <div class="pankuzuvar"></div>
        <div class="pankuzulist" :class="{'pankuzuiconselect': item.id === 1}">
          <i class="fas fa-cash-register pankuzuicon"></i>
          <p>注文最終確認</p>
        </div>
        <div class="pankuzuvar"></div>
        <div class="pankuzulist" :class="{'pankuzuiconselect': item.id === 2}">
          <i class="fas fa-handshake pankuzuicon"></i>
          <p>購入完了</p>
        </div>
      </div>
    </div>
</template>
<script>
export default {
    props: {
        item: {
            type: Object,
            required: true
        }
    },
    data(){
        return {
            name: null
        }
    },
    methods: {
        change(){
            if(this.item.id === 0){
                this.name = "ショッピングカート";
            }else if(this.item.id === 1){
                this.name = "注文内容の確認";
            }else if(this.item.id === 2){
                this.name = "注文完了";
            }
        }
    },
    watch: {
        $route: {
        handler(){
            this.change();
        },
        immediate: true
        }
    },
}
</script>