<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Home" />

    <!-- Hero Section -->
    <section class="relative h-[750px] overflow-hidden">
        <div class="absolute inset-0">
            <img src="/template/images/slide/slide1.jpg" alt="Safari hero" class="h-full w-full object-cover" />
            <div class="absolute inset-0 bg-black/30"></div>
        </div>
        <img src="/template/images/slide/mask-slide.png" alt="" class="pointer-events-none absolute bottom-0 left-0 w-full" />

        <div class="relative z-10 flex h-full items-center">
            <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="max-w-2xl">
                    <span class="text-lg font-light italic text-white/90">Explore the world</span>
                    <h1 class="mb-6 mt-3 text-4xl leading-tight font-bold text-white md:text-5xl lg:text-6xl">
                        Tour Travel &amp; Adventure<br />
                        <span class="text-[#66c333]">Camping</span>
                    </h1>
                    <p class="mb-8 max-w-lg text-base text-white/80 md:text-lg">
                        Discover breathtaking destinations and unforgettable experiences with Simply Safaris.
                    </p>
                    <div class="flex flex-wrap items-center gap-4">
                        <a href="#" class="inline-flex items-center gap-2 rounded bg-[#4DA528] px-6 py-3.5 font-semibold text-white transition-colors hover:bg-[#3d8420]">
                            Let's get started
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14" /><path d="m12 5 7 7-7 7" /></svg>
                        </a>
                        <a href="#" class="inline-flex items-center gap-2 font-medium text-white transition-colors hover:text-[#8dd266]">
                            Who we are
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10" /><path d="m10 8 4 4-4 4" /></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="absolute right-12 top-1/3 hidden lg:block">
            <div class="relative h-24 w-24 animate-spin" style="animation-duration:10s">
                <svg viewBox="0 0 100 100" class="h-full w-full">
                    <defs><path id="circle-home" d="M 50,50 m -37,0 a 37,37 0 1,1 74,0 a 37,37 0 1,1 -74,0" /></defs>
                    <text fill="white" font-size="10.5" font-weight="600" letter-spacing="3">
                        <textPath href="#circle-home">BOOKING * BOOKING * BOOKING *</textPath>
                    </text>
                </svg>
            </div>
        </div>
    </section>

    <!-- Search Form Widget -->
    <div class="relative z-20 -mt-12 px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-6xl">
            <div class="rounded-xl bg-white p-4 shadow-xl md:p-6">
                <form class="flex flex-col gap-4 lg:flex-row lg:items-end">
                    <div class="grid flex-1 grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <div class="relative">
                            <label class="mb-1 block text-xs font-medium text-gray-500">Destination</label>
                            <div class="flex items-center gap-2 rounded-lg border border-gray-200 bg-gray-50 px-3 py-2.5">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4DA528" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" /><circle cx="12" cy="10" r="3" /></svg>
                                <select class="w-full cursor-pointer appearance-none bg-transparent text-sm text-gray-700 outline-none">
                                    <option>Melborn, Australia</option>
                                    <option>HaNoi, Vietnam</option>
                                    <option>Tokyo, Japan</option>
                                    <option>Seoul, Korea</option>
                                </select>
                            </div>
                        </div>
                        <div class="relative">
                            <label class="mb-1 block text-xs font-medium text-gray-500">Type</label>
                            <div class="flex items-center gap-2 rounded-lg border border-gray-200 bg-gray-50 px-3 py-2.5">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4DA528" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" /></svg>
                                <select class="w-full cursor-pointer appearance-none bg-transparent text-sm text-gray-700 outline-none">
                                    <option>Booking Type</option>
                                    <option>Group Tour</option>
                                    <option>Private Tour</option>
                                    <option>Family Tour</option>
                                </select>
                            </div>
                        </div>
                        <div class="relative">
                            <label class="mb-1 block text-xs font-medium text-gray-500">Duration</label>
                            <div class="flex items-center gap-2 rounded-lg border border-gray-200 bg-gray-50 px-3 py-2.5">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4DA528" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10" /><polyline points="12 6 12 12 16 14" /></svg>
                                <select class="w-full cursor-pointer appearance-none bg-transparent text-sm text-gray-700 outline-none">
                                    <option>2-4 days</option>
                                    <option>5-7 days</option>
                                    <option>1-2 weeks</option>
                                    <option>2+ weeks</option>
                                </select>
                            </div>
                        </div>
                        <div class="relative">
                            <label class="mb-1 block text-xs font-medium text-gray-500">Guests</label>
                            <div class="flex items-center gap-2 rounded-lg border border-gray-200 bg-gray-50 px-3 py-2.5">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4DA528" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" /><circle cx="9" cy="7" r="4" /><path d="M22 21v-2a4 4 0 0 0-3-3.87" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /></svg>
                                <input type="text" value="0" class="w-full bg-transparent text-sm text-gray-700 outline-none" />
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="button" class="flex items-center gap-2 rounded-lg border border-gray-200 px-4 py-2.5 text-gray-500 transition-colors hover:border-[#4DA528] hover:text-[#4DA528]">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="21" y1="10" x2="3" y2="10" /><line x1="21" y1="6" x2="3" y2="6" /><line x1="21" y1="14" x2="3" y2="14" /><line x1="21" y1="18" x2="3" y2="18" /></svg>
                            Filters
                        </button>
                        <button type="submit" class="flex items-center gap-2 rounded-lg bg-[#4DA528] px-6 py-2.5 font-medium text-white transition-colors hover:bg-[#3d8420]">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8" /><path d="m21 21-4.3-4.3" /></svg>
                            Search
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Social Proof Bar -->
    <section class="py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center justify-center gap-4 sm:flex-row">
                <div class="flex -space-x-3">
                    <img v-for="n in 6" :key="n" :src="`/template/images/avata/${n}.jpg`" alt="" class="h-10 w-10 rounded-full border-2 border-white object-cover" />
                    <div class="flex h-10 w-10 items-center justify-center rounded-full border-2 border-white bg-[#4DA528] text-xs font-bold text-white">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14" /><path d="m12 5 7 7-7 7" /></svg>
                    </div>
                </div>
                <p class="text-sm text-gray-600">
                    <span class="font-bold text-[#4DA528]">2,500</span> people booked Tomorrowland Event in last 24 hours
                </p>
            </div>
        </div>
    </section>

    <!-- About Us Section -->
    <section class="py-16 lg:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center gap-12 lg:flex-row lg:gap-16">
                <div class="relative w-full lg:w-1/2">
                    <div class="relative overflow-hidden rounded-2xl">
                        <img src="/template/images/about-us/about-h41.jpg" alt="Travel video" class="h-auto w-full rounded-2xl" />
                        <a href="https://www.youtube.com/watch?v=n9LgeoJE4EI" target="_blank" class="absolute inset-0 flex items-center justify-center">
                            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-white shadow-lg transition-transform hover:scale-110">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="#4DA528" stroke="none"><path d="M8 5v14l11-7z" /></svg>
                            </div>
                        </a>
                    </div>
                    <img src="/template/images/about-us/vali.png" alt="" class="absolute -right-4 -bottom-4 h-20 w-20 animate-bounce" style="animation-duration:3s" />
                </div>

                <div class="w-full lg:w-1/2">
                    <span class="text-sm font-medium tracking-wider text-[#4DA528] uppercase">Explore the world</span>
                    <h2 class="mb-4 mt-3 text-3xl leading-tight font-bold text-gray-900 md:text-4xl">
                        Great opportunity for <em class="font-normal text-gray-400">adventure</em> &amp; travels
                    </h2>
                    <p class="mb-8 leading-relaxed text-gray-500">
                        Welcome to Simply Safaris! We are a professional and reliable travel company that offers a wide range of safari and adventure tours to the world's most stunning destinations.
                    </p>

                    <div class="mb-8 grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div class="flex items-start gap-4">
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-[#f0f9eb] text-[#4DA528]">
                                <svg width="28" height="28" viewBox="0 0 51 51" fill="currentColor"><path d="M37.6 9.1c0-3.3-2.7-6-6-6s-6 2.7-6 6 2.7 6 6 6 6-2.7 6-6zm-6 3c-1.7 0-3-1.3-3-3s1.3-3 3-3 3 1.3 3 3-1.4 3-3 3z" /><path d="M50.3 10.8c-1.2-1.9-3.7-2.5-5.7-1.4l-10.3 5.6H15.2V1.5c0-.8-.7-1.5-1.5-1.5H1.5C.7 0 0 .7 0 1.5v9.1c0 .8.7 1.5 1.5 1.5h10.7v37.4c0 .8.7 1.5 1.5 1.5s1.5-.7 1.5-1.5V24h9v22.5c0 3.9 4.6 5.9 7.5 3.3 2.9 2.6 7.5.5 7.5-3.3V23.3l10-6.5c2-1.3 2.6-4 .8-6z" /></svg>
                            </div>
                            <div>
                                <h6 class="mb-1 font-bold text-gray-900"><a href="#" class="transition-colors hover:text-[#4DA528]">Trusted travel guide</a></h6>
                                <p class="text-sm text-gray-500">Expert guides with years of experience in safari adventures worldwide.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-[#f0f9eb] text-[#4DA528]">
                                <svg width="28" height="28" viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 23.1V38.8H38.8V12.3H34.1" /><path d="M5.9 12.3H1.2V38.8H20V23.2" /><path d="M27 9.1H34.1V34.1H26.5C23.6 34.1 21 36 20 38.8" /><path d="M13 9.1H5.9V34.1H13.5C16.4 34.1 19.1 36 20 38.8" /><path d="M20 1.2c-5.3 0-8.7 5.7-6.2 10.3L20 23l6.2-11.5c2.5-4.7-.9-10.3-6.2-10.3z" /><circle cx="20" cy="8.2" r="2.3" /></svg>
                            </div>
                            <div>
                                <h6 class="mb-1 font-bold text-gray-900"><a href="#" class="transition-colors hover:text-[#4DA528]">Personalized Trips</a></h6>
                                <p class="text-sm text-gray-500">Tailored safari experiences designed around your preferences and schedule.</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-6">
                        <a href="#" class="inline-flex items-center gap-2 rounded bg-[#4DA528] px-6 py-3 font-semibold text-white transition-colors hover:bg-[#3d8420]">
                            More about us
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14" /><path d="m12 5 7 7-7 7" /></svg>
                        </a>
                        <div class="flex items-center gap-3">
                            <img src="/template/images/avata/10.jpg" alt="CEO" class="h-12 w-12 rounded-full object-cover" />
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Ebrahim H.</p>
                                <span class="text-xs text-[#4DA528]">CEO &amp; Founder</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex items-center gap-2 text-sm font-medium text-[#4DA528]">
                        <svg width="20" height="18" viewBox="0 0 33 30" fill="currentColor"><path d="M24.3.2c1.7 0 3.3.7 4.4 1.8 1.2 1.2 1.8 2.7 1.8 4.4 0 1-.2 2-.7 2.8-.8 1.6-4.9 4.4-5.6 7.4-1.2-2.9-4.8-5.8-5.6-7.4-.4-.9-.7-1.8-.7-2.8 0-3.4 2.8-6.2 6.2-6.2z" /><circle cx="24.3" cy="6.4" r="2.3" fill="white" /><path d="M9.4 6.8c2.4 0 4.5.9 6 2.5 1.6 1.6 2.5 3.7 2.5 6 0 1.3-.3 2.6-1 3.8-1.1 2.2-6.6 6-7.5 10.1C8.5 24.8 3 21 1.9 18.8c-.6-1.1-1-2.4-1-3.8 0-4.7 3.8-8.4 8.4-8.4z" /><circle cx="9.4" cy="15.2" r="4.2" fill="white" /></svg>
                        <span>Checkout Beautiful Places Around the World.</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tour Package Section -->
    <section class="bg-gray-50 py-16 lg:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-10 text-center">
                <span class="text-sm font-medium tracking-wider text-[#4DA528] uppercase">Explore the world</span>
                <h2 class="mt-3 text-3xl font-bold text-gray-900 md:text-4xl">
                    Amazing Featured Tour <em class="font-normal text-gray-400">Package</em> the world
                </h2>
            </div>

            <div class="mb-10 flex flex-wrap justify-center gap-2">
                <button class="rounded-full bg-[#4DA528] px-5 py-2 text-sm font-medium text-white transition-colors">New York</button>
                <button class="rounded-full border border-gray-200 bg-white px-5 py-2 text-sm font-medium text-gray-600 transition-colors hover:border-[#4DA528] hover:text-[#4DA528]">London</button>
                <button class="rounded-full border border-gray-200 bg-white px-5 py-2 text-sm font-medium text-gray-600 transition-colors hover:border-[#4DA528] hover:text-[#4DA528]">Tokyo</button>
                <button class="rounded-full border border-gray-200 bg-white px-5 py-2 text-sm font-medium text-gray-600 transition-colors hover:border-[#4DA528] hover:text-[#4DA528]">Los Angelas</button>
                <button class="rounded-full border border-gray-200 bg-white px-5 py-2 text-sm font-medium text-gray-600 transition-colors hover:border-[#4DA528] hover:text-[#4DA528]">Manila</button>
            </div>

            <div class="mb-10 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div v-for="n in 4" :key="n" class="group overflow-hidden rounded-2xl bg-white shadow-sm transition-shadow hover:shadow-lg">
                    <a href="#" class="relative block overflow-hidden">
                        <img :src="`/template/images/travel-list/${n}.jpg`" alt="Tour" class="h-52 w-full object-cover transition-transform duration-500 group-hover:scale-105" />
                        <div class="absolute top-3 left-3 right-3 flex items-start justify-between">
                            <span class="rounded-full bg-[#4DA528] px-3 py-1 text-xs font-medium text-white">Featured</span>
                            <div class="flex gap-2">
                                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-white/90 text-xs text-gray-600"><svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M20.8 7.8c-.7-2.4-2.6-4.3-5-5C14.8 2.5 13.5 2 12 2S9.2 2.5 8.2 2.8c-2.4.7-4.3 2.6-5 5C2.8 9 2.3 10.5 2.3 12s.5 3 1 4.2c.7 2.4 2.6 4.3 5 5 1 .3 2.3.8 3.8.8s2.8-.5 3.8-.8c2.4-.7 4.3-2.6 5-5 .5-1.2 1-2.7 1-4.2s-.6-3-1.1-4.2z"/></svg> 5</span>
                                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-white/90 text-xs text-gray-600"><svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg> 2</span>
                            </div>
                        </div>
                    </a>
                    <div class="p-4">
                        <span class="mb-2 inline-block rounded bg-[#f0f9eb] px-2 py-0.5 text-xs font-medium text-[#4DA528]">Bestseller</span>
                        <div class="mb-2 flex items-center gap-1 text-xs text-gray-400"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" /><circle cx="12" cy="10" r="3" /></svg> United States USA</div>
                        <h3 class="mb-2 font-bold leading-snug text-gray-900 transition-colors hover:text-[#4DA528]"><a href="#">Days and 6 nights From Moscow</a></h3>
                        <div class="mb-3 flex items-center gap-1 text-xs text-yellow-400">
                            ★★★★★ <span class="text-gray-400">(1 Review)</span>
                        </div>
                        <div class="mb-3 flex items-center justify-between text-xs text-gray-500">
                            <span class="flex items-center gap-1"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10" /><polyline points="12 6 12 12 16 14" /></svg> 5 days</span>
                            <span class="flex items-center gap-1"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" /><circle cx="9" cy="7" r="4" /><path d="M23 21v-2a4 4 0 0 0-3-3.87" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /></svg> 12 Person</span>
                        </div>
                        <div class="flex items-center justify-between border-t border-gray-100 pt-3">
                            <div><span class="text-xs text-gray-400">From</span> <span class="text-sm font-bold text-[#4DA528]">$169.00</span> <span class="text-xs text-gray-400 line-through">$199.00</span></div>
                            <button class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 text-gray-400 transition-colors hover:bg-[#4DA528] hover:text-white"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z" /></svg></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <a href="#" class="inline-flex items-center gap-2 rounded bg-[#4DA528] px-6 py-3 font-semibold text-white transition-colors hover:bg-[#3d8420]">
                    View all tour
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14" /><path d="m12 5 7 7-7 7" /></svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Activities Section -->
    <section class="relative overflow-hidden bg-gray-900 py-16 lg:py-24">
        <img src="/template/images/page/mask-activiti.png" alt="" class="pointer-events-none absolute top-0 left-0 w-full opacity-20" />
        <img src="/template/images/page/mask-print-2.png" alt="" class="pointer-events-none absolute right-0 bottom-0 opacity-20" />

        <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="mb-10 text-center text-6xl font-bold tracking-widest text-white/10 md:text-8xl">ACTIVITIES</h2>

            <div class="mb-10 flex flex-wrap justify-center gap-4">
                <button class="flex flex-col items-center gap-2 rounded-xl bg-[#4DA528] px-6 py-4 text-white transition-all">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z" /></svg>
                    <span class="text-sm">Couple camping or cabin</span>
                </button>
                <button class="flex flex-col items-center gap-2 rounded-xl bg-white/10 px-6 py-4 text-white/70 transition-all hover:bg-white/20">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" /></svg>
                    <span class="text-sm">Adventure &amp; Climbing</span>
                </button>
                <button class="flex flex-col items-center gap-2 rounded-xl bg-white/10 px-6 py-4 text-white/70 transition-all hover:bg-white/20">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" /></svg>
                    <span class="text-sm">Fishing &amp; Swimming</span>
                </button>
                <button class="flex flex-col items-center gap-2 rounded-xl bg-white/10 px-6 py-4 text-white/70 transition-all hover:bg-white/20">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2" /></svg>
                    <span class="text-sm">Mountain &amp; Hill Hiking</span>
                </button>
                <button class="flex flex-col items-center gap-2 rounded-xl bg-white/10 px-6 py-4 text-white/70 transition-all hover:bg-white/20">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13" /><polygon points="16 8 20 8 23 11 23 16 16 16 16 8" /><circle cx="5.5" cy="18.5" r="2.5" /><circle cx="18.5" cy="18.5" r="2.5" /></svg>
                    <span class="text-sm">Trailers &amp; RV Sports</span>
                </button>
            </div>

            <div class="flex flex-col overflow-hidden rounded-2xl bg-white lg:flex-row">
                <div class="w-full lg:w-1/2">
                    <img src="/template/images/about-us/about-h4.jpg" alt="Activity" class="min-h-[300px] h-full w-full object-cover" />
                </div>
                <div class="flex w-full flex-col justify-center p-8 lg:w-1/2 lg:p-10">
                    <span class="mb-2 text-sm font-medium tracking-wider text-[#4DA528] uppercase">Welcome to our Print</span>
                    <h3 class="mb-6 text-2xl font-bold text-gray-900 md:text-3xl">Real adventure &amp; enjoy your dream tours</h3>
                    <div class="mb-8 flex flex-wrap gap-6">
                        <div class="flex items-center gap-3 text-gray-600">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#f0f9eb] text-[#4DA528]"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5z" /><path d="M2 17l10 5 10-5" /><path d="M2 12l10 5 10-5" /></svg></div>
                            <span>Real adventure Feel</span>
                        </div>
                        <div class="flex items-center gap-3 text-gray-600">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#f0f9eb] text-[#4DA528]"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" /></svg></div>
                            <span>Comfort &amp; Secure trip</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <a href="#" class="flex h-12 w-12 items-center justify-center rounded-full bg-[#4DA528] text-white transition-colors hover:bg-[#3d8420]"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14" /><path d="m12 5 7 7-7 7" /></svg></a>
                        <a href="#" class="font-semibold text-gray-900 transition-colors hover:text-[#4DA528]">Get Started Today</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Offer Package Section -->
    <section class="relative overflow-hidden bg-white py-16 lg:py-24">
        <img :src="`/template/images/travel-list/1.jpg`" alt="" class="pointer-events-none absolute inset-0 h-full w-full object-cover opacity-10" />
        <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center gap-12 lg:flex-row">
                <div class="w-full lg:w-5/12">
                    <span class="text-sm font-medium tracking-wider text-[#4DA528] uppercase">Explore the world</span>
                    <h2 class="mb-4 mt-3 text-3xl leading-tight font-bold text-gray-900 md:text-4xl">
                        Amazing Featured Tour <em class="font-normal text-gray-400">Package</em> the world
                    </h2>
                    <p class="mb-8 text-gray-500">Discover exclusive tour packages with unbeatable prices and unforgettable experiences.</p>

                    <div class="mb-8 flex items-center gap-6">
                        <div class="text-center">
                            <span class="text-5xl font-bold text-[#4DA528]">25</span>
                            <span class="text-lg text-[#4DA528]">% off</span>
                        </div>
                        <p class="text-sm italic text-gray-600">Discover Great <span class="font-semibold text-[#4DA528]">Discount</span> Deals Around the World</p>
                    </div>

                    <div class="mb-8 flex items-center gap-4">
                        <div class="text-sm text-gray-600">
                            <span class="block font-bold text-gray-900">Hurry Up!</span>
                            <span>Offer Ends in:</span>
                        </div>
                        <div class="flex gap-2">
                            <div class="min-w-[56px] rounded-lg bg-white px-3 py-2 text-center shadow">
                                <div class="text-lg font-bold text-gray-900">12</div>
                                <div class="text-[10px] uppercase text-gray-400">Days</div>
                            </div>
                            <div class="min-w-[56px] rounded-lg bg-white px-3 py-2 text-center shadow">
                                <div class="text-lg font-bold text-gray-900">08</div>
                                <div class="text-[10px] uppercase text-gray-400">Hours</div>
                            </div>
                            <div class="min-w-[56px] rounded-lg bg-white px-3 py-2 text-center shadow">
                                <div class="text-lg font-bold text-gray-900">45</div>
                                <div class="text-[10px] uppercase text-gray-400">Min</div>
                            </div>
                            <div class="min-w-[56px] rounded-lg bg-white px-3 py-2 text-center shadow">
                                <div class="text-lg font-bold text-gray-900">30</div>
                                <div class="text-[10px] uppercase text-gray-400">Sec</div>
                            </div>
                        </div>
                    </div>

                    <a href="#" class="inline-flex items-center gap-2 rounded bg-[#4DA528] px-6 py-3 font-semibold text-white transition-colors hover:bg-[#3d8420]">
                        Explore More
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14" /><path d="m12 5 7 7-7 7" /></svg>
                    </a>
                </div>

                <div class="w-full lg:w-7/12">
                    <div class="mx-auto max-w-sm overflow-hidden rounded-2xl bg-white shadow-lg">
                        <a href="#" class="group relative block overflow-hidden">
                            <img src="/template/images/travel-list/2.jpg" alt="Offer Tour" class="h-56 w-full object-cover transition-transform duration-500 group-hover:scale-105" />
                            <div class="absolute top-3 left-3 right-3 flex items-start justify-between">
                                <span class="rounded-full bg-[#4DA528] px-3 py-1 text-xs font-medium text-white">Featured</span>
                                <div class="flex gap-2">
                                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-white/90 text-xs text-gray-600"><svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M20.8 7.8c-.7-2.4-2.6-4.3-5-5C14.8 2.5 13.5 2 12 2S9.2 2.5 8.2 2.8c-2.4.7-4.3 2.6-5 5C2.8 9 2.3 10.5 2.3 12s.5 3 1 4.2c.7 2.4 2.6 4.3 5 5 1 .3 2.3.8 3.8.8s2.8-.5 3.8-.8c2.4-.7 4.3-2.6 5-5 .5-1.2 1-2.7 1-4.2s-.6-3-1.1-4.2z"/></svg> 5</span>
                                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-white/90 text-xs text-gray-600"><svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg> 2</span>
                                </div>
                            </div>
                        </a>
                        <div class="p-4">
                            <span class="mb-2 inline-block rounded bg-[#f0f9eb] px-2 py-0.5 text-xs font-medium text-[#4DA528]">Bestseller</span>
                            <div class="mb-2 flex items-center gap-1 text-xs text-gray-400"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" /><circle cx="12" cy="10" r="3" /></svg> United States USA</div>
                            <h3 class="mb-2 font-bold leading-snug text-gray-900 transition-colors hover:text-[#4DA528]"><a href="#">Days and 6 nights From Moscow</a></h3>
                            <div class="mb-3 flex items-center gap-1 text-xs text-yellow-400">★★★★★ <span class="text-gray-400">(1 Review)</span></div>
                            <div class="mb-3 flex items-center justify-between text-xs text-gray-500">
                                <span class="flex items-center gap-1"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10" /><polyline points="12 6 12 12 16 14" /></svg> 5 days</span>
                                <span class="flex items-center gap-1"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" /><circle cx="9" cy="7" r="4" /><path d="M23 21v-2a4 4 0 0 0-3-3.87" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /></svg> 12 Person</span>
                            </div>
                            <div class="flex items-center justify-between border-t border-gray-100 pt-3">
                                <div><span class="text-xs text-gray-400">From</span> <span class="text-sm font-bold text-[#4DA528]">$169.00</span> <span class="text-xs text-gray-400 line-through">$199.00</span></div>
                                <button class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 text-gray-400 transition-colors hover:bg-[#4DA528] hover:text-white"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z" /></svg></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Counter / Stats Section -->
    <section class="relative py-20">
        <div class="absolute inset-0 bg-[#4DA528]"></div>
        <img src="/template/images/page/couter-top.png" alt="" class="pointer-events-none absolute top-0 left-0 w-full" />
        <img src="/template/images/page/counter-bottom.png" alt="" class="pointer-events-none absolute bottom-0 left-0 w-full" />

        <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-16 flex flex-col items-center justify-between gap-6 border-b border-white/20 pb-8 lg:flex-row">
                <div class="flex items-center gap-4">
                    <img src="/template/images/page/ready.png" alt="" class="h-14 w-14 rounded-full object-cover" />
                    <div>
                        <h3 class="text-xl font-bold text-white md:text-2xl">Ready to adventure and enjoy natural</h3>
                        <p class="text-sm text-white/70">Lorem ipsum dolor sit amet, consectetur notted adipisicin</p>
                    </div>
                </div>
                <a href="#" class="shrink-0 rounded bg-white px-6 py-3 font-semibold text-[#4DA528] transition-colors hover:bg-gray-100">Let's get started</a>
            </div>

            <div class="grid grid-cols-2 gap-8 lg:grid-cols-4">
                <div class="text-center">
                    <div class="mx-auto mb-4 h-20 w-20 text-white/80">
                        <svg viewBox="0 0 85 78" fill="currentColor"><path d="M0 62.7V40.9h13.8l9.9 15.7V30.8c-1.8-.5-3.1-2-3.1-4 0-2.3 1.8-4.1 4-4.4v-3.6h2.5v3.4h7.9l8.3-2.7c-1.7-.8-3-2.2-3.7-3.9l-1.5-3.8c-.7-1.8-.6-3.7.3-5.4.9-1.7 2.4-3 4.3-3.6 4-1.3 8.4.6 9.9 4.2l1.3 3.3h4.1v2.3h-3.5c.2 1.2 0 2.4-.4 3.5 1-.2 2.1-.2 3.1.3l1.2.5 9.6-.7 3.6 8.5-2.3.8-2.9-7-5.9.4c0 .1.1.2.2.3l8 18.8 4.7-3.4-2.9-7 2.3-.8 3.6 8.5-6.6 4.8c1.6 2.7 3.8 6.6.7 11.6l7.3 16.3v3.5H85v2.3H0v-9.1h2.5v6.8h53.4L43.3 61.9H18.8v-2.3h3.9l-10.4-16.5H2.5v19.6H0z"/></svg>
                    </div>
                    <div class="mb-2 text-4xl font-bold text-white">5489</div>
                    <div class="mx-auto mb-2 h-0.5 w-10 bg-white/40"></div>
                    <p class="text-sm text-white/80">Happy<br />Traveller</p>
                </div>
                <div class="text-center">
                    <div class="mx-auto mb-4 h-20 w-20 text-white/80">
                        <svg viewBox="0 0 69 68" fill="none" stroke="currentColor" stroke-width="3"><path d="M26.5 2.3C13.5 2.3 2.9 12.8 2.9 25.9c0 6.4 2.5 12.1 6.6 16.4L2.9 49.5h23.6c13 0 23.6-10.6 23.6-23.6S39.5 2.3 26.5 2.3z"/><path d="M29.5 49.3c1.1 9.1 8.8 16.2 18.2 16.2h18.4l-5.2-5.6c3.2-3.3 5.2-7.8 5.2-12.7 0-9.4-7.1-17.2-16.2-18.2"/><path d="M33.1 16.4c-3.1 0-5.8 2.1-6.6 5v.1c-.8-2.9-3.5-5-6.6-5-3.6 0-6.6 2.9-6.6 6.6 0 8.2 13.1 16.4 13.1 16.4s13.1-8.2 13.1-16.4c0-3.6-3-6.6-6.6-6.6z"/></svg>
                    </div>
                    <div class="mb-2 text-4xl font-bold text-white">99.9%</div>
                    <div class="mx-auto mb-2 h-0.5 w-10 bg-white/40"></div>
                    <p class="text-sm text-white/80">Total Positive<br />Reviews</p>
                </div>
                <div class="text-center">
                    <div class="mx-auto mb-4 h-20 w-20 text-white/80">
                        <svg viewBox="0 0 62 62" fill="currentColor"><path d="M18.6 13.2c0-3.2-2.6-5.7-5.7-5.7S7.1 10 7.1 13.2s2.6 5.7 5.7 5.7 5.8-2.5 5.8-5.7zm-5.7 2.1c-1.2 0-2.1-1-2.1-2.1s1-2.1 2.1-2.1 2.1 1 2.1 2.1-1 2.1-2.1 2.1z"/><path d="M62 30.1c0-1.8-2-3.1-3.7-2.3l-4.5 2.3h-9.4l-8.7 4.6c-.5.3-1.2.4-1.7.2-.6-.2-1-.7-1-1.3v-4.6l6.3-3.3c1.6-.8 2.7-2.4 3.1-4.2.3-1.5.1-3-.5-4.3l-1.8-3.8c-.6-1.2-1.6-2.1-2.9-2.5l-7.2-2.3c-.5-.2-1-.6-1.3-1.1L18.8 0l-8 6.2v9.1c0 .7.4 1.4 1 1.7l7.2 4.2v19.8c0 .7.4 1.3 1 1.6l10.4 5.7c.6.3 1.4.3 2 0l10.4-5.7c.6-.3 1-1 1-1.7v-6.1l5.2-2.7c.8-.4 1.7-.4 2.5 0l5.2 2.7v13.4c0 1.8 1.5 3.3 3.3 3.3h.7c1.8 0 3.3-1.5 3.3-3.3V30.1zM27.1 26.3l-3.8 2v-6.1l6.8-3.6 3.8 7.7h-6.8z"/></svg>
                    </div>
                    <div class="mb-2 text-4xl font-bold text-white">190+</div>
                    <div class="mx-auto mb-2 h-0.5 w-10 bg-white/40"></div>
                    <p class="text-sm text-white/80">Tour<br />Completed</p>
                </div>
                <div class="text-center">
                    <div class="mx-auto mb-4 h-20 w-20 text-white/80">
                        <svg viewBox="0 0 74 62" fill="currentColor"><path d="M45.8 35.4l7.5-7.3c.3-.3.3-.6.2-.9 0-.3-.3-.6-.6-.6l-10.3-1.5-4.6-9.3c-.2-.3-.5-.5-.8-.5s-.7.2-.9.5l-4.6 9.3-10.3 1.5c-.3 0-.6.3-.7.6 0 .3 0 .7.3.9l7.4 7.3-1.8 10.3c-.1.3.1.7.4.9.3.2.6.2.9.1l9.2-4.8 9.2 4.8c.3.1.7.1.9-.1.3-.2.4-.5.4-.9L45.8 35.4z"/></svg>
                    </div>
                    <div class="mb-2 text-4xl font-bold text-white">5489</div>
                    <div class="mx-auto mb-2 h-0.5 w-10 bg-white/40"></div>
                    <p class="text-sm text-white/80">Awards<br />Winning</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Destinations Section -->
    <section class="bg-white py-16 lg:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-10 text-center">
                <span class="text-sm font-medium tracking-wider text-[#4DA528] uppercase">Explore the world</span>
                <h2 class="mt-3 text-3xl font-bold text-gray-900 md:text-4xl">We provide top tourist destinations</h2>
            </div>
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div v-for="n in 6" :key="n" class="group relative overflow-hidden rounded-2xl">
                    <a href="#" class="relative block h-64">
                        <img :src="`/template/images/gallery/${n + 4}.jpg`" alt="Destination" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                        <span class="absolute top-4 left-4 rounded-full bg-[#4DA528] px-3 py-1 text-xs font-medium text-white">{{ n === 1 ? 3 : n === 2 ? 7 : n === 3 ? 9 : n === 4 ? 4 : 3 }} tours</span>
                        <div class="absolute right-4 bottom-4 left-4">
                            <span class="text-sm text-white/70">Nation</span>
                            <h6 class="text-lg font-bold text-white"><a href="#" class="transition-colors hover:text-[#8dd266]">{{ ['Switzerland', 'Thailand', 'UK', 'Hongkong', 'Sri Lanka', 'Singapore'][n - 1] }}</a></h6>
                            <div class="mt-2 flex items-center justify-between">
                                <span class="text-sm text-white/80">View all tours</span>
                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#4DA528] text-white"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14" /><path d="m12 5 7 7-7 7" /></svg></span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Adventures For Everyone -->
    <section class="bg-gray-50 py-16 lg:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-10">
                <span class="text-sm font-medium tracking-wider text-[#4DA528] uppercase">Explore the world</span>
                <h2 class="mt-3 text-3xl font-bold text-gray-900 md:text-4xl">Adventures for everyone</h2>
            </div>

            <div class="mb-8 flex flex-wrap gap-2 overflow-x-auto pb-2">
                <button class="flex items-center gap-2 rounded-full bg-[#4DA528] px-5 py-2.5 text-sm font-medium whitespace-nowrap text-white transition-colors">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z" /></svg>
                    Hiking &amp; Tracking
                </button>
                <button class="flex items-center gap-2 rounded-full border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium whitespace-nowrap text-gray-600 transition-colors hover:border-[#4DA528] hover:text-[#4DA528]">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12h20" /><path d="M2 12c0-3 2-5 4-5s4 2 4 5" /><path d="M14 12c0-3 2-5 4-5s4 2 4 5" /></svg>
                    River Cruises
                </button>
                <button class="flex items-center gap-2 rounded-full border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium whitespace-nowrap text-gray-600 transition-colors hover:border-[#4DA528] hover:text-[#4DA528]">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" /></svg>
                    Adventure Tour
                </button>
                <button class="flex items-center gap-2 rounded-full border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium whitespace-nowrap text-gray-600 transition-colors hover:border-[#4DA528] hover:text-[#4DA528]">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4" /><path d="M6 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2" /></svg>
                    Animals plant
                </button>
            </div>

            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                <div v-for="n in 8" :key="n" class="flex items-center gap-3 rounded-xl bg-white p-3 transition-shadow hover:shadow-md">
                    <a href="#" class="shrink-0"><img :src="`/template/images/gallery/${n + 6}.jpg`" alt="" class="h-16 w-16 rounded-lg object-cover" /></a>
                    <div class="min-w-0">
                        <span class="text-xs text-gray-400">(3 Tour)</span>
                        <h6 class="truncate text-sm font-bold text-gray-900 transition-colors hover:text-[#4DA528]"><a href="#">Switzerland</a></h6>
                        <p class="text-xs font-semibold text-[#4DA528]">$169.00</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonial Section -->
    <section class="bg-white py-16 lg:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center gap-12 lg:flex-row">
                <div class="relative w-full lg:w-5/12">
                    <div class="flex gap-4">
                        <img src="/template/images/about-us/about-h41.jpg" alt="" class="h-72 w-1/2 rounded-2xl object-cover" />
                        <img src="/template/images/about-us/about-h4.jpg" alt="" class="mt-12 h-72 w-1/2 rounded-2xl object-cover" />
                    </div>
                </div>

                <div class="w-full lg:w-7/12">
                    <div class="rounded-2xl bg-gray-50 p-8 lg:p-10">
                        <div class="mb-8">
                            <div class="mb-4">
                                <h3 class="text-xl font-bold text-gray-900">Michael Chen</h3>
                                <span class="text-sm text-[#4DA528]">Photographer</span>
                            </div>
                            <p class="mb-6 leading-relaxed text-gray-600">
                                The safari was a dream come true. Capturing wildlife in their natural habitat with expert guidance made this trip unforgettable. Highly recommended!
                            </p>
                            <div class="h-0.5 w-12 bg-[#4DA528]"></div>
                        </div>
                        <div class="flex gap-3">
                            <img :src="`/template/images/avata/${n}.jpg`" v-for="n in 3" :key="n" alt="" :class="['h-12 w-12 cursor-pointer rounded-full object-cover transition-opacity', n === 1 ? 'ring-2 ring-[#4DA528] ring-offset-2' : 'opacity-60 hover:opacity-100']" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Banner Contact Section -->
    <section class="relative overflow-hidden bg-gray-800 py-20">
        <div class="absolute inset-0">
            <img src="/template/images/page/bg-counter.jpg" alt="" class="h-full w-full object-cover opacity-30" />
        </div>
        <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center gap-12 lg:flex-row">
                <div class="w-full lg:w-7/12">
                    <span class="text-lg italic text-[#66c333]">Explore the world</span>
                    <h2 class="mb-8 mt-3 text-3xl leading-tight font-bold text-white md:text-4xl">
                        Ready to travel with real adventure &amp; enjoy natural
                    </h2>
                    <div class="flex flex-wrap items-center gap-6">
                        <a href="https://www.youtube.com/watch?v=n9LgeoJE4EI" target="_blank" class="flex h-14 w-14 items-center justify-center rounded-full bg-white transition-transform hover:scale-110">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="#4DA528" stroke="none"><path d="M8 5v14l11-7z" /></svg>
                        </a>
                        <div class="text-sm text-white/80">
                            <span>Contact us at</span>
                            <a href="mailto:info@simplysafaris.com" class="block font-medium text-[#66c333] transition-colors hover:text-[#8dd266]">info@simplysafaris.com</a>
                        </div>
                    </div>
                </div>
                <div class="flex w-full justify-center lg:w-5/12">
                    <img src="/template/images/page/ready.png" alt="" class="max-w-xs object-contain lg:max-w-sm" />
                </div>
            </div>
        </div>
    </section>

    <!-- Blog Section -->
    <section class="py-16 lg:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-10 text-center">
                <span class="text-sm font-medium tracking-wider text-[#4DA528] uppercase">Explore the world</span>
                <h2 class="mt-3 text-3xl font-bold text-gray-900 md:text-4xl">
                    Latest news &amp; articles <em class="font-normal text-gray-400">from</em> the blog
                </h2>
            </div>
            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                <div v-for="n in 3" :key="n" class="group">
                    <a href="#" class="relative mb-4 block overflow-hidden rounded-xl">
                        <img :src="`/template/images/travel-list/${n}.jpg`" alt="Blog" class="h-56 w-full object-cover transition-transform duration-500 group-hover:scale-105" />
                        <span class="absolute bottom-3 left-3 flex items-center gap-1 rounded-full bg-white/90 px-3 py-1 text-xs font-medium text-gray-700">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20" /></svg>
                            Traveling
                        </span>
                    </a>
                    <div class="mb-3 flex items-center gap-4 text-xs text-gray-400">
                        <span class="flex items-center gap-1"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2" /><line x1="16" y1="2" x2="16" y2="6" /><line x1="8" y1="2" x2="8" y2="6" /><line x1="3" y1="10" x2="21" y2="10" /></svg> 02 Apr 2021</span>
                        <span class="flex items-center gap-1"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" /></svg> Comments (03)</span>
                    </div>
                    <h3 class="mb-2 text-lg font-bold text-gray-900 transition-colors hover:text-[#4DA528]"><a href="#">The 8 best things about Touristy</a></h3>
                    <p class="mb-4 text-sm text-gray-500">Business is the activity of making or buying and selling products.</p>
                    <a href="#" class="inline-flex items-center gap-1 text-sm font-medium text-[#4DA528] transition-colors hover:text-[#3d8420]">
                        Read More
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14" /><path d="m12 5 7 7-7 7" /></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Bottom CTA Bar -->
    <section class="pb-0">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center justify-between gap-6 rounded-t-2xl bg-[#4DA528] p-6 md:flex-row md:p-8">
                <div class="flex items-center gap-4">
                    <img src="/template/images/page/ready.png" alt="" class="h-12 w-12 rounded-full object-cover" />
                    <div>
                        <h3 class="text-lg font-bold text-white">Ready to adventure and enjoy natural</h3>
                        <p class="text-sm text-white/70">Lorem ipsum dolor sit amet, consectetur notted adipisicin</p>
                    </div>
                </div>
                <a href="#" class="shrink-0 rounded bg-white px-6 py-3 font-semibold text-[#4DA528] transition-colors hover:bg-gray-100">Let's get started</a>
            </div>
        </div>
    </section>
</template>
