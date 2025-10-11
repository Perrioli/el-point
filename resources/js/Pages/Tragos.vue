<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    productos: { type: Array, default: () => [] }
});

const whatsappNumber = '5493881234567';
const instagramUsername = 'elpoint_juy';
const whatsappMessage = encodeURIComponent('¡Hola El Point! Quisiera hacer un pedido.');
</script>

<template>

    <Head title="Nuestra Carta" />
    <PublicLayout>
        <div class="container">
            <div class="menu-card">
                <div class="d-flex justify-content-center mb-4">
                    <img src="/images/logo-el-point.png" alt="Logo El Point" style="max-width: 200px; height: auto;">
                </div>

                <h1 class="text-center display-4 my-4">Carta de Tragos</h1>

                <div class="menu-list mx-auto">
                    <div v-for="producto in productos" :key="producto.id_producto" class="menu-item">
                        <span class="item-name">{{ producto.nombre }}</span>
                        <span class="item-leader"></span>
                        <span class="item-price">${{ producto.precio }}</span>
                    </div>
                </div>

                <div class="text-center mt-5">
                    <h4 class="mb-4">¿Listo para tu Point?</h4>
                    <div class="row justify-content-center">
                        <div class="col-md-5 mb-3">
                            <a :href="`https://wa.me/${whatsappNumber}?text=${whatsappMessage}`" target="_blank"
                                class="btn btn-success btn-lg btn-block p-3">
                                <i class="fab fa-whatsapp mr-2"></i> WhatsApp
                            </a>
                        </div>
                        <div class="col-md-5 mb-3">
                            <a :href="`https://ig.me/m/${instagramUsername}`" target="_blank"
                                class="btn btn-instagram btn-lg btn-block p-3">
                                <i class="fab fa-instagram mr-2"></i> Instagram
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

<style scoped>
.menu-card {
    background-color: rgba(10, 10, 10, 0.75);
    backdrop-filter: blur(10px);
    color: #ffffff;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    margin-top: 2rem;
    margin-bottom: 2rem;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.menu-list {
    font-family: 'Rye', cursive;
    font-size: 1.2rem;
    max-width: 700px;
    margin-left: auto;
    margin-right: auto;
}

.menu-item {
    display: flex;
    align-items: baseline;
    margin-bottom: 1rem;
}

.item-name {
    flex-shrink: 0;
}

.item-leader {
    flex-grow: 1;
    border-bottom: 2px dotted rgba(255, 255, 255, 0.3);
    margin: 0 0.8rem;
    transform: translateY(-4px);
}

.item-price {
    flex-shrink: 0;
    font-weight: bold;
    color: #00aaff;
}

.btn-instagram {
    background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
    color: white !important;
    border: none;
}
</style>