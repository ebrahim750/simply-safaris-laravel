<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Play, Star, Clock, Users } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';
import { home } from '@/routes';

const showVideoModal = ref(false);

const openVideo = () => {
    showVideoModal.value = true;
};

const closeVideo = () => {
    showVideoModal.value = false;
};

onMounted(() => {
    const swiperScript = document.createElement('script');
    swiperScript.src = '/template/js/swiper-bundle.min.js';
    swiperScript.onload = () => {
        // @ts-expect-error - Swiper is loaded dynamically
        if (window.Swiper) {
            // @ts-expect-error - Swiper is loaded dynamically
            new window.Swiper('.brand-logo-tailwind', {
                autoplay: {
                    delay: 0,
                    disableOnInteraction: false,
                },
                slidesPerView: 2,
                loop: true,
                spaceBetween: 30,
                speed: 10000,
                observer: true,
                observeParents: true,
                breakpoints: {
                    600: { slidesPerView: 2, spaceBetween: 0 },
                    992: { slidesPerView: 3, spaceBetween: 0 },
                    1200: { slidesPerView: 5, spaceBetween: 0 },
                },
            });
        }
    };
    document.head.appendChild(swiperScript);
});
</script>

<template>
    <Head title="About Us" />

    <!-- Breadcrumb -->
    <section
        class="relative flex items-center bg-cover bg-center bg-no-repeat py-[137px]"
        style="background-image: url('/template/images/page/breakcrumb.jpg')"
    >
        <div class="absolute inset-0 bg-gradient-to-r from-[rgba(4,29,11,0.89)] to-[rgba(0,0,0,0.33)]"></div>
        <div class="relative z-[1] mx-auto w-full max-w-[1320px] px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="mb-[26px] text-[45px] leading-[50px] font-semibold text-white">About Us</h1>
                <nav class="flex items-center justify-center text-[18px] leading-[22.68px]">
                    <Link :href="home()" class="relative mr-[30px] text-[#4DA528] after:absolute after:right-[-18px] after:top-1/2 after:-translate-y-1/2 after:text-[10px] after:text-white after:content-['\203A']">Home</Link>
                    <span class="text-white">About Us</span>
                </nav>
                <img class="absolute left-[7%] top-[40%] hidden w-auto lg:block" src="/template/images/page/mask-bcrumb.png" alt="" />
            </div>
        </div>
    </section>

    <!-- About Content -->
    <section class="py-[103px] max-lg:py-20">
        <div class="mx-auto max-w-[1320px] px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-start">
                <div class="mb-10 w-full lg:mb-0 lg:w-1/2 xl:pr-[5.5em]">
                    <div class="relative">
                        <div class="relative z-10 max-w-[372px]">
                            <img src="/template/images/about-us/about-h41.jpg" alt="Safari adventure" class="w-full rounded-lg" />
                            <div class="absolute bottom-[-20px] left-0 right-0 z-0">
                                <img src="/template/images/page/shape5.1.png" alt="" class="w-full" />
                            </div>
                        </div>
                        <div class="absolute top-[31.5%] right-0 z-20 max-w-[349px] w-full">
                            <img src="/template/images/about-us/about-h4.jpg" alt="Safari landscape" class="w-full rounded-lg" />
                            <div class="absolute bottom-[-20px] left-0 right-0 z-0">
                                <img src="/template/images/page/shape5.1.png" alt="" class="w-full" />
                            </div>
                        </div>
                        <div class="absolute bottom-[-17.5%] left-[29%] z-30 inline-block animate-[pulse_3s_linear_infinite_alternate] rounded-lg border-2 border-dashed border-[#EDEEEE] bg-white px-[34px] py-[32px] pb-[25px] text-center">
                            <span class="text-[50px] font-bold leading-[25px] text-[#4DA528]">25</span>
                            <p class="mt-3 text-[17px] font-medium leading-[25px] text-[#081E2A]">year of<br />experience</p>
                        </div>
                        <div class="absolute top-[31.7%] left-[-41%] z-20 rotate-[-90deg] bg-[#4DA528] px-[26px] py-[20px] text-[20px] font-bold text-white [clip-path:polygon(100%_0,100%_50%,100%_100%,0%_100%,5%_50%,0%_0)]">
                            25,000 + Customer Worldwide
                        </div>
                    </div>
                </div>

                <div class="w-full lg:w-1/2 lg:pl-[17px]">
                    <span class="font-['Yesteryear'] text-[28px] leading-[46px] text-[#4DA528]">Explore the world</span>
                    <h2 class="mb-[18px] text-[36px] leading-[48px] font-semibold text-[#081E2A]">
                        Great opportunity for <span class="font-['Yesteryear'] text-[#64748B]">adventure</span> &amp; travels
                    </h2>
                    <p class="text-[16px] leading-[30.8px] text-[#4F545A]">
                        Welcome to Simply Safaris! We are a professional and reliable safari company that offers a wide range of unforgettable experiences.
                    </p>

                    <div class="mt-8 grid gap-6 md:grid-cols-2">
                        <div class="flex gap-4">
                            <div class="relative shrink-0">
                                <div class="flex size-10 items-center justify-center text-[#4DA528]">
                                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 23.125V38.8281H38.8281V12.2656H34.1406"/><path d="M5.85938 12.2656H1.17188V38.8281H20V23.2031"/><path d="M26.9598 9.14062H34.1406V34.1406H26.5035C23.5528 34.1406 20.933 36.0287 20 38.8281"/><path d="M13.0402 9.14062H5.85938V34.1406H13.4965C16.4472 34.1406 19.067 36.0287 20 38.8281"/><path d="M19.9999 1.17188C14.6972 1.17188 11.311 6.82758 13.815 11.5017L19.9999 23.0469L26.1848 11.5017C28.6888 6.82758 25.3025 1.17188 19.9999 1.17188Z"/><circle cx="20" cy="8.20312" r="2.6875"/></svg>
                                </div>
                                <div class="absolute top-[-4px] right-[-10px] size-[22px] rounded-full bg-[#FF970D]"></div>
                            </div>
                            <div>
                                <h6 class="mb-2 font-semibold text-[#081E2A]">Trusted travel guide</h6>
                                <p class="text-[16px] leading-[30px] text-[#4F545A]">We are a professional safari company that offers a wide range of experiences</p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="relative shrink-0">
                                <div class="flex size-10 items-center justify-center text-[#4DA528]">
                                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 23.125V38.8281H38.8281V12.2656H34.1406"/><path d="M5.85938 12.2656H1.17188V38.8281H20V23.2031"/><path d="M26.9598 9.14062H34.1406V34.1406H26.5035C23.5528 34.1406 20.933 36.0287 20 38.8281"/><path d="M13.0402 9.14062H5.85938V34.1406H13.4965C16.4472 34.1406 19.067 36.0287 20 38.8281"/><path d="M19.9999 1.17188C14.6972 1.17188 11.311 6.82758 13.815 11.5017L19.9999 23.0469L26.1848 11.5017C28.6888 6.82758 25.3025 1.17188 19.9999 1.17188Z"/><circle cx="20" cy="8.20312" r="2.6875"/></svg>
                                </div>
                                <div class="absolute top-[-4px] right-[-10px] size-[22px] rounded-full bg-[#FF970D]"></div>
                            </div>
                            <div>
                                <h6 class="mb-2 font-semibold text-[#081E2A]">Personalized Trips</h6>
                                <p class="text-[16px] leading-[30px] text-[#4F545A]">We offer personalized trips tailored to your preferences and desires</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex items-center gap-6">
                        <a href="#" class="inline-flex items-center gap-2.5 rounded-[5px] bg-[#4DA528] px-9 py-[15px] text-[15px] font-semibold uppercase tracking-[0.05em] text-white transition-all hover:bg-[#FF970D]">
                            More about us
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        </a>
                        <div class="flex items-center gap-3">
                            <img src="/template/images/avata/10.jpg" alt="CEO" class="size-12 rounded-full object-cover" />
                            <div>
                                <img src="/template/images/page/name.png" alt="Signature" class="h-auto w-24" />
                                <span class="text-[14px] text-[#4DA528]">CEO &amp; Founder</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Video Section -->
    <section class="relative overflow-hidden">
        <div class="relative mx-auto max-w-[1320px] px-4 sm:px-6 lg:px-8">
            <div class="relative mb-[-14em] overflow-hidden rounded-2xl">
                <img src="/template/images/about-us/about-h41.jpg" alt="" class="h-[500px] w-full object-cover" />
                <div class="absolute inset-0 flex flex-col items-center justify-center bg-black/50">
                    <button type="button" class="group relative flex size-20 items-center justify-center rounded-full border-2 border-white text-white transition-transform hover:scale-110" @click="openVideo">
                        <Play class="ml-1 size-8 fill-white" />
                    </button>
                    <p class="mt-4 font-['Yesteryear'] text-2xl text-white">Watch Video</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Tours -->
    <section class="bg-[#F4F6F8] pt-[370px] pb-[218px] max-lg:pt-[250px] max-lg:pb-20">
        <div class="mx-auto max-w-[1320px] px-4 sm:px-6 lg:px-8">
            <div class="mb-[30px] text-center">
                <span class="font-['Yesteryear'] text-[28px] leading-[46px] text-[#4DA528]">Featured Tour</span>
                <h2 class="text-[36px] font-semibold text-[#081E2A]">Our top this week</h2>
            </div>

            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                <div v-for="i in 3" :key="i" class="overflow-hidden rounded-xl bg-white shadow-sm transition-shadow hover:shadow-md">
                    <div class="relative">
                        <img :src="`/template/images/travel-list/${i}.jpg`" alt="Tour" class="h-56 w-full object-cover" />
                        <div class="absolute top-4 left-4 right-4 flex items-center justify-between">
                            <span class="rounded-full bg-[#4DA528] px-3 py-1 text-xs font-semibold text-white">Featured</span>
                            <div class="flex gap-2">
                                <span class="flex items-center gap-1 rounded-full bg-white/90 px-2 py-1 text-xs text-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg> 5
                                </span>
                                <span class="flex items-center gap-1 rounded-full bg-white/90 px-2 py-1 text-xs text-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg> 2
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="p-5">
                        <div class="mb-2 flex items-center gap-1 text-sm text-[#4DA528]">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                            United States USA
                        </div>
                        <h3 class="mb-3 text-lg font-semibold text-[#081E2A]"><a href="#" class="hover:text-[#4DA528]">6 nights From Moscow</a></h3>
                        <div class="mb-3 flex items-center gap-0.5 text-[#FF970D]">
                            <Star v-for="j in 5" :key="j" class="size-3.5 fill-current" />
                            <span class="ml-2 text-xs text-gray-500">(1 Review)</span>
                        </div>
                        <div class="mb-4 flex gap-4">
                            <div class="flex items-center gap-1.5 text-xs text-gray-500">
                                <Clock class="size-3.5" />
                                <span>5 days</span>
                            </div>
                            <div class="flex items-center gap-1.5 text-xs text-gray-500">
                                <Users class="size-3.5" />
                                <span>12 Person</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between border-t border-gray-100 pt-4">
                            <div>
                                <span class="text-xs text-gray-500">From</span>
                                <span class="ml-1 text-lg font-bold text-[#4DA528]">$169.00</span>
                                <span class="ml-2 text-sm text-gray-400 line-through">$199.00</span>
                            </div>
                            <button type="button" class="rounded-md border border-gray-200 p-2 text-gray-400 transition-colors hover:border-[#4DA528] hover:text-[#4DA528]">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Banner -->
    <section class="relative z-10">
        <div class="mx-auto max-w-[1320px] px-4 sm:px-6 lg:px-8">
            <div class="relative flex items-center justify-between rounded-[7px] bg-[#4DA528] px-[39px] py-[35px] max-md:flex-col max-md:gap-5 max-md:text-center max-sm:px-5 max-sm:py-[30px]">
                <div class="flex items-center gap-[43px] max-md:flex-col max-md:gap-4">
                    <div class="shrink-0">
                        <img src="/template/images/page/ready.png" alt="" class="w-[74px]" />
                    </div>
                    <div>
                        <h2 class="mb-[10px] text-[36px] font-bold leading-[45px] text-white max-md:text-[29px] max-md:leading-[40px]">Ready to adventure and enjoy natural</h2>
                        <p class="text-[18px] leading-[32px] text-white/50">Explore the world's most breathtaking safari destinations with us</p>
                    </div>
                </div>
                <img src="/template/images/page/vector4.png" alt="" class="absolute right-[22%] hidden w-auto animate-spin xl:block" style="animation-duration: 8s;" />
                <div class="shrink-0 max-md:mt-5">
                    <a href="#" class="inline-block rounded-[5px] bg-white px-9 py-[23px] text-[15px] font-semibold uppercase tracking-[0.05em] text-[#4DA528] transition-all hover:bg-[#FF970D] hover:text-white">Let's get started</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Grid -->
    <section class="py-[120px] max-lg:py-20">
        <div class="mx-auto max-w-[1320px] px-4 sm:px-6 lg:px-8">
            <div class="mb-10 flex flex-wrap items-end justify-between gap-4">
                <div>
                    <span class="font-['Yesteryear'] text-[28px] leading-[46px] text-[#4DA528]">Explore the world</span>
                    <h2 class="text-[36px] font-semibold text-[#081E2A]">
                        Great opportunity for <br /><span class="font-['Yesteryear'] text-[#64748B]">adventure</span> &amp; travels
                    </h2>
                </div>
                <p class="max-w-[400px] text-[16px] text-[#4F545A]">
                    Pellentesque habitant morbi tristique senectus netus et malesuada fames ac turpis egestas. Aliquam viverra arcu.
                </p>
            </div>

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-lg bg-white p-8 shadow-sm transition-shadow hover:shadow-md">
                    <div class="mb-6 text-[#4DA528]">
                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 30V15l15-10 15 10v15a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2z"/><path d="M15 32V20h10v12"/></svg>
                    </div>
                    <h4 class="mb-2 text-lg font-semibold text-[#081E2A]"><a href="#" class="hover:text-[#4DA528]">Adventure Plan</a></h4>
                    <p class="mb-4 text-[16px] leading-[30px] text-[#4F545A]">Pellentesque habitant morbi tristique senectus netus et malesuada fames ac</p>
                    <a href="#" class="inline-flex items-center gap-1 text-sm font-semibold text-[#4DA528] transition-colors hover:text-[#FF970D]">
                        Booking Now
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>
                </div>
                <div class="rounded-lg bg-white p-8 shadow-sm transition-shadow hover:shadow-md">
                    <div class="mb-6 text-[#4DA528]">
                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 5C11.716 5 5 11.716 5 20s6.716 15 15 15 15-6.716 15-15S28.284 5 20 5z"/><path d="M20 12a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2"/></svg>
                    </div>
                    <h4 class="mb-2 text-lg font-semibold text-[#081E2A]"><a href="#" class="hover:text-[#4DA528]">Secure Camping</a></h4>
                    <p class="mb-4 text-[16px] leading-[30px] text-[#4F545A]">Pellentesque habitant morbi tristique senectus netus et malesuada fames ac</p>
                    <a href="#" class="inline-flex items-center gap-1 text-sm font-semibold text-[#4DA528] transition-colors hover:text-[#FF970D]">
                        Booking Now
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>
                </div>
                <div class="rounded-lg bg-white p-8 shadow-sm transition-shadow hover:shadow-md">
                    <div class="mb-6 text-[#4DA528]">
                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="10" width="36" height="20" rx="2"/><circle cx="12" cy="20" r="2"/><circle cx="28" cy="20" r="2"/><path d="M8 28h24"/></svg>
                    </div>
                    <h4 class="mb-2 text-lg font-semibold text-[#081E2A]"><a href="#" class="hover:text-[#4DA528]">Trailers &amp; RV Sports</a></h4>
                    <p class="mb-4 text-[16px] leading-[30px] text-[#4F545A]">Pellentesque habitant morbi tristique senectus netus et malesuada fames ac</p>
                    <a href="#" class="inline-flex items-center gap-1 text-sm font-semibold text-[#4DA528] transition-colors hover:text-[#FF970D]">
                        Booking Now
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>
                </div>
                <div class="rounded-lg bg-white p-8 shadow-sm transition-shadow hover:shadow-md">
                    <div class="mb-6 text-[#4DA528]">
                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 5 6 35h28L20 5z"/><path d="M20 15v8"/><circle cx="20" cy="27" r="1.5"/></svg>
                    </div>
                    <h4 class="mb-2 text-lg font-semibold text-[#081E2A]"><a href="#" class="hover:text-[#4DA528]">Luxury cabin</a></h4>
                    <p class="mb-4 text-[16px] leading-[30px] text-[#4F545A]">Pellentesque habitant morbi tristique senectus netus et malesuada fames ac</p>
                    <a href="#" class="inline-flex items-center gap-1 text-sm font-semibold text-[#4DA528] transition-colors hover:text-[#FF970D]">
                        Booking Now
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Counter -->
    <section
        class="relative bg-fixed bg-cover bg-center py-[170px] pb-[71px]"
        style="background-image: url('/template/images/page/bg-counter.jpg')"
    >
        <div class="absolute inset-0 bg-gradient-to-r from-[rgba(0,0,0,0.93)] to-[rgba(0,0,0,0.35)]"></div>
        <div class="absolute top-0 left-0 h-[428px] w-[305px] bg-gradient-to-t from-[rgba(77,165,40,0.52)] via-[rgba(77,165,40,0.25)] to-transparent opacity-95 [clip-path:polygon(0_0,0%_100%,100%_0)]"></div>
        <div class="absolute right-0 bottom-0 h-[428px] w-[305px] bg-gradient-to-b from-transparent via-[rgba(241,176,10,0.31)] to-[rgba(241,176,10,0.45)] opacity-95 [clip-path:polygon(0_100%,100%_100%,100%_0)]"></div>

        <div class="relative z-10 mx-auto max-w-[1320px] px-4 sm:px-6 lg:px-8">
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                <div class="text-center">
                    <div class="mb-[25px] flex justify-center text-white">
                        <svg width="85" height="78" viewBox="0 0 85 78" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="1" y="10" width="83" height="58" rx="5"/><circle cx="42" cy="35" r="10"/></svg>
                    </div>
                    <div class="text-[48px] font-bold text-white">5,489</div>
                    <span class="mx-auto mt-3 block h-0.5 w-12 bg-[#4DA528]"></span>
                    <p class="mt-4 text-[16px] text-white">Happy<br />Traveller</p>
                </div>
                <div class="text-center">
                    <div class="mb-[25px] flex justify-center text-white">
                        <svg width="69" height="68" viewBox="0 0 69 68" fill="none" stroke="currentColor" stroke-width="2"><path d="M26.5 2.27C13.47 2.27 2.91 12.83 2.91 25.86c0 6.36 2.53 12.12 6.62 16.37L2.91 49.45h23.59c13.03 0 23.59-10.56 23.59-23.59S39.53 2.27 26.5 2.27z"/><path d="M29.52 49.26c1.07 9.11 8.82 16.18 18.22 16.18h18.35l-5.15-5.62c3.18-3.3 5.15-7.78 5.15-12.73 0-9.4-7.07-17.15-16.18-18.22"/><path d="M33.06 16.36c-3.05 0-5.83 2.09-6.55 4.91v0c-.73-2.83-3.5-4.91-6.55-4.91-3.62 0-6.55 2.93-6.55 6.55 0 8.19 13.1 16.38 13.1 16.38s13.11-8.19 13.11-16.38c0-3.62-2.94-6.55-6.56-6.55z"/></svg>
                    </div>
                    <div class="text-[48px] font-bold text-white">99.9%</div>
                    <span class="mx-auto mt-3 block h-0.5 w-12 bg-[#4DA528]"></span>
                    <p class="mt-4 text-[16px] text-white">Total Positive<br />Reviews</p>
                </div>
                <div class="text-center">
                    <div class="mb-[25px] flex justify-center text-white">
                        <svg width="62" height="62" viewBox="0 0 62 62" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M31 5v55"/><path d="M5 31h52"/></svg>
                    </div>
                    <div class="text-[48px] font-bold text-white">190+</div>
                    <span class="mx-auto mt-3 block h-0.5 w-12 bg-[#4DA528]"></span>
                    <p class="mt-4 text-[16px] text-white">Tour<br />Completed</p>
                </div>
                <div class="text-center">
                    <div class="mb-[25px] flex justify-center text-white">
                        <svg width="74" height="62" viewBox="0 0 74 62" fill="none" stroke="currentColor" stroke-width="1.5"><polygon points="37,5 14,57 60,57"/><circle cx="37" cy="35" r="8"/></svg>
                    </div>
                    <div class="text-[48px] font-bold text-white">5,489</div>
                    <span class="mx-auto mt-3 block h-0.5 w-12 bg-[#4DA528]"></span>
                    <p class="mt-4 text-[16px] text-white">Awards<br />Winning</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Instagram Gallery -->
    <section class="py-[120px] max-lg:py-20">
        <div class="mx-auto max-w-[1320px] px-4 sm:px-6 lg:px-8">
            <div class="mb-10 text-center">
                <span class="font-['Yesteryear'] text-[28px] leading-[46px] text-[#4DA528]">Explore the world</span>
                <h2 class="text-[36px] font-semibold text-[#081E2A]">Recent Instagram Post</h2>
            </div>
            <div class="grid grid-cols-2 gap-[22px] sm:grid-cols-3 lg:grid-cols-5">
                <a v-for="n in 5" :key="n" href="#" class="group relative overflow-hidden rounded-lg">
                    <img :src="`/template/images/gallery/${7 + n}.jpg`" alt="Instagram post" class="size-full object-cover transition-transform duration-300 group-hover:scale-110" />
                    <div class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1.5"/></svg>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- Brand Logos -->
    <section class="bg-[#F3F8F6] py-12">
        <div class="mx-auto max-w-[1320px] px-4 sm:px-6 lg:px-8">
            <div class="swiper brand-logo-tailwind overflow-hidden">
                <div class="swiper-wrapper">
                    <div v-for="n in 6" :key="n" class="swiper-slide flex items-center justify-center">
                        <img src="/template/images/page/brand-logo.png" alt="Brand logo" class="h-auto max-h-12 w-auto opacity-50 grayscale transition-all hover:opacity-100 hover:grayscale-0" />
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section (with negative margin) -->
    <section class="bg-[#F4F6F8] py-[120px] max-lg:py-20">
        <div class="mx-auto max-w-[1320px] px-4 sm:px-6 lg:px-8">
            <div class="relative -mb-[93px] flex items-center justify-between rounded-[7px] bg-[#4DA528] px-[39px] py-[35px] max-md:flex-col max-md:gap-5 max-md:text-center max-sm:px-5 max-sm:py-[30px]">
                <div class="flex items-center gap-[43px] max-md:flex-col max-md:gap-4">
                    <div class="shrink-0">
                        <img src="/template/images/page/ready.png" alt="" class="w-[74px]" />
                    </div>
                    <div>
                        <h2 class="mb-[10px] text-[36px] font-bold leading-[45px] text-white max-md:text-[29px] max-md:leading-[40px]">Ready to adventure and enjoy natural</h2>
                        <p class="text-[18px] leading-[32px] text-white/50">Explore the world's most breathtaking safari destinations with us</p>
                    </div>
                </div>
                <img src="/template/images/page/vector4.png" alt="" class="absolute right-[22%] hidden w-auto animate-spin xl:block" style="animation-duration: 8s;" />
                <div class="shrink-0 max-md:mt-5">
                    <a href="#" class="inline-block rounded-[5px] bg-white px-9 py-[23px] text-[15px] font-semibold uppercase tracking-[0.05em] text-[#4DA528] transition-all hover:bg-[#FF970D] hover:text-white">Let's get started</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Video Modal -->
    <div v-if="showVideoModal" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/90 p-5" @click="closeVideo">
        <div class="relative w-full max-w-[900px]" @click.stop>
            <button type="button" class="absolute -top-10 right-0 p-2 text-2xl text-white transition-colors hover:text-[#4DA528]" @click="closeVideo">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
            <div class="aspect-video overflow-hidden rounded-lg bg-black">
                <iframe
                    src="https://www.youtube.com/embed/n9LgeoJE4EI?autoplay=1"
                    title="YouTube video player"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen
                    class="size-full"
                ></iframe>
            </div>
        </div>
    </div>
</template>
