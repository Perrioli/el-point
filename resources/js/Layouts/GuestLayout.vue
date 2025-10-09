<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="auth-background min-vh-100 d-flex flex-column justify-content-center align-items-center p-4">
        <div>
            <Link href="/">
                <img src="/images/logo-el-point.png" alt="Logo El Point" style="width: 230px; height: auto;">
            </Link>
        </div>

        <div class="w-100" style="max-width: 400px;">
            <div class="card shadow-sm mt-4">
                <div class="card-body p-4">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.auth-background {
    background-image: url('/images/fondo-bar.jpg');
    background-size: cover;
    background-position: center center;
}
</style>