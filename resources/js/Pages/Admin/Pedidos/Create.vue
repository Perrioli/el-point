<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    productos: {
        type: Array,
        required: true,
    }
});

const productoSeleccionadoId = ref(null);
const cantidad = ref(1);

const form = useForm({
    persona: '',
    comentarios: '',
    precio_total: 0,
    productos_pedido: [],
});

const agregarProducto = () => {
    if (!productoSeleccionadoId.value || cantidad.value <= 0) {
        alert('Por favor, selecciona un producto y una cantidad válida.');
        return;
    }

    const productoAgregado = props.productos.find(p => p.id_producto === productoSeleccionadoId.value);
    
    form.productos_pedido.push({
        producto_id: productoAgregado.id_producto,
        nombre: productoAgregado.nombre,
        cantidad: cantidad.value,
        precio_unitario: productoAgregado.precio,
    });

    productoSeleccionadoId.value = null;
    cantidad.value = 1;
};

const quitarProducto = (index) => {
    form.productos_pedido.splice(index, 1);
};

const totalPedido = computed(() => {
    let total = form.productos_pedido.reduce((sum, item) => sum + (item.cantidad * item.precio_unitario), 0);
    form.precio_total = total;
    return total;
});

const submit = () => {
    if (form.productos_pedido.length === 0) {
        alert('Debes añadir al menos un producto al pedido.');
        return;
    }
    form.post(route('admin.pedidos.store'));
};
</script>

<template>
    <Head title="Registrar Pedido" />
    <AuthenticatedLayout>
        <template #header>Registrar Nuevo Pedido</template>

        <form @submit.prevent="submit">
            <div class="card">
                <div class="card-header"><h3 class="card-title">Datos del Pedido</h3></div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="persona">Nombre del Cliente</label>
                        <input type="text" v-model="form.persona" id="persona" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="comentarios">Comentarios</label>
                        <textarea v-model="form.comentarios" id="comentarios" class="form-control"></textarea>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3 class="card-title">Añadir Productos</h3></div>
                <div class="card-body row align-items-end">
                    <div class="col-md-6 form-group">
                        <label>Producto</label>
                        <select v-model="productoSeleccionadoId" class="form-control">
                            <option :value="null" disabled>-- Selecciona un producto --</option>
                            <option v-for="producto in productos" :key="producto.id_producto" :value="producto.id_producto">
                                {{ producto.nombre }} - ${{ producto.precio }}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Cantidad</label>
                        <input type="number" v-model="cantidad" class="form-control" min="1">
                    </div>
                    <div class="col-md-3 form-group">
                        <button type="button" @click="agregarProducto" class="btn btn-secondary">Añadir</button>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3 class="card-title">Resumen del Pedido</h3></div>
                <div class="card-body p-0">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio Unit.</th>
                                <th>Subtotal</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="form.productos_pedido.length === 0"><td colspan="5" class="text-center">Añade productos al pedido.</td></tr>
                            <tr v-for="(item, index) in form.productos_pedido" :key="index">
                                <td>{{ item.nombre }}</td>
                                <td>{{ item.cantidad }}</td>
                                <td>${{ item.precio_unitario }}</td>
                                <td>${{ (item.cantidad * item.precio_unitario).toFixed(2) }}</td>
                                <td><button type="button" @click="quitarProducto(index)" class="btn btn-sm btn-danger">Quitar</button></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-right">Total:</th>
                                <th colspan="2">${{ totalPedido.toFixed(2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                 <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary" :disabled="form.processing">Guardar Pedido</button>
                </div>
            </div>
        </form>
    </AuthenticatedLayout>
</template>