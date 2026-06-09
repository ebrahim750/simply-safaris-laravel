<script setup lang="ts">
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed, nextTick, onMounted, onUnmounted, ref, watch } from 'vue';
import { CalendarDays, Loader2, MapPin, Search, X } from 'lucide-vue-next';
import { debounce } from 'lodash-es';
import { about, contact, home } from '@/routes';
import destinations from '@/routes/destinations';
import tours from '@/routes/tours';

const page = usePage();
const mobileMenuOpen = ref(false);
const languageOpen = ref(false);
const searchOpen = ref(false);
const openDropdowns = ref<Set<string>>(new Set());

const destinationsData = computed(() => page.props.destinations as Array<{
    id: number;
    name: string;
    slug: string;
    country_code: string | null;
    places: Array<{ id: number; name: string; slug: string }>;
}>);
const headerLower = ref<HTMLElement | null>(null);
const isHeaderFixed = ref(false);
const isHeaderSmall = ref(false);
const headerSpacerHeight = ref(0);
const searchInput = ref<HTMLInputElement | null>(null);
const searchQuery = ref('');
const suggestions = ref<Array<{
    id: number;
    name: string;
    url: string;
    main_image: string;
    duration_days: number;
    price: string;
    places: Array<{ name: string; country: string | null }>;
}>>([]);
const isLoading = ref(false);

let headerOffsetTop = 0;
let headerHeight = 0;

const closeMobileMenu = () => {
    mobileMenuOpen.value = false;
    openDropdowns.value.clear();
};

const closeHeaderDropdowns = () => {
    languageOpen.value = false;
    searchOpen.value = false;
};

const handleEscape = (e: KeyboardEvent) => {
    if (e.key === 'Escape') {
        searchOpen.value = false;
    }
};

const toggleDropdown = (slug: string) => {
    const newSet = new Set(openDropdowns.value);
    if (newSet.has(slug)) {
        newSet.delete(slug);
    } else {
        newSet.add(slug);
    }
    openDropdowns.value = newSet;
};

const isDropdownOpen = (slug: string): boolean => {
    return openDropdowns.value.has(slug);
};

const measureStickyHeader = () => {
    if (!headerLower.value) {
        return;
    }

    const wasFixed = isHeaderFixed.value;
    isHeaderFixed.value = false;

    const bounds = headerLower.value.getBoundingClientRect();
    headerOffsetTop = bounds.top + window.scrollY;
    headerHeight = headerLower.value.offsetHeight;
    headerSpacerHeight.value = headerHeight;
    isHeaderFixed.value = wasFixed;
};

const handleStickyHeader = () => {
    isHeaderFixed.value = window.scrollY > headerOffsetTop + headerHeight;
    isHeaderSmall.value = window.scrollY > 500;
};

const refreshStickyHeader = () => {
    measureStickyHeader();
    handleStickyHeader();
};

const handleSearchSubmit = () => {
    const query = searchQuery.value.trim();
    if (query) {
        searchOpen.value = false;
        router.get(tours.index({ query: { search: query } }).url);
    }
};

const fetchSuggestions = debounce(async (query: string) => {
    if (!query.trim()) {
        suggestions.value = [];
        return;
    }

    isLoading.value = true;
    try {
        const response = await fetch(`/tours/search?q=${encodeURIComponent(query)}`);
        const data = await response.json();
        suggestions.value = data.results;
    } finally {
        isLoading.value = false;
    }
}, 1000);

watch(searchQuery, (newValue) => {
    fetchSuggestions(newValue);
});

watch(searchOpen, (open) => {
    if (open) {
        nextTick(() => {
            searchInput.value?.focus();
        });
    } else {
        searchQuery.value = '';
        suggestions.value = [];
    }
});

onMounted(async () => {
    await nextTick();
    refreshStickyHeader();
    window.addEventListener('scroll', handleStickyHeader, { passive: true });
    window.addEventListener('resize', refreshStickyHeader);
    window.addEventListener('keydown', handleEscape);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleStickyHeader);
    window.removeEventListener('resize', refreshStickyHeader);
    window.removeEventListener('keydown', handleEscape);
});
</script>

<template>
    <header class="main-header home3-header flex">
        <div id="header">
            <div class="header-top home3-header-top">
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
                                <li>
                                    <a href="#"><i class="icon-icon-2"></i></a>
                                </li>
                                <li>
                                    <a href="#"><i class="icon-icon_03"></i></a>
                                </li>
                                <li>
                                    <a href="#"><i class="icon-x"></i></a>
                                </li>
                                <li>
                                    <a href="#"><i class="icon-icon"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div
                ref="headerLower"
                class="header-lower home3-header-lower"
                :class="{
                    'is-fixed': isHeaderFixed,
                    'is-small': isHeaderSmall,
                }"
            >
                <div class="tf-container full">
                    <div class="row">
                        <div class="col-lg-12">
                            <div
                                class="inner-container justify-space align-center flex"
                            >
                                <div class="logo-box">
                                    <div class="logo">
                                        <Link href="/">
                                            <img
                                                src="/images/simplysafaris.png"
                                                alt="Logo"
                                            />
                                        </Link>
                                    </div>
                                </div>
                                <div class="nav-outer align-center flex">
                                    <nav
                                        class="main-menu show navbar-expand-md"
                                    >
                                        <div
                                            class="navbar-collapse clearfix"
                                            :class="{
                                                'mobile-show': mobileMenuOpen,
                                            }"
                                            id="navbarSupportedContent"
                                        >
                                            <ul class="navigation clearfix">
                                                <li
                                                    :class="{
                                                        current:
                                                            $page.url === '/',
                                                    }"
                                                >
                                                    <Link
                                                        :href="home()"
                                                        @click="closeMobileMenu"
                                                        >Home</Link
                                                    >
                                                </li>
                                                <li
                                                    class="dropdown2"
                                                    :class="{
                                                        current: $page.url.startsWith('/destinations'),
                                                    }"
                                                >
                                                    <Link
                                                        :href="destinations.index()"
                                                        @click="closeMobileMenu"
                                                        >Destinations</Link
                                                    >
                                                    <ul>
                                                        <li
                                                            v-for="country in destinationsData"
                                                            :key="country.id"
                                                            :class="{ dropdown2: country.places.length > 0 }"
                                                        >
                                                            <Link
                                                                :href="destinations.country({ country: country.slug })"
                                                                @click="closeMobileMenu"
                                                                class="flex items-center gap-5"
                                                            >
                                                                <img
                                                                    v-if="country.country_code"
                                                                    :src="`https://flagcdn.com/w40/${country.country_code.toLowerCase()}.png`"
                                                                    :alt="`${country.name} flag`"
                                                                    class="inline-block shrink-0 rounded-md object-cover"
                                                                    style="width: 30px; height: auto; margin-right: 12px;"
                                                                />
                                                                <span class="leading-none">{{ country.name }}</span>
                                                            </Link>
                                                            <ul v-if="country.places.length > 0">
                                                                <li
                                                                    v-for="place in country.places"
                                                                    :key="place.id"
                                                                >
                                                                    <Link
                                                                        :href="`${destinations.country({ country: country.slug }).url}#${place.slug}`"
                                                                        @click="closeMobileMenu"
                                                                        >{{ place.name }}</Link
                                                                    >
                                                                </li>
                                                            </ul>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li
                                                    :class="{
                                                        current:
                                                            $page.url ===
                                                            '/tours',
                                                    }"
                                                >
                                                    <Link
                                                        :href="tours.index()"
                                                        @click="closeMobileMenu"
                                                        >Tours</Link
                                                    >
                                                </li>
                                                <li
                                                    :class="{
                                                        current:
                                                            $page.url ===
                                                            '/about',
                                                    }"
                                                >
                                                    <Link
                                                        :href="about()"
                                                        @click="closeMobileMenu"
                                                        >About</Link
                                                    >
                                                </li>
                                                <li
                                                    :class="{
                                                        current:
                                                            $page.url ===
                                                            '/contact',
                                                    }"
                                                >
                                                    <Link
                                                        :href="contact()"
                                                        @click="closeMobileMenu"
                                                        >Contact</Link
                                                    >
                                                </li>
                                            </ul>
                                        </div>
                                    </nav>
                                </div>
                                <div class="header-account align-center flex">
                                    <div class="search-mobie relative">
                                        <button
                                            type="button"
                                            class="show-search"
                                            :class="{ show: searchOpen }"
                                            aria-label="Toggle search"
                                            @click="searchOpen = !searchOpen"
                                        >
                                            <i class="icon-Vector5"></i>
                                        </button>
                                    </div>
                                    <div class="language">
                                        <div
                                            class="nice-select"
                                            :class="{ open: languageOpen }"
                                            tabindex="0"
                                            @click="
                                                languageOpen = !languageOpen
                                            "
                                        >
                                            <img
                                                src="/template/images/page/language.svg"
                                                alt=""
                                            />
                                            <span class="current">English</span>
                                            <ul class="list">
                                                <li class="option selected">
                                                    English
                                                </li>
                                                <li class="option">Vietnam</li>
                                                <li class="option">German</li>
                                                <li class="option">Russian</li>
                                                <li class="option">Canada</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="mobile-nav-toggler mobile-button"
                                    @click="mobileMenuOpen = !mobileMenuOpen"
                                >
                                    <i class="icon-bar"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div
                v-show="isHeaderFixed"
                :style="{ height: `${headerSpacerHeight}px` }"
            ></div>
        </div>

        <!-- Search Overlay Backdrop -->
        <Transition
            enter-active-class="transition ease-out duration-300"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-200"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="searchOpen"
                class="fixed inset-0 z-[9998] bg-black/60"
                @click="searchOpen = false"
            ></div>
        </Transition>

        <!-- Search Overlay Panel -->
        <Transition
            enter-active-class="transition ease-out duration-500"
            enter-from-class="-translate-y-full"
            enter-to-class="translate-y-0"
            leave-active-class="transition ease-in duration-300"
            leave-from-class="translate-y-0"
            leave-to-class="-translate-y-full"
        >
            <div
                v-if="searchOpen"
                class="fixed inset-x-0 top-0 z-[9999] flex h-[75vh] flex-col bg-white shadow-2xl"
            >
                <div class="flex justify-end px-10 py-8 lg:px-20 lg:py-14">
                    <div
                        role="button"
                        tabindex="0"
                        class="cursor-pointer p-2 text-gray-400 transition-colors hover:text-gray-900"
                        @click="searchOpen = false"
                        @keydown.enter="searchOpen = false"
                    >
                        <X class="h-7 w-7" stroke-width="1.5" />
                    </div>
                </div>

                <div
                    class="flex flex-1 justify-center px-6 transition-all duration-500 lg:px-16"
                    :class="suggestions.length > 0 || isLoading ? 'items-start pt-12 lg:pt-20' : 'items-center'"
                >
                    <div class="w-full max-w-5xl">
                        <label class="mb-6 block text-sm font-medium uppercase tracking-widest text-gray-400">
                            What are you looking for?
                        </label>
                        <div class="relative">
                            <input
                                ref="searchInput"
                                v-model="searchQuery"
                                type="text"
                                placeholder="Search tours..."
                                class="w-full !rounded-none !border-0 !border-b-2 !border-gray-200 !bg-transparent py-6 pr-12 text-3xl text-gray-900 placeholder-gray-300 transition-colors focus:!border-gray-900 focus:!outline-none focus:!ring-0 focus:!shadow-none lg:pr-14 lg:text-5xl"
                                @keydown.enter="handleSearchSubmit"
                            />
                            <Search
                                class="pointer-events-none absolute right-4 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-300 lg:right-6 lg:h-6 lg:w-6"
                            />
                        </div>

                        <div class="mt-8 flex min-h-[3rem] justify-center">
                            <Loader2
                                v-if="isLoading"
                                class="h-10 w-10 animate-spin text-gray-400"
                            />
                        </div>

                        <div
                            v-if="suggestions.length > 0"
                            class="mt-6 max-h-[50vh] overflow-y-auto overflow-x-hidden rounded-none border border-gray-100 bg-white"
                        >
                            <Link
                                v-for="suggestion in suggestions"
                                :key="suggestion.id"
                                :href="suggestion.url"
                                class="flex items-center gap-4 border-b border-gray-100 px-5 py-3 transition-colors last:border-b-0 hover:bg-gray-50"
                                @click="searchOpen = false"
                            >
                                <div class="h-14 w-20 shrink-0 overflow-hidden rounded-md">
                                    <img
                                        :src="suggestion.main_image"
                                        :alt="suggestion.name"
                                        class="!h-14 !w-20 object-cover"
                                    />
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-base font-semibold text-gray-900">
                                        {{ suggestion.name }}
                                    </p>
                                    <p class="mt-1 flex flex-wrap items-center gap-x-4 gap-y-1 truncate !text-[13px] text-gray-500">
                                        <span v-if="suggestion.places.length > 0" class="inline-flex items-center gap-1">
                                            <MapPin class="h-3.5 w-3.5 shrink-0 text-[#4DA528]" />
                                            {{ [...new Set(suggestion.places.map((p) => p.country).filter(Boolean))].join(', ') }}
                                        </span>
                                        <span v-if="suggestion.duration_days" class="inline-flex items-center gap-1">
                                            <CalendarDays class="h-3.5 w-3.5 shrink-0 text-[#4DA528]" />
                                            {{ suggestion.duration_days }} {{ suggestion.duration_days === 1 ? 'day' : 'days' }}
                                        </span>
                                    </p>
                                    <p class="mt-1 text-sm font-semibold text-gray-800">
                                        {{ suggestion.price }}
                                    </p>
                                </div>
                            </Link>
                        </div>
                    </div>
                </div>


            </div>
        </Transition>
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

.home3-header-lower {
    padding-left: 201px;
    padding-right: 306px;
}

.home3-header-top {
    padding-left: 201px;
    padding-right: 306px;
}

.home3-header-lower .header-account .search-mobie {
    margin: 0 20px;
}

.show-search {
    background: transparent;
    border: 0;
    cursor: pointer;
    padding: 0;
}

.header-account .search-mobie .show-search i {
    color: #081e2a;
    font-size: 19px;
}

.header-account .language .nice-select {
    align-items: center;
    display: inline-flex;
    gap: 8px;
}

.header-account .language .nice-select > img {
    margin-right: 0;
}

@media (max-width: 1570px) {
    .home3-header-top,
    .home3-header-lower {
        padding-left: 15px;
        padding-right: 15px;
    }
}

@media (max-width: 991px) {
    .home3-header .header-account {
        display: none;
    }

    .home3-header-lower {
        padding: 20px 15px;
    }
}
</style>
