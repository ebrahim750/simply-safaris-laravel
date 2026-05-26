<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { SliderRange, SliderRoot, SliderThumb, SliderTrack } from 'reka-ui';
import { computed, onMounted, onUnmounted, ref } from 'vue';
import {
    animateCountTo,
    useScrollAnimate,
} from '@/composables/useScrollAnimate';

useScrollAnimate();

const activeTourTab = ref('new-york');
const activeActivityTab = ref('camping');
const activeAdventureTab = ref('hiking');

const openDropdown = ref<string | null>(null);
const showAdvancedSearch = ref(false);

const searchForm = ref({
    destination: 'Melborn, Australia',
    type: 'Booking Type',
    duration: '2-4 days',
    guests: '0',
});

const destinationOptions = [
    'Melborn, Australia',
    'HaNoi, Vietnam',
    'Tokyo, Japan',
    'Seoul, Korea',
];
const typeOptions = [
    'Booking Type',
    'Group Tour',
    'Private Tour',
    'Family Tour',
];
const durationRange = ref([2, 4]);
const durationMin = computed(() => durationRange.value[0] ?? 1);
const durationMax = computed(() => durationRange.value[1] ?? 30);
const durationDays = computed(
    () => `${durationMin.value}-${durationMax.value} days`,
);

function selectOption(
    field: 'destination' | 'type' | 'duration',
    value: string,
) {
    searchForm.value[field] = value;
    openDropdown.value = null;
}

const tourTabs = [
    { id: 'new-york', label: 'New York' },
    { id: 'london', label: 'London' },
    { id: 'tokyo', label: 'Tokyo' },
    { id: 'los-angeles', label: 'Los Angelas' },
    { id: 'manila', label: 'Manila' },
];

const activityTabs = [
    { id: 'camping', label: 'Couple camping or cabin', icon: 'icon-Group-22' },
    { id: 'climbing', label: 'Adventure & Climbing', icon: 'icon-Group-31' },
    { id: 'fishing', label: 'Fishing & Swimming', icon: 'icon-fishing-1' },
    { id: 'hiking', label: 'Mountain & Hill Hiking', icon: 'icon-Group-4' },
    { id: 'trailers', label: 'Trailers & RV Sports', icon: '' },
];

const adventureTabs = [
    { id: 'hiking', label: 'Hiking & Tracking', icon: 'icon-Group1' },
    { id: 'river', label: 'River Cruises', icon: 'icon-river-1' },
    { id: 'adventure', label: 'Adventure Tour', icon: 'icon-adventure-1' },
    { id: 'animals', label: 'Animals plant', icon: 'icon-deer-1' },
];

const destinations = [
    {
        image: '/template/images/destination/list.jpg',
        nation: 'Switzerland',
        tours: 3,
    },
    {
        image: '/template/images/destination/list1.jpg',
        nation: 'Thailand',
        tours: 7,
    },
    {
        image: '/template/images/destination/list2.jpg',
        nation: 'United Kingdom',
        tours: 9,
    },
    {
        image: '/template/images/destination/list3.jpg',
        nation: 'Hongkong',
        tours: 4,
    },
    {
        image: '/template/images/destination/list4.jpg',
        nation: 'Sri Lanka',
        tours: 3,
    },
    {
        image: '/template/images/destination/list.jpg',
        nation: 'Singapore',
        tours: 3,
    },
];

const adventures = [
    {
        image: '/template/images/tour/tour1.jpg',
        title: 'Switzerland',
        tours: 3,
        price: '$169.00',
    },
    {
        image: '/template/images/tour/tour2.jpg',
        title: 'Switzerland',
        tours: 3,
        price: '$169.00',
    },
    {
        image: '/template/images/tour/tour3.jpg',
        title: 'Switzerland',
        tours: 3,
        price: '$169.00',
    },
    {
        image: '/template/images/tour/tour4.jpg',
        title: 'Switzerland',
        tours: 3,
        price: '$169.00',
    },
    {
        image: '/template/images/tour/tour5.jpg',
        title: 'Switzerland',
        tours: 3,
        price: '$169.00',
    },
    {
        image: '/template/images/tour/tour6.jpg',
        title: 'Switzerland',
        tours: 3,
        price: '$169.00',
    },
    {
        image: '/template/images/tour/tour7.jpg',
        title: 'Switzerland',
        tours: 3,
        price: '$169.00',
    },
    {
        image: '/template/images/tour/tour8.jpg',
        title: 'Switzerland',
        tours: 3,
        price: '$169.00',
    },
];

const blogPosts = [
    {
        image: '/template/images/blog/bl1.jpg',
        category: 'Traveling',
        date: '02 Apr 2021',
        comments: '03',
        title: 'The 8 best things about Touristy',
    },
    {
        image: '/template/images/blog/bl2.jpg',
        category: 'Business',
        date: '02 Apr 2021',
        comments: '03',
        title: 'The 8 best things about Touristy',
    },
    {
        image: '/template/images/blog/bl3.jpg',
        category: 'Cavesse',
        date: '02 Apr 2021',
        comments: '03',
        title: 'The 8 best things about Touristy',
    },
];

// Countdown timer
const countdown = ref({ days: 12, hours: 8, minutes: 45, seconds: 30 });
let countdownInterval: ReturnType<typeof setInterval> | null = null;

function startCountdown() {
    const end = new Date();
    end.setDate(end.getDate() + 12);
    end.setHours(end.getHours() + 8);
    end.setMinutes(end.getMinutes() + 45);
    end.setSeconds(end.getSeconds() + 30);

    function tick() {
        const now = new Date().getTime();
        const distance = end.getTime() - now;
        if (distance <= 0) {
            countdown.value = { days: 0, hours: 0, minutes: 0, seconds: 0 };
            if (countdownInterval) clearInterval(countdownInterval);
            return;
        }
        countdown.value = {
            days: Math.floor(distance / (1000 * 60 * 60 * 24)),
            hours: Math.floor(
                (distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60),
            ),
            minutes: Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)),
            seconds: Math.floor((distance % (1000 * 60)) / 1000),
        };
    }
    tick();
    countdownInterval = setInterval(tick, 1000);
}

// Counter animations
const counter1 = ref<HTMLElement | null>(null);
const counter2 = ref<HTMLElement | null>(null);
const counter3 = ref<HTMLElement | null>(null);
const counter4 = ref<HTMLElement | null>(null);

onMounted(() => {
    // Load Swiper script dynamically
    const swiperScript = document.createElement('script');
    swiperScript.src = '/template/js/swiper-bundle.min.js';
    swiperScript.onload = () => {
        // @ts-ignore
        if (window.Swiper) {
            // Testimonials
            // @ts-ignore
            const thumbSwiper = new window.Swiper('.testimonial-image', {
                spaceBetween: 22,
                slidesPerView: 3,
                freeMode: true,
                watchSlidesProgress: true,
            });
            // @ts-ignore
            new window.Swiper('.mySwiper2', {
                speed: 1500,
                spaceBetween: 30,
                autoplay: { delay: 5000, disableOnInteraction: false },
                thumbs: { swiper: thumbSwiper },
            });
            // @ts-ignore
            new window.Swiper('.offer-package-swipper', {
                slidesPerView: 2,
                spaceBetween: 30,
                observer: true,
                observeParents: true,
                speed: 1500,
                breakpoints: {
                    0: {
                        slidesPerView: 1,
                    },
                    600: {
                        slidesPerView: 2,
                    },
                },
            });
        }
    };
    document.head.appendChild(swiperScript);

    startCountdown();

    // Counter observer
    const counterObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    if (entry.target === counter1.value)
                        animateCountTo(counter1.value!, 5489, 2000);
                    if (entry.target === counter2.value)
                        animateCountTo(counter2.value!, 99.9, 2000, '%', 1);
                    if (entry.target === counter3.value)
                        animateCountTo(counter3.value!, 190, 2000, '+');
                    if (entry.target === counter4.value)
                        animateCountTo(counter4.value!, 5489, 2000);
                    counterObserver.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.5 },
    );

    [counter1, counter2, counter3, counter4].forEach((ref) => {
        if (ref.value) counterObserver.observe(ref.value);
    });
});

onUnmounted(() => {
    if (countdownInterval) clearInterval(countdownInterval);
});
</script>

<template>
    <Head title="Home" />

    <!-- Hero -->
    <section class="slider relative">
        <div class="slider-home1 relative overflow-hidden">
            <div class="silider-image">
                <img
                    src="/template/images/slide/slide1.jpg"
                    alt="Image"
                    class="image-slide"
                />
                <img
                    src="/template/images/slide/mask-slide.png"
                    alt="Image"
                    class="mask-slide"
                />
                <img
                    src="/template/images/slide/mask-fly.png"
                    alt="Image"
                    class="mask-flane"
                />
                <div class="booking-title tf-anime-rorate">
                    <p class="booking">Booking</p>
                    <span></span>
                </div>
            </div>
            <div class="slider-content">
                <div class="tf-container">
                    <div class="row">
                        <div class="col-lg-8">
                            <span class="sub-title text-main font-yes fs-28-46"
                                >Explore the world</span
                            >
                            <h1 class="title-slide mb-32 text-white">
                                Tour Travel &amp; adventure
                                <span class="animationtext clip text-main">
                                    <span class="cd-words-wrapper">
                                        <span class="item-text is-visible"
                                            >Camping</span
                                        >
                                    </span>
                                </span>
                            </h1>
                            <p class="des mb-45 text-white">
                                Discover breathtaking destinations and
                                unforgettable experiences with Simply Safaris.
                            </p>
                            <div class="btn-group">
                                <a href="#" class="btn-main">
                                    <p class="btn-main-text">
                                        Let's get started
                                    </p>
                                    <p class="iconer">
                                        <i class="icon-arrow-right"></i>
                                    </p>
                                </a>
                                <a href="#" class="btn-w-wa"
                                    >Who we are <i class="icon-Group-13"></i
                                ></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Hero -->

    <!-- Widget Select Form -->
    <div class="mt--82 z-index4 relative">
        <div class="tf-container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="search-form-widget-slider relative">
                        <form action="#" id="search-form-slider">
                            <div class="wd-search flex">
                                <div class="form-group flex">
                                    <i class="icon-18"></i>
                                    <div class="search-bar-group">
                                        <label>Destination</label>
                                        <div
                                            class="nice-select"
                                            :class="{
                                                open:
                                                    openDropdown ===
                                                    'destination',
                                            }"
                                            tabindex="0"
                                            @click.stop="
                                                openDropdown =
                                                    openDropdown ===
                                                    'destination'
                                                        ? null
                                                        : 'destination'
                                            "
                                        >
                                            <span class="current">{{
                                                searchForm.destination
                                            }}</span>
                                            <ul class="list">
                                                <li
                                                    v-for="opt in destinationOptions"
                                                    :key="opt"
                                                    class="option"
                                                    :class="{
                                                        selected:
                                                            searchForm.destination ===
                                                            opt,
                                                    }"
                                                    @click.stop="
                                                        selectOption(
                                                            'destination',
                                                            opt,
                                                        )
                                                    "
                                                >
                                                    {{ opt }}
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group flex">
                                    <i class="icon-hiking-1-1"></i>
                                    <div class="search-bar-group">
                                        <label>Type</label>
                                        <div
                                            class="nice-select"
                                            :class="{
                                                open: openDropdown === 'type',
                                            }"
                                            tabindex="0"
                                            @click.stop="
                                                openDropdown =
                                                    openDropdown === 'type'
                                                        ? null
                                                        : 'type'
                                            "
                                        >
                                            <span class="current">{{
                                                searchForm.type
                                            }}</span>
                                            <ul class="list">
                                                <li
                                                    v-for="opt in typeOptions"
                                                    :key="opt"
                                                    class="option"
                                                    :class="{
                                                        selected:
                                                            searchForm.type ===
                                                            opt,
                                                    }"
                                                    @click.stop="
                                                        selectOption(
                                                            'type',
                                                            opt,
                                                        )
                                                    "
                                                >
                                                    {{ opt }}
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group flex">
                                    <i class="icon-time-left"></i>
                                    <div class="search-bar-group relative">
                                        <label>Duration</label>
                                        <div
                                            class="nice-select"
                                            :class="{
                                                open:
                                                    openDropdown === 'duration',
                                            }"
                                            tabindex="0"
                                            @click.stop="
                                                openDropdown =
                                                    openDropdown === 'duration'
                                                        ? null
                                                        : 'duration'
                                            "
                                        >
                                            <span class="current">{{
                                                durationDays
                                            }}</span>
                                        </div>
                                        <div
                                            v-show="openDropdown === 'duration'"
                                            class="duration-slider-panel"
                                        >
                                            <div class="slider-header flex-two">
                                                <span
                                                    >{{ durationMin }} -
                                                    {{ durationMax }} days</span
                                                >
                                                <button
                                                    class="slider-close"
                                                    @click.stop="
                                                        openDropdown = null
                                                    "
                                                >
                                                    &times;
                                                </button>
                                            </div>
                                            <SliderRoot
                                                v-model="durationRange"
                                                class="range-slider"
                                                :min="1"
                                                :max="30"
                                                :step="1"
                                                :min-steps-between-thumbs="1"
                                            >
                                                <SliderTrack
                                                    class="slider-track"
                                                >
                                                    <SliderRange
                                                        class="slider-fill"
                                                    />
                                                </SliderTrack>
                                                <SliderThumb
                                                    v-for="(
                                                        _, index
                                                    ) in durationRange"
                                                    :key="index"
                                                    class="slider-thumb"
                                                    :aria-label="
                                                        index === 0
                                                            ? 'Minimum duration'
                                                            : 'Maximum duration'
                                                    "
                                                />
                                            </SliderRoot>
                                            <div class="slider-labels flex-two">
                                                <span>1 day</span>
                                                <span>30 days</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group flex">
                                    <i class="icon-user"></i>
                                    <div class="search-bar-group">
                                        <label>Guests</label>
                                        <input
                                            type="text"
                                            v-model="searchForm.guests"
                                        />
                                    </div>
                                </div>
                                <div class="form-group flex-two">
                                    <div
                                        class="icon-icon-filter"
                                        @click="
                                            showAdvancedSearch =
                                                !showAdvancedSearch
                                        "
                                    >
                                        <i class="icon-14"></i>
                                    </div>
                                    <a href="#" class="btn-search"
                                        ><i class="icon-Vector5"></i>Search</a
                                    >
                                </div>
                            </div>
                            <div
                                class="wd-search-form"
                                :class="{ show: showAdvancedSearch }"
                            >
                                <div class="input-group-grid">
                                    <fieldset class="group-select relative">
                                        <label>Filter By Price</label>
                                        <div class="widget widget-price">
                                            <div class="slider-labels">
                                                <div class="caption flex-three">
                                                    <p class="price-range">
                                                        Price:
                                                    </p>
                                                    <div class="number-range">
                                                        <span>$100</span> -
                                                        <span>$5,000</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <fieldset
                                        class="group-select input-npd relative"
                                    >
                                        <div class="search-bar-group relative">
                                            <label>Language</label>
                                            <div
                                                class="nice-select"
                                                :class="{
                                                    open:
                                                        openDropdown ===
                                                        'language',
                                                }"
                                                tabindex="0"
                                                @click.stop="
                                                    openDropdown =
                                                        openDropdown ===
                                                        'language'
                                                            ? null
                                                            : 'language'
                                                "
                                            >
                                                <span class="current"
                                                    >English</span
                                                >
                                                <ul class="list">
                                                    <li
                                                        class="option selected"
                                                        @click.stop="
                                                            openDropdown = null
                                                        "
                                                    >
                                                        English
                                                    </li>
                                                    <li
                                                        class="option"
                                                        @click.stop="
                                                            openDropdown = null
                                                        "
                                                    >
                                                        Japanese
                                                    </li>
                                                    <li
                                                        class="option"
                                                        @click.stop="
                                                            openDropdown = null
                                                        "
                                                    >
                                                        Vietnamese
                                                    </li>
                                                    <li
                                                        class="option"
                                                        @click.stop="
                                                            openDropdown = null
                                                        "
                                                    >
                                                        Korean
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <fieldset
                                        class="group-select input-npd relative"
                                    >
                                        <div class="search-bar-group relative">
                                            <label>Month</label>
                                            <div
                                                class="nice-select"
                                                :class="{
                                                    open:
                                                        openDropdown ===
                                                        'month',
                                                }"
                                                tabindex="0"
                                                @click.stop="
                                                    openDropdown =
                                                        openDropdown === 'month'
                                                            ? null
                                                            : 'month'
                                                "
                                            >
                                                <span class="current">Any</span>
                                                <ul class="list">
                                                    <li
                                                        class="option selected"
                                                        @click.stop="
                                                            openDropdown = null
                                                        "
                                                    >
                                                        Any
                                                    </li>
                                                    <li
                                                        class="option"
                                                        @click.stop="
                                                            openDropdown = null
                                                        "
                                                    >
                                                        1 Month
                                                    </li>
                                                    <li
                                                        class="option"
                                                        @click.stop="
                                                            openDropdown = null
                                                        "
                                                    >
                                                        2 Months
                                                    </li>
                                                    <li
                                                        class="option"
                                                        @click.stop="
                                                            openDropdown = null
                                                        "
                                                    >
                                                        3 Months
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <fieldset
                                        class="group-select input-npd relative"
                                    >
                                        <div class="search-bar-group relative">
                                            <label>Duration</label>
                                            <div
                                                class="nice-select"
                                                :class="{
                                                    open:
                                                        openDropdown ===
                                                        'adv-duration',
                                                }"
                                                tabindex="0"
                                                @click.stop="
                                                    openDropdown =
                                                        openDropdown ===
                                                        'adv-duration'
                                                            ? null
                                                            : 'adv-duration'
                                                "
                                            >
                                                <span class="current">Any</span>
                                                <ul class="list">
                                                    <li
                                                        class="option selected"
                                                        @click.stop="
                                                            openDropdown = null
                                                        "
                                                    >
                                                        Any
                                                    </li>
                                                    <li
                                                        class="option"
                                                        @click.stop="
                                                            openDropdown = null
                                                        "
                                                    >
                                                        10-15 days
                                                    </li>
                                                    <li
                                                        class="option"
                                                        @click.stop="
                                                            openDropdown = null
                                                        "
                                                    >
                                                        15-30 days
                                                    </li>
                                                    <li
                                                        class="option"
                                                        @click.stop="
                                                            openDropdown = null
                                                        "
                                                    >
                                                        20-30 days
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <div class="group-check-box-wrap">
                                        <div class="checkbox">
                                            <input
                                                id="check4"
                                                type="checkbox"
                                                name="check"
                                                value="check"
                                            />
                                            <label for="check4"
                                                >Accepts Credit Cards</label
                                            >
                                        </div>
                                        <div class="checkbox">
                                            <input
                                                id="check5"
                                                type="checkbox"
                                                name="check"
                                                value="check"
                                            />
                                            <label for="check5"
                                                >Car Parking</label
                                            >
                                        </div>
                                    </div>
                                    <div class="group-check-box-wrap">
                                        <div class="checkbox">
                                            <input
                                                id="check6"
                                                type="checkbox"
                                                name="check"
                                                value="check"
                                            />
                                            <label for="check6"
                                                >Free Coupons</label
                                            >
                                        </div>
                                        <div class="checkbox">
                                            <input
                                                id="check7"
                                                type="checkbox"
                                                name="check"
                                                value="check"
                                            />
                                            <label for="check7"
                                                >Laundry Service</label
                                            >
                                        </div>
                                    </div>
                                    <div class="group-check-box-wrap">
                                        <div class="checkbox">
                                            <input
                                                id="check8"
                                                type="checkbox"
                                                name="check"
                                                value="check"
                                            />
                                            <label for="check8"
                                                >Outdoor Seating</label
                                            >
                                        </div>
                                        <div class="checkbox">
                                            <input
                                                id="check9"
                                                type="checkbox"
                                                name="check"
                                                value="check"
                                            />
                                            <label for="check9"
                                                >Reservations</label
                                            >
                                        </div>
                                    </div>
                                    <div class="group-check-box-wrap">
                                        <div class="checkbox">
                                            <input
                                                id="check10"
                                                type="checkbox"
                                                name="check"
                                                value="check"
                                            />
                                            <label for="check10"
                                                >Restaurant</label
                                            >
                                        </div>
                                        <div class="checkbox">
                                            <input
                                                id="check11"
                                                type="checkbox"
                                                name="check"
                                                value="check"
                                            />
                                            <label for="check11"
                                                >Smoking Allowed</label
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Widget Select Form -->

    <!-- Widget Aboutus -->
    <section class="about-us pb-150">
        <div class="tf-container">
            <div class="row pt-35">
                <div class="col-lg-12 wow fadeInUp flex">
                    <div class="image-list flex-three">
                        <img
                            src="/template/images/avata/6.jpg"
                            alt="Image"
                            class="item"
                        />
                        <img
                            src="/template/images/avata/5.jpg"
                            alt="Image"
                            class="item"
                        />
                        <img
                            src="/template/images/avata/4.jpg"
                            alt="Image"
                            class="item"
                        />
                        <img
                            src="/template/images/avata/3.jpg"
                            alt="Image"
                            class="item"
                        />
                        <img
                            src="/template/images/avata/1.jpg"
                            alt="Image"
                            class="item"
                        />
                        <img
                            src="/template/images/avata/2.jpg"
                            alt="Image"
                            class="item"
                        />
                        <div class="icon item flex-five text-white">
                            <i class="icon-uniE914"></i>
                        </div>
                    </div>
                    <p class="client">
                        2,500 people booked Tomorrowland Event in last 24 hours
                    </p>
                </div>
            </div>
            <div class="row pt-115">
                <div class="col-lg-6">
                    <div class="travel-video relative">
                        <img
                            src="/template/images/about-us/image-video.png"
                            alt="Image"
                            class="image-video"
                        />
                        <div class="video-wrap">
                            <a
                                href="https://www.youtube.com/watch?v=n9LgeoJE4EI"
                                class="widget-icon-video widget-videos flex-five z-index3"
                            >
                                <i class="icon-Polygon-4"></i>
                            </a>
                        </div>
                        <img
                            src="/template/images/about-us/vali.png"
                            alt="Image"
                            class="mask-video tf-anime-rorate"
                        />
                        <img
                            src="/template/images/page/enjoy.png"
                            alt="Image"
                            class="mask-enjoy"
                        />
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="inner-content-about">
                        <span
                            class="sub-title-heading text-main wow fadeInUp mb-15"
                            >Explore the world</span
                        >
                        <h2 class="title-heading wow fadeInUp mb-18">
                            Great opportunity for
                            <span class="text-gray font-yes">adventure</span>
                            &amp; travels
                        </h2>
                        <p class="des-heading wow fadeInUp">
                            Welcome to Simply Safaris! We are a professional and
                            reliable travel company that offers a wide range of
                            safari and adventure tours to the world's most
                            stunning destinations.
                        </p>
                        <div class="row wow fadeInUp mt-27">
                            <div class="col-sm-6">
                                <div class="icon-box-style3">
                                    <div class="icon flex-three">
                                        <svg
                                            width="51"
                                            height="51"
                                            viewBox="0 0 51 51"
                                            fill="none"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <g clip-path="url(#clip0_40_471)">
                                                <path
                                                    d="M37.5511 9.06618C37.5511 5.77106 34.8703 3.09027 31.5752 3.09027C28.2801 3.09027 25.5993 5.77106 25.5993 9.06618C25.5993 12.3613 28.2801 15.0421 31.5752 15.0421C34.8703 15.0421 37.5511 12.3613 37.5511 9.06618ZM31.5752 12.0541C29.9277 12.0541 28.5873 10.7137 28.5873 9.06618C28.5873 7.41862 29.9277 6.07823 31.5752 6.07823C33.2228 6.07823 34.5632 7.41862 34.5632 9.06618C34.5632 10.7137 33.2228 12.0541 31.5752 12.0541Z"
                                                    fill="currentColor"
                                                />
                                                <path
                                                    d="M50.2947 10.8487C49.0667 8.96556 46.5591 8.33461 44.586 9.41226L34.279 15.0416C33.3084 15.0416 16.1894 15.0416 15.1914 15.0416V1.49617C15.1914 0.671101 14.5225 0.00219727 13.6974 0.00219727H1.49398C0.668903 0.00219727 0 0.671101 0 1.49617V10.5596C0 11.3847 0.668903 12.0536 1.49398 12.0536H12.2033V49.5026C12.2033 50.3277 12.8722 50.9966 13.6973 50.9966C14.5224 50.9966 15.1913 50.3277 15.1913 49.5026V24.0054H24.1551V46.5147C24.1551 50.399 28.7646 52.4156 31.625 49.8526C34.4825 52.4131 39.0949 50.4048 39.0949 46.5147V23.3212L49.0299 16.8488C51.038 15.5407 51.6042 12.8565 50.2947 10.8487ZM2.98795 9.06556V2.99005H12.2033V9.06556H2.98795ZM34.613 48.0086C33.7892 48.0086 33.119 47.3384 33.119 46.5146C33.119 45.3559 33.119 38.5353 33.119 37.4511C33.119 36.626 32.4501 35.9571 31.625 35.9571C30.7999 35.9571 30.131 36.626 30.131 37.4511V46.5146C30.131 47.3384 29.4608 48.0086 28.637 48.0086C27.8133 48.0086 27.1431 47.3384 27.1431 46.5146V32.9692H36.1069V46.5146C36.1069 47.3384 35.4367 48.0086 34.613 48.0086ZM47.399 14.3452L36.7854 21.2596C36.3622 21.5354 36.1069 22.0063 36.1069 22.5114V29.9812H27.1431V22.5114C27.1431 21.6863 26.4742 21.0174 25.6491 21.0174H15.1913V18.0294C35.5362 17.9654 34.7563 18.1854 35.3764 17.8467L46.0182 12.0346C46.6312 11.6997 47.4104 11.8957 47.7919 12.4809C48.1988 13.1046 48.0231 13.9387 47.399 14.3452Z"
                                                    fill="currentColor"
                                                />
                                            </g>
                                            <defs>
                                                <clipPath id="clip0_40_471">
                                                    <rect
                                                        width="51"
                                                        height="51"
                                                        fill="white"
                                                    />
                                                </clipPath>
                                            </defs>
                                        </svg>
                                    </div>
                                    <h6 class="title mb-10">
                                        <a href="#">Trusted travel guide</a>
                                    </h6>
                                    <p class="des">
                                        Expert guides with years of experience
                                        in safari adventures worldwide.
                                    </p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="icon-box-style3">
                                    <div class="icon flex-three">
                                        <svg
                                            width="40"
                                            height="40"
                                            viewBox="0 0 40 40"
                                            fill="none"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <mask
                                                id="mask0_40_476"
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
                                            <g mask="url(#mask0_40_476)">
                                                <path
                                                    d="M20 23.125V38.8281H38.8281V12.2656H34.1406"
                                                    stroke="currentColor"
                                                    stroke-width="2"
                                                    stroke-miterlimit="10"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                />
                                                <path
                                                    d="M5.85938 12.2656H1.17188V38.8281H20V23.2031"
                                                    stroke="currentColor"
                                                    stroke-width="2"
                                                    stroke-miterlimit="10"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                />
                                                <path
                                                    d="M26.9598 9.14062H34.1406V34.1406H26.5035C23.5528 34.1406 20.933 36.0287 20 38.8281"
                                                    stroke="currentColor"
                                                    stroke-width="2"
                                                    stroke-miterlimit="10"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                />
                                                <path
                                                    d="M13.0402 9.14062H5.85938V34.1406H13.4965C16.4472 34.1406 19.067 36.0287 20 38.8281"
                                                    stroke="currentColor"
                                                    stroke-width="2"
                                                    stroke-miterlimit="10"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                />
                                                <path
                                                    d="M20 1.17188C14.6974 1.17188 11.3111 6.82758 13.8151 11.5017L20 23.0469L26.1849 11.5017C28.6889 6.82758 25.3027 1.17188 20 1.17188Z"
                                                    stroke="currentColor"
                                                    stroke-width="2"
                                                    stroke-miterlimit="10"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                />
                                                <path
                                                    d="M22.3438 8.20312C22.3438 9.4975 21.2944 10.5469 20 10.5469C18.7056 10.5469 17.6562 9.4975 17.6562 8.20312C17.6562 6.90875 18.7056 5.85937 20 5.85937C21.2944 5.85937 22.3438 6.90875 22.3438 8.20312Z"
                                                    stroke="currentColor"
                                                    stroke-width="2"
                                                    stroke-miterlimit="10"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                />
                                            </g>
                                        </svg>
                                    </div>
                                    <h6 class="title mb-10">
                                        <a href="#">Personalized Trips</a>
                                    </h6>
                                    <p class="des">
                                        Tailored safari experiences designed
                                        around your preferences and schedule.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="flex-three btn-wrap-about mb-30">
                            <a href="#" class="btn-main">
                                <p class="btn-main-text">More about us</p>
                                <p class="iconer">
                                    <i class="icon-arrow-right"></i>
                                </p>
                            </a>
                            <div class="profile flex-three">
                                <div class="image">
                                    <img
                                        src="/template/images/avata/10.jpg"
                                        alt=""
                                    />
                                </div>
                                <div class="content">
                                    <img
                                        src="/template/images/page/name.png"
                                        alt=""
                                    />
                                    <span class="text-main"
                                        >Ceo &amp; Founder</span
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="map-check flex-three">
                            <div class="icon">
                                <svg
                                    width="33"
                                    height="30"
                                    viewBox="0 0 33 30"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <g clip-path="url(#clip0_321_31)">
                                        <path
                                            d="M24.3102 0.240601C26.0182 0.240601 27.5818 0.938222 28.7124 2.04479C29.819 3.17542 30.5166 4.73906 30.5166 6.44703C30.5166 7.45738 30.2761 8.39556 29.8671 9.23751C29.0733 10.8252 25.0078 13.6157 24.3102 16.6467C23.1555 13.7119 19.5471 10.8252 18.7533 9.23751C18.3203 8.39556 18.0797 7.45738 18.0797 6.44703C18.0797 3.00703 20.8702 0.240601 24.3102 0.240601Z"
                                            fill="currentColor"
                                        />
                                        <path
                                            d="M24.3102 3.29565C26.0181 3.29565 27.3893 4.6909 27.3893 6.37481C27.3893 8.08278 26.0181 9.45397 24.3102 9.45397C22.6022 9.45397 21.231 8.08278 21.231 6.37481C21.231 4.6909 22.6022 3.29565 24.3102 3.29565Z"
                                            fill="#FEFEFE"
                                        />
                                        <path
                                            d="M9.44363 6.78381C11.8011 6.78381 13.894 7.72199 15.4336 9.26157C16.9731 10.8012 17.9113 12.9181 17.9113 15.2515C17.9113 16.5986 17.5986 17.8976 17.0212 19.0283C15.9387 21.1933 10.4299 24.9941 9.44363 29.1077C7.87999 25.1144 2.97259 21.1933 1.89007 19.0283C1.31273 17.8976 1 16.5986 1 15.2515C1 10.5606 4.77678 6.78381 9.44363 6.78381Z"
                                            fill="currentColor"
                                        />
                                        <path
                                            d="M9.4436 10.9695C11.777 10.9695 13.6534 12.8458 13.6534 15.1552C13.6534 17.4646 11.777 19.3409 9.4436 19.3409C7.13424 19.3409 5.25787 17.4646 5.25787 15.1552C5.25787 12.8458 7.13424 10.9695 9.4436 10.9695Z"
                                            fill="#FEFEFE"
                                        />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_321_31">
                                            <rect
                                                width="33"
                                                height="30"
                                                fill="white"
                                            />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </div>
                            <span class="text-main"
                                >Checkout Beautiful Places Around the
                                World.</span
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Widget Aboutus -->

    <!-- Widget Tourpackage -->
    <section class="tour-package pd-main">
        <div class="tf-container w-1456">
            <div class="row">
                <div class="col-lg-12">
                    <div class="center m0-auto w-text-heading">
                        <span
                            class="sub-title-heading text-main wow fadeInUp mb-15"
                            >Explore the world</span
                        >
                        <h2 class="title-heading wow fadeInUp mb-40">
                            Amazing Featured Tour
                            <span class="text-gray font-yes">Package</span> the
                            world
                        </h2>
                    </div>
                    <div class="tab-tour-list">
                        <ul class="nav justify-content-center tab-list mb-37">
                            <li
                                class="nav-item"
                                v-for="tab in tourTabs"
                                :key="tab.id"
                            >
                                <button
                                    class="nav-link"
                                    :class="{
                                        active: activeTourTab === tab.id,
                                    }"
                                    @click="activeTourTab = tab.id"
                                >
                                    {{ tab.label }}
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div
                                v-for="tab in tourTabs"
                                :key="tab.id"
                                v-show="activeTourTab === tab.id"
                            >
                                <div class="row">
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="tour-listing">
                                            <a
                                                href="#"
                                                class="tour-listing-image"
                                            >
                                                <div class="badge-top flex-two">
                                                    <span class="feature"
                                                        >Featured</span
                                                    >
                                                    <div
                                                        class="badge-media flex-five"
                                                    >
                                                        <span class="media"
                                                            ><i
                                                                class="icon-Group-1000002909"
                                                            ></i
                                                            >5</span
                                                        >
                                                        <span class="media"
                                                            ><i
                                                                class="icon-Group-1000002910"
                                                            ></i
                                                            >2</span
                                                        >
                                                    </div>
                                                </div>
                                                <img
                                                    src="/template/images/travel-list/1.jpg"
                                                    alt="Image Listing"
                                                />
                                            </a>
                                            <div class="tour-listing-content">
                                                <span class="tag-listing"
                                                    >Bestseller</span
                                                >
                                                <span class="map"
                                                    ><i class="icon-Vector4"></i
                                                    >United States USA</span
                                                >
                                                <h3 class="title-tour-list">
                                                    <a href="#"
                                                        >Days and 6 nights From
                                                        Moscow</a
                                                    >
                                                </h3>
                                                <div class="review">
                                                    <i class="icon-Star"></i
                                                    ><i class="icon-Star"></i
                                                    ><i class="icon-Star"></i
                                                    ><i class="icon-Star"></i
                                                    ><i class="icon-Star"></i>
                                                    <span>(1 Review)</span>
                                                </div>
                                                <div
                                                    class="icon-box flex-three"
                                                >
                                                    <div
                                                        class="icons flex-three"
                                                    >
                                                        <i
                                                            class="icon-time-left"
                                                        ></i
                                                        ><span>5 days</span>
                                                    </div>
                                                    <div
                                                        class="icons flex-three"
                                                    >
                                                        <svg
                                                            width="21"
                                                            height="16"
                                                            viewBox="0 0 21 16"
                                                            fill="none"
                                                            xmlns="http://www.w3.org/2000/svg"
                                                        >
                                                            <path
                                                                d="M4.34766 4.79761C4.34766 2.94013 5.85346 1.43433 7.71094 1.43433C9.56841 1.43433 11.0742 2.94013 11.0742 4.79761C11.0742 6.65508 9.56841 8.16089 7.71094 8.16089C5.85346 8.16089 4.34766 6.65508 4.34766 4.79761Z"
                                                                stroke="currentColor"
                                                                stroke-width="1.7"
                                                                stroke-miterlimit="10"
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                            />
                                                            <path
                                                                d="M9.5977 15.1797H2.46098C1.34827 15.1797 0.558268 14.0954 0.898984 13.0362C1.80408 10.222 4.57804 8.18566 7.69301 8.18566C9.17897 8.18566 10.5566 8.64906 11.6895 9.43922"
                                                                stroke="currentColor"
                                                                stroke-width="1.7"
                                                                stroke-miterlimit="10"
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                            />
                                                            <path
                                                                d="M17.1035 15.1797V9.02734"
                                                                stroke="currentColor"
                                                                stroke-width="1.7"
                                                                stroke-miterlimit="10"
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                            />
                                                            <path
                                                                d="M20.1797 12.1035H14.0273"
                                                                stroke="currentColor"
                                                                stroke-width="1.7"
                                                                stroke-miterlimit="10"
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                            />
                                                        </svg>
                                                        <span>12 Person</span>
                                                    </div>
                                                </div>
                                                <div class="flex-two">
                                                    <div
                                                        class="price-box flex-three"
                                                    >
                                                        <p>
                                                            From
                                                            <span
                                                                class="price-sale"
                                                                >$169.00</span
                                                            >
                                                        </p>
                                                        <span class="price"
                                                            >$199.00</span
                                                        >
                                                    </div>
                                                    <div class="icon-bookmark">
                                                        <i
                                                            class="icon-Vector-151"
                                                        ></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="tour-listing">
                                            <a
                                                href="#"
                                                class="tour-listing-image"
                                            >
                                                <div class="badge-top flex-two">
                                                    <span class="feature"
                                                        >Featured</span
                                                    >
                                                    <div
                                                        class="badge-media flex-five"
                                                    >
                                                        <span class="media"
                                                            ><i
                                                                class="icon-Group-1000002909"
                                                            ></i
                                                            >5</span
                                                        >
                                                        <span class="media"
                                                            ><i
                                                                class="icon-Group-1000002910"
                                                            ></i
                                                            >2</span
                                                        >
                                                    </div>
                                                </div>
                                                <img
                                                    src="/template/images/travel-list/2.jpg"
                                                    alt="Image Listing"
                                                />
                                            </a>
                                            <div class="tour-listing-content">
                                                <span class="tag-listing"
                                                    >Trending</span
                                                >
                                                <span class="map"
                                                    ><i class="icon-Vector4"></i
                                                    >United States USA</span
                                                >
                                                <h3 class="title-tour-list">
                                                    <a href="#"
                                                        >Days and 6 nights From
                                                        Moscow</a
                                                    >
                                                </h3>
                                                <div class="review">
                                                    <i class="icon-Star"></i
                                                    ><i class="icon-Star"></i
                                                    ><i class="icon-Star"></i
                                                    ><i class="icon-Star"></i
                                                    ><i class="icon-Star"></i>
                                                    <span>(1 Review)</span>
                                                </div>
                                                <div
                                                    class="icon-box flex-three"
                                                >
                                                    <div
                                                        class="icons flex-three"
                                                    >
                                                        <i
                                                            class="icon-time-left"
                                                        ></i
                                                        ><span>5 days</span>
                                                    </div>
                                                    <div
                                                        class="icons flex-three"
                                                    >
                                                        <svg
                                                            width="21"
                                                            height="16"
                                                            viewBox="0 0 21 16"
                                                            fill="none"
                                                            xmlns="http://www.w3.org/2000/svg"
                                                        >
                                                            <path
                                                                d="M4.34766 4.79761C4.34766 2.94013 5.85346 1.43433 7.71094 1.43433C9.56841 1.43433 11.0742 2.94013 11.0742 4.79761C11.0742 6.65508 9.56841 8.16089 7.71094 8.16089C5.85346 8.16089 4.34766 6.65508 4.34766 4.79761Z"
                                                                stroke="currentColor"
                                                                stroke-width="1.7"
                                                                stroke-miterlimit="10"
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                            />
                                                            <path
                                                                d="M9.5977 15.1797H2.46098C1.34827 15.1797 0.558268 14.0954 0.898984 13.0362C1.80408 10.222 4.57804 8.18566 7.69301 8.18566C9.17897 8.18566 10.5566 8.64906 11.6895 9.43922"
                                                                stroke="currentColor"
                                                                stroke-width="1.7"
                                                                stroke-miterlimit="10"
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                            />
                                                            <path
                                                                d="M17.1035 15.1797V9.02734"
                                                                stroke="currentColor"
                                                                stroke-width="1.7"
                                                                stroke-miterlimit="10"
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                            />
                                                            <path
                                                                d="M20.1797 12.1035H14.0273"
                                                                stroke="currentColor"
                                                                stroke-width="1.7"
                                                                stroke-miterlimit="10"
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                            />
                                                        </svg>
                                                        <span>12 Person</span>
                                                    </div>
                                                </div>
                                                <div class="flex-two">
                                                    <div
                                                        class="price-box flex-three"
                                                    >
                                                        <p>
                                                            From
                                                            <span
                                                                class="price-sale"
                                                                >$169.00</span
                                                            >
                                                        </p>
                                                        <span class="price"
                                                            >$199.00</span
                                                        >
                                                    </div>
                                                    <div class="icon-bookmark">
                                                        <i
                                                            class="icon-Vector-151"
                                                        ></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="tour-listing">
                                            <a
                                                href="#"
                                                class="tour-listing-image"
                                            >
                                                <div class="badge-top flex-two">
                                                    <span class="feature"
                                                        >Featured</span
                                                    >
                                                    <div
                                                        class="badge-media flex-five"
                                                    >
                                                        <span class="media"
                                                            ><i
                                                                class="icon-Group-1000002909"
                                                            ></i
                                                            >5</span
                                                        >
                                                        <span class="media"
                                                            ><i
                                                                class="icon-Group-1000002910"
                                                            ></i
                                                            >2</span
                                                        >
                                                    </div>
                                                </div>
                                                <img
                                                    src="/template/images/travel-list/3.jpg"
                                                    alt="Image Listing"
                                                />
                                            </a>
                                            <div class="tour-listing-content">
                                                <span class="tag-listing"
                                                    >Hot sell</span
                                                >
                                                <span class="map"
                                                    ><i class="icon-Vector4"></i
                                                    >United States USA</span
                                                >
                                                <h3 class="title-tour-list">
                                                    <a href="#"
                                                        >Days and 6 nights From
                                                        Moscow</a
                                                    >
                                                </h3>
                                                <div class="review">
                                                    <i class="icon-Star"></i
                                                    ><i class="icon-Star"></i
                                                    ><i class="icon-Star"></i
                                                    ><i class="icon-Star"></i
                                                    ><i class="icon-Star"></i>
                                                    <span>(1 Review)</span>
                                                </div>
                                                <div
                                                    class="icon-box flex-three"
                                                >
                                                    <div
                                                        class="icons flex-three"
                                                    >
                                                        <i
                                                            class="icon-time-left"
                                                        ></i
                                                        ><span>5 days</span>
                                                    </div>
                                                    <div
                                                        class="icons flex-three"
                                                    >
                                                        <svg
                                                            width="21"
                                                            height="16"
                                                            viewBox="0 0 21 16"
                                                            fill="none"
                                                            xmlns="http://www.w3.org/2000/svg"
                                                        >
                                                            <path
                                                                d="M4.34766 4.79761C4.34766 2.94013 5.85346 1.43433 7.71094 1.43433C9.56841 1.43433 11.0742 2.94013 11.0742 4.79761C11.0742 6.65508 9.56841 8.16089 7.71094 8.16089C5.85346 8.16089 4.34766 6.65508 4.34766 4.79761Z"
                                                                stroke="currentColor"
                                                                stroke-width="1.7"
                                                                stroke-miterlimit="10"
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                            />
                                                            <path
                                                                d="M9.5977 15.1797H2.46098C1.34827 15.1797 0.558268 14.0954 0.898984 13.0362C1.80408 10.222 4.57804 8.18566 7.69301 8.18566C9.17897 8.18566 10.5566 8.64906 11.6895 9.43922"
                                                                stroke="currentColor"
                                                                stroke-width="1.7"
                                                                stroke-miterlimit="10"
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                            />
                                                            <path
                                                                d="M17.1035 15.1797V9.02734"
                                                                stroke="currentColor"
                                                                stroke-width="1.7"
                                                                stroke-miterlimit="10"
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                            />
                                                            <path
                                                                d="M20.1797 12.1035H14.0273"
                                                                stroke="currentColor"
                                                                stroke-width="1.7"
                                                                stroke-miterlimit="10"
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                            />
                                                        </svg>
                                                        <span>12 Person</span>
                                                    </div>
                                                </div>
                                                <div class="flex-two">
                                                    <div
                                                        class="price-box flex-three"
                                                    >
                                                        <p>
                                                            From
                                                            <span
                                                                class="price-sale"
                                                                >$169.00</span
                                                            >
                                                        </p>
                                                        <span class="price"
                                                            >$199.00</span
                                                        >
                                                    </div>
                                                    <div class="icon-bookmark">
                                                        <i
                                                            class="icon-Vector-151"
                                                        ></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="tour-listing">
                                            <a
                                                href="#"
                                                class="tour-listing-image"
                                            >
                                                <div class="badge-top flex-two">
                                                    <span class="feature"
                                                        >Featured</span
                                                    >
                                                    <div
                                                        class="badge-media flex-five"
                                                    >
                                                        <span class="media"
                                                            ><i
                                                                class="icon-Group-1000002909"
                                                            ></i
                                                            >5</span
                                                        >
                                                        <span class="media"
                                                            ><i
                                                                class="icon-Group-1000002910"
                                                            ></i
                                                            >2</span
                                                        >
                                                    </div>
                                                </div>
                                                <img
                                                    src="/template/images/travel-list/4.jpg"
                                                    alt="Image Listing"
                                                />
                                            </a>
                                            <div class="tour-listing-content">
                                                <span class="tag-listing"
                                                    >Bestseller</span
                                                >
                                                <span class="map"
                                                    ><i class="icon-Vector4"></i
                                                    >United States USA</span
                                                >
                                                <h3 class="title-tour-list">
                                                    <a href="#"
                                                        >Days and 6 nights From
                                                        Moscow</a
                                                    >
                                                </h3>
                                                <div class="review">
                                                    <i class="icon-Star"></i
                                                    ><i class="icon-Star"></i
                                                    ><i class="icon-Star"></i
                                                    ><i class="icon-Star"></i
                                                    ><i class="icon-Star"></i>
                                                    <span>(1 Review)</span>
                                                </div>
                                                <div
                                                    class="icon-box flex-three"
                                                >
                                                    <div
                                                        class="icons flex-three"
                                                    >
                                                        <i
                                                            class="icon-time-left"
                                                        ></i
                                                        ><span>5 days</span>
                                                    </div>
                                                    <div
                                                        class="icons flex-three"
                                                    >
                                                        <svg
                                                            width="21"
                                                            height="16"
                                                            viewBox="0 0 21 16"
                                                            fill="none"
                                                            xmlns="http://www.w3.org/2000/svg"
                                                        >
                                                            <path
                                                                d="M4.34766 4.79761C4.34766 2.94013 5.85346 1.43433 7.71094 1.43433C9.56841 1.43433 11.0742 2.94013 11.0742 4.79761C11.0742 6.65508 9.56841 8.16089 7.71094 8.16089C5.85346 8.16089 4.34766 6.65508 4.34766 4.79761Z"
                                                                stroke="currentColor"
                                                                stroke-width="1.7"
                                                                stroke-miterlimit="10"
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                            />
                                                            <path
                                                                d="M9.5977 15.1797H2.46098C1.34827 15.1797 0.558268 14.0954 0.898984 13.0362C1.80408 10.222 4.57804 8.18566 7.69301 8.18566C9.17897 8.18566 10.5566 8.64906 11.6895 9.43922"
                                                                stroke="currentColor"
                                                                stroke-width="1.7"
                                                                stroke-miterlimit="10"
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                            />
                                                            <path
                                                                d="M17.1035 15.1797V9.02734"
                                                                stroke="currentColor"
                                                                stroke-width="1.7"
                                                                stroke-miterlimit="10"
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                            />
                                                            <path
                                                                d="M20.1797 12.1035H14.0273"
                                                                stroke="currentColor"
                                                                stroke-width="1.7"
                                                                stroke-miterlimit="10"
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                            />
                                                        </svg>
                                                        <span>12 Person</span>
                                                    </div>
                                                </div>
                                                <div class="flex-two">
                                                    <div
                                                        class="price-box flex-three"
                                                    >
                                                        <p>
                                                            From
                                                            <span
                                                                class="price-sale"
                                                                >$169.00</span
                                                            >
                                                        </p>
                                                        <span class="price"
                                                            >$199.00</span
                                                        >
                                                    </div>
                                                    <div class="icon-bookmark">
                                                        <i
                                                            class="icon-Vector-151"
                                                        ></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 center mt-44">
                                        <a href="#" class="btn-main">
                                            <p class="btn-main-text">
                                                View all tour
                                            </p>
                                            <p class="iconer">
                                                <i class="icon-13"></i>
                                            </p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Widget Tourpackage -->

    <!-- Widget activities -->
    <section class="tf-widget-activities pd-main relative overflow-hidden">
        <img
            src="/template/images/page/mask-activiti.png"
            alt="image"
            class="mask-top"
        />
        <img
            src="/template/images/page/mask-print-2.png"
            alt="image"
            class="mask-bottom"
        />
        <div class="tf-container">
            <div class="row z-index3 relative">
                <div class="col-lg-12 wow fadeInUp mb-60">
                    <div class="clip-text">Activities</div>
                </div>
                <div class="col-lg-12">
                    <ul class="nav nav-tabs-activities justify-content-center">
                        <li
                            class="nav-item"
                            v-for="tab in activityTabs"
                            :key="tab.id"
                        >
                            <button
                                class="nav-link"
                                :class="{
                                    active: activeActivityTab === tab.id,
                                }"
                                @click="activeActivityTab = tab.id"
                            >
                                <span class="icon flex-five" v-if="tab.icon"
                                    ><i :class="tab.icon"></i
                                ></span>
                                <span>{{ tab.label }}</span>
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content mt-44">
                        <div
                            v-for="tab in activityTabs"
                            :key="tab.id"
                            v-show="activeActivityTab === tab.id"
                        >
                            <div class="tabs-activities-content flex">
                                <div class="activities-image">
                                    <img
                                        src="/template/images/page/1.jpg"
                                        alt="Image"
                                    />
                                </div>
                                <div class="activities-content relative">
                                    <span class="sub-title text-white"
                                        >Welcome to our Print</span
                                    >
                                    <h3
                                        class="title-activitis mb-60 text-white"
                                    >
                                        Real adventure &amp; enjoy your dream
                                        tours
                                    </h3>
                                    <div class="flex-three mb-30">
                                        <div
                                            class="flex-three icon-list-wrap text-white"
                                        >
                                            <div class="icon">
                                                <i class="icon-Page-1"></i>
                                            </div>
                                            <span class="icon-lists"
                                                >Real adventure Feel</span
                                            >
                                        </div>
                                        <div
                                            class="flex-three icon-list-wrap text-white"
                                        >
                                            <div class="icon">
                                                <i
                                                    class="icon-rubber-ring-1"
                                                ></i>
                                            </div>
                                            <span class="icon-lists"
                                                >Comfort &amp; Secure trip</span
                                            >
                                        </div>
                                    </div>
                                    <div class="flex-three btn-wrap-activitis">
                                        <a
                                            href="#"
                                            class="icon-activitis flex-five"
                                            ><i class="icon-Vector-21"></i
                                        ></a>
                                        <a href="#" class="get-start text-white"
                                            >Get Started Today</a
                                        >
                                    </div>
                                    <img
                                        src="/template/images/page/mask-tap.png"
                                        alt="image"
                                        class="mask-tab"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Widget activities -->

    <!-- Widget Offer Package -->
    <section class="offer-package pd-main bg-1 relative">
        <img
            src="/template/images/page/feature.jpg"
            alt="image"
            class="feature-ofer"
        />
        <div class="tf-container">
            <div class="row align-center z-index3 relative">
                <div class="col-lg-5">
                    <div class="content">
                        <div class="mb-50">
                            <span class="sub-title-heading text-main mb-15"
                                >Explore the world</span
                            >
                            <h2 class="title-heading wow fadeInUp mb-32">
                                Amazing Featured Tour<span
                                    class="text-gray font-yes"
                                >
                                    Package</span
                                >
                                the world
                            </h2>
                            <p class="des-heading wow fadeInUp">
                                Discover exclusive tour packages with unbeatable
                                prices and unforgettable experiences.
                            </p>
                        </div>
                        <div class="inner-content flex-three">
                            <div class="offer">
                                <span class="number"
                                    >25 <span>% off</span></span
                                >
                            </div>
                            <p class="font-italic">
                                Discover Great
                                <span class="text-main">Discount</span> Deals
                                Around the World
                            </p>
                        </div>
                        <div class="count-dow-wrap flex-three mb-50">
                            <div class="title-counters">
                                <span>Hurry Up!</span>
                                <p>Offer Ends in:</p>
                            </div>
                            <div class="count-down relative">
                                <div class="featured-countdown">
                                    <div class="countdown__item">
                                        <span class="countdown__value">{{
                                            countdown.days
                                                .toString()
                                                .padStart(2, '0')
                                        }}</span>
                                        <span class="countdown__label"
                                            >Days</span
                                        >
                                    </div>
                                    <div class="countdown__item">
                                        <span class="countdown__value">{{
                                            countdown.hours
                                                .toString()
                                                .padStart(2, '0')
                                        }}</span>
                                        <span class="countdown__label"
                                            >Hours</span
                                        >
                                    </div>
                                    <div class="countdown__item">
                                        <span class="countdown__value">{{
                                            countdown.minutes
                                                .toString()
                                                .padStart(2, '0')
                                        }}</span>
                                        <span class="countdown__label"
                                            >Minutes</span
                                        >
                                    </div>
                                    <div class="countdown__item">
                                        <span class="countdown__value">{{
                                            countdown.seconds
                                                .toString()
                                                .padStart(2, '0')
                                        }}</span>
                                        <span class="countdown__label"
                                            >Seconds</span
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="btn-wap">
                            <a href="#" class="btn-main">
                                <p class="btn-main-text">Explore More</p>
                                <p class="iconer">
                                    <i class="icon-arrow-right"></i>
                                </p>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="on-week-swipper-wrap relative">
                        <div
                            class="swiper offer-package-swipper relative overflow-hidden"
                        >
                            <div class="swiper-wrapper">
                                <div
                                    v-for="n in 4"
                                    :key="n"
                                    class="swiper-slide"
                                >
                                    <div class="tour-listing">
                                        <a href="#" class="tour-listing-image">
                                            <div class="badge-top flex-two">
                                                <span class="feature"
                                                    >Featured</span
                                                >
                                                <div
                                                    class="badge-media flex-five"
                                                >
                                                    <span class="media"
                                                        ><i
                                                            class="icon-Group-1000002909"
                                                        ></i
                                                        >5</span
                                                    >
                                                    <span class="media"
                                                        ><i
                                                            class="icon-Group-1000002910"
                                                        ></i
                                                        >2</span
                                                    >
                                                </div>
                                            </div>
                                            <img
                                                :src="`/template/images/travel-list/${14 + n}.jpg`"
                                                alt="Image Listing"
                                            />
                                        </a>
                                        <div class="tour-listing-content">
                                            <span class="tag-listing"
                                                >Bestseller</span
                                            >
                                            <span class="map"
                                                ><i class="icon-Vector4"></i
                                                >United States USA</span
                                            >
                                            <h3 class="title-tour-list">
                                                <a href="#"
                                                    >Days and 6 nights From
                                                    Moscow</a
                                                >
                                            </h3>
                                            <div class="review">
                                                <i class="icon-Star"></i
                                                ><i class="icon-Star"></i
                                                ><i class="icon-Star"></i
                                                ><i class="icon-Star"></i
                                                ><i class="icon-Star"></i>
                                                <span>(1 Review)</span>
                                            </div>
                                            <div class="icon-box flex-three">
                                                <div class="icons flex-three">
                                                    <i
                                                        class="icon-time-left"
                                                    ></i
                                                    ><span>5 days</span>
                                                </div>
                                                <div class="icons flex-three">
                                                    <svg
                                                        width="21"
                                                        height="16"
                                                        viewBox="0 0 21 16"
                                                        fill="none"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                    >
                                                        <path
                                                            d="M4.34766 4.79761C4.34766 2.94013 5.85346 1.43433 7.71094 1.43433C9.56841 1.43433 11.0742 2.94013 11.0742 4.79761C11.0742 6.65508 9.56841 8.16089 7.71094 8.16089C5.85346 8.16089 4.34766 6.65508 4.34766 4.79761Z"
                                                            stroke="currentColor"
                                                            stroke-width="1.7"
                                                            stroke-miterlimit="10"
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                        />
                                                        <path
                                                            d="M9.5977 15.1797H2.46098C1.34827 15.1797 0.558268 14.0954 0.898984 13.0362C1.80408 10.222 4.57804 8.18566 7.69301 8.18566C9.17897 8.18566 10.5566 8.64906 11.6895 9.43922"
                                                            stroke="currentColor"
                                                            stroke-width="1.7"
                                                            stroke-miterlimit="10"
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                        />
                                                        <path
                                                            d="M17.1035 15.1797V9.02734"
                                                            stroke="currentColor"
                                                            stroke-width="1.7"
                                                            stroke-miterlimit="10"
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                        />
                                                        <path
                                                            d="M20.1797 12.1035H14.0273"
                                                            stroke="currentColor"
                                                            stroke-width="1.7"
                                                            stroke-miterlimit="10"
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                        />
                                                    </svg>
                                                    <span>12 Person</span>
                                                </div>
                                            </div>
                                            <div class="flex-two">
                                                <div
                                                    class="price-box flex-three"
                                                >
                                                    <p>
                                                        From
                                                        <span class="price-sale"
                                                            >$169.00</span
                                                        >
                                                    </p>
                                                    <span class="price"
                                                        >$199.00</span
                                                    >
                                                </div>
                                                <div class="icon-bookmark">
                                                    <i
                                                        class="icon-Vector-151"
                                                    ></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Widget Offer Package -->

    <!-- Widget Counter -->
    <section class="widget-counter relative">
        <img
            src="/template/images/page/couter-top.png"
            alt="image"
            class="counter-top"
        />
        <img
            src="/template/images/page/counter-bottom.png"
            alt="image"
            class="counter-bottom"
        />
        <div class="tf-container">
            <div class="row mb-50">
                <div class="col-lg-9 flex-three cta-wrap">
                    <div class="image">
                        <img
                            src="/template/images/page/ready.png"
                            alt="Image"
                        />
                    </div>
                    <div class="content">
                        <h2 class="title-call mb-18 text-white">
                            Ready to adventure and enjoy natural
                        </h2>
                        <p class="des text-white">
                            Lorem ipsum dolor sit amet, consectetur notted
                            adipisicin
                        </p>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="callt-to-action-button text-end">
                        <a href="#" class="get-call">Let's get started</a>
                    </div>
                </div>
            </div>
            <div class="row mb--20em z-index3 relative">
                <div class="col-lg-3 col-6">
                    <div class="tf-counter center tf-countto">
                        <div class="icon mb-32">
                            <svg
                                width="85"
                                height="78"
                                viewBox="0 0 85 78"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <g clip-path="url(#clip0_73_163)">
                                    <path
                                        d="M0.0001297 62.7258V40.8836H13.8123L23.6738 56.5469V30.7932C21.8717 30.3047 20.5539 28.7854 20.5539 26.9907C20.5539 25.1959 21.8719 23.6766 23.6738 23.1882V19.6274H26.164V23.0228H34.0214L42.2742 20.2923C40.5417 19.4812 39.2295 18.1339 38.5631 16.4693L37.0245 12.6269C36.3224 10.8736 36.4162 8.97097 37.2889 7.26997C38.1617 5.56883 39.7044 4.28055 41.6335 3.64238C45.629 2.32045 50.0632 4.19982 51.5178 7.83194L52.8295 11.1081H56.9344V13.3717H53.4829C53.6137 14.5576 53.4036 15.7347 52.9075 16.8001C53.9147 16.5731 54.9711 16.6649 55.929 17.0709C56.0077 17.1043 56.0845 17.1399 56.1604 17.1769L65.7933 16.4479L69.3985 24.9877L67.0736 25.7987L64.1378 18.8444L58.2401 19.2907C58.256 19.3271 58.2726 19.363 58.2874 19.3998L66.2437 38.2462L70.9088 34.8833L67.9658 27.9121L70.2907 27.1011L73.8887 35.6241L67.2858 40.3839C68.9118 43.0765 71.0927 47.0103 67.9751 52.0045L75.2358 68.2575V71.7799H85V74.0435H0.0001297V64.9892H2.49036V71.7799H55.9091L43.2514 61.9348H18.818V59.6712H22.7718L12.3683 43.1471H2.49036V62.7258H0.0001297ZM42.4663 52.8814H37.4858V57.4085H36.2407C34.5033 57.4085 34.208 57.9361 34.082 58.1612C33.8733 58.5338 33.7961 59.0659 33.7674 59.6712H42.4661V52.8814H42.4663ZM31.8627 57.1345C32.4504 56.0851 33.5014 55.4469 34.9957 55.2293V44.618C34.9957 41.4976 37.7885 38.9591 41.2213 38.9591H49.0128L43.2766 30.034L36.6066 30.9582H26.1642V59.671H31.2763C31.3129 58.7726 31.4375 57.8938 31.8627 57.1345ZM23.0439 26.9907C23.0439 27.9303 23.885 28.6948 24.9188 28.6948H28.3347V25.2863H24.9189C23.885 25.2863 23.0439 26.051 23.0439 26.9907ZM49.1776 8.60609C48.4067 6.68069 46.3994 5.48281 44.2657 5.48281C43.674 5.48281 43.0724 5.57501 42.485 5.76923C40.0504 6.57476 38.6741 8.86534 39.1402 11.1079H50.1792L49.1776 8.60609ZM39.9726 13.3717L40.9031 15.6951C41.3776 16.8803 42.3376 17.8292 43.6063 18.3669C44.874 18.9043 46.29 18.963 47.593 18.5326L47.5977 18.5311C49.9664 17.7462 51.3333 15.5556 50.9743 13.3717H39.9726ZM55.9589 20.2021L55.3208 18.6833L51.4385 19.995L52.0766 21.5138L55.9589 20.2021ZM61.0878 20.9075L60.4497 19.3887L56.5674 20.7004L57.2055 22.2192L61.0878 20.9075ZM42.7136 25.1808L55.7456 30.3936L42.7136 36.8623V25.1808Z"
                                        fill="currentColor"
                                    />
                                    <path
                                        d="M7.47058 45.4106H4.98035V47.6742H7.47058V45.4106Z"
                                        fill="currentColor"
                                    />
                                    <path
                                        d="M7.47058 49.938H4.98035V52.2016H7.47058V49.938Z"
                                        fill="currentColor"
                                    />
                                    <path
                                        d="M54.4991 23.7441L53.6076 21.63L51.2819 22.4403L52.1734 24.5544L54.4991 23.7441Z"
                                        fill="currentColor"
                                    />
                                    <path
                                        d="M56.2822 27.9688L55.3907 25.8546L53.0648 26.6649L53.9563 28.7791L56.2822 27.9688Z"
                                        fill="currentColor"
                                    />
                                </g>
                                <defs>
                                    <clipPath id="clip0_73_163">
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
                        <div ref="counter1" class="number-counter">0</div>
                        <span class="line"></span>
                        <p class="title-counter">Happy <br />Traveller</p>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="tf-counter center tf-countto">
                        <div class="icon mb-32">
                            <svg
                                width="69"
                                height="68"
                                viewBox="0 0 69 68"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <g clip-path="url(#clip0_73_177)">
                                    <mask
                                        id="mask0_73_177"
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
                                    <g mask="url(#mask0_73_177)">
                                        <path
                                            d="M26.505 2.26924C13.4757 2.26924 2.91333 12.8315 2.91333 25.8609C2.91333 32.2223 5.44315 37.9842 9.53644 42.2273L2.91333 49.4526H26.505C39.5343 49.4526 50.0967 38.8902 50.0967 25.8609C50.0967 12.8315 39.5343 2.26924 26.505 2.26924Z"
                                            stroke="currentColor"
                                            stroke-width="3"
                                            stroke-miterlimit="10"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />
                                        <path
                                            d="M29.5166 49.2619C30.5896 58.3735 38.3375 65.4424 47.7375 65.4424H66.0866L60.9352 59.8227C64.1191 56.5225 66.0866 52.041 66.0866 47.0933C66.0866 37.6932 59.0178 29.9454 49.9061 28.8724"
                                            stroke="currentColor"
                                            stroke-width="3"
                                            stroke-miterlimit="10"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />
                                        <path
                                            d="M33.0583 16.3586C30.0046 16.3586 27.2325 18.4469 26.5051 21.2735V21.2734C25.7776 18.4469 23.0054 16.3586 19.9518 16.3586C16.3324 16.3586 13.3986 19.2924 13.3986 22.9118C13.3986 31.1034 26.5051 39.2949 26.5051 39.2949C26.5051 39.2949 39.6115 31.1034 39.6115 22.9118C39.6115 19.2924 36.6777 16.3586 33.0583 16.3586Z"
                                            stroke="currentColor"
                                            stroke-width="3"
                                            stroke-miterlimit="10"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />
                                    </g>
                                </g>
                                <defs>
                                    <clipPath id="clip0_73_177">
                                        <rect
                                            width="67.1053"
                                            height="67.1053"
                                            fill="white"
                                            transform="translate(0.947388 0.302734)"
                                        />
                                    </clipPath>
                                </defs>
                            </svg>
                        </div>
                        <div ref="counter2" class="number-counter percen">
                            0
                        </div>
                        <span class="line"></span>
                        <p class="title-counter">Total Positive<br />Reviews</p>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="tf-counter center tf-countto">
                        <div class="icon mb-32">
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
                        <div ref="counter3" class="number-counter plus">0</div>
                        <span class="line"></span>
                        <p class="title-counter">Tour<br />Completed</p>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="tf-counter center tf-countto">
                        <div class="icon mb-32">
                            <svg
                                width="74"
                                height="62"
                                viewBox="0 0 74 62"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M73.9347 32.4115C73.888 32.373 73.8201 32.3607 73.7672 32.3785C73.7027 32.3984 73.6435 32.4156 73.5877 32.4319L73.5604 32.4399C73.4228 32.4802 73.3039 32.5147 73.1861 32.562C71.3254 33.3013 69.8604 34.5679 68.7075 36.4341C68.4228 36.8952 68.2023 37.3744 68.0266 37.8524C68.3147 36.2447 68.3814 35.6183 68.4324 33.8193C68.4348 33.7438 68.4348 33.6671 68.4348 33.5908C68.4348 33.3827 68.4348 33.1861 68.4803 33.0067C68.5348 32.7942 68.6444 32.5822 68.7505 32.377C68.7804 32.319 68.8103 32.2609 68.8349 32.2099C68.8435 32.1999 68.8711 32.183 68.8819 32.1814C69.414 32.1001 69.8846 31.8742 70.3208 31.4908C70.7604 31.1026 71.1219 30.6274 71.3955 30.078C72.3774 28.1089 72.8688 25.9325 72.8563 23.6099C72.8552 23.3858 72.8289 23.1677 72.8 22.9305C72.7868 22.8242 72.7734 22.7155 72.7623 22.6029C72.756 22.5412 72.718 22.4863 72.6629 22.4596C72.6085 22.4332 72.5429 22.4351 72.488 22.4683C72.4548 22.489 72.4264 22.5059 72.4006 22.5209C72.3507 22.5506 72.3127 22.5738 72.2767 22.5998C71.4391 23.2002 70.7003 23.9477 70.0806 24.8209C69.0694 26.2482 68.4812 27.8621 68.3228 29.6375C68.3071 29.4396 68.2911 29.2417 68.2751 29.0438L68.2696 28.972C68.2074 28.1961 68.1433 27.3937 68.091 26.6047C68.0778 26.4049 68.0394 26.2105 68.0023 26.0225C67.9307 25.661 67.863 25.3195 67.9713 24.9617C67.996 24.8808 68.0168 24.7994 68.0375 24.7182C68.0887 24.5169 68.336 24.207 68.7157 23.8681L68.7299 23.8553C68.87 23.7366 69.5893 23.0785 69.9973 21.7768C70.4115 20.4543 70.3802 19.0725 70.3574 18.0635L70.3568 18.0366C70.3264 16.6495 70.1765 15.6044 69.8708 14.6471C69.8313 14.5247 69.7844 14.4039 69.7347 14.2771C69.7089 14.211 69.6826 14.1428 69.6555 14.0714C69.6332 14.0109 69.5798 13.9669 69.5173 13.9566L69.4867 13.9538C69.4325 13.9538 69.3819 13.9781 69.3476 14.02L69.0506 14.3819C67.6269 16.1183 66.9274 18.1549 66.9712 20.4352C66.9788 20.8368 67.0089 21.2487 67.0621 21.674C66.7002 20.4252 66.2759 19.2096 65.7932 18.0401C65.7832 18.0145 65.7767 17.9828 65.7..."
                                    fill="currentColor"
                                />
                                <path
                                    d="M24.9672 59.363C23.767 59.0979 22.5732 58.7115 21.3171 58.182C21.3021 58.175 21.2648 58.1329 21.2532 58.095C21.128 57.6682 21.086 57.2383 21.1246 56.7805C21.3706 53.8579 19.6302 50.7783 16.9869 49.4581C16.9307 49.4295 16.8721 49.4049 16.8046 49.3772L16.7419 49.3513C16.6824 49.3258 16.6098 49.3364 16.5618 49.375C16.5099 49.4151 16.4841 49.4804 16.4946 49.5458L16.567 49.9939C16.6312 50.3905 16.694 50.7785 16.7543 51.1669L16.7695 51.2686C16.9392 52.3724 17.1147 53.5138 17.4565 54.6104C17.765 55.5997 18.2409 56.3804 18.9029 56.9786C17.5044 56.154 16.2012 55.1629 15.0177 54.0231C14.9928 53.9992 14.9633 53.9358 14.959 53.8835L14.9273 53.1013C14.9271 53.0735 14.9268 52.9997 14.9425 52.9631C15.5255 51.605 15.6748 50.0763 15.3994 48.2899C15.1338 46.567 14.3982 44.9826 13.2127 43.5806C13.074 43.4164 12.9516 43.3333 12.8488 43.3333C12.8238 43.3333 12.8012 43.3383 12.7834 43.3476C12.7288 43.3747 12.6919 43.4272 12.6847 43.4878L12.653 43.7607C12.6285 43.9632 12.6049 44.1621 12.5838 44.3613C12.5472 44.7122 12.5072 45.0626 12.4669 45.4131L12.4612 45.4638C12.361 46.339 12.2571 47.2442 12.2022 48.1505C12.1384 49.2077 12.1574 50.4424 12.7822 51.5846C12.7856 51.5904 12.7886 51.5965 12.7919 51.6023C11.9233 50.5161 11.1304 49.3472 10.4229 48.1085C10.3604 47.9984 10.355 47.9017 10.3667 47.7615C10.417 47.1685 10.6926 46.6866 10.9829 46.2545C11.5436 45.4176 11.883 44.4284 12.0835 43.0469C12.3638..."
                                    fill="currentColor"
                                />
                                <path
                                    d="M45.798 35.4259L53.2507 28.1614C53.5033 27.9151 53.5945 27.5463 53.4853 27.2108C53.3762 26.8749 53.0861 26.6301 52.7367 26.5794L42.4382 25.0839L37.8318 15.7518C37.6756 15.4352 37.3533 15.2349 37.0003 15.2349C36.6473 15.2349 36.3248 15.4356 36.1688 15.7518L31.564 25.0839L21.2639 26.5794C20.9147 26.6301 20.6244 26.8751 20.5153 27.2108C20.4061 27.5463 20.4971 27.9151 20.75 28.1614L28.2026 35.4259L26.4442 45.6828C26.3845 46.0306 26.5275 46.3823 26.8131 46.5896C27.0984 46.7972 27.4768 46.8246 27.7897 46.6603L37.0007 41.8183L46.2117 46.6603C46.3472 46.7317 46.4954 46.7668 46.6429 46.7668C46.8352 46.7668 47.0265 46.7071 47.188 46.5896C47.4737 46.3825 47.6169 46.0306 47.5569 45.6828L45.798 35.4259Z"
                                    fill="currentColor"
                                />
                            </svg>
                        </div>
                        <div ref="counter4" class="number-counter">0</div>
                        <span class="line"></span>
                        <p class="title-counter">Awards<br />Winning</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Widget Counter -->

    <!-- Widget destination -->
    <section class="widget-destination">
        <div class="tf-container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="center m0-auto w-text-heading mb-40">
                        <span
                            class="sub-title-heading text-main wow fadeInUp mb-15"
                            >Explore the world</span
                        >
                        <h2 class="title-heading wow fadeInUp">
                            We provide top tourist destinations
                        </h2>
                    </div>
                </div>
            </div>
            <div class="grid-three-destination">
                <div
                    class="tf-widget-destination"
                    v-for="(dest, i) in destinations"
                    :key="i"
                >
                    <a href="#" class="destination-imgae">
                        <span class="tour">{{ dest.tours }} tours</span>
                        <img :src="dest.image" alt="" />
                    </a>
                    <div class="destination-content">
                        <span class="nation">{{ dest.nation }}</span>
                        <div class="flex-two btn-destination">
                            <h6 class="title">
                                <a href="#">View all tours</a>
                            </h6>
                            <a href="#" class="flex-five btn-view"
                                ><i class="icon-Vector-32"></i
                            ></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Widget destination -->

    <!-- Widget Adventure -->
    <section class="widget-adventure">
        <div class="tf-container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mb-40">
                        <span
                            class="sub-title-heading text-main wow fadeInUp mb-15"
                            >Explore the world</span
                        >
                        <h2 class="title-heading wow fadeInUp">
                            Adventures for everyone
                        </h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="adventure-content">
                        <nav class="adventure-scroll mb-40">
                            <div class="nav nav-justified nav-tabs-adventure">
                                <li
                                    class="nav-link flex-three"
                                    :class="{
                                        active: activeAdventureTab === tab.id,
                                    }"
                                    v-for="tab in adventureTabs"
                                    :key="tab.id"
                                    @click="activeAdventureTab = tab.id"
                                >
                                    <i :class="tab.icon"></i>
                                    <span>{{ tab.label }}</span>
                                </li>
                            </div>
                        </nav>
                        <div class="tab-content">
                            <div
                                v-for="tab in adventureTabs"
                                :key="tab.id"
                                v-show="activeAdventureTab === tab.id"
                            >
                                <div class="row">
                                    <div
                                        class="col-sm-6 col-lg-3 col-6"
                                        v-for="(item, i) in adventures"
                                        :key="i"
                                    >
                                        <div
                                            class="tf-adventure flex-three mb-43"
                                        >
                                            <a href="#" class="adventure-image">
                                                <img :src="item.image" alt="" />
                                            </a>
                                            <div class="adventure-image">
                                                <span class="tour-ad"
                                                    >({{
                                                        item.tours
                                                    }}
                                                    Tour)</span
                                                >
                                                <h6 class="title-ad">
                                                    <a href="#">{{
                                                        item.title
                                                    }}</a>
                                                </h6>
                                                <p class="price-ad text-main">
                                                    {{ item.price }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Widget Adventure -->

    <!-- Widget Testimonial -->
    <section class="widget-testimonial-style01">
        <div class="tf-container">
            <div class="row">
                <div class="col-md-5 relative">
                    <div
                        class="image-box-tesimonial box-testimonial1 wow fadeInLeft"
                    >
                        <img
                            src="/template/images/page/testimonial1.jpg"
                            alt=""
                        />
                    </div>
                    <div
                        class="image-box-tesimonial box-testimonial2 wow fadeInUp"
                    >
                        <img
                            src="/template/images/page/testimonial2.jpg"
                            alt=""
                        />
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="widget-testimonial overflow-hidden">
                        <div class="swiper mySwiper2">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <div class="testimonial-content relative">
                                        <div class="profile mb-15">
                                            <h3 class="name">Piter Bowman</h3>
                                            <span class="job"
                                                >Business CEO</span
                                            >
                                        </div>
                                        <p class="tes">
                                            Leverage agile frameworks to provide
                                            a robust synopsis for high level
                                            overviews. Iterative in approaches
                                            to corporate strategy data foster go
                                            to collaborative thinking.
                                        </p>
                                        <span class="line"></span>
                                        <div class="icon-tes flex-five">
                                            <i
                                                class="icon-Group-1000002944"
                                            ></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="testimonial-content relative">
                                        <div class="profile mb-15">
                                            <h3 class="name">Sarah Johnson</h3>
                                            <span class="job"
                                                >Travel Blogger</span
                                            >
                                        </div>
                                        <p class="tes">
                                            Simply Safaris provided the most
                                            incredible experience. From start to
                                            finish, everything was perfectly
                                            organized and the guides were
                                            incredibly knowledgeable.
                                        </p>
                                        <span class="line"></span>
                                        <div class="icon-tes flex-five">
                                            <i
                                                class="icon-Group-1000002944"
                                            ></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="testimonial-content relative">
                                        <div class="profile mb-15">
                                            <h3 class="name">Michael Chen</h3>
                                            <span class="job"
                                                >Photographer</span
                                            >
                                        </div>
                                        <p class="tes">
                                            The safari was a dream come true.
                                            Capturing wildlife in their natural
                                            habitat with expert guidance made
                                            this trip unforgettable. Highly
                                            recommended!
                                        </p>
                                        <span class="line"></span>
                                        <div class="icon-tes flex-five">
                                            <i
                                                class="icon-Group-1000002944"
                                            ></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper testimonial-image">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide avata">
                                    <img
                                        src="/template/images/avata/9.jpg"
                                        alt="Image Testimonial"
                                    />
                                </div>
                                <div class="swiper-slide avata">
                                    <img
                                        src="/template/images/avata/7.jpg"
                                        alt="Image Testimonial"
                                    />
                                </div>
                                <div class="swiper-slide avata">
                                    <img
                                        src="/template/images/avata/8.jpg"
                                        alt="Image Testimonial"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Widget Testimonial -->

    <!-- Widget Banner Contact -->
    <section class="widget-banner-contact relative">
        <div class="tf-container">
            <div class="row z-index3 relative">
                <div class="col-lg-7 content-banner-contact">
                    <div class="mb-32">
                        <span
                            class="sub-title-heading text-main font-yes fs-28-46 mb-15"
                            >Explore the world</span
                        >
                        <h2 class="title-heading text-white">
                            Ready to travel with real adventure &amp; enjoy
                            natural
                        </h2>
                    </div>
                    <div class="flex-three">
                        <div class="video-wrap">
                            <a
                                href="https://www.youtube.com/watch?v=n9LgeoJE4EI"
                                class="widget-icon-video flex-five widget-videos"
                            >
                                <i class="icon-Polygon-4"></i>
                            </a>
                        </div>
                        <address>
                            Contact us at
                            <a href="mailto:info@simplysafaris.com"
                                >info@simplysafaris.com</a
                            ><br />
                        </address>
                    </div>
                    <img
                        src="/template/images/page/vector2.png"
                        alt="image"
                        class="mask-icon-banner"
                    />
                </div>
                <div class="col-lg-5">
                    <div class="image-banner-contact">
                        <img src="/template/images/page/ewewe1.png" alt="" />
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Widget Banner Contact -->

    <!-- Widget Banner Blog -->
    <section class="pd-main">
        <div class="tf-container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="center m0-auto w-text-heading">
                        <span
                            class="sub-title-heading text-main wow fadeInUp mb-15"
                            >Explore the world</span
                        >
                        <h2 class="title-heading wow fadeInUp mb-40">
                            Latest news &amp; articles
                            <span class="text-gray font-yes">from</span> the
                            blog
                        </h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4" v-for="(post, i) in blogPosts" :key="i">
                    <div class="tf-widget-blog blog-style">
                        <a href="#" class="blog-image">
                            <img :src="post.image" alt="" />
                            <div class="category-blog">
                                <i class="icon-Group-8"></i>
                                <span>{{ post.category }}</span>
                            </div>
                        </a>
                        <div class="blog-content">
                            <ul class="meta-list flex-three">
                                <li>
                                    <i class="icon-4"></i>
                                    <a href="#"
                                        ><span>{{ post.date }}</span></a
                                    >
                                </li>
                                <li>
                                    <i class="icon-7"></i>
                                    <a href="#"
                                        ><span
                                            >Comments ({{
                                                post.comments
                                            }})</span
                                        ></a
                                    >
                                </li>
                            </ul>
                            <h3 class="entry-title">
                                <a href="#">{{ post.title }}</a>
                            </h3>
                            <p class="des">
                                Business is the activity of making or buying and
                                selling products.
                            </p>
                            <a href="#" class="btn-read-more"
                                >Read More <i class="icon-Vector-4"></i
                            ></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Widget Banner Blog -->

    <!-- Bottom CTA -->
    <section class="mb--93">
        <div class="tf-container">
            <div class="callt-to-action flex-two">
                <div class="callt-to-action-content flex-three">
                    <div class="image">
                        <img
                            src="/template/images/page/ready.png"
                            alt="Image"
                        />
                    </div>
                    <div class="content">
                        <h2 class="title-call">
                            Ready to adventure and enjoy natural
                        </h2>
                        <p class="des">
                            Lorem ipsum dolor sit amet, consectetur notted
                            adipisicin
                        </p>
                    </div>
                </div>
                <img
                    src="/template/images/page/vector4.png"
                    alt=""
                    class="shape-ab"
                />
                <div class="callt-to-action-button">
                    <a href="#" class="get-call">Let's get started</a>
                </div>
            </div>
        </div>
    </section>
    <!-- Bottom CTA -->
</template>

<style scoped>
.duration-slider-panel {
    position: absolute;
    top: calc(100% + 8px);
    left: 0;
    width: 280px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    padding: 16px 20px;
    z-index: 100;
}

.slider-header {
    margin-bottom: 12px;
    font-weight: 600;
    font-size: 14px;
    color: #081e2a;
}

.slider-close {
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    color: #999;
    line-height: 1;
}

.slider-close:hover {
    color: #4da528;
}

.range-slider {
    position: relative;
    display: flex;
    align-items: center;
    width: 100%;
    height: 18px;
    margin-bottom: 8px;
    margin-top: 6px;
    user-select: none;
    touch-action: none;
}

.slider-track {
    position: relative;
    flex: 1;
    height: 4px;
    background: #e0e0e0;
    border-radius: 2px;
}

.slider-fill {
    position: absolute;
    height: 100%;
    background: #4da528;
    border-radius: 2px;
}

.slider-thumb {
    display: block;
    width: 18px;
    height: 18px;
    background: #4da528;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
    cursor: pointer;
}

.slider-thumb:focus-visible {
    outline: 2px solid rgba(77, 165, 40, 0.35);
    outline-offset: 2px;
}

.slider-labels {
    font-size: 12px;
    color: #999;
}
</style>
