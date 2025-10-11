<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue'; // Importamos ref para la vista previa

const form = useForm({
    nombre: '',
    precio: null,
    foto: null, // Añadimos el campo para la foto
    es_promocion: false, // Nuevo campo para marcar como promoción
});

// Variable reactiva para la URL de la vista previa
const photoPreview = ref(null);

// Función para actualizar la vista previa
const updatePhotoPreview = (event) => {
    const file = event.target.files[0];
    if (!file) return;
    photoPreview.value = URL.createObjectURL(file);
};

const submit = () => {
    // Al hacer post, Inertia manejará el archivo automáticamente
    form.post(route('admin.productos.store'));
};

const goBack = () => {
    router.get(route('admin.productos.index'));
}
</script>

<template>

    <Head title="Crear Producto" />

    <AuthenticatedLayout>
        <template #header>
            Crear Nuevo Producto
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
                        <input type="number" step="0.01" class="form-control" id="precio" v-model="form.precio"
                            required>
                        <div v-if="form.errors.precio" class="text-danger">{{ form.errors.precio }}</div>
                    </div>

                    <div class="form-group">
                        <label for="foto">Foto del Producto</label>
                        <input type="file" class="form-control-file" id="foto"
                            @input="form.foto = $event.target.files[0]" @change="updatePhotoPreview">
                        <div v-if="form.errors.foto" class="text-danger">{{ form.errors.foto }}</div>

                        <div v-if="photoPreview" class="mt-2">
                            <img :src="photoPreview" class="img-thumbnail" width="150" />
                        </div>
                    </div>

                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="es_promocion" v-model="form.es_promocion">
                        <label class="form-check-label" for="es_promocion">¿Es promoción?</label>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary" :disabled="form.processing">Guardar</button>
                    <button type="button" class="btn btn-secondary ml-1" @click="goBack">Cancelar</button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>