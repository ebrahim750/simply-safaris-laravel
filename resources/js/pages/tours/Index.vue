<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { home } from '@/routes';
import tourRoutes from '@/routes/tours';

defineProps<{
    tours: Array<{
        id: number;
        name: string;
        main_image: string | null;
        price: string;
        price_amount: number;
        duration_days: number | null;
        categories: Array<{ id: number; name: string }>;
        places: Array<{ id: number; name: string }>;
    }>;
}>();
</script>

<template>
    <Head title="Tours" />

    <!-- Breadcrumb -->
    <section class="breadcumb-section">
        <div class="tf-container">
            <div class="row">
                <div class="col-lg-12 center z-index1">
                    <h1 class="title">Tours</h1>
                    <ul class="breadcumb-list flex-five">
                        <li><Link :href="home()">Home</Link></li>
                        <li><span>Tours</span></li>
                    </ul>
                    <img
                        class="bcrumb-ab"
                        src="/template/images/page/mask-bcrumb.png"
                        alt=""
                    />
                </div>
            </div>
        </div>
    </section>

    <!-- Widget archieve tour -->
    <section class="archieve-tour">
        <div class="tf-container">
            <div class="row">
                <div class="col-lg-4">
                    <form action="/" class="sider-bar-tour-package">
                        <div class="widget-filter mb-40">
                            <h6 class="title-tour">Search by Filter</h6>
                            <div class="group-select-wrap">
                                <fieldset class="group-select relative mb-22">
                                    <i class="icon-Vector-8"></i>
                                    <div class="search-bar-group relative">
                                        <label>Destination</label>
                                        <div class="nice-select" tabindex="0">
                                            <span class="current">Location</span>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="group-select relative mb-22">
                                    <i class="icon-Vector-22"></i>
                                    <div class="search-bar-group relative">
                                        <label>Activities</label>
                                        <div class="nice-select" tabindex="0">
                                            <span class="current">Booking Type</span>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="group-select relative mb-22">
                                    <i class="icon-Group-111"></i>
                                    <div class="search-bar-group relative">
                                        <label>Date</label>
                                        <div class="nice-select" tabindex="0">
                                            <span class="current">Date From</span>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="group-select relative mb-22">
                                    <i class="icon-user"></i>
                                    <div class="search-bar-group relative">
                                        <label>0</label>
                                        <div class="nice-select" tabindex="0">
                                            <span class="current">Guests</span>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="group-select relative mb-40">
                                    <h6 class="title-tour">Filter By Price</h6>
                                    <div class="widget widget-price">
                                        <div class="slider-labels">
                                            <div class="caption flex-three">
                                                <p class="price-range">Price:</p>
                                                <div class="number-range">
                                                    <span>$100</span>
                                                    <span>$5,000</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="group-select relative input-npd mb-22">
                                    <div class="search-bar-group relative">
                                        <label>0</label>
                                        <div class="nice-select" tabindex="0">
                                            <span class="current">English</span>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="group-select relative input-npd mb-22">
                                    <div class="search-bar-group relative">
                                        <label>Any</label>
                                        <div class="nice-select" tabindex="0">
                                            <span class="current">Month</span>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="group-select relative input-npd">
                                    <div class="search-bar-group relative">
                                        <label>Any</label>
                                        <div class="nice-select" tabindex="0">
                                            <span class="current">Duration</span>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        <div class="widget-filter mb-40">
                            <h6 class="title-tour">Search by Filter</h6>
                            <div class="group-check-box-wrap">
                                <div class="checkbox">
                                    <input id="check" type="checkbox" name="check" value="check" />
                                    <label for="check">10+</label>
                                </div>
                                <div class="checkbox">
                                    <input id="check1" type="checkbox" name="check" value="check" />
                                    <label for="check1">12+</label>
                                </div>
                                <div class="checkbox">
                                    <input id="check2" type="checkbox" name="check" value="check" />
                                    <label for="check2">15+</label>
                                </div>
                                <div class="checkbox">
                                    <input id="check3" type="checkbox" name="check" value="check" />
                                    <label for="check3">Adults</label>
                                </div>
                            </div>
                        </div>
                        <div class="widget-filter mb-40">
                            <h6 class="title-tour">Search by Filter</h6>
                            <div class="group-check-box-wrap">
                                <div class="checkbox">
                                    <input id="check4-bar" type="checkbox" name="check" value="check" />
                                    <label for="check4-bar">Accepts Credit Cards</label>
                                </div>
                                <div class="checkbox">
                                    <input id="check5-bar" type="checkbox" name="check" value="check" />
                                    <label for="check5-bar">Car Parking</label>
                                </div>
                                <div class="checkbox">
                                    <input id="check6-bar" type="checkbox" name="check" value="check" />
                                    <label for="check6-bar">Free Coupons</label>
                                </div>
                                <div class="checkbox">
                                    <input id="check7-bar" type="checkbox" name="check" value="check" />
                                    <label for="check7-bar">Laundry Service</label>
                                </div>
                                <div class="checkbox">
                                    <input id="check8-bar" type="checkbox" name="check" value="check" />
                                    <label for="check8-bar">Outdoor Seating</label>
                                </div>
                                <div class="checkbox">
                                    <input id="check9-bar" type="checkbox" name="check" value="check" />
                                    <label for="check9-bar">Reservations</label>
                                </div>
                                <div class="checkbox">
                                    <input id="check10-bar" type="checkbox" name="check" value="check" />
                                    <label for="check10-bar">Restaurant</label>
                                </div>
                                <div class="checkbox">
                                    <input id="check11-bar" type="checkbox" name="check" value="check" />
                                    <label for="check11-bar">Smoking Allowed</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-8 listing-list-car-wrap">
                    <form action="/" class="tf-my-listing mb-37">
                        <div class="row align-center">
                            <div class="col-sm-5">
                                <p class="showing">
                                    Showing <span class="text-main">{{ tours.length }}</span> of
                                    {{ tours.length }} Results
                                </p>
                            </div>
                            <div class="col-sm-7 group-bar-wrap flex-six">
                                <div class="listing-all-wrap">
                                    <div class="flex-three">
                                        <div class="group-select-recently">
                                            <div class="nice-select" tabindex="0">
                                                <i class="icon-Vector6"></i
                                                ><span class="current">Price</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="toolbar-list">
                                        <div class="form-group">
                                            <a class="btn-display-listing-grid active">
                                                <i class="icon-list"></i>
                                            </a>
                                        </div>
                                        <div class="form-group">
                                            <a class="btn-display-listing-list">
                                                <i class="icon-Group-1000001297"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="listing-list-car-grid mb-60">
                        <div
                            v-for="tour in tours"
                            :key="tour.id"
                            class="tour-listing box-sd"
                        >
                            <Link
                                :href="tourRoutes.show({ tour: tour.id })"
                                class="tour-listing-image"
                            >
                                <div class="badge-top flex-two">
                                    <span class="feature">Featured</span>
                                    <div class="badge-media flex-five">
                                        <span class="media"
                                            ><i class="icon-Group-1000002909"></i>5</span
                                        >
                                        <span class="media"
                                            ><i class="icon-Group-1000002910"></i>2</span
                                        >
                                    </div>
                                </div>
                                <img
                                    v-if="tour.main_image"
                                    :src="tour.main_image"
                                    :alt="tour.name"
                                />
                                <img
                                    v-else
                                    src="https://placehold.co/600x400/e2e8f0/475569?text=No+Image"
                                    :alt="tour.name"
                                />
                            </Link>
                            <div class="tour-listing-content">
                                <span class="map"
                                    ><i class="icon-Vector4"></i
                                    >{{
                                        tour.places.length > 0
                                            ? tour.places.map((p) => p.name).join(', ')
                                            : 'Worldwide'
                                    }}</span
                                >
                                <h3 class="title-tour-list">
                                    <Link :href="tourRoutes.show({ tour: tour.id })"
                                        >{{ tour.name }}</Link
                                    >
                                </h3>
                                <div class="icon-box flex-three">
                                    <div class="icons flex-three">
                                        <i class="icon-time-left"></i>
                                        <span
                                            >{{ tour.duration_days ?? 'N/A' }}
                                            {{ tour.duration_days === 1 ? 'day' : 'days' }}</span
                                        >
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
                                    <div class="price-box flex-three">
                                        <p>
                                            From <span class="price-sale">{{ tour.price }}</span>
                                        </p>
                                    </div>
                                    <div class="icon-bookmark">
                                        <i class="icon-Vector-151"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" v-if="tours.length > 0">
                        <div class="col-md-12">
                            <ul class="tf-pagination flex-three">
                                <li>
                                    <a class="pages-link" href="#">
                                        <i class="icon-29"></i
                                    ></a>
                                </li>
                                <li class="pages-item active" aria-current="page">
                                    <a class="pages-link" href="#">1</a>
                                </li>
                                <li>
                                    <a class="pages-link" href="#"
                                        ><i class="icon--1"></i
                                    ></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


</template>
