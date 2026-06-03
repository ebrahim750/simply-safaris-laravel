<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed, nextTick, onMounted, onUnmounted, ref } from 'vue';
import { about, contact, home } from '@/routes';
import destinations from '@/routes/destinations';

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

onMounted(async () => {
    await nextTick();
    refreshStickyHeader();
    window.addEventListener('scroll', handleStickyHeader, { passive: true });
    window.addEventListener('resize', refreshStickyHeader);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleStickyHeader);
    window.removeEventListener('resize', refreshStickyHeader);
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
                                                                        :href="destinations.place({ country: country.slug, place: place.slug })"
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
                                        <div class="dropdown">
                                            <button
                                                type="button"
                                                class="show-search"
                                                :class="{ show: searchOpen }"
                                                aria-label="Toggle search"
                                                @click="
                                                    searchOpen = !searchOpen
                                                "
                                            >
                                                <i class="icon-Vector5"></i>
                                            </button>
                                            <ul
                                                class="dropdown-menu top-search"
                                                :class="{ show: searchOpen }"
                                            >
                                                <form
                                                    id="search-bar-widget"
                                                    :action="home().url"
                                                    @submit="
                                                        closeHeaderDropdowns
                                                    "
                                                >
                                                    <input
                                                        type="text"
                                                        name="search"
                                                        placeholder="Search here..."
                                                    />
                                                    <button type="submit">
                                                        <i
                                                            class="icon-search-2"
                                                        ></i>
                                                    </button>
                                                </form>
                                            </ul>
                                        </div>
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

.dropdown-menu.top-search.show {
    display: block;
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
