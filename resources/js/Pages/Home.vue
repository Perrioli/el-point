<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Navigation, Pagination } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import { computed } from 'vue';
const promociones = computed(() => props.productos.filter(p => p.es_promocion));

const props = defineProps({
    productos: { type: Array, default: () => [] }
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

        <section v-if="promociones.length > 0" class="menu-section">
            <h1 class="section-title text-center text-white mb-5 display-3">
                <i class="fas fa-fire text-danger"></i>
                Promociones Especiales
            </h1>
            <div class="row">
                <div v-for="promo in promociones" :key="promo.id_producto" class="col-md-6 mb-4">
                    <div class="card h-100 shadow promo-card">
                        <div class="row no-gutters h-100">
                            <div class="col-md-4 d-flex align-items-center justify-content-center p-3">
                                <img v-if="promo.foto_url" :src="`/storage/${promo.foto_url}`" class="card-img-top"
                                    :alt="promo.nombre"/>
                                    <div v-else class="foto-placeholder">
                                            <i class="fas fa-cocktail fa-3x text-muted"></i>
                                        </div>
                            </div>
                            <div class="col-md-8 d-flex flex-column justify-content-center">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">{{ promo.nombre }}</h5>
                                    <p class="card-text h4 font-weight-bold text-success">${{ promo.precio }}</p>
                                    <p class="card-text"><small class="text-muted">¡Aprovecha esta oferta por tiempo limitado!</small></p>
                                </div>
                            </div>
                        </div>
                    </div>
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
                                :alt="producto.nombre"/>
                                <div v-else class="foto-placeholder">
                                            <i class="fas fa-cocktail fa-3x text-muted"></i>
                                        </div>
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

.foto-placeholder {
    width: 100%;
    height: 120px;
    background: linear-gradient(135deg, #333, #555);
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
}

.menu-section {
    background-color: #1a1a1a;
    padding: 80px 0;
}

.product-card {
    background-color: rgba(35, 35, 35, 0.8);
    backdrop-filter: blur(5px);
    transition: transform 0.3s ease;
}

.card-img-top {
  width: auto;
  height: 120px;
  object-fit: contain;
  background: #222;
  display: block;
  margin: 0 auto;
}

.promo-card {
  border: 2px solid #f7931e;
  border-radius: 15px;
  background: #222;
  transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
}

.promo-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 8px 32px #f7931e55;
    border-color: #ffb347;
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