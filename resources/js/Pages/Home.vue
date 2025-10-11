<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Navigation, Pagination } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';


defineProps({
    productos: Array,
});

const modules = [Navigation, Pagination];
</script>

<template>

    <Head title="Bienvenido" />
    <PublicLayout>
        <section class="hero-section">
            <div class="video-background-container">
                <video autoplay loop muted playsinline class="video-background">
                    <source src="/videos/bar-video.mp4" type="video/mp4">
                </video>
                <div class="overlay-content">
                    <img src="/images/logo-el-point.png" alt="Logo El Point" style="max-width: 400px; height: auto;">
                </div>
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
                                :alt="producto.nombre" style="height: 250px; object-fit: cover;">
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
.hero-section {
    height: 100vh;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.video-background-container,
.overlay-content {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.video-background {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.overlay-content {
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgba(0, 0, 0, 0.5);
}

.menu-section {
    background-color: #0d0d0d;
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