<template>
    <div class="photo">
        <RouterLink class="photo__click" :to="`/product/${item.id}`">
            <figure class="photo__wrapper">
                <img class="photo__image" :src="`/storage/${item.img_name}`" :alt="`Photo by ${item.name}`">
            </figure>
        </RouterLink>
        <div class="photo__username">
            {{item.name}}
        </div>
        <div class="photo__color">
            {{item.color}}
        </div>
        <div class="photo__block">
            <div class="photo__price">
                ¥{{item.price}}
            </div>
            <button class="photo__like" :class="{ 'photo__liked': item.login_id }" title="お気に入り" @click.prevent="favorite" >
                <i class="fas fa-heart"></i>
            </button>
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
    methods: {
        favorite(){
            this.$emit('favorite', {
                id: this.item.id
            })
        }
    }
}
</script>