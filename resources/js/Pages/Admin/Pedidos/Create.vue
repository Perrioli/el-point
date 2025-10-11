<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue'; // Importamos ref y computed

// ----
// PROPS
// ----
// Recibimos la lista de todos los productos disponibles desde el controlador
const props = defineProps({
    productos: {
        type: Array,
        required: true,
    }
});

// ----
// ESTADO LOCAL (para el selector de productos)
// ----
const productoSeleccionadoId = ref(null);
const cantidad = ref(1);

// ----
// FORMULARIO PRINCIPAL (lo que se enviará al backend)
// ----
const form = useForm({
    persona: '',
    comentarios: '',
    metodo_pago:'',
    precio_total: 0,
    productos_pedido: [], // Array que contendrá los productos de este pedido
});

// ----
// LÓGICA DEL FORMULARIO
// ----
// Función para añadir el producto seleccionado a la lista del pedido
const agregarProducto = () => {
    // Validaciones básicas
    if (!productoSeleccionadoId.value || cantidad.value <= 0) {
        alert('Por favor, selecciona un producto y una cantidad válida.');
        return;
    }

    // Buscamos el producto completo en nuestra lista de props
    const productoAgregado = props.productos.find(p => p.id_producto === productoSeleccionadoId.value);
    
    // Añadimos el producto al array del formulario
    form.productos_pedido.push({
        producto_id: productoAgregado.id_producto,
        nombre: productoAgregado.nombre,
        cantidad: cantidad.value,
        precio_unitario: productoAgregado.precio,
    });

    // Reseteamos los campos del selector
    productoSeleccionadoId.value = null;
    cantidad.value = 1;
};

// Función para quitar un producto de la lista
const quitarProducto = (index) => {
    form.productos_pedido.splice(index, 1);
};

// Propiedad computada para calcular el total automáticamente
const totalPedido = computed(() => {
    let total = form.productos_pedido.reduce((sum, item) => sum + (item.cantidad * item.precio_unitario), 0);
    form.precio_total = total; // Actualizamos el precio_total del formulario
    return total;
});

// Función para enviar el formulario completo
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
                    <div class="form-group">
                        <label for="metodo_pago">Metodo de Pago</label>
                        <select v-model="form.metodo_pago" id="metodo_pago" class="form-control" required>
                            <option value="">-- Selecciona método de pago --</option>
                            <option value="Efectivo">Efectivo</option>
                            <option value="Transferencia">Transferencia</option>
                        </select>
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