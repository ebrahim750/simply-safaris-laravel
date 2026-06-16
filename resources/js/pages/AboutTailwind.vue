<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import Swiper from 'swiper';
import { Autoplay } from 'swiper/modules';
import { onMounted, ref } from 'vue';
import 'swiper/css/bundle';
import { home } from '@/routes';

const showVideoModal = ref(false);

const openVideo = () => {
    showVideoModal.value = true;
};

const closeVideo = () => {
    showVideoModal.value = false;
};

onMounted(() => {
    Swiper.use([Autoplay]);

    new Swiper('.brand-logo-tailwind', {
        autoplay: { delay: 0, disableOnInteraction: false },
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
});
</script>

<template>
    <Head title="About Us" />

    <!-- Breadcrumb -->
    <section
        class="relative flex items-center bg-cover bg-center bg-no-repeat py-[137px]"
        style="background-image: url('/template/images/page/breakcrumb.jpg')"
    >
        <div
            class="absolute inset-0 bg-gradient-to-r from-[rgba(4,29,11,0.89)] to-[rgba(0,0,0,0.33)]"
        ></div>
        <div
            class="relative z-[1] mx-auto w-full max-w-[1320px] px-4 sm:px-6 lg:px-8"
        >
            <div class="text-center">
                <h1
                    class="mb-[26px] text-[45px] leading-[50px] font-semibold text-white"
                >
                    About Us
                </h1>
                <nav
                    class="flex items-center justify-center text-[18px] leading-[22.68px]"
                >
                    <Link
                        :href="home()"
                        class="relative mr-[30px] text-[#4DA528] after:absolute after:top-1/2 after:right-[-18px] after:-translate-y-1/2 after:text-[10px] after:text-white after:content-['\203A']"
                        >Home</Link
                    >
                    <span class="text-white">About Us</span>
                </nav>
                <img
                    class="absolute top-[40%] left-[7%] hidden w-auto lg:block"
                    src="/template/images/page/mask-bcrumb.png"
                    alt=""
                />
            </div>
        </div>
    </section>

    <!-- About Us Content -->
    <section class="pt-[103px] pb-[180px] max-lg:py-20">
        <div class="mx-auto max-w-[1320px] px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-start">
                <!-- Left: Images -->
                <div class="mb-10 w-full lg:mb-0 lg:w-1/2 xl:pr-[5.5em]">
                    <div class="relative pt-[23px] pl-[13px]">
                        <!-- Main image -->
                        <div class="relative max-w-[372px]">
                            <img
                                src="/template/images/about-us/about-h41.jpg"
                                alt="Safari adventure"
                                class="w-full"
                            />
                            <img
                                src="/template/images/page/shape5.1.png"
                                alt=""
                                class="absolute right-0 bottom-[-20px] left-0"
                            />
                            <!-- Quote badge -->
                            <span
                                class="absolute top-[31.7%] left-[-41%] z-20 rotate-[-90deg] bg-[#4DA528] px-[26px] py-[20px] text-[20px] font-bold whitespace-nowrap text-white"
                                style="
                                    clip-path: polygon(
                                        100% 0,
                                        100% 50%,
                                        100% 100%,
                                        0% 100%,
                                        5% 50%,
                                        0% 0%
                                    );
                                "
                                >25,000 + Customer Worldwide</span
                            >
                        </div>
                        <!-- Second image (overlapping) -->
                        <div class="absolute top-[31.5%] right-0 max-w-[349px]">
                            <img
                                src="/template/images/about-us/about-h4.jpg"
                                alt="Safari landscape"
                                class="w-full"
                            />
                            <img
                                src="/template/images/page/shape5.1.png"
                                alt=""
                                class="absolute right-0 bottom-[-20px] left-0"
                            />
                        </div>
                        <!-- 25 years badge -->
                        <div
                            class="absolute bottom-[-17.5%] left-[29%] z-30 inline-block rounded-lg border-2 border-dashed border-[#EDEEEE] bg-white px-[34px] pt-[32px] pb-[25px] text-center"
                            style="
                                animation: dot-anim-3 3s linear 0s infinite
                                    alternate;
                            "
                        >
                            <span
                                class="text-[50px] leading-[25px] font-bold text-[#4DA528]"
                                >25</span
                            >
                            <p
                                class="mt-3 text-[17px] leading-[25px] font-medium text-[#081E2A]"
                            >
                                year of<br />experience
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Right: Content -->
                <div class="w-full lg:w-1/2 lg:pl-[17px]">
                    <div class="mb-[30px]">
                        <span
                            class="font-['Yesteryear'] text-[28px] leading-[46px] text-[#4DA528]"
                            >Explore the world</span
                        >
                        <h2
                            class="mb-[18px] text-[36px] leading-[48px] font-semibold text-[#081E2A]"
                        >
                            Great opportunity for
                            <span class="font-['Yesteryear'] text-[#64748B]"
                                >adventure</span
                            >
                            &amp; travels
                        </h2>
                        <p class="text-[16px] leading-[30.8px] text-[#4F545A]">
                            Welcome to our Print 128 website! We are a
                            professional and reliable printing company that
                            offers a wide range of printing services to
                        </p>
                    </div>

                    <!-- Icon boxes -->
                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="flex gap-4">
                            <div class="relative inline-block shrink-0">
                                <div
                                    class="relative z-[2] flex h-10 items-center justify-center text-[40px] leading-[40px] text-[#4DA528]"
                                >
                                    <svg
                                        width="40"
                                        height="40"
                                        viewBox="0 0 40 40"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >
                                        <mask
                                            id="mask_1"
                                            style="mask-type: luminance"
                                            maskUnits="userSpaceOnUse"
                                            x="0"
                                            y="0"
                                            width="40"
                                            height="40"
                                        >
                                            <path
                                                d="M0 0H40V40H0V0Z"
                                                fill="white"
                                            />
                                        </mask>
                                        <g mask="url(#mask_1)">
                                            <path
                                                d="M20 23.125V38.8281H38.8281V12.2656H34.1406"
                                            />
                                            <path
                                                d="M5.85938 12.2656H1.17188V38.8281H20V23.2031"
                                            />
                                            <path
                                                d="M26.9598 9.14062H34.1406V34.1406H26.5035C23.5528 34.1406 20.933 36.0287 20 38.8281"
                                            />
                                            <path
                                                d="M13.0402 9.14062H5.85938V34.1406H13.4965C16.4472 34.1406 19.067 36.0287 20 38.8281"
                                            />
                                            <path
                                                d="M19.9999 1.17188C14.6972 1.17188 11.311 6.82758 13.815 11.5017L19.9999 23.0469L26.1848 11.5017C28.6888 6.82758 25.3025 1.17188 19.9999 1.17188Z"
                                            />
                                            <path
                                                d="M22.3438 8.20312C22.3438 9.4975 21.2944 10.5469 20 10.5469C18.7056 10.5469 17.6562 9.4975 17.6562 8.20312C17.6562 6.90875 18.7056 5.85937 20 5.85937C21.2944 5.85937 22.3438 6.90875 22.3438 8.20312Z"
                                            />
                                        </g>
                                    </svg>
                                </div>
                                <div
                                    class="absolute top-[-4px] right-[-10px] z-[1] size-[22px] rounded-full bg-[#FF970D]"
                                ></div>
                            </div>
                            <div>
                                <h6
                                    class="mb-2 text-[16px] font-semibold text-[#081E2A]"
                                >
                                    <a
                                        href="#"
                                        class="service-title relative z-10 text-[#081E2A] transition-colors duration-300"
                                        >Trusted travel guide</a
                                    >
                                </h6>
                                <p
                                    class="text-[16px] leading-[30px] text-[#4F545A]"
                                >
                                    Welcome to our Print 128 wesit! company that
                                    offers a wide r
                                </p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="relative inline-block shrink-0">
                                <div
                                    class="relative z-[2] flex h-10 items-center justify-center text-[40px] leading-[40px] text-[#4DA528]"
                                >
                                    <svg
                                        width="40"
                                        height="40"
                                        viewBox="0 0 40 40"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >
                                        <mask
                                            id="mask_2"
                                            style="mask-type: luminance"
                                            maskUnits="userSpaceOnUse"
                                            x="0"
                                            y="0"
                                            width="40"
                                            height="40"
                                        >
                                            <path
                                                d="M0 0H40V40H0V0Z"
                                                fill="white"
                                            />
                                        </mask>
                                        <g mask="url(#mask_2)">
                                            <path
                                                d="M20 23.125V38.8281H38.8281V12.2656H34.1406"
                                            />
                                            <path
                                                d="M5.85938 12.2656H1.17188V38.8281H20V23.2031"
                                            />
                                            <path
                                                d="M26.9598 9.14062H34.1406V34.1406H26.5035C23.5528 34.1406 20.933 36.0287 20 38.8281"
                                            />
                                            <path
                                                d="M13.0402 9.14062H5.85938V34.1406H13.4965C16.4472 34.1406 19.067 36.0287 20 38.8281"
                                            />
                                            <path
                                                d="M19.9999 1.17188C14.6972 1.17188 11.311 6.82758 13.815 11.5017L19.9999 23.0469L26.1848 11.5017C28.6888 6.82758 25.3025 1.17188 19.9999 1.17188Z"
                                            />
                                            <path
                                                d="M22.3438 8.20312C22.3438 9.4975 21.2944 10.5469 20 10.5469C18.7056 10.5469 17.6562 9.4975 17.6562 8.20312C17.6562 6.90875 18.7056 5.85937 20 5.85937C21.2944 5.85937 22.3438 6.90875 22.3438 8.20312Z"
                                            />
                                        </g>
                                    </svg>
                                </div>
                                <div
                                    class="absolute top-[-4px] right-[-10px] z-[1] size-[22px] rounded-full bg-[#FF970D]"
                                ></div>
                            </div>
                            <div>
                                <h6
                                    class="mb-2 text-[16px] font-semibold text-[#081E2A]"
                                >
                                    <a
                                        href="#"
                                        class="service-title relative z-10 text-[#081E2A] transition-colors duration-300"
                                        >Pesonalized Trips</a
                                    >
                                </h6>
                                <p
                                    class="text-[16px] leading-[30px] text-[#4F545A]"
                                >
                                    Welcome to our Print 128 wesit! company that
                                    offers a wide r
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Button + Profile -->
                    <div class="mt-8 flex items-center gap-6">
                        <a
                            href="#"
                            class="group inline-flex items-center gap-[10px] overflow-hidden rounded-[5px] bg-[#4DA528] px-8 py-[18px] transition-colors hover:bg-[#FF970D]"
                        >
                            <span
                                class="translate-x-[15px] text-[15px] font-semibold tracking-[0.05em] text-white uppercase transition-transform duration-300 group-hover:translate-x-0"
                                >More about us</span
                            >
                            <span
                                class="-translate-x-[15px] text-white opacity-0 transition-all duration-300 group-hover:translate-x-0 group-hover:opacity-100"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="16"
                                    height="16"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <path d="M5 12h14" />
                                    <path d="m12 5 7 7-7 7" />
                                </svg>
                            </span>
                        </a>
                        <div class="flex items-center gap-3">
                            <div class="size-12 overflow-hidden rounded-full">
                                <img
                                    src="/template/images/avata/10.jpg"
                                    alt="CEO"
                                    class="size-full object-cover"
                                />
                            </div>
                            <div>
                                <img
                                    src="/template/images/page/name.png"
                                    alt="Signature"
                                    class="h-auto w-24"
                                />
                                <span class="text-sm text-[#4DA528]"
                                    >Ceo &amp; Founder</span
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Video Section -->
    <section class="widget-video-about">
        <div class="mx-auto max-w-[1800px] px-4 sm:px-6 lg:px-8">
            <div
                class="video-h4-widget relative mb-[-14em] flex flex-col items-center overflow-hidden rounded-[18px] pt-[168px] pb-[65px]"
            >
                <div class="video-wrap">
                    <button
                        type="button"
                        class="video-box relative z-10 flex h-[134px] w-[138px] rotate-45 cursor-pointer items-center justify-center rounded-[25px] border-none bg-[#4DA528] transition-transform duration-300 hover:scale-105 max-lg:h-[100px] max-lg:w-[100px]"
                        @click="openVideo"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="30"
                            height="30"
                            viewBox="0 0 24 24"
                            fill="currentColor"
                            class="-rotate-45 text-white"
                        >
                            <polygon points="5,3 19,12 5,21" />
                        </svg>
                    </button>
                </div>
                <p
                    class="relative z-10 mt-[46px] text-center font-['Yesteryear'] text-[121px] leading-[121px] font-normal text-white max-lg:text-[80px] max-lg:leading-[100px]"
                >
                    Watch Video
                </p>
            </div>
        </div>
    </section>

    <!-- Featured Tours -->
    <section
        class="bg-[#F4F6F8] pt-[370px] pb-[218px] max-lg:pt-[250px] max-lg:pb-20"
    >
        <div class="mx-auto max-w-[1320px] px-4 sm:px-6 lg:px-8">
            <div class="mb-[30px] text-center">
                <span
                    class="font-['Yesteryear'] text-[28px] leading-[46px] text-[#4DA528]"
                    >Featured Tour</span
                >
                <h2 class="text-[36px] font-semibold text-[#081E2A]">
                    Our top this week
                </h2>
            </div>

            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                <div
                    v-for="i in 3"
                    :key="i"
                    class="overflow-hidden rounded-xl bg-white shadow-sm transition-shadow hover:shadow-md"
                >
                    <div class="relative">
                        <img
                            :src="`/template/images/travel-list/${i}.jpg`"
                            alt="Tour"
                            class="h-56 w-full object-cover"
                        />
                        <div
                            class="absolute top-4 right-4 left-4 flex items-center justify-between"
                        >
                            <span
                                class="rounded-full bg-[#4DA528] px-3 py-1 text-xs font-semibold text-white"
                                >Featured</span
                            >
                            <div class="flex gap-2">
                                <span
                                    class="flex items-center gap-1 rounded-full bg-white/90 px-2 py-1 text-xs text-gray-700"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="12"
                                        height="12"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path
                                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"
                                        />
                                    </svg>
                                    5
                                </span>
                                <span
                                    class="flex items-center gap-1 rounded-full bg-white/90 px-2 py-1 text-xs text-gray-700"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="12"
                                        height="12"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path
                                            d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"
                                        />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                    2
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="p-5">
                        <div
                            class="mb-2 flex items-center gap-1 text-sm text-[#4DA528]"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="14"
                                height="14"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"
                                />
                                <circle cx="12" cy="10" r="3" />
                            </svg>
                            United States USA
                        </div>
                        <h3 class="mb-3 text-lg font-semibold text-[#081E2A]">
                            <a
                                href="#"
                                class="service-title relative z-10 text-[#081E2A] transition-colors duration-300"
                                >6 nights From Moscow</a
                            >
                        </h3>
                        <div
                            class="mb-3 flex items-center gap-0.5 text-[#FF970D]"
                        >
                            <svg
                                v-for="j in 5"
                                :key="j"
                                width="16"
                                height="16"
                                viewBox="0 0 24 24"
                                fill="currentColor"
                            >
                                <polygon
                                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26"
                                />
                            </svg>
                            <span class="ml-2 text-xs text-gray-500"
                                >(1 Review)</span
                            >
                        </div>
                        <div class="mb-4 flex gap-4">
                            <div
                                class="flex items-center gap-1.5 text-xs text-gray-500"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="14"
                                    height="14"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 6v6l4 2" />
                                </svg>
                                <span>5 days</span>
                            </div>
                            <div
                                class="flex items-center gap-1.5 text-xs text-gray-500"
                            >
                                <svg
                                    width="21"
                                    height="16"
                                    viewBox="0 0 21 16"
                                    fill="none"
                                    stroke="#4DA528"
                                    stroke-width="1.7"
                                    stroke-miterlimit="10"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <path
                                        d="M4.34766 4.79761C4.34766 2.94013 5.85346 1.43433 7.71094 1.43433C9.56841 1.43433 11.0742 2.94013 11.0742 4.79761C11.0742 6.65508 9.56841 8.16089 7.71094 8.16089C5.85346 8.16089 4.34766 6.65508 4.34766 4.79761Z"
                                    />
                                    <path
                                        d="M9.5977 15.1797H2.46098C1.34827 15.1797 0.558268 14.0954 0.898984 13.0362C1.80408 10.222 4.57804 8.18566 7.69301 8.18566C9.17897 8.18566 10.5566 8.64906 11.6895 9.43922"
                                    />
                                    <path d="M17.1035 15.1797V9.02734" />
                                    <path d="M20.1797 12.1035H14.0273" />
                                </svg>
                                <span>12 Person</span>
                            </div>
                        </div>
                        <div
                            class="flex items-center justify-between border-t border-gray-100 pt-4"
                        >
                            <div>
                                <span class="text-xs text-gray-500">From </span>
                                <span class="text-lg font-bold text-[#4DA528]"
                                    >$169.00</span
                                >
                                <span
                                    class="ml-2 text-sm text-gray-400 line-through"
                                    >$199.00</span
                                >
                            </div>
                            <button
                                type="button"
                                class="rounded-md border border-gray-200 p-2 text-gray-400 transition-colors hover:border-[#4DA528] hover:text-[#4DA528]"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="16"
                                    height="16"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Banner (overlapping) -->
    <section class="relative z-10 -mt-[82px]">
        <div class="mx-auto max-w-[1320px] px-4 sm:px-6 lg:px-8">
            <div
                class="relative flex items-center justify-between rounded-[7px] bg-[#4DA528] px-[39px] py-[35px] max-md:flex-col max-md:gap-5 max-md:text-center max-sm:px-5 max-sm:py-[30px]"
            >
                <div
                    class="flex items-center gap-[43px] max-md:flex-col max-md:gap-4"
                >
                    <div class="shrink-0">
                        <img
                            src="/template/images/page/ready.png"
                            alt=""
                            class="w-[74px]"
                        />
                    </div>
                    <div>
                        <h2
                            class="mb-[10px] text-[36px] leading-[45px] font-bold text-white max-md:text-[29px] max-md:leading-[40px]"
                        >
                            Ready to adventure and enjoy natural
                        </h2>
                        <p class="text-[18px] leading-[32px] text-white/50">
                            Lorem ipsum dolor sit amet, consectetur notted
                            adipisicin
                        </p>
                    </div>
                </div>
                <img
                    src="/template/images/page/vector4.png"
                    alt=""
                    class="absolute right-[22%] hidden w-auto animate-spin xl:block"
                    style="animation-duration: 8s"
                />
                <div class="shrink-0 max-md:mt-5">
                    <a
                        href="#"
                        class="inline-block rounded-[5px] bg-white px-9 py-[23px] text-[15px] font-semibold tracking-[0.05em] text-[#4DA528] uppercase transition-all hover:bg-[#FF970D] hover:text-white"
                        >Let's get started</a
                    >
                </div>
            </div>
        </div>
    </section>

    <!-- Services Grid -->
    <section class="py-[120px] max-lg:py-20">
        <div class="mx-auto max-w-[1320px] px-4 sm:px-6 lg:px-8">
            <div class="mb-10 flex flex-wrap items-end justify-between gap-4">
                <div class="md:w-7/12">
                    <span
                        class="font-['Yesteryear'] text-[28px] leading-[46px] text-[#4DA528]"
                        >Explore the world</span
                    >
                    <h2 class="text-[36px] font-semibold text-[#081E2A]">
                        Great opportunity for <br /><span
                            class="font-['Yesteryear'] text-[#64748B]"
                            >adventure</span
                        >
                        &amp; travels
                    </h2>
                </div>
                <p
                    class="text-[16px] leading-[30.8px] text-[#4F545A] md:w-5/12"
                >
                    Pellentesque habitant morbi tristique senectus netus et
                    malesuada fames ac turp egestas. Aliquam viverra arcu. Donec
                    aliquet blandit enim feugiat mattis.
                </p>
            </div>

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div
                    class="service-card relative overflow-hidden rounded-lg p-8"
                >
                    <div
                        class="service-icon relative z-10 mb-6 text-[40px] text-[#4DA528] transition-colors duration-300"
                    >
                        <svg
                            width="40"
                            height="40"
                            viewBox="0 0 40 40"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M20 5 5 22l2 3h26l2-3L20 5z" />
                            <path d="M5 22v13h30V22" />
                        </svg>
                    </div>
                    <h4 class="mb-2 text-lg font-semibold text-[#081E2A]">
                        <a
                            href="#"
                            class="service-title relative z-10 text-[#081E2A] transition-colors duration-300"
                            >Adventure Plan</a
                        >
                    </h4>
                    <p
                        class="service-desc relative z-10 mb-4 text-[16px] leading-[30px] text-[#4F545A] transition-colors duration-300"
                    >
                        Pellentesque habitant morbi tristique senectus netus et
                        malesuada fames ac
                    </p>
                    <a
                        href="#"
                        class="service-link relative z-10 inline-flex items-center gap-1 text-sm font-semibold text-[#4DA528] transition-colors duration-300"
                    >
                        Booking Now
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="14"
                            height="14"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M5 12h14" />
                            <path d="m12 5 7 7-7 7" />
                        </svg>
                    </a>
                </div>
                <div
                    class="service-card relative overflow-hidden rounded-lg p-8"
                >
                    <div
                        class="service-icon relative z-10 mb-6 text-[40px] text-[#4DA528] transition-colors duration-300"
                    >
                        <svg
                            width="40"
                            height="40"
                            viewBox="0 0 40 40"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M12 20c0-4.4 3.6-8 8-8s8 3.6 8 8" />
                            <path d="M8 34h24" />
                            <path d="M8 34c0-8.8 7.2-12 12-12s12 3.2 12 12" />
                        </svg>
                    </div>
                    <h4 class="mb-2 text-lg font-semibold text-[#081E2A]">
                        <a
                            href="#"
                            class="service-title relative z-10 text-[#081E2A] transition-colors duration-300"
                            >Secure Camping</a
                        >
                    </h4>
                    <p
                        class="service-desc relative z-10 mb-4 text-[16px] leading-[30px] text-[#4F545A] transition-colors duration-300"
                    >
                        Pellentesque habitant morbi tristique senectus netus et
                        malesuada fames ac
                    </p>
                    <a
                        href="#"
                        class="service-link relative z-10 inline-flex items-center gap-1 text-sm font-semibold text-[#4DA528] transition-colors duration-300"
                    >
                        Booking Now
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="14"
                            height="14"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M5 12h14" />
                            <path d="m12 5 7 7-7 7" />
                        </svg>
                    </a>
                </div>
                <div
                    class="service-card relative overflow-hidden rounded-lg p-8"
                >
                    <div
                        class="service-icon relative z-10 mb-6 text-[40px] text-[#4DA528] transition-colors duration-300"
                    >
                        <svg
                            width="69"
                            height="55"
                            viewBox="0 0 69 42"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path
                                d="M49.7274 35.8052H42.7663M49.7274 35.8052C49.7274 38.6871 52.0663 41.026 54.9482 41.026C57.83 41.026 60.1689 38.6871 60.1689 35.8052M49.7274 35.8052C49.7274 32.9234 52.0663 30.5845 54.9482 30.5845C57.83 30.5845 60.1689 32.9234 60.1689 35.8052M42.7663 35.8052H18.4026M42.7663 35.8052V11.4416M42.7663 35.8052H32.3247V11.4416H42.7663M42.7663 11.4416H53.2079L58.4287 21.8831M42.7663 11.4416H55.338C57.7152 11.4416 59.3928 9.1131 58.6445 6.8612L57.482 3.38068C57.0086 1.95714 55.6791 1 54.179 1H1V35.8052H7.96105M58.4287 21.8831L64.9407 24.4901C66.2633 25.0156 67.13 26.2999 67.13 27.72V32.3247C67.13 34.2494 65.5742 35.8052 63.6495 35.8052H60.1689M58.4287 21.8831H42.7663H32.3247M18.4026 35.8052C18.4026 38.6871 16.0637 41.026 13.1818 41.026C10.3 41.026 7.96105 38.6871 7.96105 35.8052M18.4026 35.8052C18.4026 32.9234 16.0637 30.5845 13.1818 30.5845C10.3 30.5845 7.96105 32.9234 7.96105 35.8052M7.96105 21.8831H25.3637V11.4416H7.96105V21.8831Z"
                            />
                        </svg>
                    </div>
                    <h4 class="mb-2 text-lg font-semibold text-[#081E2A]">
                        <a
                            href="#"
                            class="service-title relative z-10 text-[#081E2A] transition-colors duration-300"
                            >Trailers &amp; RV Sports</a
                        >
                    </h4>
                    <p
                        class="service-desc relative z-10 mb-4 text-[16px] leading-[30px] text-[#4F545A] transition-colors duration-300"
                    >
                        Pellentesque habitant morbi tristique senectus netus et
                        malesuada fames ac
                    </p>
                    <a
                        href="#"
                        class="service-link relative z-10 inline-flex items-center gap-1 text-sm font-semibold text-[#4DA528] transition-colors duration-300"
                    >
                        Booking Now
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="14"
                            height="14"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M5 12h14" />
                            <path d="m12 5 7 7-7 7" />
                        </svg>
                    </a>
                </div>
                <div
                    class="service-card relative overflow-hidden rounded-lg p-8"
                >
                    <div
                        class="service-icon relative z-10 mb-6 text-[40px] text-[#4DA528] transition-colors duration-300"
                    >
                        <svg
                            width="40"
                            height="40"
                            viewBox="0 0 40 40"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path
                                d="M5 30V15l15-10 15 10v15a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2z"
                            />
                            <path d="M15 32V20h10v12" />
                        </svg>
                    </div>
                    <h4 class="mb-2 text-lg font-semibold text-[#081E2A]">
                        <a
                            href="#"
                            class="service-title relative z-10 text-[#081E2A] transition-colors duration-300"
                            >Luxury cabin</a
                        >
                    </h4>
                    <p
                        class="service-desc relative z-10 mb-4 text-[16px] leading-[30px] text-[#4F545A] transition-colors duration-300"
                    >
                        Pellentesque habitant morbi tristique senectus netus et
                        malesuada fames ac
                    </p>
                    <a
                        href="#"
                        class="service-link relative z-10 inline-flex items-center gap-1 text-sm font-semibold text-[#4DA528] transition-colors duration-300"
                    >
                        Booking Now
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="14"
                            height="14"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M5 12h14" />
                            <path d="m12 5 7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Counter -->
    <section
        class="relative bg-cover bg-fixed bg-center py-[170px] pb-[71px]"
        style="background-image: url('/template/images/page/bg-counter.jpg')"
    >
        <div
            class="absolute inset-0 bg-gradient-to-r from-[rgba(0,0,0,0.93)] to-[rgba(0,0,0,0.35)]"
        ></div>
        <div
            class="absolute top-0 left-0 h-[428px] w-[305px] opacity-95"
            style="
                background: linear-gradient(
                    0deg,
                    rgba(241, 176, 10, 0.45),
                    rgba(255, 183, 0, 0.31),
                    rgba(23, 38, 61, 0)
                );
                clip-path: polygon(0 0, 0% 100%, 100% 0);
            "
        ></div>
        <div
            class="absolute right-0 bottom-0 h-[428px] w-[305px] opacity-95"
            style="
                background: linear-gradient(
                    262deg,
                    rgba(77, 165, 40, 0.52),
                    rgba(77, 165, 40, 0.25),
                    rgba(23, 38, 61, 0)
                );
                clip-path: polygon(0 100%, 100% 100%, 100% 0);
            "
        ></div>

        <div class="relative z-10 mx-auto max-w-[1320px] px-4 sm:px-6 lg:px-8">
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                <div class="tf-counter-box text-center">
                    <div
                        class="counter-icon mb-[25px] flex justify-center text-white"
                    >
                        <svg
                            width="85"
                            height="78"
                            viewBox="0 0 85 78"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <g clip-path="url(#tc_clip1)">
                                <path
                                    d="M0.0001297 62.7258V40.8836H13.8123L23.6738 56.5469V30.7932C21.8717 30.3047 20.5539 28.7854 20.5539 26.9907C20.5539 25.1959 21.8719 23.6766 23.6738 23.1882V19.6274H26.164V23.0228H34.0214L42.2742 20.2923C40.5417 19.4812 39.2295 18.1339 38.5631 16.4693L37.0245 12.6269C36.3224 10.8736 36.4162 8.97097 37.2889 7.26997C38.1617 5.56883 39.7044 4.28055 41.6335 3.64238C45.629 2.32045 50.0632 4.19982 51.5178 7.83194L52.8295 11.1081H56.9344V13.3717H53.4829C53.6137 14.5576 53.4036 15.7347 52.9075 16.8001C53.9147 16.5731 54.9711 16.6649 55.929 17.0709"
                                    stroke="currentColor"
                                    stroke-width="2.5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                                <path
                                    d="M55.929 17.0709L65.7933 16.4479L69.3985 24.9877L67.0736 25.7987L64.1378 18.8444L58.2401 19.2907L66.2437 38.2462L70.9088 34.8833L67.9658 27.9121L70.2907 27.1011L73.8887 35.6241L67.2858 40.3839C68.9118 43.0765 71.0927 47.0103 67.9751 52.0045L75.2358 68.2575V71.7799H85V74.0435H0.0001297V64.9892H2.49036V71.7799H55.9091L43.2514 61.9348H18.818V59.6712H22.7718L12.3683 43.1471H2.49036V62.7258H0.0001297Z"
                                    stroke="currentColor"
                                    stroke-width="2.5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </g>
                            <defs>
                                <clipPath id="tc_clip1">
                                    <rect
                                        width="85"
                                        height="77.2632"
                                        fill="white"
                                        transform="matrix(-1 0 0 1 85 0)"
                                    />
                                </clipPath>
                            </defs>
                        </svg>
                    </div>
                    <div class="text-[48px] font-bold text-white">5,489</div>
                    <span
                        class="mx-auto mt-3 block h-0.5 w-12 bg-[#4DA528]"
                    ></span>
                    <p class="mt-4 text-[16px] text-white">
                        Happy<br />Traveller
                    </p>
                </div>
                <div class="tf-counter-box text-center">
                    <div
                        class="counter-icon mb-[25px] flex justify-center text-white"
                    >
                        <svg
                            width="69"
                            height="68"
                            viewBox="0 0 69 68"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <mask
                                id="tc_m2"
                                style="mask-type: luminance"
                                maskUnits="userSpaceOnUse"
                                x="0"
                                y="0"
                                width="69"
                                height="68"
                            >
                                <path
                                    d="M0.947388 0.30294H68.0527V67.4082H0.947388V0.30294Z"
                                    fill="white"
                                />
                            </mask>
                            <g mask="url(#tc_m2)">
                                <path
                                    d="M26.505 2.26924C13.4757 2.26924 2.91333 12.8315 2.91333 25.8609C2.91333 32.2223 5.44315 37.9842 9.53644 42.2273L2.91333 49.4526H26.505C39.5343 49.4526 50.0967 38.8902 50.0967 25.8609C50.0967 12.8315 39.5343 2.26924 26.505 2.26924Z"
                                    stroke="currentColor"
                                    stroke-width="3"
                                />
                                <path
                                    d="M29.5166 49.2619C30.5896 58.3735 38.3375 65.4424 47.7375 65.4424H66.0866L60.9352 59.8227C64.1191 56.5225 66.0866 52.041 66.0866 47.0933C66.0866 37.6932 59.0178 29.9454 49.9061 28.8724"
                                    stroke="currentColor"
                                    stroke-width="3"
                                />
                                <path
                                    d="M33.0583 16.3586C30.0046 16.3586 27.2325 18.4469 26.5051 21.2735V21.2734C25.7776 18.4469 23.0054 16.3586 19.9518 16.3586C16.3324 16.3586 13.3986 19.2924 13.3986 22.9118C13.3986 31.1034 26.5051 39.2949 26.5051 39.2949C26.5051 39.2949 39.6115 31.1034 39.6115 22.9118C39.6115 19.2924 36.6777 16.3586 33.0583 16.3586Z"
                                    stroke="currentColor"
                                    stroke-width="3"
                                />
                            </g>
                        </svg>
                    </div>
                    <div class="text-[48px] font-bold text-white">99.9%</div>
                    <span
                        class="mx-auto mt-3 block h-0.5 w-12 bg-[#4DA528]"
                    ></span>
                    <p class="mt-4 text-[16px] text-white">
                        Total Positive<br />Reviews
                    </p>
                </div>
                <div class="tf-counter-box text-center">
                    <div
                        class="counter-icon mb-[25px] flex justify-center text-white"
                    >
                        <svg
                            width="62"
                            height="62"
                            viewBox="0 0 62 62"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M18.5935 13.1632C18.5935 9.99769 16.0182 7.42236 12.8527 7.42236C9.68721 7.42236 7.11176 9.99769 7.11176 13.1632C7.11176 16.3287 9.68709 18.904 12.8526 18.904C16.0181 18.904 18.5935 16.3287 18.5935 13.1632ZM12.8526 15.2753C11.6879 15.2753 10.7405 14.3279 10.7405 13.1632C10.7405 11.9985 11.6879 11.0511 12.8526 11.0511C14.0172 11.0511 14.9647 11.9985 14.9647 13.1632C14.9647 14.3279 14.0172 15.2753 12.8526 15.2753Z"
                                fill="currentColor"
                            />
                            <path
                                d="M61.9985 30.1427C61.9699 29.4278 61.5237 28.7965 60.8594 28.5308L41.5731 20.8162C41.014 20.5927 40.3806 20.6609 39.882 20.9983C39.3834 21.3358 39.0848 21.8986 39.0848 22.5008V49.1118C36.8673 49.7672 35.148 51.5914 34.6425 53.8686H8.97854C7.04404 53.8686 5.4701 52.2947 5.4701 50.3602C5.4701 48.4257 7.04404 46.8518 8.97854 46.8518H25.3964C29.1293 46.8518 32.1663 43.8148 32.1663 40.0819C32.1663 36.349 29.1293 33.312 25.3964 33.312H16.0524L23.8586 20.2611C25.1816 18.0492 25.881 15.5177 25.881 12.9404C25.881 5.80508 20.0759 0 12.9405 0C5.80521 0 0 5.80496 0 12.9403V12.9866C0 15.5292 0.681958 18.0315 1.97207 20.2225L11.2895 36.0468C11.5802 36.5141 12.1159 36.9451 12.8468 36.9406H25.3966C27.1286 36.9406 28.5377 38.3497 28.5377 40.0818C28.5377 41.8138 27.1286 43.2229 25.3966 43.2229H8.97854C5.04308 43.2229 1.84124 46.4247 1.84124 50.3602C1.84124 54.2956 5.04308 57.4975 8.97854 57.4975H34.8937C35.8039 59.9304 38.1517 61.6673 40.8982 61.6673C44.4322 61.6673 47.3073 58.7923 47.3073 55.2582C47.3073 52.3548 45.3661 49.8976 42.7136 49.1127V40.9138L60.9922 31.841C61.6331 31.5227 62.0273 30.8576 61.9985 30.1427ZM5.09901 18.3813C4.13715 16.7476 3.62874 14.8821 3.62874 12.9864V12.9402C3.62874 7.80579 7.80592 3.6285 12.9404 3.6285C18.0749 3.6285 22.2521 7.80579 22.2521 12.9402C22.2521 14.8617 21.7307 16.749 20.7444 18.3982L12.8653 31.5711L5.09901 18.3813ZM43.6784 55.2581C43.6784 56.7912 42.431 58.0383 40.8982 58.0383C39.3654 58.0383 38.118 56.7911 38.118 55.2581C38.118 53.7269 39.3624 52.481 40.8928 52.478C40.8951 52.478 40.8973 52.4784 40.8993 52.4784C40.9013 52.4784 40.9033 52.4782 40.9052 52.4782C42.435 52.4817 43.6784 53.7273 43.6784 55.2581ZM42.7136 36.8623V25.1808L55.7456 30.3936L42.7136 36.8623Z"
                                fill="currentColor"
                            />
                        </svg>
                    </div>
                    <div class="text-[48px] font-bold text-white">190+</div>
                    <span
                        class="mx-auto mt-3 block h-0.5 w-12 bg-[#4DA528]"
                    ></span>
                    <p class="mt-4 text-[16px] text-white">
                        Tour<br />Completed
                    </p>
                </div>
                <div class="tf-counter-box text-center">
                    <div
                        class="counter-icon mb-[25px] flex justify-center text-white"
                    >
                        <svg
                            width="74"
                            height="62"
                            viewBox="0 0 74 62"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M73.9347 32.4115C73.888 32.373 73.8201 32.3607 73.7672 32.3785L73.5604 32.4399C73.4228 32.4802 73.3039 32.5147 73.1861 32.562C71.3254 33.3013 69.8604 34.5679 68.7075 36.4341C68.4228 36.8952 68.2023 37.3744 68.0266 37.8524C68.3147 36.2447 68.3814 35.6183 68.4324 33.8193C68.4348 33.7438 68.4348 33.6671 68.4348 33.5908C68.4348 33.3827 68.4348 33.1861 68.4803 33.0067C68.5348 32.7942 68.6444 32.5822 68.7505 32.377C68.7804 32.319 68.8103 32.2609 68.8349 32.2099C68.8435 32.1999 68.8711 32.183 68.8819 32.1814C69.414 32.1001 69.8846 31.8742 70.3208 31.4908C70.7604 31.1026 71.1219 30.6274 71.3955 30.078C72.3774 28.1089 72.8688 25.9325 72.8563 23.6099C72.8552 23.3858 72.8289 23.1677 72.8 22.9305C72.7868 22.8242 72.7734 22.7155 72.7623 22.6029C72.756 22.5412 72.718 22.4863 72.6629 22.4596C72.6085 22.4332 72.5429 22.4351 72.488 22.4683"
                                fill="currentColor"
                                opacity="0.5"
                            />
                            <path
                                d="M72.4006 22.5209C72.3507 22.5506 72.3127 22.5738 72.2767 22.5998C71.4391 23.2002 70.7003 23.9477 70.0806 24.8209C69.0694 26.2482 68.4812 27.8621 68.3228 29.6375C68.3071 29.4396 68.2911 29.2417 68.2751 29.0438L68.2696 28.972C68.2074 28.1961 68.1433 27.3937 68.091 26.6047C68.0778 26.4049 68.0394 26.2105 68.0023 26.0225C67.9307 25.661 67.863 25.3195 67.9713 24.9617C67.996 24.8808 68.0168 24.7994 68.0375 24.7182C68.0887 24.5169 68.336 24.207 68.7157 23.8681L68.7299 23.8553C68.87 23.7366 69.5893 23.0785 69.9973 21.7768C70.4115 20.4543 70.3802 19.0725 70.3574 18.0635L70.3568 18.0366C70.3264 16.6495 70.1765 15.6044 69.8708 14.6471C69.8313 14.5247 69.7844 14.4039 69.7347 14.2771C69.7089 14.211 69.6826 14.1428 69.6555 14.0714C69.6332 14.0109 69.5798 13.9669 69.5173 13.9566L69.4867 13.9538C69.4325 13.9538 69.3819 13.9781 69.3476 14.02L69.0506 14.3819C67.6269 16.1183 66.9274 18.1549 66.9712 20.4352C66.9788 20.8368 67.0089 21.2487 67.0621 21.674C66.7002 20.4252 66.2759 19.2096 65.7932 18.0401C65.7832 18.0145 65.7767 17.9828 65.776 17.954"
                                fill="currentColor"
                                opacity="0.35"
                            />
                            <path
                                d="M24.9672 59.363C23.767 59.0979 22.5732 58.7115 21.3171 58.182C21.3021 58.175 21.2648 58.1329 21.2532 58.095C21.128 57.6682 21.086 57.2383 21.1246 56.7805C21.3706 53.8579 19.6302 50.7783 16.9869 49.4581C16.9307 49.4295 16.8721 49.4049 16.8046 49.3772L16.7419 49.3513C16.6824 49.3258 16.6098 49.3364 16.5618 49.375C16.5099 49.4151 16.4841 49.4804 16.4946 49.5458L16.567 49.9939C16.6312 50.3905 16.694 50.7785 16.7543 51.1669L16.7695 51.2686C16.9392 52.3724 17.1147 53.5138 17.4565 54.6104C17.765 55.5997 18.2409 56.3804 18.9029 56.9786C17.5044 56.154 16.2012 55.1629 15.0177 54.0231C14.9928 53.9992 14.9633 53.9358 14.959 53.8835L14.9273 53.1013C14.9271 53.0735 14.9268 52.9997 14.9425 52.9631C15.5255 51.605 15.6748 50.0763 15.3994 48.2899C15.1338 46.567 14.3982 44.9826 13.2127 43.5806C13.074 43.4164 12.9516 43.3333 12.8488 43.3333C12.8238 43.3333 12.8012 43.3383 12.7834 43.3476C12.7288 43.3747 12.6919 43.4272 12.6847 43.4878L12.653 43.7607C12.6285 43.9632 12.6049 44.1621 12.5838 44.3613C12.5472 44.7122 12.5072 45.0626 12.4669 45.4131L12.4612 45.4638C12.361 46.339 12.2571 47.2442 12.2022 48.1505C12.1384 49.2077 12.1574 50.4424 12.7822 51.5846C12.7856 51.5904 12.7886 51.5965 12.7919 51.6023C11.9233 50.5161 11.1304 49.3472 10.4229 48.1085C10.3604 47.9984 10.355 47.9017 10.3667 47.7615C10.417 47.1685 10.6926 46.6866 10.9829 46.2545C11.5436 45.4176 11.883 44.4284 12.0835 43.0469C12.3638 41.115 12.0808 39.1769 11.2428 37.2861C11.2198 37.234 11.1929 37.1837 11.1641 37.1297L11.0986 37.0062C11.0673 36.9459 11.0003 36.9062 10.9297 36.9094C10.8592 36.9131 10.797 36.9578 10.7714 37.0227C10.5682 37.5354 10.3715 38.0293 10.1762 38.5195L10.0514 38.8328C9.63482 39.8791 9.16759 41.1007 8.89605 42.361C8.70463 43.2504 8.69295 44.1088 8.86081 44.9261C8.2094 43.3756 7.67392 41.7373 7.26382 40.0384C7.25918 40.0195 7.2577 39.9706 7.29832 39.8576C7.4901 39.3327 7.83937 38.8567 8.28192 38.5175C9.25069 37.7745 9.80027 36.7417 10.21 35.8295"
                                fill="currentColor"
                                opacity="0.2"
                            />
                            <path
                                d="M45.798 35.4259L53.2507 28.1614C53.5033 27.9151 53.5945 27.5463 53.4853 27.2108C53.3762 26.8749 53.0861 26.6301 52.7367 26.5794L42.4382 25.0839L37.8318 15.7518C37.6756 15.4352 37.3533 15.2349 37.0003 15.2349C36.6473 15.2349 36.3248 15.4356 36.1688 15.7518L31.564 25.0839L21.2639 26.5794C20.9147 26.6301 20.6244 26.8751 20.5153 27.2108C20.4061 27.5463 20.4971 27.9151 20.75 28.1614L28.2026 35.4259L26.4442 45.6828C26.3845 46.0306 26.5275 46.3823 26.8131 46.5896C27.0984 46.7972 27.4768 46.8246 27.7897 46.6603L37.0007 41.8183L46.2117 46.6603C46.3472 46.7317 46.4954 46.7668 46.6429 46.7668C46.8352 46.7668 47.0265 46.7071 47.188 46.5896C47.4737 46.3825 47.6169 46.0306 47.5569 45.6828L45.798 35.4259Z"
                                fill="currentColor"
                            />
                        </svg>
                    </div>
                    <div class="text-[48px] font-bold text-white">5,489</div>
                    <span
                        class="mx-auto mt-3 block h-0.5 w-12 bg-[#4DA528]"
                    ></span>
                    <p class="mt-4 text-[16px] text-white">
                        Awwards<br />Winning
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Instagram Gallery -->
    <section class="py-[120px] max-lg:py-20">
        <div class="mx-auto max-w-[1320px] px-4 sm:px-6 lg:px-8">
            <div class="mb-10 text-center">
                <span
                    class="font-['Yesteryear'] text-[28px] leading-[46px] text-[#4DA528]"
                    >Explore the world</span
                >
                <h2 class="text-[36px] font-semibold text-[#081E2A]">
                    Recent instagram Post
                </h2>
            </div>
            <div
                class="grid grid-cols-2 gap-[22px] sm:grid-cols-3 lg:grid-cols-5"
            >
                <a
                    v-for="n in 5"
                    :key="n"
                    href="#"
                    class="group relative overflow-hidden rounded-lg"
                >
                    <img
                        :src="`/template/images/gallery/${7 + n}.jpg`"
                        alt="Instagram post"
                        class="size-full object-cover transition-transform duration-300 group-hover:scale-110"
                    />
                    <div
                        class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 transition-opacity duration-300 group-hover:opacity-100"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="32"
                            height="32"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="white"
                            stroke-width="1.5"
                        >
                            <rect x="2" y="2" width="20" height="20" rx="5" />
                            <circle cx="12" cy="12" r="4" />
                            <circle cx="17.5" cy="6.5" r="1.5" />
                        </svg>
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
                    <div
                        v-for="n in 6"
                        :key="n"
                        class="swiper-slide flex items-center justify-center"
                    >
                        <img
                            src="/template/images/page/brand-logo.png"
                            alt="Brand logo"
                            class="h-auto max-h-12 w-auto opacity-50 grayscale transition-all hover:opacity-100 hover:grayscale-0"
                        />
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section (with negative margin) -->
    <section class="bg-[#F4F6F8] py-[120px] max-lg:py-20">
        <div class="mx-auto max-w-[1320px] px-4 sm:px-6 lg:px-8">
            <div
                class="relative -mb-[93px] flex items-center justify-between rounded-[7px] bg-[#4DA528] px-[39px] py-[35px] max-md:flex-col max-md:gap-5 max-md:text-center max-sm:px-5 max-sm:py-[30px]"
            >
                <div
                    class="flex items-center gap-[43px] max-md:flex-col max-md:gap-4"
                >
                    <div class="shrink-0">
                        <img
                            src="/template/images/page/ready.png"
                            alt=""
                            class="w-[74px]"
                        />
                    </div>
                    <div>
                        <h2
                            class="mb-[10px] text-[36px] leading-[45px] font-bold text-white max-md:text-[29px] max-md:leading-[40px]"
                        >
                            Ready to adventure and enjoy natural
                        </h2>
                        <p class="text-[18px] leading-[32px] text-white/50">
                            Explore the world's most breathtaking safari
                            destinations with us
                        </p>
                    </div>
                </div>
                <img
                    src="/template/images/page/vector4.png"
                    alt=""
                    class="absolute right-[22%] hidden w-auto animate-spin xl:block"
                    style="animation-duration: 8s"
                />
                <div class="shrink-0 max-md:mt-5">
                    <a
                        href="#"
                        class="inline-block rounded-[5px] bg-white px-9 py-[23px] text-[15px] font-semibold tracking-[0.05em] text-[#4DA528] uppercase transition-all hover:bg-[#FF970D] hover:text-white"
                        >Let's get started</a
                    >
                </div>
            </div>
        </div>
    </section>

    <!-- Video Modal -->
    <div
        v-if="showVideoModal"
        class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/90 p-5"
        @click="closeVideo"
    >
        <div class="relative w-full max-w-[900px]" @click.stop>
            <button
                type="button"
                class="absolute -top-10 right-0 p-2 text-2xl text-white transition-colors hover:text-[#4DA528]"
                @click="closeVideo"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>
            <div class="aspect-video overflow-hidden rounded-lg bg-black">
                <iframe
                    src="https://www.youtube.com/embed/n9LgeoJE4EI?autoplay=1"
                    title="YouTube video player"
                    frameborder="0"
                    allow="
                        accelerometer;
                        autoplay;
                        clipboard-write;
                        encrypted-media;
                        gyroscope;
                        picture-in-picture;
                    "
                    allowfullscreen
                    class="size-full"
                ></iframe>
            </div>
        </div>
    </div>
</template>

<style scoped>
.video-h4-widget {
    background-image: url('/template/images/page/video1.jpg');
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    background-attachment: fixed;
}

.video-h4-widget::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.64));
    z-index: 1;
}

.service-card {
    position: relative;
    overflow: hidden;
    border-radius: 8px;
    background-color: #ffffff;
    border: 1px solid rgba(224, 224, 224, 0.28);
    box-shadow: 0px 8px 25px 0px rgba(0, 0, 0, 0.06);
    padding: 49px 41px 49px 41px;
    transition: all 0.3s;
}

.service-card::before,
.service-card::after {
    content: '';
    position: absolute;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s;
    z-index: 1;
}

.service-card::before {
    bottom: 0;
    left: 0;
    right: 0;
    height: 238px;
    background: linear-gradient(
        180deg,
        rgba(45, 138, 6, 0.1),
        rgb(49, 132, 14)
    );
    clip-path: polygon(100% 0, 0% 100%, 100% 100%);
}

.service-card::after {
    right: 0;
    top: 0;
    width: 98px;
    height: 212px;
    background: #428e22;
    clip-path: polygon(100% 0, 4% 100%, 100% 66%);
}

.service-card:hover {
    background-color: #4da528;
}

.service-card:hover::before,
.service-card:hover::after {
    opacity: 1;
    visibility: visible;
}

.service-card:hover .service-icon,
.service-card:hover .service-title,
.service-card:hover .service-desc,
.service-card:hover .service-link {
    color: #ffffff;
}

.tf-counter-box {
    position: relative;
    z-index: 1;
    padding: 50px;
    border: 1px solid rgba(255, 255, 255, 0.17);
    border-radius: 23px;
    transition: all 0.3s ease;
}

.tf-counter-box::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 90%;
    height: 92%;
    border: 1px dashed rgba(255, 255, 255, 0.17);
    border-radius: 23px;
    transition: all 0.3s ease;
}

.tf-counter-box:hover::before {
    border-color: #4da528;
}

.counter-icon svg {
    transition: all 0.5s ease;
}

.tf-counter-box:hover .counter-icon svg {
    transform: rotateY(180deg);
}
</style>
