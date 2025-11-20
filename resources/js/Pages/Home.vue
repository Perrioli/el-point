<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head } from '@inertiajs/vue3';
// Swiper imports remain as they are
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import { Navigation, Pagination } from 'swiper/modules';

defineProps({
    productos: Array,
});

const modules = [Navigation, Pagination];
</script>

<template>
    <Head title="Bienvenido" />
    <PublicLayout>
        <section class="hero-section">
            <div class="vimeo-background-container">
                <iframe
                    src="https://player.vimeo.com/video/1139108727?badge=0&autopause=0&player_id=0&app_id=58479&autoplay=1&muted=1&loop=1&background=1"
                    frameborder="0"
                    allow="autoplay; fullscreen; picture-in-picture;"
                    title="bar-video">
                </iframe>
            </div>
            
            <div class="overlay-content">
                <img src="/images/logo-el-point.png" alt="Logo El Point" style="max-width: 400px; height: auto;">
            </div>
        </section>

        <section class="menu-section">
            <div class="container">
                <h2 class="text-center text-white display-4 mb-5">Nuestra Carta</h2>
                <swiper :modules="modules" :slides-per-view="3" :space-between="30" :navigation="true"
                    :pagination="{ clickable: true }" :breakpoints="{
                        '320': { slidesPerView: 1, spaceBetween: 10 },
                        '768': { slidesPerView: 2, spaceBetween: 20 },
                        '992': { slidesPerView: 3, spaceBetween: 30 },
                    }" class="pb-5">
                    <swiper-slide v-for="producto in productos" :key="producto.id_producto">
                        <div class="card product-card border-secondary h-100">
                            <img v-if="producto.foto_url" :src="`/storage/${producto.foto_url}`" class="card-img-top"
                                :alt="producto.nombre" style="aspect-ratio: 4 / 3; object-fit: cover; width: 100%;">
                            <div class="card-body text-center">
                                <h5 class="card-title text-white">{{ producto.nombre }}</h5>
                                <p class="card-text h4 font-weight-bold text-primary">${{ producto.precio }}</p>
                            </div>
                        </div>
                    </swiper-slide>
                </swiper>
            </div>
        </section>
    </PublicLayout>
</template>

<style scoped>
/* SECTION 1: HERO (VIDEO) STYLES */
.hero-section {
    height: 100vh; /* Takes full viewport height */
    position: relative; /* Needed for absolute positioning of children */
    overflow: hidden; /* Prevents iframe overflow */
}

/* Container for the Vimeo iframe */
.vimeo-background-container {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1; /* Behind the overlay */
    pointer-events: none; /* Allows clicks to pass through to elements behind (if any) */
}

/* The div wrapper Vimeo provides */
.vimeo-background-container > div {
    width: 100%;
    height: 100%;
    padding: 0 !important; /* Override Vimeo's padding */
}

/* The iframe itself - Scaling logic */
.vimeo-background-container iframe {
    /* --- Key styles for full cover --- */
    width: 100vw; /* Takes full viewport width */
    height: 56.25vw; /* Calculates height based on a 16:9 aspect ratio */
    min-height: 100vh; /* Ensures height fills viewport even if width is narrow */
    min-width: 177.77vh; /* Ensures width fills viewport even if height is tall (100vh * 16/9) */
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%); /* Centers the (potentially oversized) iframe */
}

/* Overlay content (logo) styles remain the same */
.overlay-content {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 2; /* Ensures it's above the video */
}

/* SECTION 2: MENU STYLES (Unchanged) */
.menu-section {
    background-color: #1a1a1a;
    padding: 80px 0;
}
.menu-section h2 {
    font-family: 'Rye', cursive;
}
.product-card {
    background-color: rgba(35, 35, 35, 0.8);
    backdrop-filter: blur(5px);
    transition: transform 0.3s ease;
}
.product-card:hover {
    transform: translateY(-10px);
}
:deep(.swiper-button-next),
:deep(.swiper-button-prev) {
    color: #ffffff;
}
:deep(.swiper-pagination-bullet) {
    background: #fff;
    opacity: 0.5;
    width: 10px;
    height: 10px;
    transition: all 0.3s ease;
}
:deep(.swiper-pagination-bullet-active) {
    background: #00aaff;
    opacity: 1;
    transform: scale(1.3);
}
</style>