<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    producto: {
        type: Object,
        required: true
    }
});

const form = useForm({
    _method: 'PUT',
    nombre: props.producto.nombre,
    precio: props.producto.precio,
    foto: null,
});

const photoPreview = ref(props.producto.foto_url ? `/storage/${props.producto.foto_url}` : null);

const updatePhotoPreview = (event) => {
    const file = event.target.files[0];
    if (!file) return;
    photoPreview.value = URL.createObjectURL(file);
};

const submit = () => {
    form.post(route('admin.productos.update', props.producto.id_producto));
};

const goBack = () => {
    router.get(route('admin.productos.index'));
}
</script>

<template>
    <Head title="Editar Producto" />

    <AuthenticatedLayout>
        <template #header>
            Editar Producto: {{ producto.nombre }}
        </template>

        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Datos del Producto</h3>
            </div>
            <form @submit.prevent="submit">
                <div class="card-body">
                    <div class="form-group">
                        <label for="nombre">Nombre del Producto</label>
                        <input type="text" class="form-control" id="nombre" v-model="form.nombre" required>
                        <div v-if="form.errors.nombre" class="text-danger">{{ form.errors.nombre }}</div>
                    </div>
                    <div class="form-group">
                        <label for="precio">Precio</label>
                        <input type="number" step="0.01" class="form-control" id="precio" v-model="form.precio" required>
                        <div v-if="form.errors.precio" class="text-danger">{{ form.errors.precio }}</div>
                    </div>

                    <div class="form-group">
                        <label for="foto">Cambiar Foto del Producto</label>
                        <input type="file" class="form-control-file" id="foto" @input="form.foto = $event.target.files[0]" @change="updatePhotoPreview">
                        <div v-if="form.errors.foto" class="text-danger">{{ form.errors.foto }}</div>

                        <div v-if="photoPreview" class="mt-2">
                            <p>Vista Previa:</p>
                            <img :src="photoPreview" class="img-thumbnail" width="150" />
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary" :disabled="form.processing">Actualizar</button>
                    <button type="button" class="btn btn-secondary ml-1" @click="goBack">Cancelar</button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>