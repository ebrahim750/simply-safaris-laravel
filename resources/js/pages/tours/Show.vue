<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { home } from '@/routes';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Navigation, Pagination } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

defineProps<{
    tour: {
        id: number;
        name: string;
        main_image: string | null;
        gallery_images: string[];
        price: string;
        price_amount: number;
        duration_days: number | null;
        overview: string | null;
        pricing_detailed: string | null;
        external_url: string | null;
        external_link_button_text: string | null;
        show_external_booking: boolean;
        categories: Array<{ id: number; name: string }>;
        activities: Array<{ id: number; name: string }>;
        places: Array<{ id: number; name: string; country?: string }>;
        itinerary_items: Array<{ title: string; content: string | null }>;
        inclusions: Array<{ text: string }>;
        exclusions: Array<{ text: string }>;
    };
}>();

const activeTab = ref('overview');

const tabs = [
    { id: 'overview', label: 'Overview', icon: 'icon-Vector-51' },
    { id: 'itinerary', label: 'Itinerary', icon: 'icon-destination-2-1' },
    { id: 'location', label: 'Location share', icon: 'icon-map-1' },
    { id: 'rates', label: 'Rates', icon: 'icon-price-tag-1' },
    { id: 'gallery', label: 'Gallery', icon: 'icon-image-gallery-1' },
];

function setTab(id: string): void {
    activeTab.value = id;
}
</script>

<template>
    <Head :title="tour.name" />

    <!-- Hero -->
    <section
        class="breadcumb-section hero-section"
        :style="{
            backgroundImage: tour.main_image
                ? `url(${tour.main_image})`
                : 'url(/template/images/page/breakcrumb.jpg)',
        }"
    >
        <div class="tf-container">
            <div class="row">
                <div class="col-lg-12 center z-index1">
                    <h1 class="title">{{ tour.name }}</h1>
                    <ul class="breadcumb-list flex-five">
                        <li><Link :href="home()">Home</Link></li>
                        <li><Link href="/tours">Tours</Link></li>
                        <li><span>{{ tour.name }}</span></li>
                    </ul>

                </div>
            </div>
        </div>
    </section>

    <!-- Tabs -->
    <section class="tour-single">
        <div class="tf-container">
            <div class="row">
                <div class="col-lg-12">
                    <ul
                        class="nav justify-content-between tab-tour-single"
                        style="position: relative; z-index: 2"
                        role="tablist"
                    >
                        <li
                            v-for="tab in tabs"
                            :key="tab.id"
                            class="nav-item"
                            role="presentation"
                        >
                            <button
                                :class="[
                                    'nav-link',
                                    { active: activeTab === tab.id },
                                ]"
                                type="button"
                                role="tab"
                                :aria-selected="activeTab === tab.id"
                                @click="setTab(tab.id)"
                            >
                                <i :class="tab.icon"></i> {{ tab.label }}
                            </button>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="row pd-main">
                <div class="col-lg-12">
                    <div class="tab-content">
                        <!-- Overview Tab -->
                        <div
                            v-show="activeTab === 'overview'"
                            class="tab-pane fade show active"
                            role="tabpanel"
                            tabindex="0"
                        >
                            <div class="row mb-50">
                                <div class="col-lg-12">
                                    <div class="inner-heading-wrap flex-two">
                                        <div class="inner-heading">
                                            <h2 class="title">{{ tour.name }}</h2>
                                            <ul class="flex-three list-wrap-heading">
                                                <li
                                                    v-if="tour.duration_days"
                                                    class="flex-three"
                                                >
                                                    <i class="icon-time-left"></i>
                                                    <span
                                                        >{{ tour.duration_days }}
                                                        {{
                                                            tour.duration_days === 1
                                                                ? 'day'
                                                                : 'days'
                                                        }}</span
                                                    >
                                                </li>
                                                <li
                                                    v-if="tour.places.length > 0"
                                                    class="flex-three"
                                                >
                                                    <i class="icon-18"></i>
                                                    <span>{{ tour.places.map((p) => p.name).join(', ') }}</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="inner-price">
                                            <p>
                                                <span style="margin-right: 4px">From</span> <span class="price-sale text-main">{{ tour.price }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-40">
                                <div class="col-lg-12">
                                    <Swiper
                                        v-if="tour.gallery_images.length > 0"
                                        :modules="[Navigation, Pagination]"
                                        navigation
                                        :pagination="{ clickable: true }"
                                        :slides-per-view="4"
                                        :space-between="16"
                                        :breakpoints="{
                                            320: { slidesPerView: 1, spaceBetween: 12 },
                                            640: { slidesPerView: 2, spaceBetween: 12 },
                                            992: { slidesPerView: 3, spaceBetween: 16 },
                                            1200: { slidesPerView: 4, spaceBetween: 16 },
                                        }"
                                        class="tour-gallery-swiper"
                                    >
                                        <SwiperSlide
                                            v-for="(
                                                img, index
                                            ) in tour.gallery_images"
                                            :key="index"
                                        >
                                            <img
                                                :src="img"
                                                :alt="`${tour.name} gallery image ${index + 1}`"
                                            />
                                        </SwiperSlide>
                                    </Swiper>
                                    <div
                                        v-else
                                        class="gallery-placeholder"
                                    >
                                        <img
                                            src="/template/images/destination/1.jpg"
                                            alt="image"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="information-content-tour">
                                        <div
                                            v-if="tour.overview"
                                            class="description-wrap mb-40"
                                        >
                                            <span class="description"
                                                >Description:</span
                                            >
                                            <div
                                                class="des"
                                                v-html="tour.overview"
                                            ></div>
                                        </div>

                                        <div class="description-wrap mb-40">
                                            <span class="description"
                                                >Advance Facilities</span
                                            >
                                            <p class="des">
                                                Leave your guidebooks at home and
                                                dive into the local cultures that
                                                make each destination so special.
                                                We'll connect you with our
                                                exclusive experiences. Each trip is
                                                carefully crafted to let enjoy your
                                                vacation.
                                            </p>
                                        </div>

                                        <div class="description-wrap mb-40">
                                            <span class="description"
                                                >What to Expect</span
                                            >
                                            <p class="des mb-18">
                                                Leave your guidebooks at home and
                                                dive into the local cultures that
                                                make each destination so special.
                                                We'll connect you with our
                                                exclusive experiences. Each trip is
                                                carefully crafted to let enjoy your
                                                vacation.
                                            </p>
                                            <ul class="listing-des">
                                                <li><p>View the City Walls</p></li>
                                                <li><p>Hiking in the forest</p></li>
                                                <li>
                                                    <p>
                                                        Discover the famous view
                                                        point "The Lark"
                                                    </p>
                                                </li>
                                                <li><p>Sunset on the cruise</p></li>
                                            </ul>
                                        </div>

                                        <div class="expect-wrap mb-70">
                                            <h4 class="title mb-18">
                                                What to Expect
                                            </h4>
                                            <div class="expect flex-three">
                                                <span>Departure/Return Location</span>
                                                <p>
                                                    2 Taijichang St, Dongcheng Qu,
                                                    Beijing Shi, China
                                                </p>
                                            </div>
                                            <div class="expect flex-three">
                                                <span>Departure Time</span>
                                                <p>
                                                    Please arrive by 9:15 AM for a
                                                    departure at 9:30 AM
                                                </p>
                                            </div>
                                            <div class="expect flex-three">
                                                <span>Return Time</span>
                                                <p>Approximately 8:30 PM</p>
                                            </div>
                                            <div class="expect flex-three">
                                                <span>Return Time</span>
                                                <p>Approximately 8:30 PM</p>
                                            </div>
                                            <div class="expect flex-three">
                                                <span>Return Time</span>
                                                <p>Approximately 8:30 PM</p>
                                            </div>
                                        </div>

                                        <div class="expect-wrap mb-70">
                                            <h4 class="title mb-40">
                                                Included/Exclude
                                            </h4>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <ul class="listing-clude">
                                                        <li class="flex-three">
                                                            <i class="icon-Vector-7"></i>
                                                            <p>
                                                                Pick and Drop
                                                                Services
                                                            </p>
                                                        </li>
                                                        <li class="flex-three">
                                                            <i class="icon-Vector-7"></i>
                                                            <p>1 Meal Per Day</p>
                                                        </li>
                                                        <li class="flex-three">
                                                            <i class="icon-Vector-7"></i>
                                                            <p>
                                                                Cruise Dinner &
                                                                Music Event
                                                            </p>
                                                        </li>
                                                        <li class="flex-three">
                                                            <i class="icon-Vector-7"></i>
                                                            <p>
                                                                Visit 7 Best Places
                                                                in the City With
                                                                Group
                                                            </p>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="col-md-6">
                                                    <ul class="listing-clude">
                                                        <li class="flex-three">
                                                            <i class="icon-Vector-7"></i>
                                                            <p>
                                                                Pick and Drop
                                                                Services
                                                            </p>
                                                        </li>
                                                        <li class="flex-three">
                                                            <i class="icon-Vector-7"></i>
                                                            <p>1 Meal Per Day</p>
                                                        </li>
                                                        <li class="flex-three">
                                                            <i class="icon-Vector-7"></i>
                                                            <p>
                                                                Cruise Dinner &
                                                                Music Event
                                                            </p>
                                                        </li>
                                                        <li class="flex-three">
                                                            <i class="icon-Vector-7"></i>
                                                            <p>
                                                                Visit 7 Best Places
                                                                in the City With
                                                                Group
                                                            </p>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="expect-wrap">
                                            <h4 class="title mb-40">
                                                Tour Amenities
                                            </h4>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <ul class="listing-icon">
                                                        <li class="flex-three">
                                                            <i class="icon-10"></i>
                                                            <p>Air Conditioning</p>
                                                        </li>
                                                        <li class="flex-three">
                                                            <i class="icon-10"></i>
                                                            <p>Microwave</p>
                                                        </li>
                                                        <li class="flex-three">
                                                            <i class="icon-10"></i>
                                                            <p>Central Heating</p>
                                                        </li>
                                                        <li class="flex-three">
                                                            <i class="icon-10"></i>
                                                            <p>Central Heating</p>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="col-md-4">
                                                    <ul class="listing-icon">
                                                        <li class="flex-three">
                                                            <i class="icon-10"></i>
                                                            <p>Laundry</p>
                                                        </li>
                                                        <li class="flex-three">
                                                            <i class="icon-10"></i>
                                                            <p>Outdoor Shower</p>
                                                        </li>
                                                        <li class="flex-three">
                                                            <i class="icon-10"></i>
                                                            <p>Swimming Pool</p>
                                                        </li>
                                                        <li class="flex-three">
                                                            <i class="icon-10"></i>
                                                            <p>Wifi</p>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="col-md-4">
                                                    <ul class="listing-icon">
                                                        <li class="flex-three">
                                                            <i class="icon-10"></i>
                                                            <p>Window Covering</p>
                                                        </li>
                                                        <li class="flex-three">
                                                            <i class="icon-10"></i>
                                                            <p>Refrigerator</p>
                                                        </li>
                                                        <li class="flex-three">
                                                            <i class="icon-10"></i>
                                                            <p>Alarm System</p>
                                                        </li>
                                                        <li class="flex-three">
                                                            <i class="icon-10"></i>
                                                            <p>Window Coverings</p>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sidebar -->
                                <div class="col-lg-4">
                                    <div class="side-bar-right">
                                        <div class="sidebar-widget">
                                            <h6 class="block-heading">
                                                Book This Tour
                                            </h6>
                                            <form action="/" id="form-book-tour">
                                                <div class="input-wrap mb-30">
                                                    <input type="date" />
                                                </div>
                                                <div class="flex-two mb-30">
                                                    <span class="label">Time:</span>
                                                    <div class="radio">
                                                        <input
                                                            id="first"
                                                            type="radio"
                                                            name="numbers"
                                                            value="first"
                                                            checked
                                                        />
                                                        <label for="first"
                                                            >14.00</label
                                                        >
                                                        <input
                                                            id="second"
                                                            type="radio"
                                                            name="numbers"
                                                            value="second"
                                                        />
                                                        <label for="second"
                                                            >16.00</label
                                                        >
                                                    </div>
                                                </div>
                                                <div
                                                    class="input-wrap-sellect mb-30"
                                                >
                                                    <span class="label"
                                                        >Tickets:</span
                                                    >
                                                    <div class="flex-two mb-15">
                                                        <p>
                                                            Children (0-12
                                                            years)$129.00
                                                        </p>
                                                        <div
                                                            class="nice-select"
                                                            tabindex="0"
                                                        >
                                                            <span class="current"
                                                                >1</span
                                                            >
                                                            <ul class="list">
                                                                <li
                                                                    data-value=""
                                                                    class="option selected focus"
                                                                >
                                                                    1
                                                                </li>
                                                                <li
                                                                    data-value="2"
                                                                    class="option"
                                                                >
                                                                    2
                                                                </li>
                                                                <li
                                                                    data-value="3"
                                                                    class="option"
                                                                >
                                                                    3
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="flex-two mb-15">
                                                        <p>
                                                            Youth (13-17
                                                            years)$169.00
                                                        </p>
                                                        <div
                                                            class="nice-select"
                                                            tabindex="0"
                                                        >
                                                            <span class="current"
                                                                >1</span
                                                            >
                                                            <ul class="list">
                                                                <li
                                                                    data-value=""
                                                                    class="option selected focus"
                                                                >
                                                                    1
                                                                </li>
                                                                <li
                                                                    data-value="2"
                                                                    class="option"
                                                                >
                                                                    2
                                                                </li>
                                                                <li
                                                                    data-value="3"
                                                                    class="option"
                                                                >
                                                                    3
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="flex-two">
                                                        <p>
                                                            Adult (18+)
                                                            years)$189.00
                                                        </p>
                                                        <div
                                                            class="nice-select"
                                                            tabindex="0"
                                                        >
                                                            <span class="current"
                                                                >1</span
                                                            >
                                                            <ul class="list">
                                                                <li
                                                                    data-value=""
                                                                    class="option selected focus"
                                                                >
                                                                    1
                                                                </li>
                                                                <li
                                                                    data-value="2"
                                                                    class="option"
                                                                >
                                                                    2
                                                                </li>
                                                                <li
                                                                    data-value="3"
                                                                    class="option"
                                                                >
                                                                    3
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div
                                                    class="input-wrap-checkbox mb-30"
                                                >
                                                    <span class="label"
                                                        >Add Extra</span
                                                    >
                                                    <div class="checkbox">
                                                        <input
                                                            id="check"
                                                            type="checkbox"
                                                            name="check"
                                                            value="check"
                                                        />
                                                        <label for="check"
                                                            >Service per
                                                            booking</label
                                                        >
                                                    </div>
                                                    <div class="checkbox">
                                                        <input
                                                            id="check1"
                                                            type="checkbox"
                                                            name="check"
                                                            value="check"
                                                        />
                                                        <label for="check1"
                                                            >Service per
                                                            person</label
                                                        >
                                                    </div>
                                                    <div class="extra">
                                                        <div class="flex-three">
                                                            <span class="name"
                                                                >Adult:</span
                                                            >
                                                            <span class="price"
                                                                >$18.00</span
                                                            >
                                                        </div>
                                                        <div class="flex-three">
                                                            <span class="name"
                                                                >Youth:</span
                                                            >
                                                            <span class="price"
                                                                >$16.00</span
                                                            >
                                                        </div>
                                                        <div class="flex-three">
                                                            <span class="name"
                                                                >Children:</span
                                                            >
                                                            <span class="price"
                                                                >$16.00</span
                                                            >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex-two mb-40">
                                                    <span class="label">Total:</span>
                                                    <span class="total text-main"
                                                        >$130.00</span
                                                    >
                                                </div>
                                                <button type="submit">
                                                    Procced Booking
                                                </button>
                                            </form>
                                        </div>
                                        <div class="sidebar-widget">
                                            <h6 class="block-heading">
                                                Book With Confidence
                                            </h6>
                                            <ul class="category-confidence">
                                                <li class="flex-three">
                                                    <i
                                                        class="icon-customer-service-1"
                                                    ></i>
                                                    <span
                                                        >Customer care available
                                                        24/7</span
                                                    >
                                                </li>
                                                <li class="flex-three">
                                                    <i class="icon-Vector-6"></i>
                                                    <span
                                                        >Hand-picked Tours &
                                                        Activities</span
                                                    >
                                                </li>
                                                <li class="flex-three">
                                                    <i class="icon-insurance-1"></i>
                                                    <span
                                                        >Free Travel
                                                        Insureance</span
                                                    >
                                                </li>
                                                <li class="flex-three">
                                                    <i
                                                        class="icon-price-tag-1-1"
                                                    ></i>
                                                    <span
                                                        >No-hassle best price
                                                        guarantee</span
                                                    >
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="sidebar-widget">
                                            <h4 class="block-heading">
                                                Recent News
                                            </h4>
                                            <div class="recent-post-list">
                                                <div class="list-recent flex-three">
                                                    <a
                                                        href="#"
                                                        class="recent-image"
                                                    >
                                                        <img
                                                            src="/template/images/blog/re-blog1.jpg"
                                                            alt="Image"
                                                        />
                                                    </a>
                                                    <div class="recent-info">
                                                        <div class="start">
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                        </div>
                                                        <h4 class="title">
                                                            <a href="#"
                                                                >Walking the
                                                                Amalfi Coast</a
                                                            >
                                                        </h4>
                                                        <p>
                                                            From
                                                            <span class="text-main"
                                                                >$129.00</span
                                                            >
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="list-recent flex-three">
                                                    <a
                                                        href="#"
                                                        class="recent-image"
                                                    >
                                                        <img
                                                            src="/template/images/blog/re-blog2.jpg"
                                                            alt="Image"
                                                        />
                                                    </a>
                                                    <div class="recent-info">
                                                        <div class="start">
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                        </div>
                                                        <h4 class="title">
                                                            <a href="#"
                                                                >Walking the
                                                                Amalfi Coast</a
                                                            >
                                                        </h4>
                                                        <p>
                                                            From
                                                            <span class="text-main"
                                                                >$129.00</span
                                                            >
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="list-recent flex-three">
                                                    <a
                                                        href="#"
                                                        class="recent-image"
                                                    >
                                                        <img
                                                            src="/template/images/blog/re-blog3.jpg"
                                                            alt="Image"
                                                        />
                                                    </a>
                                                    <div class="recent-info">
                                                        <div class="start">
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                        </div>
                                                        <h4 class="title">
                                                            <a href="#"
                                                                >Walking the
                                                                Amalfi Coast</a
                                                            >
                                                        </h4>
                                                        <p>
                                                            From
                                                            <span class="text-main"
                                                                >$129.00</span
                                                            >
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Itinerary Tab -->
                        <div
                            v-show="activeTab === 'itinerary'"
                            class="tab-pane fade show active"
                            role="tabpanel"
                            tabindex="0"
                        >
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="planing-content-tour">
                                        <h3 class="title-plan">Tour Plan :</h3>
                                        <div
                                            v-for="(
                                                item, index
                                            ) in tour.itinerary_items"
                                            :key="index"
                                            class="tour-planing-section flex"
                                        >
                                            <div class="number-box flex-five">
                                                {{ String(index + 1).padStart(2, '0') }}
                                            </div>
                                            <div class="content-box">
                                                <h5 class="title">
                                                    {{ item.title }}
                                                </h5>
                                                <div
                                                    v-if="item.content"
                                                    class="des"
                                                    v-html="item.content"
                                                ></div>
                                            </div>
                                        </div>

                                        <div
                                            v-if="tour.itinerary_items.length === 0"
                                            class="tour-planing-section flex"
                                        >
                                            <div class="content-box">
                                                <p class="des">
                                                    No itinerary items available for
                                                    this tour.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="side-bar-right">
                                        <div class="sidebar-widget">
                                            <h6 class="block-heading">
                                                Book This Tour
                                            </h6>
                                            <form action="/" id="form-book-tour">
                                                <div class="input-wrap mb-30">
                                                    <input type="date" />
                                                </div>
                                                <div class="flex-two mb-30">
                                                    <span class="label">Time:</span>
                                                    <div class="radio">
                                                        <input
                                                            id="first-it"
                                                            type="radio"
                                                            name="numbers-it"
                                                            value="first"
                                                            checked
                                                        />
                                                        <label for="first-it"
                                                            >14.00</label
                                                        >
                                                        <input
                                                            id="second-it"
                                                            type="radio"
                                                            name="numbers-it"
                                                            value="second"
                                                        />
                                                        <label for="second-it"
                                                            >16.00</label
                                                        >
                                                    </div>
                                                </div>
                                                <div
                                                    class="input-wrap-sellect mb-30"
                                                >
                                                    <span class="label"
                                                        >Tickets:</span
                                                    >
                                                    <div class="flex-two mb-15">
                                                        <p>
                                                            Children (0-12
                                                            years)$129.00
                                                        </p>
                                                        <div
                                                            class="nice-select"
                                                            tabindex="0"
                                                        >
                                                            <span class="current"
                                                                >1</span
                                                            >
                                                            <ul class="list">
                                                                <li
                                                                    data-value=""
                                                                    class="option selected focus"
                                                                >
                                                                    1
                                                                </li>
                                                                <li
                                                                    data-value="2"
                                                                    class="option"
                                                                >
                                                                    2
                                                                </li>
                                                                <li
                                                                    data-value="3"
                                                                    class="option"
                                                                >
                                                                    3
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="flex-two mb-15">
                                                        <p>
                                                            Youth (13-17
                                                            years)$169.00
                                                        </p>
                                                        <div
                                                            class="nice-select"
                                                            tabindex="0"
                                                        >
                                                            <span class="current"
                                                                >1</span
                                                            >
                                                            <ul class="list">
                                                                <li
                                                                    data-value=""
                                                                    class="option selected focus"
                                                                >
                                                                    1
                                                                </li>
                                                                <li
                                                                    data-value="2"
                                                                    class="option"
                                                                >
                                                                    2
                                                                </li>
                                                                <li
                                                                    data-value="3"
                                                                    class="option"
                                                                >
                                                                    3
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="flex-two">
                                                        <p>
                                                            Adult (18+)
                                                            years)$189.00
                                                        </p>
                                                        <div
                                                            class="nice-select"
                                                            tabindex="0"
                                                        >
                                                            <span class="current"
                                                                >1</span
                                                            >
                                                            <ul class="list">
                                                                <li
                                                                    data-value=""
                                                                    class="option selected focus"
                                                                >
                                                                    1
                                                                </li>
                                                                <li
                                                                    data-value="2"
                                                                    class="option"
                                                                >
                                                                    2
                                                                </li>
                                                                <li
                                                                    data-value="3"
                                                                    class="option"
                                                                >
                                                                    3
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div
                                                    class="input-wrap-checkbox mb-30"
                                                >
                                                    <span class="label"
                                                        >Add Extra</span
                                                    >
                                                    <div class="checkbox">
                                                        <input
                                                            id="check-it"
                                                            type="checkbox"
                                                            name="check-it"
                                                            value="check"
                                                        />
                                                        <label for="check-it"
                                                            >Service per
                                                            booking</label
                                                        >
                                                    </div>
                                                    <div class="checkbox">
                                                        <input
                                                            id="check1-it"
                                                            type="checkbox"
                                                            name="check-it"
                                                            value="check"
                                                        />
                                                        <label for="check1-it"
                                                            >Service per
                                                            person</label
                                                        >
                                                    </div>
                                                    <div class="extra">
                                                        <div class="flex-three">
                                                            <span class="name"
                                                                >Adult:</span
                                                            >
                                                            <span class="price"
                                                                >$18.00</span
                                                            >
                                                        </div>
                                                        <div class="flex-three">
                                                            <span class="name"
                                                                >Youth:</span
                                                            >
                                                            <span class="price"
                                                                >$16.00</span
                                                            >
                                                        </div>
                                                        <div class="flex-three">
                                                            <span class="name"
                                                                >Children:</span
                                                            >
                                                            <span class="price"
                                                                >$16.00</span
                                                            >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex-two mb-40">
                                                    <span class="label">Total:</span>
                                                    <span class="total text-main"
                                                        >$130.00</span
                                                    >
                                                </div>
                                                <button type="submit">
                                                    Procced Booking
                                                </button>
                                            </form>
                                        </div>
                                        <div class="sidebar-widget">
                                            <h6 class="block-heading">
                                                Book With Confidence
                                            </h6>
                                            <ul class="category-confidence">
                                                <li class="flex-three">
                                                    <i
                                                        class="icon-customer-service-1"
                                                    ></i>
                                                    <span
                                                        >Customer care available
                                                        24/7</span
                                                    >
                                                </li>
                                                <li class="flex-three">
                                                    <i class="icon-Vector-6"></i>
                                                    <span
                                                        >Hand-picked Tours &
                                                        Activities</span
                                                    >
                                                </li>
                                                <li class="flex-three">
                                                    <i class="icon-insurance-1"></i>
                                                    <span
                                                        >Free Travel
                                                        Insureance</span
                                                    >
                                                </li>
                                                <li class="flex-three">
                                                    <i
                                                        class="icon-price-tag-1-1"
                                                    ></i>
                                                    <span
                                                        >No-hassle best price
                                                        guarantee</span
                                                    >
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="sidebar-widget">
                                            <h4 class="block-heading">
                                                Recent News
                                            </h4>
                                            <div class="recent-post-list">
                                                <div class="list-recent flex-three">
                                                    <a
                                                        href="#"
                                                        class="recent-image"
                                                    >
                                                        <img
                                                            src="/template/images/blog/re-blog1.jpg"
                                                            alt="Image"
                                                        />
                                                    </a>
                                                    <div class="recent-info">
                                                        <div class="start">
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                        </div>
                                                        <h4 class="title">
                                                            <a href="#"
                                                                >Walking the
                                                                Amalfi Coast</a
                                                            >
                                                        </h4>
                                                        <p>
                                                            From
                                                            <span class="text-main"
                                                                >$129.00</span
                                                            >
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="list-recent flex-three">
                                                    <a
                                                        href="#"
                                                        class="recent-image"
                                                    >
                                                        <img
                                                            src="/template/images/blog/re-blog2.jpg"
                                                            alt="Image"
                                                        />
                                                    </a>
                                                    <div class="recent-info">
                                                        <div class="start">
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                        </div>
                                                        <h4 class="title">
                                                            <a href="#"
                                                                >Walking the
                                                                Amalfi Coast</a
                                                            >
                                                        </h4>
                                                        <p>
                                                            From
                                                            <span class="text-main"
                                                                >$129.00</span
                                                            >
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="list-recent flex-three">
                                                    <a
                                                        href="#"
                                                        class="recent-image"
                                                    >
                                                        <img
                                                            src="/template/images/blog/re-blog3.jpg"
                                                            alt="Image"
                                                        />
                                                    </a>
                                                    <div class="recent-info">
                                                        <div class="start">
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                        </div>
                                                        <h4 class="title">
                                                            <a href="#"
                                                                >Walking the
                                                                Amalfi Coast</a
                                                            >
                                                        </h4>
                                                        <p>
                                                            From
                                                            <span class="text-main"
                                                                >$129.00</span
                                                            >
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Location share Tab -->
                        <div
                            v-show="activeTab === 'location'"
                            class="tab-pane fade show active"
                            role="tabpanel"
                            tabindex="0"
                        >
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="localtion-content-tour">
                                        <div class="map2 relative mb-32">
                                            <div id="map2"></div>
                                        </div>
                                        <div class="flex-three map-list mb-50">
                                            <i class="icon-18"></i>
                                            <p>1421 San Pedro St, Los Angeles, CA</p>
                                        </div>
                                        <h3 class="title-location">
                                            Description:
                                        </h3>
                                        <p class="des mb-22">
                                            Description It is a long established
                                            fact that a reader will be distrac by
                                            any websites look for ways mornings
                                            even of spring prevent AdBlock from
                                            blocking annoying ads. As a result,
                                            we've focused on improving our funct
                                            walk so that we can overcome these
                                            anti-ad blocking attempts. Of course,
                                            you can help us continue improve our
                                            ad blocking ability by reporting any
                                            time you run into a website that won't
                                            allow you to block the readable dine
                                            content of a page when looking at its
                                            layout. It is a long established fact
                                        </p>
                                        <ul class="listing-des">
                                            <li><p>View the City Walls</p></li>
                                            <li><p>Hiking in the forest</p></li>
                                            <li>
                                                <p>
                                                    Discover the famous view point
                                                    "The Lark"
                                                </p>
                                            </li>
                                            <li><p>Sunset on the cruise</p></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="side-bar-right">
                                        <div class="sidebar-widget">
                                            <h6 class="block-heading">
                                                Book This Tour
                                            </h6>
                                            <form action="/" id="form-book-tour">
                                                <div class="input-wrap mb-30">
                                                    <input type="date" />
                                                </div>
                                                <div class="flex-two mb-30">
                                                    <span class="label">Time:</span>
                                                    <div class="radio">
                                                        <input
                                                            id="first-loc"
                                                            type="radio"
                                                            name="numbers-loc"
                                                            value="first"
                                                            checked
                                                        />
                                                        <label for="first-loc"
                                                            >14.00</label
                                                        >
                                                        <input
                                                            id="second-loc"
                                                            type="radio"
                                                            name="numbers-loc"
                                                            value="second"
                                                        />
                                                        <label for="second-loc"
                                                            >16.00</label
                                                        >
                                                    </div>
                                                </div>
                                                <div
                                                    class="input-wrap-sellect mb-30"
                                                >
                                                    <span class="label"
                                                        >Tickets:</span
                                                    >
                                                    <div class="flex-two mb-15">
                                                        <p>
                                                            Children (0-12
                                                            years)$129.00
                                                        </p>
                                                        <div
                                                            class="nice-select"
                                                            tabindex="0"
                                                        >
                                                            <span class="current"
                                                                >1</span
                                                            >
                                                            <ul class="list">
                                                                <li
                                                                    data-value=""
                                                                    class="option selected focus"
                                                                >
                                                                    1
                                                                </li>
                                                                <li
                                                                    data-value="2"
                                                                    class="option"
                                                                >
                                                                    2
                                                                </li>
                                                                <li
                                                                    data-value="3"
                                                                    class="option"
                                                                >
                                                                    3
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="flex-two mb-15">
                                                        <p>
                                                            Youth (13-17
                                                            years)$169.00
                                                        </p>
                                                        <div
                                                            class="nice-select"
                                                            tabindex="0"
                                                        >
                                                            <span class="current"
                                                                >1</span
                                                            >
                                                            <ul class="list">
                                                                <li
                                                                    data-value=""
                                                                    class="option selected focus"
                                                                >
                                                                    1
                                                                </li>
                                                                <li
                                                                    data-value="2"
                                                                    class="option"
                                                                >
                                                                    2
                                                                </li>
                                                                <li
                                                                    data-value="3"
                                                                    class="option"
                                                                >
                                                                    3
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="flex-two">
                                                        <p>
                                                            Adult (18+)
                                                            years)$189.00
                                                        </p>
                                                        <div
                                                            class="nice-select"
                                                            tabindex="0"
                                                        >
                                                            <span class="current"
                                                                >1</span
                                                            >
                                                            <ul class="list">
                                                                <li
                                                                    data-value=""
                                                                    class="option selected focus"
                                                                >
                                                                    1
                                                                </li>
                                                                <li
                                                                    data-value="2"
                                                                    class="option"
                                                                >
                                                                    2
                                                                </li>
                                                                <li
                                                                    data-value="3"
                                                                    class="option"
                                                                >
                                                                    3
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div
                                                    class="input-wrap-checkbox mb-30"
                                                >
                                                    <span class="label"
                                                        >Add Extra</span
                                                    >
                                                    <div class="checkbox">
                                                        <input
                                                            id="check-loc"
                                                            type="checkbox"
                                                            name="check-loc"
                                                            value="check"
                                                        />
                                                        <label for="check-loc"
                                                            >Service per
                                                            booking</label
                                                        >
                                                    </div>
                                                    <div class="checkbox">
                                                        <input
                                                            id="check1-loc"
                                                            type="checkbox"
                                                            name="check-loc"
                                                            value="check"
                                                        />
                                                        <label for="check1-loc"
                                                            >Service per
                                                            person</label
                                                        >
                                                    </div>
                                                    <div class="extra">
                                                        <div class="flex-three">
                                                            <span class="name"
                                                                >Adult:</span
                                                            >
                                                            <span class="price"
                                                                >$18.00</span
                                                            >
                                                        </div>
                                                        <div class="flex-three">
                                                            <span class="name"
                                                                >Youth:</span
                                                            >
                                                            <span class="price"
                                                                >$16.00</span
                                                            >
                                                        </div>
                                                        <div class="flex-three">
                                                            <span class="name"
                                                                >Children:</span
                                                            >
                                                            <span class="price"
                                                                >$16.00</span
                                                            >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex-two mb-40">
                                                    <span class="label">Total:</span>
                                                    <span class="total text-main"
                                                        >$130.00</span
                                                    >
                                                </div>
                                                <button type="submit">
                                                    Procced Booking
                                                </button>
                                            </form>
                                        </div>
                                        <div class="sidebar-widget">
                                            <h6 class="block-heading">
                                                Book With Confidence
                                            </h6>
                                            <ul class="category-confidence">
                                                <li class="flex-three">
                                                    <i
                                                        class="icon-customer-service-1"
                                                    ></i>
                                                    <span
                                                        >Customer care available
                                                        24/7</span
                                                    >
                                                </li>
                                                <li class="flex-three">
                                                    <i class="icon-Vector-6"></i>
                                                    <span
                                                        >Hand-picked Tours &
                                                        Activities</span
                                                    >
                                                </li>
                                                <li class="flex-three">
                                                    <i class="icon-insurance-1"></i>
                                                    <span
                                                        >Free Travel
                                                        Insureance</span
                                                    >
                                                </li>
                                                <li class="flex-three">
                                                    <i
                                                        class="icon-price-tag-1-1"
                                                    ></i>
                                                    <span
                                                        >No-hassle best price
                                                        guarantee</span
                                                    >
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="sidebar-widget">
                                            <h4 class="block-heading">
                                                Recent News
                                            </h4>
                                            <div class="recent-post-list">
                                                <div class="list-recent flex-three">
                                                    <a
                                                        href="#"
                                                        class="recent-image"
                                                    >
                                                        <img
                                                            src="/template/images/blog/re-blog1.jpg"
                                                            alt="Image"
                                                        />
                                                    </a>
                                                    <div class="recent-info">
                                                        <div class="start">
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                        </div>
                                                        <h4 class="title">
                                                            <a href="#"
                                                                >Walking the
                                                                Amalfi Coast</a
                                                            >
                                                        </h4>
                                                        <p>
                                                            From
                                                            <span class="text-main"
                                                                >$129.00</span
                                                            >
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="list-recent flex-three">
                                                    <a
                                                        href="#"
                                                        class="recent-image"
                                                    >
                                                        <img
                                                            src="/template/images/blog/re-blog2.jpg"
                                                            alt="Image"
                                                        />
                                                    </a>
                                                    <div class="recent-info">
                                                        <div class="start">
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                        </div>
                                                        <h4 class="title">
                                                            <a href="#"
                                                                >Walking the
                                                                Amalfi Coast</a
                                                            >
                                                        </h4>
                                                        <p>
                                                            From
                                                            <span class="text-main"
                                                                >$129.00</span
                                                            >
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="list-recent flex-three">
                                                    <a
                                                        href="#"
                                                        class="recent-image"
                                                    >
                                                        <img
                                                            src="/template/images/blog/re-blog3.jpg"
                                                            alt="Image"
                                                        />
                                                    </a>
                                                    <div class="recent-info">
                                                        <div class="start">
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                        </div>
                                                        <h4 class="title">
                                                            <a href="#"
                                                                >Walking the
                                                                Amalfi Coast</a
                                                            >
                                                        </h4>
                                                        <p>
                                                            From
                                                            <span class="text-main"
                                                                >$129.00</span
                                                            >
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Rates Tab -->
                        <div
                            v-show="activeTab === 'rates'"
                            class="tab-pane fade show active"
                            role="tabpanel"
                            tabindex="0"
                        >
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="review-content-tour">
                                        <div
                                            v-if="tour.pricing_detailed"
                                            class="description-wrap mb-40"
                                        >
                                            <span class="description"
                                                >Detailed Pricing:</span
                                            >
                                            <div
                                                class="des"
                                                v-html="tour.pricing_detailed"
                                            ></div>
                                        </div>
                                        <div
                                            v-else
                                            class="description-wrap mb-40"
                                        >
                                            <p class="des">
                                                Detailed pricing information is not
                                                available for this tour.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="side-bar-right">
                                        <div class="sidebar-widget">
                                            <h6 class="block-heading">
                                                Book This Tour
                                            </h6>
                                            <form action="/" id="form-book-tour">
                                                <div class="input-wrap mb-30">
                                                    <input type="date" />
                                                </div>
                                                <div class="flex-two mb-30">
                                                    <span class="label">Time:</span>
                                                    <div class="radio">
                                                        <input
                                                            id="first-rates"
                                                            type="radio"
                                                            name="numbers-rates"
                                                            value="first"
                                                            checked
                                                        />
                                                        <label for="first-rates"
                                                            >14.00</label
                                                        >
                                                        <input
                                                            id="second-rates"
                                                            type="radio"
                                                            name="numbers-rates"
                                                            value="second"
                                                        />
                                                        <label for="second-rates"
                                                            >16.00</label
                                                        >
                                                    </div>
                                                </div>
                                                <div
                                                    class="input-wrap-sellect mb-30"
                                                >
                                                    <span class="label"
                                                        >Tickets:</span
                                                    >
                                                    <div class="flex-two mb-15">
                                                        <p>
                                                            Children (0-12
                                                            years)$129.00
                                                        </p>
                                                        <div
                                                            class="nice-select"
                                                            tabindex="0"
                                                        >
                                                            <span class="current"
                                                                >1</span
                                                            >
                                                            <ul class="list">
                                                                <li
                                                                    data-value=""
                                                                    class="option selected focus"
                                                                >
                                                                    1
                                                                </li>
                                                                <li
                                                                    data-value="2"
                                                                    class="option"
                                                                >
                                                                    2
                                                                </li>
                                                                <li
                                                                    data-value="3"
                                                                    class="option"
                                                                >
                                                                    3
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="flex-two mb-15">
                                                        <p>
                                                            Youth (13-17
                                                            years)$169.00
                                                        </p>
                                                        <div
                                                            class="nice-select"
                                                            tabindex="0"
                                                        >
                                                            <span class="current"
                                                                >1</span
                                                            >
                                                            <ul class="list">
                                                                <li
                                                                    data-value=""
                                                                    class="option selected focus"
                                                                >
                                                                    1
                                                                </li>
                                                                <li
                                                                    data-value="2"
                                                                    class="option"
                                                                >
                                                                    2
                                                                </li>
                                                                <li
                                                                    data-value="3"
                                                                    class="option"
                                                                >
                                                                    3
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="flex-two">
                                                        <p>
                                                            Adult (18+)
                                                            years)$189.00
                                                        </p>
                                                        <div
                                                            class="nice-select"
                                                            tabindex="0"
                                                        >
                                                            <span class="current"
                                                                >1</span
                                                            >
                                                            <ul class="list">
                                                                <li
                                                                    data-value=""
                                                                    class="option selected focus"
                                                                >
                                                                    1
                                                                </li>
                                                                <li
                                                                    data-value="2"
                                                                    class="option"
                                                                >
                                                                    2
                                                                </li>
                                                                <li
                                                                    data-value="3"
                                                                    class="option"
                                                                >
                                                                    3
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div
                                                    class="input-wrap-checkbox mb-30"
                                                >
                                                    <span class="label"
                                                        >Add Extra</span
                                                    >
                                                    <div class="checkbox">
                                                        <input
                                                            id="check-rates"
                                                            type="checkbox"
                                                            name="check-rates"
                                                            value="check"
                                                        />
                                                        <label for="check-rates"
                                                            >Service per
                                                            booking</label
                                                        >
                                                    </div>
                                                    <div class="checkbox">
                                                        <input
                                                            id="check1-rates"
                                                            type="checkbox"
                                                            name="check-rates"
                                                            value="check"
                                                        />
                                                        <label for="check1-rates"
                                                            >Service per
                                                            person</label
                                                        >
                                                    </div>
                                                    <div class="extra">
                                                        <div class="flex-three">
                                                            <span class="name"
                                                                >Adult:</span
                                                            >
                                                            <span class="price"
                                                                >$18.00</span
                                                            >
                                                        </div>
                                                        <div class="flex-three">
                                                            <span class="name"
                                                                >Youth:</span
                                                            >
                                                            <span class="price"
                                                                >$16.00</span
                                                            >
                                                        </div>
                                                        <div class="flex-three">
                                                            <span class="name"
                                                                >Children:</span
                                                            >
                                                            <span class="price"
                                                                >$16.00</span
                                                            >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex-two mb-40">
                                                    <span class="label">Total:</span>
                                                    <span class="total text-main"
                                                        >$130.00</span
                                                    >
                                                </div>
                                                <button type="submit">
                                                    Procced Booking
                                                </button>
                                            </form>
                                        </div>
                                        <div class="sidebar-widget">
                                            <h6 class="block-heading">
                                                Book With Confidence
                                            </h6>
                                            <ul class="category-confidence">
                                                <li class="flex-three">
                                                    <i
                                                        class="icon-customer-service-1"
                                                    ></i>
                                                    <span
                                                        >Customer care available
                                                        24/7</span
                                                    >
                                                </li>
                                                <li class="flex-three">
                                                    <i class="icon-Vector-6"></i>
                                                    <span
                                                        >Hand-picked Tours &
                                                        Activities</span
                                                    >
                                                </li>
                                                <li class="flex-three">
                                                    <i class="icon-insurance-1"></i>
                                                    <span
                                                        >Free Travel
                                                        Insureance</span
                                                    >
                                                </li>
                                                <li class="flex-three">
                                                    <i
                                                        class="icon-price-tag-1-1"
                                                    ></i>
                                                    <span
                                                        >No-hassle best price
                                                        guarantee</span
                                                    >
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="sidebar-widget">
                                            <h4 class="block-heading">
                                                Recent News
                                            </h4>
                                            <div class="recent-post-list">
                                                <div class="list-recent flex-three">
                                                    <a
                                                        href="#"
                                                        class="recent-image"
                                                    >
                                                        <img
                                                            src="/template/images/blog/re-blog1.jpg"
                                                            alt="Image"
                                                        />
                                                    </a>
                                                    <div class="recent-info">
                                                        <div class="start">
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                        </div>
                                                        <h4 class="title">
                                                            <a href="#"
                                                                >Walking the
                                                                Amalfi Coast</a
                                                            >
                                                        </h4>
                                                        <p>
                                                            From
                                                            <span class="text-main"
                                                                >$129.00</span
                                                            >
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="list-recent flex-three">
                                                    <a
                                                        href="#"
                                                        class="recent-image"
                                                    >
                                                        <img
                                                            src="/template/images/blog/re-blog2.jpg"
                                                            alt="Image"
                                                        />
                                                    </a>
                                                    <div class="recent-info">
                                                        <div class="start">
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                        </div>
                                                        <h4 class="title">
                                                            <a href="#"
                                                                >Walking the
                                                                Amalfi Coast</a
                                                            >
                                                        </h4>
                                                        <p>
                                                            From
                                                            <span class="text-main"
                                                                >$129.00</span
                                                            >
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="list-recent flex-three">
                                                    <a
                                                        href="#"
                                                        class="recent-image"
                                                    >
                                                        <img
                                                            src="/template/images/blog/re-blog3.jpg"
                                                            alt="Image"
                                                        />
                                                    </a>
                                                    <div class="recent-info">
                                                        <div class="start">
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                        </div>
                                                        <h4 class="title">
                                                            <a href="#"
                                                                >Walking the
                                                                Amalfi Coast</a
                                                            >
                                                        </h4>
                                                        <p>
                                                            From
                                                            <span class="text-main"
                                                                >$129.00</span
                                                            >
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Shot Gallery Tab -->
                        <div
                            v-show="activeTab === 'gallery'"
                            class="tab-pane fade show active"
                            role="tabpanel"
                            tabindex="0"
                        >
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="gallery-content-tour">
                                        <div
                                            v-for="(
                                                img, index
                                            ) in tour.gallery_images"
                                            :key="index"
                                            :class="`image-gallery${(index % 6) + 1} image`"
                                        >
                                            <img
                                                :src="img"
                                                alt="image"
                                                :class="index % 2 === 0 ? 'item1' : 'item2'"
                                            />
                                        </div>
                                        <div
                                            v-if="tour.gallery_images.length === 0"
                                            class="image-gallery1 image"
                                        >
                                            <img
                                                src="/template/images/gallery/gallery.jpg"
                                                alt="image"
                                                class="item1"
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="side-bar-right">
                                        <div class="sidebar-widget">
                                            <h6 class="block-heading">
                                                Book This Tour
                                            </h6>
                                            <form action="/" id="form-book-tour">
                                                <div class="input-wrap mb-30">
                                                    <input type="date" />
                                                </div>
                                                <div class="flex-two mb-30">
                                                    <span class="label">Time:</span>
                                                    <div class="radio">
                                                        <input
                                                            id="first-gal"
                                                            type="radio"
                                                            name="numbers-gal"
                                                            value="first"
                                                            checked
                                                        />
                                                        <label for="first-gal"
                                                            >14.00</label
                                                        >
                                                        <input
                                                            id="second-gal"
                                                            type="radio"
                                                            name="numbers-gal"
                                                            value="second"
                                                        />
                                                        <label for="second-gal"
                                                            >16.00</label
                                                        >
                                                    </div>
                                                </div>
                                                <div
                                                    class="input-wrap-sellect mb-30"
                                                >
                                                    <span class="label"
                                                        >Tickets:</span
                                                    >
                                                    <div class="flex-two mb-15">
                                                        <p>
                                                            Children (0-12
                                                            years)$129.00
                                                        </p>
                                                        <div
                                                            class="nice-select"
                                                            tabindex="0"
                                                        >
                                                            <span class="current"
                                                                >1</span
                                                            >
                                                            <ul class="list">
                                                                <li
                                                                    data-value=""
                                                                    class="option selected focus"
                                                                >
                                                                    1
                                                                </li>
                                                                <li
                                                                    data-value="2"
                                                                    class="option"
                                                                >
                                                                    2
                                                                </li>
                                                                <li
                                                                    data-value="3"
                                                                    class="option"
                                                                >
                                                                    3
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="flex-two mb-15">
                                                        <p>
                                                            Youth (13-17
                                                            years)$169.00
                                                        </p>
                                                        <div
                                                            class="nice-select"
                                                            tabindex="0"
                                                        >
                                                            <span class="current"
                                                                >1</span
                                                            >
                                                            <ul class="list">
                                                                <li
                                                                    data-value=""
                                                                    class="option selected focus"
                                                                >
                                                                    1
                                                                </li>
                                                                <li
                                                                    data-value="2"
                                                                    class="option"
                                                                >
                                                                    2
                                                                </li>
                                                                <li
                                                                    data-value="3"
                                                                    class="option"
                                                                >
                                                                    3
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="flex-two">
                                                        <p>
                                                            Adult (18+)
                                                            years)$189.00
                                                        </p>
                                                        <div
                                                            class="nice-select"
                                                            tabindex="0"
                                                        >
                                                            <span class="current"
                                                                >1</span
                                                            >
                                                            <ul class="list">
                                                                <li
                                                                    data-value=""
                                                                    class="option selected focus"
                                                                >
                                                                    1
                                                                </li>
                                                                <li
                                                                    data-value="2"
                                                                    class="option"
                                                                >
                                                                    2
                                                                </li>
                                                                <li
                                                                    data-value="3"
                                                                    class="option"
                                                                >
                                                                    3
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div
                                                    class="input-wrap-checkbox mb-30"
                                                >
                                                    <span class="label"
                                                        >Add Extra</span
                                                    >
                                                    <div class="checkbox">
                                                        <input
                                                            id="check-gal"
                                                            type="checkbox"
                                                            name="check-gal"
                                                            value="check"
                                                        />
                                                        <label for="check-gal"
                                                            >Service per
                                                            booking</label
                                                        >
                                                    </div>
                                                    <div class="checkbox">
                                                        <input
                                                            id="check1-gal"
                                                            type="checkbox"
                                                            name="check-gal"
                                                            value="check"
                                                        />
                                                        <label for="check1-gal"
                                                            >Service per
                                                            person</label
                                                        >
                                                    </div>
                                                    <div class="extra">
                                                        <div class="flex-three">
                                                            <span class="name"
                                                                >Adult:</span
                                                            >
                                                            <span class="price"
                                                                >$18.00</span
                                                            >
                                                        </div>
                                                        <div class="flex-three">
                                                            <span class="name"
                                                                >Youth:</span
                                                            >
                                                            <span class="price"
                                                                >$16.00</span
                                                            >
                                                        </div>
                                                        <div class="flex-three">
                                                            <span class="name"
                                                                >Children:</span
                                                            >
                                                            <span class="price"
                                                                >$16.00</span
                                                            >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex-two mb-40">
                                                    <span class="label">Total:</span>
                                                    <span class="total text-main"
                                                        >$130.00</span
                                                    >
                                                </div>
                                                <button type="submit">
                                                    Procced Booking
                                                </button>
                                            </form>
                                        </div>
                                        <div class="sidebar-widget">
                                            <h6 class="block-heading">
                                                Book With Confidence
                                            </h6>
                                            <ul class="category-confidence">
                                                <li class="flex-three">
                                                    <i
                                                        class="icon-customer-service-1"
                                                    ></i>
                                                    <span
                                                        >Customer care available
                                                        24/7</span
                                                    >
                                                </li>
                                                <li class="flex-three">
                                                    <i class="icon-Vector-6"></i>
                                                    <span
                                                        >Hand-picked Tours &
                                                        Activities</span
                                                    >
                                                </li>
                                                <li class="flex-three">
                                                    <i class="icon-insurance-1"></i>
                                                    <span
                                                        >Free Travel
                                                        Insureance</span
                                                    >
                                                </li>
                                                <li class="flex-three">
                                                    <i
                                                        class="icon-price-tag-1-1"
                                                    ></i>
                                                    <span
                                                        >No-hassle best price
                                                        guarantee</span
                                                    >
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="sidebar-widget">
                                            <h4 class="block-heading">
                                                Recent News
                                            </h4>
                                            <div class="recent-post-list">
                                                <div class="list-recent flex-three">
                                                    <a
                                                        href="#"
                                                        class="recent-image"
                                                    >
                                                        <img
                                                            src="/template/images/blog/re-blog1.jpg"
                                                            alt="Image"
                                                        />
                                                    </a>
                                                    <div class="recent-info">
                                                        <div class="start">
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                        </div>
                                                        <h4 class="title">
                                                            <a href="#"
                                                                >Walking the
                                                                Amalfi Coast</a
                                                            >
                                                        </h4>
                                                        <p>
                                                            From
                                                            <span class="text-main"
                                                                >$129.00</span
                                                            >
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="list-recent flex-three">
                                                    <a
                                                        href="#"
                                                        class="recent-image"
                                                    >
                                                        <img
                                                            src="/template/images/blog/re-blog2.jpg"
                                                            alt="Image"
                                                        />
                                                    </a>
                                                    <div class="recent-info">
                                                        <div class="start">
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                        </div>
                                                        <h4 class="title">
                                                            <a href="#"
                                                                >Walking the
                                                                Amalfi Coast</a
                                                            >
                                                        </h4>
                                                        <p>
                                                            From
                                                            <span class="text-main"
                                                                >$129.00</span
                                                            >
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="list-recent flex-three">
                                                    <a
                                                        href="#"
                                                        class="recent-image"
                                                    >
                                                        <img
                                                            src="/template/images/blog/re-blog3.jpg"
                                                            alt="Image"
                                                        />
                                                    </a>
                                                    <div class="recent-info">
                                                        <div class="start">
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                            <i class="icon-Star"></i>
                                                        </div>
                                                        <h4 class="title">
                                                            <a href="#"
                                                                >Walking the
                                                                Amalfi Coast</a
                                                            >
                                                        </h4>
                                                        <p>
                                                            From
                                                            <span class="text-main"
                                                                >$129.00</span
                                                            >
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
            </div>
        </div>
    </section>
</template>

<style scoped>
.tf-container {
    max-width: 1320px;
}
.tf-container.w-1456 {
    max-width: 1475px;
}

.tour-gallery-swiper img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 8px;
}

.tour-gallery-swiper :deep(.swiper-button-next),
.tour-gallery-swiper :deep(.swiper-button-prev) {
    width: 36px;
    height: 36px;
    background: #ffffff;
    border-radius: 50%;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    color: #081e2a;
}

.tour-gallery-swiper :deep(.swiper-button-next::after),
.tour-gallery-swiper :deep(.swiper-button-prev::after) {
    font-size: 14px;
    font-weight: 700;
}

.tour-gallery-swiper :deep(.swiper-pagination-bullet-active) {
    background: #4da528;
}

.gallery-placeholder img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 8px;
}
</style>
