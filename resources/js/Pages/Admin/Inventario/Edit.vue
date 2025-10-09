<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InventarioForm from './Partials/Form.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    item: {
        type: Object,
        required: true
    }
});

const form = useForm({
    _method: 'PUT',
    producto_stock: props.item.producto_stock,
    cantidad: props.item.cantidad,
    costo: props.item.costo,
});

const submit = () => {
    form.post(route('admin.inventario.update', props.item.id_stock));
};

const goBack = () => {
    router.get(route('admin.inventario.index'));
}
</script>

<template>
    <Head title="Editar Ítem del Inventario" />
    <AuthenticatedLayout>
        <template #header>Editar Ítem: {{ item.producto_stock }}</template>

        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Datos del Ítem</h3>
            </div>
            <InventarioForm :form="form" @submit="submit">
                 <div class="card-footer">
                    <button type="submit" class="btn btn-primary" :disabled="form.processing">Actualizar</button>
                    <button type="button" class="btn btn-secondary ml-1" @click="goBack">Cancelar</button>
                </div>
            </InventarioForm>
        </div>
    </AuthenticatedLayout>
</template>