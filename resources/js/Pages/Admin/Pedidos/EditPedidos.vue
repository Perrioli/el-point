<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm, Link, Head } from '@inertiajs/vue3';

const props = defineProps({
    pedido: Object,
    productos: Array
});

// Formulario con datos actuales del pedido
const form = useForm({
    comentarios: props.pedido.comentarios || '',
    metodo_pago: props.pedido.metodo_pago || '',
    productos: props.pedido.productos
        ? props.pedido.productos.map(p => ({
            id_producto: p.id_producto,
            nombre: p.nombre,
            cantidad: p.pivot.cantidad,
            precio_unitario: p.pivot.precio_unitario
        }))
        : []
});

//funcion para enviar el formulario
function submit() {
    form.put(route('admin.pedidos.update', props.pedido.id_pedido));
};
</script>

<template>

    <Head title="Editar Pedido" />
    <AuthenticatedLayout>
        <template #header>Edicion de Pedido</template>
    <div class="container mt-4">
        <h2> Pedido #{{ props.pedido.numero_caja }} - {{ props.pedido.persona }}</h2>
        <form @submit.prevent="submit" class="card p-4 mt-3">
            <div class="mb-3">
                <label for="comentarios" class="form-label">Comentarios</label>
                <textarea name="comentarios" v-model="form.comentarios" class="form-control" rows="2"></textarea>
            </div>
            <div class="mb-3">
                <label for="metodo_pago" class="form-label">Metodo de Pago</label>
                <select name="metodo_pago" v-model="form.metodo_pago" class="form-select">
                    <option value="Efectivo">Efectivo</option>
                    <option value="Transferencia">Transferencia</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Productos</label>
                <div v-for="(prod,idx) in form.productos" :key="prod.id_producto" class="row align-items-center mb-2 g-2">
    <div class="col-6">
        <span class="form-control-plaintext">
            {{ props.pedido.productos.find(p => p.id_producto === prod.id_producto)?.nombre || prod.nombre }}
        </span>
    </div>
    <div class="col-4">
        <input 
            type="number" 
            v-model.number="prod.cantidad" 
            class="form-control" 
            min="1" 
            :name="`productos[${idx}][cantidad]`"
        >
        <input 
            type="hidden" 
            :value="prod.id_producto" 
            :name="`productos[${idx}][id_producto]`"
        >
    </div>
    </div>
            </div>
            <button type="submit" class="btn btn-success">Guardar Cambios</button>
            <Link :href="route('admin.pedidos.index')" class="btn btn-secondary ms-2">Cancelar</Link>
        </form>
    </div>
    </AuthenticatedLayout>
</template>