<template>
    <div class="photo">
        <RouterLink v-if="item.display_status === 1" class="photo__click" :to="`/product/${item.id}`">
            <figure class="photo__wrapper">
                <img class="photo__image" :src="`/storage/${item.img_name}`" :alt="`Photo by ${item.name}`">
            </figure>
        </RouterLink>
        <figure v-else-if="item.display_status === 0" class="photo__wrapper_none">
                <figcaption class="photo__image_none_font">販売停止中</figcaption>
                <img class="photo__image photo__image_none_image" :src="`/storage/${item.img_name}`" :alt="`Photo by ${item.name}`">
            </figure>
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
            <button class="photo__like photo__liked" title="お気に入り" @click.prevent="favorite" >
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