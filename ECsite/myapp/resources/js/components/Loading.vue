<template>
	<div class="loadingbackground" :class="{ 'clickblock': item.type === 1 }">
		<div class="loadingcontainer" :class="{ 'clickcontainer': item.type === 1 }">
			<div class="loadingfont" :class="{ 'clickfont': item.type === 1 }">L</div>
			<div class="loadingfont" :class="{ 'clickfont': item.type === 1 }">O</div>
			<div class="loadingfont" :class="{ 'clickfont': item.type === 1 }">A</div>
			<div class="loadingfont" :class="{ 'clickfont': item.type === 1 }">D</div>
			<div class="loadingfont" :class="{ 'clickfont': item.type === 1 }">I</div>
			<div class="loadingfont" :class="{ 'clickfont': item.type === 1 }">N</div>
			<div class="loadingfont" :class="{ 'clickfont': item.type === 1 }">G</div>
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
    }
}
</script>