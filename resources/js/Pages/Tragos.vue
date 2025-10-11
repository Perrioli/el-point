<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    productos: { type: Array, default: () => [] },
    loading: { type: Boolean, default: false }
});

const whatsappNumber = '5493881234567';
const whatsappMessage = encodeURIComponent('¡Hola El Point! Quisiera hacer un pedido.');

import { computed } from 'vue';
const validProductos = computed(() => props.productos.filter(p => p && p.nombre && p.precio));

// Separar productos normales de promociones
const productosNormales = computed(() =>
    validProductos.value.filter(p => !p.es_promocion)
);

const promociones = computed(() =>
    validProductos.value.filter(p => p.es_promocion)
);

const formatPrice = (precio) => {
    return parseFloat(precio).toLocaleString('es-AR', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    });
};

const agregarTrago = (producto) => {
    const mensaje = encodeURIComponent(`¡Hola! Quiero pedir: ${producto.nombre} - $${formatPrice(producto.precio)}`);
    window.open(`https://wa.me/${whatsappNumber}?text=${mensaje}`, '_blank');
};
</script>

<template>
    <Head title="Carta de Tragos - El Point" />
    <PublicLayout>
        <div class="container text-white">
            <!-- Header -->
            <div class="text-center my-5">
                <h1 class="display-3 font-weight-bold mb-3 carta-title">
                    🍹 NUESTRA CARTA
                </h1>
                <p class="lead mb-4">Los mejores tragos de la ciudad te esperan</p>
                <div class="divider mx-auto"></div>
            </div>

            <!-- Estado de carga -->
            <div v-if="loading" class="text-center py-5">
                <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Cargando productos...</span>
                </div>
                <p class="h5">Preparando la carta perfecta para ti...</p>
            </div>

            <!-- Estado vacío -->
            <div v-else-if="validProductos.length === 0" class="text-center py-5">
                <div class="empty-state">
                    <i class="fas fa-cocktail fa-5x text-muted mb-4"></i>
                    <h3 class="text-white mb-3">¡Pronto tendremos tragos increíbles!</h3>
                    <p class="text-muted">Estamos preparando una carta espectacular para ti</p>
                </div>
            </div>

            <div v-else class="row justify-content-center">
                <div class="col-lg-8 col-md-10">

                    <!-- Sección de Promociones -->
                    <div v-if="promociones.length > 0" class="mb-5">
                        <h2 class="section-title mb-4">
                            <i class="fas fa-fire text-danger"></i>
                            Promociones Especiales
                        </h2>

                        <div v-for="producto in promociones"
                             :key="`promo-${producto.id_producto}`"
                             class="promocion-card mb-4 d-flex align-items-center">
                            <div class="p-3 d-flex align-items-center justify-content-center" style="min-width:140px;">
                                <img
                                    v-if="producto.foto_url"
                                    :src="`/storage/${producto.foto_url}`"
                                    class="promo-img"
                                    :alt="producto.nombre"
                                />
                                <div v-else class="promo-placeholder d-flex align-items-center justify-content-center">
                                    <i class="fas fa-cocktail fa-3x text-muted"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="promo-info p-3">
                                    <h4 class="promo-name mb-2">{{ producto.nombre }}</h4>
                                    <p v-if="producto.descripcion" class="promo-description text-muted mb-3">
                                        {{ producto.descripcion }}
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="promo-price">
                                            ${{ formatPrice(producto.precio) }}
                                        </span>
                                        <button
                                            @click="agregarTrago(producto)"
                                            class="btn btn-success btn-sm"
                                        >
                                            <i class="fab fa-whatsapp"></i>
                                            Pedir
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sección de Carta Regular -->
                    <div v-if="productosNormales.length > 0">
                        <h2 class="section-title mb-4">
                            <i class="fas fa-list-ul"></i>
                            Carta de Tragos
                        </h2>

                        <div class="carta-container">
                            <div v-for="producto in productosNormales"
                                 :key="producto.id_producto"
                                 class="carta-item carta-lista-vertical">
                                <div class="row align-items-center">
                                    <div class="col-8">
                                        <span class="item-name">{{ producto.nombre }}</span>
                                        <span v-if="producto.descripcion" class="item-description d-block text-muted">{{ producto.descripcion }}</span>
                                    </div>
                                    <div class="col-4 text-end">
                                        <span class="item-price">${{ formatPrice(producto.precio) }}</span>
                                    </div>
                                </div>
                                <span v-if="producto.categoria" class="item-category ms-2">{{ producto.categoria }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección de contacto -->
            <div class="contact-section text-center my-5 p-4">
                <h4 class="text-white mb-4">
                    <i class="fas fa-star text-warning"></i>
                    ¿Listo para tu Point perfecto?
                    <i class="fas fa-star text-warning"></i>
                </h4>
                <p class="text-muted mb-4">Hacé tu pedido ahora y disfrutá en minutos</p>

                <div class="row justify-content-center">
                    <div class="col-lg-4 col-md-6 mb-3">
                        <a :href="`https://wa.me/${whatsappNumber}?text=${whatsappMessage}`"
                           target="_blank"
                           class="btn btn-whatsapp btn-lg w-100 p-3 d-flex align-items-center justify-content-center">
                            <i class="fab fa-whatsapp fa-2x me-3"></i>
                            <div class="text-start">
                                <div class="h6 mb-0">Pedir por</div>
                                <div class="h5 mb-0">WhatsApp</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-3">
                        <a href="https://www.instagram.com/elpoint_juy/"
                           target="_blank"
                           class="btn btn-instagram btn-lg w-100 p-3 d-flex align-items-center justify-content-center">
                            <i class="fab fa-instagram fa-2x me-3"></i>
                            <div class="text-start">
                                <div class="h6 mb-0">Seguinos en</div>
                                <div class="h5 mb-0">Instagram</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

// Estilos
<style scoped>
/* Header de la carta */
.carta-title {
    background: linear-gradient(45deg, #ff6b35, #f7931e);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    text-shadow: none !important;
}

.divider {
    width: 100px;
    height: 4px;
    background: linear-gradient(45deg, #ff6b35, #f7931e);
    border-radius: 2px;
}

/* Títulos de sección */
.section-title {
    color: #f7931e;
    font-weight: bold;
    border-bottom: 2px solid rgba(247, 147, 30, 0.3);
    padding-bottom: 10px;
    margin-bottom: 20px;
}

/* Promociones destacadas */
.promocion-card {
    background: rgba(25, 25, 25, 0.95);
    border: 2px solid #f7931e;
    border-radius: 15px;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 8px 32px rgba(247, 147, 30, 0.2);
}

.promocion-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 16px 48px rgba(247, 147, 30, 0.3);
}

.promo-img-container {
    position: relative;
    height: 200px;
}

.promo-img {
  width: auto;
  height: 120px;
  object-fit: contain;
  background: #222;
  display: block;
  margin: 0 auto;
}

.promo-placeholder {
  width: 120px;
  height: 120px;
  background: linear-gradient(135deg, #333, #555);
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 10px;
}

.promo-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: linear-gradient(45deg, #ff6b35, #f7931e);
    color: white;
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: bold;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

.promo-info {
    background: rgba(0, 0, 0, 0.3);
    border-radius: 10px;
}

.promo-name {
    color: #f7931e;
    font-weight: bold;
    font-size: 1.3rem;
}

.promo-description {
    font-size: 0.9rem;
    line-height: 1.4;
}

.promo-price {
    font-size: 1.5rem;
    font-weight: bold;
    color: #22c55e;
}

/* Carta tradicional */
.carta-container {
    background: rgba(25, 25, 25, 0.9);
    border-radius: 15px;
    padding: 20px;
    backdrop-filter: blur(10px);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
}

.carta-lista-vertical {
    position: relative;
    padding: 15px 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
}

.carta-lista-vertical:last-child {
    border-bottom: none;
}

.carta-lista-vertical:hover {
    background: rgba(247, 147, 30, 0.1);
    border-radius: 10px;
    padding-left: 15px;
    padding-right: 15px;
}

.item-name {
    color: white;
    font-weight: 600;
    font-size: 1.1rem;
}

.item-description {
    font-size: 0.9rem;
    color: #ccc;
}

.item-price {
    font-size: 1.2rem;
    font-weight: bold;
    color: #22c55e;
    min-width: 80px;
    text-align: right;
}

.item-category {
    display: inline-block;
    margin-top: 8px;
    background: rgba(102, 126, 234, 0.8);
    color: white;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 0.7rem;
    font-weight: 500;
}

/* Botones de contacto */
.btn-whatsapp {
    background: linear-gradient(135deg, #25d366, #128c7e);
    border: none;
    border-radius: 15px;
    color: white;
    transition: all 0.3s ease;
    box-shadow: 0 6px 20px rgba(37, 211, 102, 0.3);
}

.btn-whatsapp:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(37, 211, 102, 0.4);
    color: white;
}

.btn-instagram {
    background: linear-gradient(135deg, #e4405f, #833ab4, #fd1d1d);
    border: none;
    border-radius: 15px;
    color: white;
    transition: all 0.3s ease;
    box-shadow: 0 6px 20px rgba(228, 64, 95, 0.3);
}

.btn-instagram:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(228, 64, 95, 0.4);
    color: white;
}

/* Sección de contacto */
.contact-section {
    background: rgba(15, 15, 15, 0.8);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

/* Estado vacío */
.empty-state {
    padding: 60px 20px;
    background: rgba(25, 25, 25, 0.6);
    border-radius: 20px;
    backdrop-filter: blur(10px);
}

/* Responsive */
@media (max-width: 768px) {
    .carta-title {
        font-size: 2.5rem;
    }

    .promocion-card .row {
        flex-direction: column;
    }

    .promo-img-container {
        height: 150px;
        margin-bottom: 15px;
    }

    .item-actions {
        flex-direction: column;
        align-items: end;
    }

    .item-price {
        margin-bottom: 10px;
        margin-right: 0;
    }

    .item-category {
        position: static;
        display: inline-block;
        margin-top: 10px;
    }
}
</style>
