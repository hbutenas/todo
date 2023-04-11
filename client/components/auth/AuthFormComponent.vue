<template>
    <form class="w-72 m-7" @submit.prevent="$emit('authEmit')">
        <slot/>
    </form>
</template>

<script setup>
const emit = defineEmits([
    'authEmit'
]);
</script>
