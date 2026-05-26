<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { onMounted, onUnmounted, ref } from 'vue';
import { about, contact, home } from '@/routes';

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
    <footer class="footer footer-style1">
        <div class="tf-container">
            <div class="footer-main public-footer-main">
                <div class="footer-logo public-footer-column">
                    <div class="logo-footer">
                        <img src="/images/simplysafaris.png" alt="Logo" />
                    </div>
                    <p class="des-footer">
                        Discover the world's most breathtaking safari
                        destinations with Simply Safaris. Unforgettable
                        adventures await.
                    </p>
                    <ul class="footer-info">
                        <li class="flex-three">
                            <i class="icon-noun-mail-5780740-1"></i>
                            <p>info@simplysafaris.com</p>
                        </li>
                        <li class="flex-three">
                            <i class="icon-Group-9"></i>
                            <p>+1 684 555-0102</p>
                        </li>
                        <li class="flex-three">
                            <i class="icon-Layer-19"></i>
                            <p>6391 Elgin St. Celina, NYC 10299</p>
                        </li>
                    </ul>
                </div>
                <div class="footer-service public-footer-column">
                    <h5 class="title">Services</h5>
                    <ul class="footer-menu">
                        <li><Link :href="about()">About Us</Link></li>
                        <li><a href="#">Gallery</a></li>
                        <li><a href="#">Our Team</a></li>
                        <li><a href="#">Blog Insights</a></li>
                        <li><Link :href="contact()">Contact</Link></li>
                    </ul>
                </div>
                <div class="footer-gallery public-footer-column">
                    <h5 class="title">Gallery</h5>
                    <div class="gallery-img">
                        <a
                            href="/template/images/gallery/gl1.jpg"
                            data-fancybox="gallery"
                        >
                            <img
                                src="/template/images/gallery/gl1.jpg"
                                alt="image gallery"
                            />
                        </a>
                        <a
                            href="/template/images/gallery/gl2.jpg"
                            data-fancybox="gallery"
                        >
                            <img
                                src="/template/images/gallery/gl2.jpg"
                                alt="image gallery"
                            />
                        </a>
                        <a
                            href="/template/images/gallery/gl3.jpg"
                            data-fancybox="gallery"
                        >
                            <img
                                src="/template/images/gallery/gl3.jpg"
                                alt="image gallery"
                            />
                        </a>
                        <a
                            href="/template/images/gallery/gl4.jpg"
                            data-fancybox="gallery"
                        >
                            <img
                                src="/template/images/gallery/gl4.jpg"
                                alt="image gallery"
                            />
                        </a>
                        <a
                            href="/template/images/gallery/gl5.jpg"
                            data-fancybox="gallery"
                        >
                            <img
                                src="/template/images/gallery/gl5.jpg"
                                alt="image gallery"
                            />
                        </a>
                        <a
                            href="/template/images/gallery/gl6.jpg"
                            data-fancybox="gallery"
                        >
                            <img
                                src="/template/images/gallery/gl6.jpg"
                                alt="image gallery"
                            />
                        </a>
                    </div>
                </div>
            </div>
            <div class="row footer-bottom">
                <div class="col-md-4">
                    <p class="copy-right public-footer-credit">
                        Website by
                        <a
                            href="https://simplyit.co.tz"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="text-main"
                            >SimplyIT</a
                        >
                    </p>
                </div>
                <div class="col-md-4">
                    <p class="copy-right public-footer-copyright">
                        Simply Safaris &copy; {{ currentYear }} | All rights
                        reserved.
                    </p>
                </div>
                <div class="col-md-4">
                    <ul
                        class="footer-bottom-links flex-six public-footer-bottom-links"
                    >
                        <li>
                            <a href="#" class="footer-bottom-link"
                                >Terms & Conditions</a
                            >
                        </li>
                        <li class="footer-link-separator" aria-hidden="true">
                            |
                        </li>
                        <li>
                            <a href="#" class="footer-bottom-link"
                                >Privacy Policy</a
                            >
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <a
        id="scroll-top"
        class="button-go"
        :class="{ show: showBackToTop }"
        @click="scrollToTop"
    ></a>
</template>

<style scoped>
@media (min-width: 768px) {
    .public-footer-main {
        display: grid !important;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 48px;
    }

    .public-footer-column {
        width: auto !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
}

.public-footer-bottom-links {
    gap: 12px;
}

.public-footer-credit,
.public-footer-copyright,
.footer-bottom-link {
    color: #ffffff !important;
    font-size: 13px !important;
}

.public-footer-credit,
.public-footer-copyright {
    margin-bottom: 0;
}

.public-footer-copyright {
    text-align: center;
}

.public-footer-bottom-links {
    justify-content: flex-end;
}

.footer-link-separator {
    color: rgba(255, 255, 255, 0.4);
    font-size: 13px;
}

@media (max-width: 767px) {
    .public-footer-credit,
    .public-footer-copyright {
        text-align: center;
    }

    .public-footer-bottom-links {
        justify-content: center;
        margin-top: 10px;
    }
}

#scroll-top {
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s, visibility 0.3s;
    cursor: pointer;
}

#scroll-top.show {
    opacity: 1;
    visibility: visible;
}
</style>
