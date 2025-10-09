<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InventarioForm from './Partials/Form.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';

const form = useForm({
    producto_stock: '',
    cantidad: null,
    costo: null,
});

const submit = () => {
    form.post(route('admin.inventario.store'));
};

const goBack = () => {
    router.get(route('admin.inventario.index'));
}
</script>

<template>
    <Head title="Añadir Ítem al Inventario" />
    <AuthenticatedLayout>
        <template #header>Añadir Ítem al Inventario</template>

        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Datos del Ítem</h3>
            </div>
            <InventarioForm :form="form" @submit="submit">
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary" :disabled="form.processing">Guardar</button>
                    <button type="button" class="btn btn-secondary ml-1" @click="goBack">Cancelar</button>
                </div>
            </InventarioForm>
        </div>
    </AuthenticatedLayout>
</template>