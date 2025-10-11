<script setup>
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Pedidos en Pantalla" />
    <div class="bg-dark text-white vh-100 p-4">
        <slot />
    </div>
</template>

<style>
body {
    margin: 0;
    padding: 0;
    overflow: hidden;
}
</style>