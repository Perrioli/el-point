<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    pedido: Object,
    productos: Array,
});

const productoSeleccionadoId = ref(null);
const cantidad = ref(1);

const form = useForm({
    persona: props.pedido.persona,
    comentarios: props.pedido.comentarios,
    precio_total: props.pedido.precio_total,
    productos_pedido: props.pedido.productos.map(p => ({
        producto_id: p.id_producto,
        nombre: p.nombre,
        cantidad: p.pivot.cantidad,
        precio_unitario: p.pivot.precio_unitario,
    })),
});

const agregarProducto = () => { /* ... (esta función es idéntica a la de Create.vue) ... */ };
const quitarProducto = (index) => { form.productos_pedido.splice(index, 1); };
const totalPedido = computed(() => { /* ... (esta función es idéntica a la de Create.vue) ... */ });

const submit = () => {
    if (form.productos_pedido.length === 0) {
        alert('Debes añadir al menos un producto al pedido.');
        return;
    }
    form.put(route('admin.pedidos.update', props.pedido.id_pedido));
};
</script>
<template>
    <Head title="Editar Pedido" />
    <AuthenticatedLayout>
        <template #header>Editar Pedido #{{ pedido.numero_caja }}</template>
        </AuthenticatedLayout>
</template>