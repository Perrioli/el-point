<script setup>
defineProps({
    form: {
        type: Object,
        required: true,
    },
});

defineEmits(['submit']);
</script>

<template>
    <form @submit.prevent="$emit('submit')">
        <div class="card-body">
            <div class="form-group">
                <label for="producto_stock">Nombre del Ítem</label>
                <input type="text" class="form-control" id="producto_stock" v-model="form.producto_stock" required>
                <div v-if="form.errors.producto_stock" class="text-danger">{{ form.errors.producto_stock }}</div>
            </div>
            <div class="form-group">
                <label for="cantidad">Cantidad</label>
                <input type="number" class="form-control" id="cantidad" v-model="form.cantidad" required>
                <div v-if="form.errors.cantidad" class="text-danger">{{ form.errors.cantidad }}</div>
            </div>
            <div class="form-group">
                <label for="costo">Costo Unitario</label>
                <input type="number" step="0.01" class="form-control" id="costo" v-model="form.costo" required>
                <div v-if="form.errors.costo" class="text-danger">{{ form.errors.costo }}</div>
            </div>
        </div>

        <slot />
    </form>
</template>