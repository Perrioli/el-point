<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';
import 'swiper/css/pagination';
import { Pagination } from 'swiper/modules';

const props = defineProps({
    productos: { type: Array, default: () => [] },
    loading: { type: Boolean, default: false }
});

const whatsappNumber = '5493881234567';
const whatsappMessage = encodeURIComponent('¡Hola El Point! Quisiera hacer un pedido.');

import { computed } from 'vue';
const validProductos = computed(() => props.productos.filter(p => p && p.nombre && p.precio));

const verDetalles = (id) => {
    router.visit(route('tragos.show', id));
};

const modules = [Pagination];
</script>

<template>

    <Head title="Nuestra Carta" />
    <PublicLayout>
        <div class="container text-white">
            <h1 class="text-center display-4 my-4 no-text-shadow">Seleccion de tragos</h1>

            <!-- Estado de carga -->
            <div v-if="loading" class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando productos...</span>
                </div>
                <p class="mt-2">Preparando la carta...</p>
            </div>

            <!-- Estado vacío -->
            <div v-else-if="validProductos.length === 0" class="text-center py-5">
                <p>No hay productos disponibles en este momento. ¡Vuelve pronto!</p>
            </div>

            <!-- Carrusel con paginación -->
            <swiper v-else :modules="modules" :slides-per-view="3" :space-between="30" :pagination="{ clickable: true }"
                :breakpoints="{
                    '320': { slidesPerView: 1, spaceBetween: 10 },
                    '768': { slidesPerView: 2, spaceBetween: 20 },
                    '992': { slidesPerView: 3, spaceBetween: 30 },
                }" class="pb-5">
                <swiper-slide v-for="producto in validProductos" :key="producto.id_producto">
                    <div class="card product-card border-secondary h-100" @click="verDetalles(producto.id_producto)">
                        <img v-if="producto.foto_url" :src="`/storage/${producto.foto_url}`" class="card-img-top"
                            :alt="`Imagen de ${producto.nombre}`" loading="lazy">
                        <div class="card-body text-center">
                            <h5 class="card-title text-white">{{ producto.nombre }}</h5>
                            <p class="card-text h4 font-weight-bold text-primary">${{ producto.precio }}</p>
                            <span v-if="producto.categoria" class="badge bg-secondary">{{ producto.categoria }}</span>
                        </div>
                    </div>
                </swiper-slide>
            </swiper>

            <!-- Botón WhatsApp -->
            <div class="text-center my-5">
                <h4 class="text-white mb-4">¿Listo para tu Point?</h4>
                <div class="row justify-content-center">
                    <div class="col-md-4 mb-3">
                        <a :href="`https://wa.me/${whatsappNumber}?text=${whatsappMessage}`" target="_blank"
                            class="btn btn-success btn-lg btn-block p-3 d-flex align-items-center justify-content-center">
                            <i class="fab fa-whatsapp fa-2x mr-3"></i>
                            <span class="h5 mb-0">Pedir por WhatsApp</span>
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="https://www.instagram.com/elpoint_juy/" target="_blank"
                            class="btn btn-light btn-lg btn-block p-3 d-flex align-items-center justify-content-center text-dark">
                            <i class="fab fa-instagram fa-2x mr-3"></i>
                            <span class="h5 mb-0">Pedir por Instagram</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

<style scoped>
/* Fondo coherente con el home */
.carta-section {
    background-color: rgba(15, 15, 15, 0.6);
    backdrop-filter: blur(4px);
    padding-bottom: 50px;
}

/* Cards */
.product-card {
    background-color: rgba(25, 25, 25, 0.4);
    backdrop-filter: blur(5px);
    transition: background-color 0.3s ease-in-out;
    cursor: pointer;
    border: none;
    box-shadow: none;
}

.product-card .card-body {
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.product-card:hover {
    background-color: rgba(25, 25, 25, 0.75);
}

.product-card img {
    height: 250px;
    object-fit: cover;
    width: 100%;
}

.no-text-shadow {
    text-shadow: none !important;
}

/* Swiper pagination elegante */
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

:deep(.swiper-pagination) {
    bottom: 0 !important;
    margin-top: 20px;
}

/* Botón WhatsApp */
.whatsapp-btn {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1000;
    display: none;
}

@media (max-width: 768px) {
    .whatsapp-btn {
        display: block;
    }

    .text-center.my-5 .whatsapp-btn {
        display: none;
    }
}
</style>