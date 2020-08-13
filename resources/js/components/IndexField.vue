<script>
    export default {
        props: ['resourceName', 'field'],

        mounted: function () {
            console.log(this.field);
        }
    }
</script>

<template>
    <span>{{ field.value }}</span>
</template>
