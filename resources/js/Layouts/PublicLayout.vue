<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, onBeforeUnmount } from 'vue';

const isMenuOpen = ref(false);
const navRef = ref(null);
const toggleBtnRef = ref(null);

const handleClickOutside = (event) => {
    if (!isMenuOpen.value) return;

    const navEl = navRef.value;
    const btnEl = toggleBtnRef.value;
    const target = event.target;

    if (navEl && (navEl === target || navEl.contains(target))) return;
    if (btnEl && (btnEl === target || btnEl.contains(target))) return;

    isMenuOpen.value = false;
};

onMounted(() => {
    if (typeof document !== 'undefined') {
        document.addEventListener('click', handleClickOutside);
    }
});

onBeforeUnmount(() => {
    if (typeof document !== 'undefined') {
        document.removeEventListener('click', handleClickOutside);
    }
});
</script>

<template>

    <Head title="El Point" />

    <div :class="{ 'themed-background text-white': $page.component !== 'Home' }">
        <slot name="background" />

        <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
            <div class="container">
                <Link class="navbar-brand font-weight-bold" :href="route('home')">
                El Point
                </Link>

                <button class="navbar-toggler" type="button" aria-label="Toggle navigation"
                    @click="isMenuOpen = !isMenuOpen" ref="toggleBtnRef">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="navbar-collapse" :class="{ show: isMenuOpen }" id="publicNavbar" ref="navRef">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <Link class="nav-link" :href="route('home')"
                                :class="{ active: $page.component === 'Home' }">Inicio</Link>
                        </li>
                        <li class="nav-item">
                            <Link class="nav-link" :href="route('tragos.index')"
                                :class="{ active: $page.component === 'Tragos' }">Hacer pedido</Link>
                        </li>
                        <li class="nav-item">
                            <Link class="nav-link" :href="route('nosotros.index')"
                                :class="{ active: $page.component === 'Nosotros' }">Nosotros</Link>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main :class="{ 'pt-5 mt-5 container': $page.component !== 'Home' }">
            <slot />
        </main>

        <footer class="text-white pt-5 pb-4">
            <div class="container text-center text-md-left">
                <div class="row">
                    <div class="col-md-3 mx-auto mt-3">
                        <img src="/images/logo-el-point.png" alt="Logo El Point" class="img-fluid"
                            style="max-width: 150px;" />
                    </div>

                    <div class="col-md-2 mx-auto mt-3">
                        <h6 class="text-uppercase mb-4 font-weight-bold">Navegación</h6>
                        <p>
                            <Link :href="route('home')" class="text-white">Inicio</Link>
                        </p>
                        <p>
                            <Link :href="route('tragos.index')" class="text-white">Hacer pedido</Link>
                        </p>
                        <p>
                            <Link :href="route('nosotros.index')" class="text-white">Nosotros</Link>
                        </p>
                    </div>

                    <div class="col-md-3 mx-auto mt-3">
                        <h6 class="text-uppercase mb-4 font-weight-bold">Síguenos</h6>
                        <a href="https://www.instagram.com/elpoint_juy/"
                            class="btn btn-outline-light btn-floating m-1"><i class="fab fa-instagram"></i></a>
                        <a href="https://www.tiktok.com/@elpointjuy" class="btn btn-outline-light btn-floating m-1"><i
                                class="fab fa-tiktok"></i></a>
                    </div>

                    <div class="col-md-4 mx-auto mt-3">
                        <h6 class="text-uppercase mb-4 font-weight-bold">Contacto</h6>
                        <p><i class="fas fa-home me-2"></i> DIRECCION, San Salvador de Jujuy</p>
                        <p><i class="fas fa-phone me-2"></i> +54 9 388 123 4567</p>
                    </div>
                </div>
            </div>
            <div class="text-center mt-3">
                <p>&copy; 2025 - 2026 El Point. Todos los derechos reservados.</p>
            </div>
            <div class="text-center mt-3">
                <p>&copy; Archiment IT.</p>
            </div>
            <div class="text-center mt-2">
                <Link :href="route('login')" class="text-muted" style="font-size: 0.8rem; text-decoration: none;">
                Login
                </Link>
            </div>
        </footer>
    </div>
</template>

<style>
/* --- NAVBAR --- */
.navbar {
    z-index: 1050;
    background-color: rgba(10, 10, 10, 0.3);
    backdrop-filter: blur(8px);
    transition: background-color 0.3s ease-in-out;
}

.navbar:hover {
    background-color: rgba(10, 10, 10, 0.6);
}

.nav-link.active {
    font-weight: bold;
}

/* --- BACKGROUND --- */
.themed-background {
    position: relative;
    background-image: url('/images/fondo-bar.jpg');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
}

.themed-background::before {
    content: '';
    position: relative;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.6);
    z-index: 1;
}

.themed-background main,
.themed-background nav {
    position: relative;
}

/* --- MENU RESPONSIVE (glass + animación) --- */
.navbar-collapse {
    display: none;
    opacity: 0;
    transform: translateY(-10px);
    transition: all 0.3s ease-in-out;
}

/* mostrar cuando isMenuOpen = true */
.navbar-collapse.show {
    display: block;
    opacity: 1;
    transform: translateY(0);
}

.product-card .card-body {
    display: flex;
    flex-direction: column;
    justify-content: center;
}

@media (max-width: 991.98px) {
    .navbar-collapse.show {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: rgba(15, 15, 15, 0.95);
        backdrop-filter: blur(10px);
        padding: 1rem;
        border-bottom-left-radius: 0.5rem;
        border-bottom-right-radius: 0.5rem;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
    }

    .navbar-nav {
        width: 100%;
        text-align: center;
    }

    .navbar-nav .nav-link {
        padding: 0.75rem;
        display: block;
    }
}

.navbar-nav .nav-item {
    margin: 0.5rem 0;
}

@media (min-width: 992px) {
    .navbar-collapse {
        display: flex !important;
        opacity: 1 !important;
        transform: none !important;
        justify-content: flex-end;
        position: static !important;
        background: transparent !important;
        backdrop-filter: none !important;
        padding: 0 !important;
    }
}

/* --- FOOTER --- */
footer {
    background-color: #0d0d0d;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    color: #ffffff;
    padding-top: 3rem;
    padding-bottom: 2rem;
}

footer h6 {
    font-weight: 700;
    margin-bottom: 1rem;
    letter-spacing: 1px;
}

footer p,
footer a {
    color: #cccccc;
    font-size: 0.95rem;
}

footer a:hover {
    color: #ffffff;
    text-decoration: none;
}

footer .btn-floating {
    border: 1px solid rgba(255, 255, 255, 0.5);
    color: white;
    border-radius: 50%;
    width: 38px;
    height: 38px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

footer .btn-floating:hover {
    background-color: rgba(255, 255, 255, 0.15);
    transform: scale(1.1);
}

@media (max-width: 767.98px) {
    footer .row {
        text-align: center;
    }

    footer .col-md-3,
    footer .col-md-2,
    footer .col-md-4 {
        margin-bottom: 1.8rem;
    }

    footer img {
        display: block;
        margin: 0 auto 1rem auto;
        max-width: 130px;
    }

    footer .btn-floating {
        margin: 0 0.3rem;
    }

    footer p,
    footer h6 {
        text-align: center;
    }
}
</style>
