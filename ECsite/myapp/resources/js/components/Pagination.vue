<template>
    <div class="pagination">
        <RouterLink v-if="! isFirstPage" :to="`/topPage/?page=${currentPage - 1}`" class="button moveButton">
            &laquo; prev
        </RouterLink>
        <RouterLink v-if="! isLastPage" :to="`/topPage/?page=${currentPage + 1}`" class="button moveButton">
            next &raquo;
        </RouterLink>
    </div>
</template>
<script>
export default {
    props: {
        currentPage: {
            type: Number,
            required: true
        },
        lastPage: {
            type: Number,
            required: true
        }
    },
    computed: {
        isFirstPage(){
            return this.currentPage === 1;
        },
        isLastPage(){
            return this.currentPage === this.lastPage;
        }
    }
}
</script>