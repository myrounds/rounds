<template>
    <div v-if="loading" class="spinner">
        <img src="../../images/loading.svg" width="40">
    </div>

</template>

<script>
    export default {
        props: ['loading']
    }
</script>