<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    pedido: {
        type: Object,
        required: true,
    }
});
</script>

<template>
    <div class="card mb-3 shadow-sm">
        <div class="card-header d-flex justify-content-between">
            <strong>#{{ pedido.numero_caja }} - {{ pedido.persona }}</strong>
            <strong>Total: ${{ pedido.precio_total }}</strong>
        </div>
        <div class="card-body">
            <ul class="list-unstyled mb-0">
                <li v-for="producto in pedido.productos" :key="producto.id_producto">
                    {{ producto.pivot.cantidad }}x {{ producto.nombre }}
                </li>
            </ul>
            <p v-if="pedido.comentarios" class="card-text text-muted border-top pt-2 mt-2 mb-0">
                <em>Comentarios: {{ pedido.comentarios }}</em>
            </p>
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center">
            <small>Pedido: {{ new Date(pedido.created_at).toLocaleTimeString() }}</small>

            <div>
                <template v-if="pedido.status === 'pendiente'">
                    <Link :href="route('admin.pedidos.edit', pedido.id_pedido)" class="btn btn-sm btn-info">Editar
                    </Link>
                    <button @click="$emit('delete-pedido', pedido.id_pedido)"
                        class="btn btn-sm btn-danger ml-1">Cancelar</button>
                    <Link :href="route('admin.pedidos.listo', pedido.id_pedido)" method="patch" as="button"
                        class="btn btn-sm btn-warning ml-1">
                    Listo
                    </Link>
                </template>

                <Link v-if="pedido.status === 'listo'" :href="route('admin.pedidos.entregado', pedido.id_pedido)"
                    method="patch" as="button" class="btn btn-sm btn-success">
                Entregado
                </Link>

                <small v-if="pedido.status === 'entregado'">Entregado: {{ new
                    Date(pedido.hora_entrega).toLocaleTimeString() }}</small>
            </div>
        </div>
    </div>
</template>