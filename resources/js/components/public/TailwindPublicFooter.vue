<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { onMounted, onUnmounted, ref } from 'vue';
import { about, contact } from '@/routes';
import { index as blogIndex } from '@/routes/blog';

const currentYear = new Date().getFullYear();
const showBackToTop = ref(false);

const handleScroll = () => {
    showBackToTop.value = window.scrollY > 300;
};

const scrollToTop = () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll, { passive: true });
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>

<template>
    <footer class="bg-[#1D231F]">
        <!-- Main Footer -->
        <div class="mx-auto grid max-w-[1320px] gap-12 px-4 py-[165px] max-lg:py-20 md:grid-cols-3 sm:px-6 lg:px-8">
            <!-- Logo + Info -->
            <div class="lg:pr-16">
                <div class="mb-[26px]">
                    <img
                        src="/images/simplysafaris.png"
                        alt="Simply Safaris"
                        class="h-auto w-[130px]"
                    />
                </div>
                <p class="mb-5 text-[16px] leading-[30.8px] text-white">
                    Discover the world's most breathtaking safari destinations with Simply Safaris. Unforgettable adventures await.
                </p>
                <ul class="space-y-[11px]">
                    <li class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#4DA528" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                        <span class="text-[16px] font-medium leading-[34px] text-white">info@simplysafaris.com</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#4DA528" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <span class="text-[16px] font-medium leading-[34px] text-white">+1 684 555-0102</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#4DA528" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        <span class="text-[16px] font-medium leading-[34px] text-white">6391 Elgin St. Celina, NYC 10299</span>
                    </li>
                </ul>
            </div>

            <!-- Services Links -->
            <div class="lg:pr-8">
                <h5 class="mb-[25px] text-[20px] font-semibold leading-[35px] text-white">Services</h5>
                <ul class="space-y-[11px]">
                    <li>
                        <Link :href="about()" class="text-[16px] font-semibold leading-[35px] text-[#B4B4B4] transition-all hover:ml-[5px] hover:text-[#4DA528]">About Us</Link>
                    </li>
                    <li><a href="#" class="text-[16px] font-semibold leading-[35px] text-[#B4B4B4] transition-all hover:ml-[5px] hover:text-[#4DA528]">Gallery</a></li>
                    <li><a href="#" class="text-[16px] font-semibold leading-[35px] text-[#B4B4B4] transition-all hover:ml-[5px] hover:text-[#4DA528]">Our Team</a></li>
                    <li><Link :href="blogIndex()" class="text-[16px] font-semibold leading-[35px] text-[#B4B4B4] transition-all hover:ml-[5px] hover:text-[#4DA528]">Blog Insights</Link></li>
                    <li><Link :href="contact()" class="text-[16px] font-semibold leading-[35px] text-[#B4B4B4] transition-all hover:ml-[5px] hover:text-[#4DA528]">Contact</Link></li>
                </ul>
            </div>

            <!-- Gallery -->
            <div>
                <h5 class="mb-[25px] text-[20px] font-semibold leading-[35px] text-white">Gallery</h5>
                <div class="grid grid-cols-3 gap-[10px]">
                    <a href="/template/images/gallery/gl1.jpg" target="_blank" class="overflow-hidden rounded-[2px]">
                        <img src="/template/images/gallery/gl1.jpg" alt="Gallery image" class="size-full object-cover transition-transform hover:scale-105" />
                    </a>
                    <a href="/template/images/gallery/gl2.jpg" target="_blank" class="overflow-hidden rounded-[2px]">
                        <img src="/template/images/gallery/gl2.jpg" alt="Gallery image" class="size-full object-cover transition-transform hover:scale-105" />
                    </a>
                    <a href="/template/images/gallery/gl3.jpg" target="_blank" class="overflow-hidden rounded-[2px]">
                        <img src="/template/images/gallery/gl3.jpg" alt="Gallery image" class="size-full object-cover transition-transform hover:scale-105" />
                    </a>
                    <a href="/template/images/gallery/gl4.jpg" target="_blank" class="overflow-hidden rounded-[2px]">
                        <img src="/template/images/gallery/gl4.jpg" alt="Gallery image" class="size-full object-cover transition-transform hover:scale-105" />
                    </a>
                    <a href="/template/images/gallery/gl5.jpg" target="_blank" class="overflow-hidden rounded-[2px]">
                        <img src="/template/images/gallery/gl5.jpg" alt="Gallery image" class="size-full object-cover transition-transform hover:scale-105" />
                    </a>
                    <a href="/template/images/gallery/gl6.jpg" target="_blank" class="overflow-hidden rounded-[2px]">
                        <img src="/template/images/gallery/gl6.jpg" alt="Gallery image" class="size-full object-cover transition-transform hover:scale-105" />
                    </a>
                </div>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-[rgba(242,242,242,0.07)]">
            <div class="mx-auto flex max-w-[1320px] flex-col items-center gap-3 px-4 py-7 md:flex-row md:justify-between sm:px-6 lg:px-8">
                <p class="text-[13px] text-white">
                    Website by
                    <a href="https://simplyit.co.tz" target="_blank" rel="noopener noreferrer" class="text-[#4DA528] hover:underline">SimplyIT</a>
                </p>
                <p class="text-center text-[13px] text-white">
                    Simply Safaris &copy; {{ currentYear }} | All rights reserved.
                </p>
                <div class="flex items-center gap-3 text-[13px] text-white">
                    <a href="#" class="transition-colors hover:text-[#4DA528]">Terms &amp; Conditions</a>
                    <span class="text-white/40">|</span>
                    <a href="#" class="transition-colors hover:text-[#4DA528]">Privacy Policy</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top -->
    <button
        type="button"
        class="fixed left-5 bottom-8 z-50 flex size-12 cursor-pointer items-center justify-center rounded-full bg-[#4DA528] text-white shadow-lg transition-all duration-300 hover:-translate-y-1"
        :class="showBackToTop ? 'opacity-60 visible' : 'opacity-0 invisible'"
        @click="scrollToTop"
        aria-label="Back to top"
    >
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
    </button>
</template>
