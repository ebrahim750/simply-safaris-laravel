<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { home, contact, login } from '@/routes';

const mobileMenuOpen = ref(false);

const closeMobileMenu = () => {
    mobileMenuOpen.value = false;
};
</script>

<template>
    <header class="main-header flex">
        <div id="header">
            <div class="header-top">
                <div class="header-top-wrap flex-two">
                    <div class="header-top-right">
                        <ul class="flex-three">
                            <li class="flex-three">
                                <i class="icon-mail"></i>
                                <span>info@simplysafaris.com</span>
                            </li>
                            <li class="flex-three">
                                <i class="icon-phone"></i>
                                <span>+1 684 555-0102</span>
                            </li>
                        </ul>
                    </div>
                    <div class="header-top-left flex-two">
                        <div class="follow-social flex-two">
                            <span>Follow Us :</span>
                            <ul class="flex-two">
                                <li><a href="#"><i class="icon-icon-2"></i></a></li>
                                <li><a href="#"><i class="icon-icon_03"></i></a></li>
                                <li><a href="#"><i class="icon-x"></i></a></li>
                                <li><a href="#"><i class="icon-icon"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header-lower">
                <div class="tf-container full">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="inner-container flex justify-space align-center">
                                <div class="mobile-nav-toggler mobie-mt mobile-button" @click="mobileMenuOpen = !mobileMenuOpen">
                                    <i class="icon-Vector3"></i>
                                </div>
                                <div class="logo-box">
                                    <div class="logo">
                                        <Link href="/">
                                            <img src="/images/simplysafaris.png" alt="Logo" />
                                        </Link>
                                    </div>
                                </div>
                                <div class="nav-outer flex align-center">
                                    <nav class="main-menu show navbar-expand-md">
                                        <div class="navbar-collapse clearfix" :class="{ 'mobile-show': mobileMenuOpen }" id="navbarSupportedContent">
                                            <ul class="navigation clearfix">
                                                <li :class="{ current: $page.url === '/' }">
                                                    <Link :href="home()" @click="closeMobileMenu">Home</Link>
                                                </li>
                                                <li :class="{ current: $page.url === '/contact' }">
                                                    <Link :href="contact()" @click="closeMobileMenu">Contact</Link>
                                                </li>
                                            </ul>
                                        </div>
                                    </nav>
                                </div>
                                <div class="header-account flex align-center">
                                    <div class="register">
                                        <ul class="flex align-center">
                                            <li>
                                                <Link :href="login()" class="flex-three">
                                                    <img src="/template/images/page/avata.jpg" alt="image" />
                                                </Link>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <img src="/template/images/page/fl1.png" alt="" class="fly-ab" />
            </div>
        </div>
    </header>
</template>

<style scoped>
/* Mobile menu toggle */
@media (max-width: 991px) {
    .main-header .main-menu .navbar-collapse {
        display: none;
    }
    .main-header .main-menu .navbar-collapse.mobile-show {
        display: block;
    }
}
</style>
