<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { dashboard, login, register } from '@/routes';

const page = usePage();
const appName = computed(() => (page.props.name as string) || 'Wild Horizon');

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);
</script>

<template>
    <Head :title="appName">
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link
            rel="preconnect"
            href="https://fonts.gstatic.com"
            crossorigin=""
        />
        <link
            href="https://fonts.googleapis.com/css2?family=Newsreader:ital,opsz,wght@0,6..72,200..800;1,6..72,200..800&family=Work+Sans:wght@300;400;500;600;700&display=swap"
            rel="stylesheet"
        />
        <link
            href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap"
            rel="stylesheet"
        />
    </Head>

    <div
        class="welcome-marketing min-h-screen bg-wh-background text-wh-on-surface font-wh-body selection:bg-wh-secondary-container selection:text-wh-on-secondary-container"
    >
        <!-- TopNavBar -->
        <nav
            class="fixed top-0 z-50 w-full bg-wh-background/80 backdrop-blur-md"
        >
            <div
                class="mx-auto flex max-w-screen-2xl flex-col gap-4 px-6 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-8"
            >
                <div
                    class="flex items-center justify-between gap-4 sm:contents"
                >
                    <div
                        class="font-wh-headline text-2xl font-bold text-wh-primary"
                    >
                        {{ appName }}
                    </div>
                    <div class="flex shrink-0 items-center gap-2 sm:hidden">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="dashboard()"
                            class="rounded-md border border-wh-outline/40 px-3 py-2 text-sm font-medium text-wh-primary"
                        >
                            Dashboard
                        </Link>
                        <template v-else>
                            <Link
                                :href="login()"
                                class="rounded-md px-3 py-2 text-sm text-wh-on-surface-variant"
                            >
                                Log in
                            </Link>
                            <Link
                                v-if="canRegister"
                                :href="register()"
                                class="rounded-md bg-linear-to-br from-wh-primary to-wh-primary-container px-4 py-2 text-sm font-medium text-wh-on-primary"
                            >
                                Sign up
                            </Link>
                        </template>
                    </div>
                </div>
                <div
                    class="hidden items-center gap-x-8 font-wh-headline tracking-tight md:flex"
                >
                    <a
                        class="border-b-2 border-wh-secondary pb-1 text-wh-primary transition-colors duration-300 hover:text-wh-secondary"
                        href="#expeditions"
                    >
                        Adventures
                    </a>
                    <a
                        class="text-wh-on-surface-variant transition-colors duration-300 hover:text-wh-secondary"
                        href="#expeditions"
                    >
                        Destinations
                    </a>
                    <a
                        class="text-wh-on-surface-variant transition-colors duration-300 hover:text-wh-secondary"
                        href="#testimonials"
                    >
                        The Journal
                    </a>
                    <a
                        class="text-wh-on-surface-variant transition-colors duration-300 hover:text-wh-secondary"
                        href="#contact"
                    >
                        Contact
                    </a>
                </div>
                <div class="hidden items-center gap-3 sm:flex">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="dashboard()"
                        class="rounded-md border border-wh-outline/40 px-4 py-2 text-sm font-medium text-wh-primary hover:bg-wh-surface-container-low"
                    >
                        Dashboard
                    </Link>
                    <template v-else>
                        <Link
                            :href="login()"
                            class="hidden text-sm text-wh-on-surface-variant hover:text-wh-primary lg:inline"
                        >
                            Log in
                        </Link>
                        <Link
                            :href="canRegister ? register() : login()"
                            class="rounded-md bg-linear-to-br from-wh-primary to-wh-primary-container px-6 py-2.5 font-medium text-wh-on-primary transition-all hover:opacity-90"
                        >
                            Book Now
                        </Link>
                    </template>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section
            class="relative flex min-h-screen items-center overflow-hidden pt-24 md:h-[972px] md:min-h-0 md:pt-0"
        >
            <div class="absolute inset-0 z-0">
                <img
                    alt="Wild Horizon safari hero — savanna at golden hour"
                    class="h-full w-full object-cover"
                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuCjwUuNT4oqCqW5b6gaQmGH5WBprl0sf859eULKA2gAdiZ2RiCU00cu-swbPXW3rdK4v0RbSbsp0V66lprySUQJAvARViAsY9dAfQzopZTJ6RtfsBT8dEyb94_UP8-1VZEAUqARgDHy6KYXok6qitzpsJ8u3U-A1Hba737Ko5EIqS0sswEYIEPxxwEIgql62StpARnnFnpKGcr66c_QCDP3VIi3j39E28MfDi1rxoOxBIqKXmR21lm5xlXdLVL_TxQTgNaAqZyoGXQH"
                />
                <div
                    class="absolute inset-0 bg-linear-to-b from-transparent via-transparent to-wh-background/40"
                />
            </div>
            <div class="relative z-10 max-w-5xl px-8 md:px-20">
                <span
                    class="mb-4 inline-block text-xs font-semibold uppercase tracking-[0.2em] text-wh-on-surface-variant"
                >
                    Curated Wilderness Journeys
                </span>
                <h1
                    class="text-shadow-editorial mb-8 font-wh-headline text-5xl leading-none tracking-tighter text-wh-primary sm:text-6xl md:text-8xl"
                >
                    Embrace <br />
                    <span class="pr-4 font-light italic text-wh-secondary"
                        >the</span
                    >
                    Untamed
                </h1>
                <p
                    class="mb-10 max-w-xl text-lg font-light leading-relaxed text-wh-on-surface-variant md:text-2xl"
                >
                    Step into a world where the horizon never ends. Boutique
                    expeditions designed for the soul that seeks the wild.
                </p>
                <div class="flex flex-wrap gap-6">
                    <Link
                        v-if="canRegister"
                        :href="register()"
                        class="rounded-md bg-wh-secondary px-8 py-4 text-lg font-medium text-wh-on-secondary transition-all hover:brightness-110"
                    >
                        Start Your Journey
                    </Link>
                    <Link
                        v-else
                        :href="login()"
                        class="rounded-md bg-wh-secondary px-8 py-4 text-lg font-medium text-wh-on-secondary transition-all hover:brightness-110"
                    >
                        Start Your Journey
                    </Link>
                    <a
                        class="group flex items-center gap-3 text-lg font-semibold text-wh-primary"
                        href="#expeditions"
                    >
                        View Expeditions
                        <span
                            class="h-[2px] w-12 bg-wh-secondary-container transition-all group-hover:w-16"
                        />
                    </a>
                </div>
            </div>
        </section>

        <!-- Featured Expeditions -->
        <section
            id="expeditions"
            class="bg-wh-surface px-8 py-24 md:px-20"
        >
            <div class="mx-auto max-w-screen-2xl">
                <div class="mb-16">
                    <span
                        class="text-xs font-bold uppercase tracking-widest text-wh-on-surface-variant"
                    >
                        The Collection
                    </span>
                    <h2
                        class="mt-2 font-wh-headline text-4xl text-wh-primary md:text-5xl"
                    >
                        Featured Expeditions
                    </h2>
                </div>
                <div
                    class="grid h-auto grid-cols-1 gap-8 md:h-[800px] md:grid-cols-12"
                >
                    <div
                        class="relative overflow-hidden rounded-xl bg-wh-surface-container-low p-2 md:col-span-7"
                    >
                        <div
                            class="group relative h-full min-h-[280px] w-full overflow-hidden asymmetric-radius md:min-h-0"
                        >
                            <img
                                alt="The Great Serengeti Migration"
                                class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuA8GhtlbtLpNcHAyBanGVLWc-w0XHyYI_o_9_K2tvo6Rqoih-leQ9MB5K30TOWtPvLo_k2roAzALOqd95EzwhVEdSKghhuR9GpTZ7BarmzsSayroYMjhxGZOdcwD2XAojsY8IaHUI9O4muuLJaA9QUoVYW6rapj-Le2UAxjL3nlg9q1vWES_2vDdCgWc4hQCXG9Kkd_AxLWD49JD8IJR6yedvi8HX70ZEc15nG3i09FPfwq0ka5-8VKl0Afu0esQV46QRkaZP2oCUxk"
                            />
                            <div
                                class="absolute inset-0 bg-linear-to-t from-black/60 to-transparent"
                            />
                            <div class="absolute bottom-8 left-8 text-white">
                                <span
                                    class="font-wh-body text-xs tracking-widest uppercase opacity-80"
                                    >Tanzania</span
                                >
                                <h3 class="mt-2 mb-4 font-wh-headline text-3xl">
                                    The Great Serengeti Migration
                                </h3>
                                <div class="flex items-center gap-4">
                                    <span
                                        class="rounded border border-white/30 bg-white/10 px-3 py-1 text-sm backdrop-blur-sm"
                                        >12 Days</span
                                    >
                                    <span
                                        class="font-semibold text-wh-secondary-container"
                                        >Explore Journey →</span
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="flex flex-col gap-8 md:col-span-5"
                    >
                        <div
                            class="relative min-h-[220px] flex-1 overflow-hidden rounded-xl bg-wh-surface-container-low p-2 md:min-h-0"
                        >
                            <div
                                class="group relative h-full min-h-[200px] w-full overflow-hidden asymmetric-radius"
                            >
                                <img
                                    alt="Gorilla trekking in Rwanda"
                                    class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuAZmOYcM29Vghd7Kt9vMggrhGDziN8l3Ms-ulyNfGGUDodcxXMZCjAAsCF9YFK5ZMx-cKehqNqtrGXo_0IZiUVCo3r3GEaKzouu4klnGVN-qBeCBTm6hA9UWePewtmdBwGAHVcVzA1SAd2KREP7QMkuIdMybDI3kyIn4icPN-fUVc7YtpjARlNatPuXSJIa8mSDdXATFNTBBDBGyfsKVtq7U-d3DgB7kVqh_a4dBpy08iVYSqQANdPL9iAc_NVw8cf_OH3_suhf3GL2"
                                />
                                <div
                                    class="absolute inset-0 bg-linear-to-t from-black/60 to-transparent"
                                />
                                <div
                                    class="absolute bottom-6 left-6 text-white"
                                >
                                    <span
                                        class="font-wh-body text-xs tracking-widest uppercase opacity-80"
                                        >Rwanda</span
                                    >
                                    <h3 class="mt-1 font-wh-headline text-2xl">
                                        Mist of the Volcanoes
                                    </h3>
                                    <p class="mt-2 text-sm opacity-90">
                                        Primate trekking &amp; conservation
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div
                            class="relative min-h-[220px] flex-1 overflow-hidden rounded-xl bg-wh-surface-container-low p-2 md:min-h-0"
                        >
                            <div
                                class="group relative h-full min-h-[200px] w-full overflow-hidden asymmetric-radius"
                            >
                                <img
                                    alt="Okavango Delta safari"
                                    class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuAqIE9givqpjc1U0Ivpw49NYmcZtBafDC2QSO1pJcorUn77fYvOGBpo97_0eBn64M-n6Kw3S5FPBanATVDt9psZfV8w76oI9myTnRvDLEYAF08LbQOSi0ugu0W-bH9hG-r_WCzeO-Ba3nd_8b7uczBo0hYzftfE04CYrG3vQWRtQ5J3EY244jEDe3vWldb0tJokzdy5i27IDVrTD0wEWP9YVDnRGGx1j-wGODqm-MBzSJK9yEIfCdasx0GjWmUpp0cxOIO4zCdLx1TM"
                                />
                                <div
                                    class="absolute inset-0 bg-linear-to-t from-black/60 to-transparent"
                                />
                                <div
                                    class="absolute bottom-6 left-6 text-white"
                                >
                                    <span
                                        class="font-wh-body text-xs tracking-widest uppercase opacity-80"
                                        >Botswana</span
                                    >
                                    <h3 class="mt-1 font-wh-headline text-2xl">
                                        Waterways of the Delta
                                    </h3>
                                    <p class="mt-2 text-sm opacity-90">
                                        Canoe safaris &amp; wetland wonder
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Editorial -->
        <section
            id="story"
            class="overflow-hidden bg-wh-surface-container-low py-24"
        >
            <div
                class="mx-auto grid max-w-screen-2xl grid-cols-1 items-center gap-16 px-8 md:grid-cols-2 md:px-20"
            >
                <div class="relative">
                    <div
                        class="relative z-10 aspect-[4/5] w-4/5 overflow-hidden shadow-2xl asymmetric-radius"
                    >
                        <img
                            alt="Safari guide in the bush"
                            class="h-full w-full object-cover"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuCetfhzJ_ul1DI-betWTiOegF4Y6hHfzuVFLlnKpQyOuJc1b-rT-gokbKe9eaWxAPiYGQVfenO0YFnPSHmqUfdmuSEmfc8dA5hDe9EZiDlyo3a4OwFSB6QJppwAlwMD3GuPQcUCD4GjmBjlVNhTrmHw6TLFgWrwVGN000g8ZFxkjwBY_mxm0TNYY8Yainf5xmuI2XaNcEoLLCmu_gzMgheaoi7WAsbNRRyGxWaLFdi0qvvpftWZOIvxP3qgDmBbI36nQBIvXshhBIxu"
                        />
                    </div>
                    <div
                        class="absolute -right-4 -bottom-10 z-20 aspect-square w-1/2 bg-wh-surface-container-highest p-4 shadow-xl asymmetric-radius"
                    >
                        <img
                            alt="Luxury safari tent interior"
                            class="h-full w-full object-cover asymmetric-radius"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuBwdgUBpXs9ZlgWs-zcQ8vsYqvxjss8HM9RJYrgj0PbBwsuxssXiJX2NV-S6He3QNEIu_eMim09_saqhFA2OIU5j4c_YKV5pRgw7Rxxo4_soeKcNbpMQ5EtWNabvSoSIRFJvLo0ufGLe2uaeeIkeutzocThv569lEOL_W60JSN8jkL3U-YhG6NB7a1_wqodBeSyJk0A3JkZEPlUxHzBpWxE6rmCYcSZNmeykGBzy-0O_PZsyl1CiT58x1_68_zSm75fHRcXjiJoXDBE"
                        />
                    </div>
                    <div
                        class="absolute -top-12 -left-12 -z-10 h-64 w-64 rounded-full bg-wh-tertiary-container/10 blur-3xl"
                    />
                </div>
                <div class="space-y-8">
                    <span
                        class="text-xs font-bold uppercase tracking-[0.2em] text-wh-secondary"
                    >
                        The Soul of {{ appName }}
                    </span>
                    <h2
                        class="font-wh-headline text-5xl leading-tight text-wh-primary md:text-6xl"
                    >
                        The Story <br />
                        <span class="italic">of the</span> Wild
                    </h2>
                    <p
                        class="text-lg leading-relaxed text-wh-on-surface-variant"
                    >
                        Founded by conservationists and veteran explorers,
                        {{ appName }} was born from a desire to reconnect the
                        modern traveler with the primal rhythms of the earth. We
                        don't just show you landscapes; we invite you into the
                        narrative of the ecosystem.
                    </p>
                    <div class="grid grid-cols-2 gap-8 py-4">
                        <div>
                            <span
                                class="font-wh-headline text-3xl text-wh-primary"
                                >15+</span
                            >
                            <span
                                class="font-wh-body text-xs tracking-widest text-wh-on-surface-variant uppercase"
                                >Years of Trails</span
                            >
                        </div>
                        <div>
                            <span
                                class="font-wh-headline text-3xl text-wh-primary"
                                >100%</span
                            >
                            <span
                                class="font-wh-body text-xs tracking-widest text-wh-on-surface-variant uppercase"
                                >Eco-Certified</span
                            >
                        </div>
                    </div>
                    <a
                        class="inline-flex items-center gap-4 rounded-md bg-wh-primary px-8 py-3 font-medium text-wh-on-primary transition-all hover:bg-wh-primary-container"
                        href="#story"
                    >
                        Read Our Mission
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </a>
                </div>
            </div>
        </section>

        <!-- Testimonials -->
        <section id="testimonials" class="bg-wh-surface py-24">
            <div class="mx-auto max-w-screen-xl px-8">
                <div class="mb-16 text-center">
                    <span
                        class="text-xs font-bold tracking-widest text-wh-on-surface-variant uppercase"
                    >
                        Traveler Journals
                    </span>
                    <h2
                        class="mt-4 font-wh-headline text-4xl text-wh-primary md:text-5xl"
                    >
                        Explorer Testimonials
                    </h2>
                </div>
                <div class="grid grid-cols-1 gap-12 md:grid-cols-3">
                    <div
                        class="flex flex-col items-center space-y-6 rounded-xl border border-wh-outline-variant/10 bg-wh-surface-container-lowest p-8 text-center shadow-sm"
                    >
                        <div
                            class="h-24 w-24 overflow-hidden rounded-full border-2 border-wh-secondary p-1"
                        >
                            <img
                                alt="Portrait of Julian Thorne"
                                class="h-full w-full rounded-full object-cover"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuAgnP_gyyVuKd_jdE6ZVVtJTItczsdVQi_P_X4H5j43HohWCBcP-0p0K9lp7U3HvMMJAVlimWh0eoRUTS1b7UgCOiNh92lrIWFymeGc4tumIlKvk1Qpk-bI5U2r1L3mza_xDnZNPIJ7HitaRUEn-jPcAqGBt4Sv_qSMKzo9iGpgDfQxTRtIIqOmPbJo2dfT--xIrs321OMQClLnB6AuvgMTx4wxQfo8Xqgr4so5-WiSv0xJkG3UrPT_y6VOKcqbiXIbEDQJI9ZaTiVw"
                            />
                        </div>
                        <span class="tracking-widest text-wh-secondary"
                            >★★★★★</span
                        >
                        <p
                            class="font-wh-headline text-lg leading-relaxed italic text-wh-on-surface"
                        >
                            "The silence of the Serengeti at dawn is something I
                            will never forget. {{ appName }} made every detail
                            feel personal and profoundly meaningful."
                        </p>
                        <div>
                            <h4 class="font-bold text-wh-primary">
                                Julian Thorne
                            </h4>
                            <span class="text-sm text-wh-on-surface-variant"
                                >Photographer</span
                            >
                        </div>
                    </div>
                    <div
                        class="z-10 flex scale-105 flex-col items-center space-y-6 rounded-xl border border-wh-outline-variant/10 border-t-4 border-t-wh-secondary bg-wh-surface-container-lowest p-8 text-center shadow-sm"
                    >
                        <div
                            class="h-24 w-24 overflow-hidden rounded-full border-2 border-wh-secondary p-1"
                        >
                            <img
                                alt="Portrait of Elena Rossi"
                                class="h-full w-full rounded-full object-cover"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuDyyNNOCtheBYzRD-jeKMZHNaOPmba0WuA1wCrzQGFt44FhceBkqtkb-WMFRJRUHzK2SUdJqrNqZlvoYzXnW06q5nrzQ64DbL6yeAJVEJRRq2dAqTl9abXbvvtLUm1UShanitlTZxQ7xk4rrulCmpFXkSYn8iPwCqLIrwY-8O7_Go-jJ0FAhTsMSBZs7tYf8uWZgJvynAybExzs3R884g-7xgAsRJOnT07hxI5o-wipq1zIuhAceIi7xGOF3bFAkWmiy7xD0Ejf3G04"
                            />
                        </div>
                        <span class="tracking-widest text-wh-secondary"
                            >★★★★★</span
                        >
                        <p
                            class="font-wh-headline text-lg leading-relaxed italic text-wh-on-surface"
                        >
                            "Beyond the luxury, it was the deep knowledge of our
                            guides that blew me away. We didn't just see animals;
                            we understood their lives."
                        </p>
                        <div>
                            <h4 class="font-bold text-wh-primary">
                                Elena Rossi
                            </h4>
                            <span class="text-sm text-wh-on-surface-variant"
                                >Conservationist</span
                            >
                        </div>
                    </div>
                    <div
                        class="flex flex-col items-center space-y-6 rounded-xl border border-wh-outline-variant/10 bg-wh-surface-container-lowest p-8 text-center shadow-sm"
                    >
                        <div
                            class="h-24 w-24 overflow-hidden rounded-full border-2 border-wh-secondary p-1"
                        >
                            <img
                                alt="Portrait of Marcus Vance"
                                class="h-full w-full rounded-full object-cover"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuA-E_7WpwpGuMFj3ae1Qa2Za1mVgLkiwhMhqI5jGF4-A9KzLsQNKt0snxLzc9SgdNTt6UvqSeLVKSQqT9xRbfEMi57yYJHQlZdcWfuq8IVpVHTMUu1xxVx7YzzL1tsxkovG-Pv_LmaohHVjfCf96MhPajaK0MmHZo-9GREnOx_0bqA78H2DNUDe-pEmv1QxSaS6R5CCzrk72NdNu6aIwZMMrkFbHe92wJTwlMTgt_GDmtajC0iPUtnerxsjKfvHzyTErOqO_Cl-Wr8i"
                            />
                        </div>
                        <span class="tracking-widest text-wh-secondary"
                            >★★★★★</span
                        >
                        <p
                            class="font-wh-headline text-lg leading-relaxed italic text-wh-on-surface"
                        >
                            "A masterclass in sustainable travel. Every lodge was
                            a dream, and every experience felt respectful to the
                            wild."
                        </p>
                        <div>
                            <h4 class="font-bold text-wh-primary">
                                Marcus Vance
                            </h4>
                            <span class="text-sm text-wh-on-surface-variant"
                                >Travel Journalist</span
                            >
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer
            id="contact"
            class="w-full rounded-none bg-wh-surface-container"
        >
            <div
                class="mx-auto grid max-w-screen-2xl grid-cols-1 gap-12 px-8 py-20 md:grid-cols-3 md:px-12"
            >
                <div class="space-y-6">
                    <div
                        class="font-wh-headline text-xl italic text-wh-primary"
                    >
                        {{ appName }}
                    </div>
                    <p
                        class="max-w-xs text-sm tracking-wide text-wh-on-surface-variant"
                    >
                        Crafting immersive, high-end wilderness experiences that
                        honor the majesty of our natural world and the stories
                        of those who protect it.
                    </p>
                </div>
                <div class="grid grid-cols-2 gap-8">
                    <div class="flex flex-col gap-4">
                        <span
                            class="font-wh-headline font-bold text-wh-primary"
                            >Navigation</span
                        >
                        <a
                            class="text-sm text-wh-on-surface-variant transition-all hover:text-wh-primary"
                            href="#expeditions"
                            >Destinations</a
                        >
                        <a
                            class="text-sm text-wh-on-surface-variant transition-all hover:text-wh-primary"
                            href="#story"
                            >Our Story</a
                        >
                        <a
                            class="text-sm text-wh-on-surface-variant transition-all hover:text-wh-primary"
                            href="#testimonials"
                            >The Journal</a
                        >
                    </div>
                    <div class="flex flex-col gap-4">
                        <span
                            class="font-wh-headline font-bold text-wh-primary"
                            >Contact</span
                        >
                        <a
                            class="text-sm text-wh-on-surface-variant transition-all hover:text-wh-primary"
                            href="mailto:adventures@wildhorizon.com"
                            >adventures@wildhorizon.com</a
                        >
                        <p class="text-sm text-wh-on-surface-variant">
                            +1 (800) WILD-EXP
                        </p>
                        <div class="mt-2 flex gap-4">
                            <span
                                class="material-symbols-outlined text-xl text-wh-primary"
                                aria-hidden="true"
                                >camera</span
                            >
                            <span
                                class="material-symbols-outlined text-xl text-wh-primary"
                                aria-hidden="true"
                                >play_circle</span
                            >
                        </div>
                    </div>
                </div>
                <div class="space-y-6">
                    <span
                        class="font-wh-headline font-bold text-wh-primary"
                        >Newsletter</span
                    >
                    <p class="text-sm text-wh-on-surface-variant">
                        Subscribe to receive curated wilderness stories and
                        exclusive offers.
                    </p>
                    <form
                        class="flex items-center border-b border-wh-outline/40 pb-2"
                        @submit.prevent
                    >
                        <input
                            class="w-full border-none bg-transparent text-sm placeholder:text-wh-on-surface-variant/50 focus:ring-0"
                            type="email"
                            placeholder="Your email address"
                            autocomplete="email"
                        />
                        <button
                            type="submit"
                            class="material-symbols-outlined text-wh-secondary"
                            aria-label="Subscribe"
                        >
                            arrow_right_alt
                        </button>
                    </form>
                </div>
            </div>
            <div
                class="mx-auto flex max-w-screen-2xl flex-col items-center justify-between gap-6 border-t border-wh-outline-variant/10 px-8 pt-8 pb-12 md:flex-row md:px-12"
            >
                <p class="text-sm tracking-wide text-wh-on-surface-variant">
                    © {{ new Date().getFullYear() }} {{ appName }}. Crafted for
                    the Modern Explorer.
                </p>
                <div class="flex gap-8">
                    <a
                        class="text-sm font-semibold text-wh-on-surface-variant hover:text-wh-secondary"
                        href="#"
                        >Privacy Policy</a
                    >
                    <a
                        class="text-sm font-semibold text-wh-on-surface-variant hover:text-wh-secondary"
                        href="#"
                        >Terms of Service</a
                    >
                    <a
                        class="text-sm font-semibold text-wh-on-surface-variant hover:text-wh-secondary"
                        href="#"
                        >Sustainability</a
                    >
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
.material-symbols-outlined {
    font-family: 'Material Symbols Outlined';
    font-weight: normal;
    font-style: normal;
    font-size: 1.25rem;
    line-height: 1;
    letter-spacing: normal;
    text-transform: none;
    display: inline-block;
    white-space: nowrap;
    word-wrap: normal;
    direction: ltr;
    font-variation-settings:
        'FILL' 0,
        'wght' 400,
        'GRAD' 0,
        'opsz' 24;
}

.text-shadow-editorial {
    text-shadow: 0 2px 12px rgba(30, 27, 21, 0.2);
}

.asymmetric-radius {
    border-top-left-radius: 0.75rem;
    border-bottom-right-radius: 0.75rem;
    border-top-right-radius: 0.125rem;
    border-bottom-left-radius: 0.125rem;
}
</style>
