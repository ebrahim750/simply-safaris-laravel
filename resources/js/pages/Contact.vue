<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { CircleCheck, Loader2Icon, MapPin, Phone, Mail } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';
import { VueTelInput } from 'vue-tel-input';
import 'vue-tel-input/vue-tel-input.css';
import InputError from '@/components/InputError.vue';
import { home } from '@/routes';
import { store as contactStore } from '@/routes/contact';

defineProps<{
    turnstileSiteKey: string;
    isLocal: boolean;
    status?: string;
}>();

const submitted = ref(false);

const phoneInputRef = ref<InstanceType<typeof VueTelInput>>();

const form = useForm({
    name: '',
    email: '',
    phone: '',
    message: '',
    'cf-turnstile-response': '',
});

function submit(): void {
    form.post(contactStore.url(), {
        preserveScroll: true,
        onSuccess: () => {
            submitted.value = true;
            form.reset();
        },
    });
}

function onDropdownOpen(): void {
    setTimeout(() => {
        const root = phoneInputRef.value?.$el as HTMLElement | undefined;
        const searchInput = root?.querySelector<HTMLInputElement>('.vti__search_box');
        searchInput?.focus();
    }, 150);
}

declare global {
    interface Window {
        turnstileCallback: (token: string) => void;
        turnstileExpiredCallback: () => void;
    }
}

onMounted(() => {
    window.turnstileCallback = (token: string): void => {
        form['cf-turnstile-response'] = token;
    };

    window.turnstileExpiredCallback = (): void => {
        form['cf-turnstile-response'] = '';
    };

    const existingScript = document.querySelector(
        'script[src="https://challenges.cloudflare.com/turnstile/v0/api.js"]',
    );

    if (existingScript) {
        return;
    }

    const script = document.createElement('script');
    script.src = 'https://challenges.cloudflare.com/turnstile/v0/api.js';
    script.async = true;
    script.defer = true;
    document.head.appendChild(script);
});
</script>

<template>
    <Head title="Contact Us" />

    <!-- Breadcrumb -->
    <section class="relative flex items-center bg-cover bg-center bg-no-repeat py-[137px]" style="background-image: url('/template/images/page/breakcrumb.jpg')">
        <div class="absolute inset-0" style="background: linear-gradient(262deg, rgba(0,0,0,0.33), rgba(4,29,11,0.89))"></div>
        <div class="relative z-[1] mx-auto w-full max-w-[1320px] px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="mb-[26px] text-white" style="font-size: 65px; line-height: 82px; font-weight: 600;">Contact Us</h1>
                <nav class="flex items-center justify-center" style="font-size: 18px; line-height: 22.68px;">
                    <Link :href="home()" class="relative mr-[30px] text-[#4DA528] after:absolute after:right-[-18px] after:top-1/2 after:-translate-y-1/2 after:text-white after:content-['\203A'] after:text-[10px]">Home</Link>
                    <span class="text-white">Contact Us</span>
                </nav>
                <img class="absolute left-[7%] top-[40%] hidden w-auto lg:block" src="/template/images/page/mask-bcrumb.png" alt="" />
            </div>
        </div>
    </section>

    <!-- Contact Info Cards -->
    <section class="py-[120px] max-lg:py-20">
        <div class="mx-auto w-full max-w-[1320px] px-4 sm:px-6 lg:px-8">
            <div class="mb-[60px] grid gap-8 lg:grid-cols-3">
                <!-- Address Card -->
                <div class="rounded-lg border border-[#EDEEEE] px-10 py-[61px] text-center shadow-[0_15px_10px_0_rgba(242,242,242,0.18)] max-lg:mb-[30px] max-xl:px-5 max-xl:py-10">
                    <div class="mx-auto flex h-[71px] items-center justify-center text-[#4DA528]">
                        <MapPin class="size-12" />
                    </div>
                    <span class="mb-[18px] mt-[26px] block text-2xl font-semibold text-[#081E2A]">Address</span>
                    <p class="text-[16px] leading-[30.8px] text-[#4F545A]">
                        6391 Elgin St. Celina<br />
                        New York City, USA
                    </p>
                </div>

                <!-- Phone Card -->
                <div class="rounded-lg border border-[#EDEEEE] px-10 py-[61px] text-center shadow-[0_15px_10px_0_rgba(242,242,242,0.18)] max-lg:mb-[30px] max-xl:px-5 max-xl:py-10">
                    <div class="mx-auto flex h-[71px] items-center justify-center text-[#4DA528]">
                        <Phone class="size-12" />
                    </div>
                    <span class="mb-[18px] mt-[26px] block text-2xl font-semibold text-[#081E2A]">Phone Number</span>
                    <p class="text-[16px] leading-[30.8px] text-[#4F545A]">
                        +1 684 555-0102<br />
                        +1 684 555-0103
                    </p>
                </div>

                <!-- Email Card -->
                <div class="rounded-lg border border-[#EDEEEE] px-10 py-[61px] text-center shadow-[0_15px_10px_0_rgba(242,242,242,0.18)] max-lg:mb-[30px] max-xl:px-5 max-xl:py-10">
                    <div class="mx-auto flex h-[71px] items-center justify-center text-[#4DA528]">
                        <Mail class="size-12" />
                    </div>
                    <span class="mb-[18px] mt-[26px] block text-2xl font-semibold text-[#081E2A]">Mail Address</span>
                    <p class="text-[16px] leading-[30.8px] text-[#4F545A]">
                        info@simplysafaris.com<br />
                        support@simplysafaris.com
                    </p>
                </div>
            </div>

            <div class="-mx-4 flex flex-wrap">
                <!-- Left: Map -->
                <div class="w-full px-4 lg:w-1/2 lg:pr-[4.5em] max-lg:pr-4">
                    <div class="mb-[45px]">
                        <h2 class="text-[36px] font-semibold leading-[48px] tracking-[1px] text-[#081E2A]">Get in touch</h2>
                        <p class="mt-[18px] text-[16px] leading-[30.8px] text-[#4F545A]">
                            Have questions about our safari tours? Reach out and our team will be happy to help you plan your perfect adventure.
                        </p>
                    </div>
                    <div class="relative overflow-hidden rounded-xl">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.2!2d-73.98!3d40.75!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNDDCsDQ1JzAwLjAiTiA3M8KwNTgnNDguMCJX!5e0!3m2!1sen!2sus!4v1"
                            width="100%"
                            height="559"
                            style="border: 0"
                            :allowfullscreen="true"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                        ></iframe>
                    </div>
                </div>

                <!-- Right: Form -->
                <div class="w-full px-4 lg:w-1/2 max-lg:mt-8">
                    <div class="-ml-[25px] bg-[#F3F8F6] px-[65px] py-[56px] max-xl:px-8 max-xl:py-10 max-lg:ml-0">
                        <!-- Success State -->
                        <div
                            v-if="submitted || status"
                            class="flex flex-col items-center justify-center gap-5 py-12 text-center"
                        >
                            <CircleCheck class="size-20 text-green-500" />
                            <h3 class="text-[28px] font-semibold text-[#081E2A]">We Have Received Your Message</h3>
                            <p class="text-[16px] text-[#4F545A]">
                                {{ status || "We'll get back to you shortly." }}
                            </p>
                        </div>

                        <!-- Form -->
                        <form
                            v-else
                            id="form-contact-tailwind"
                            @submit.prevent="submit"
                        >
                            <!-- Name -->
                            <div class="relative mb-[50px]">
                                <i class="absolute left-[5px] top-[6px] text-xl text-[#4DA528]">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                </i>
                                <input
                                    v-model="form.name"
                                    type="text"
                                    name="name"
                                    placeholder="Your Name*"
                                    required
                                    class="w-full border-0 border-b border-[#EDEEEE] bg-transparent pb-[20px] pl-[33px] pt-[8px] text-[18px] leading-[22px] text-[#081E2A] placeholder:text-[18px] placeholder:font-normal placeholder:leading-[22px] placeholder:text-[#999] focus:border-b-[#081E2A] focus:outline-none focus:ring-0"
                                />
                                <InputError :message="form.errors.name" class="absolute -bottom-6 left-0" />
                            </div>

                            <!-- Email -->
                            <div class="relative mb-[50px]">
                                <i class="absolute left-[5px] top-[6px] text-xl text-[#4DA528]">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                                </i>
                                <input
                                    v-model="form.email"
                                    type="email"
                                    name="email"
                                    placeholder="Email Address*"
                                    required
                                    class="w-full border-0 border-b border-[#EDEEEE] bg-transparent pb-[20px] pl-[33px] pt-[8px] text-[18px] leading-[22px] text-[#081E2A] placeholder:text-[18px] placeholder:font-normal placeholder:leading-[22px] placeholder:text-[#999] focus:border-b-[#081E2A] focus:outline-none focus:ring-0"
                                />
                                <InputError :message="form.errors.email" class="absolute -bottom-6 left-0" />
                            </div>

                            <!-- Phone -->
                            <div class="contact-phone-wrap mb-[50px]">
                                <VueTelInput
                                    ref="phoneInputRef"
                                    v-model="form.phone"
                                    :input-options="{
                                        required: true,
                                        placeholder: 'Phone Number*',
                                    }"
                                    :dropdown-options="{
                                        showSearchBox: true,
                                        showDialCodeInSelection: true,
                                        showDialCodeInList: true,
                                        showFlags: true,
                                        searchBoxPlaceholder: 'Search...',
                                    }"
                                    default-country="TZ"
                                    mode="international"
                                    @open="onDropdownOpen"
                                />
                                <InputError :message="form.errors.phone" class="mt-1" />
                            </div>

                            <!-- Message -->
                            <div class="relative mb-[50px]">
                                <i class="absolute left-[5px] top-[6px] text-xl text-[#4DA528]">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                </i>
                                <textarea
                                    v-model="form.message"
                                    name="message"
                                    rows="7"
                                    cols="30"
                                    placeholder="Enter Your Message here*"
                                    required
                                    class="w-full resize-none border-0 border-b border-[#EDEEEE] bg-transparent pb-[20px] pl-[33px] pt-[8px] text-[18px] leading-[22px] text-[#081E2A] placeholder:text-[18px] placeholder:font-normal placeholder:leading-[22px] placeholder:text-[#999] focus:border-b-[#081E2A] focus:outline-none focus:ring-0"
                                ></textarea>
                                <InputError :message="form.errors.message" class="absolute -bottom-6 left-0" />
                            </div>

                            <!-- Turnstile -->
                            <div v-if="!isLocal" class="mb-[20px]">
                                <div
                                    class="cf-turnstile"
                                    :data-sitekey="turnstileSiteKey"
                                    data-callback="turnstileCallback"
                                    data-expired-callback="turnstileExpiredCallback"
                                ></div>
                                <InputError
                                    :message="
                                        form.errors['cf-turnstile-response']
                                    "
                                />
                            </div>

                            <!-- Submit -->
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="inline-flex items-center gap-[10px] rounded-[5px] bg-[#4DA528] px-9 py-[15px] text-[15px] font-semibold uppercase tracking-[0.05em] text-white transition-all hover:bg-[#FF970D] disabled:cursor-not-allowed disabled:opacity-60"
                            >
                                <Loader2Icon
                                    v-if="form.processing"
                                    class="size-4 animate-spin"
                                />
                                <svg v-else xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                {{
                                    form.processing
                                        ? 'Submitting...'
                                        : 'Get In Touch'
                                }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-[#F4F6F8] py-[120px] max-lg:py-20">
        <div class="mx-auto w-full max-w-[1320px] px-4 sm:px-6 lg:px-8">
            <div class="relative -mb-[93px] flex items-center justify-between rounded-[7px] bg-[#4DA528] px-[39px] py-[35px] max-md:flex-col max-md:gap-5 max-md:text-center max-sm:px-5 max-sm:py-[30px]">
                <div class="flex items-center gap-[43px] max-md:flex-col max-md:gap-4">
                    <div class="shrink-0">
                        <img
                            src="/template/images/page/ready.png"
                            alt="Ready to adventure"
                            class="w-[74px]"
                        />
                    </div>
                    <div>
                        <h2 class="mb-[10px] text-[36px] font-bold leading-[45px] text-white max-md:text-[29px] max-md:leading-[40px]">Ready to adventure and enjoy natural</h2>
                        <p class="text-[18px] leading-[32px] text-white/50 max-md:text-[16px] max-md:leading-[29px]" style="font-family: 'DM Sans', sans-serif;">
                            Explore the world's most breathtaking safari destinations with us
                        </p>
                    </div>
                </div>
                <img
                    src="/template/images/page/vector4.png"
                    alt=""
                    class="absolute right-[22%] hidden w-auto animate-spin xl:block"
                    style="animation-duration: 8s;"
                />
                <div class="shrink-0 max-md:mt-5">
                    <a
                        href="#"
                        class="inline-block rounded-[5px] bg-white px-9 py-[23px] text-[15px] font-semibold uppercase tracking-[0.05em] text-[#4DA528] transition-all hover:bg-[#FF970D] hover:text-white"
                    >
                        Let's get started
                    </a>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.contact-phone-wrap .vue-tel-input {
    width: 100%;
    border: none;
    border-bottom: 1px solid #edeeee;
    border-radius: 0;
    background-color: transparent;
    box-shadow: none;
    padding: 0;
    height: auto;
}

.contact-phone-wrap .vue-tel-input:focus-within {
    border-bottom-color: #081e2a;
    box-shadow: none;
}

:deep(.contact-phone-wrap .vue-tel-input .vti__dropdown) {
    border: none;
    border-radius: 0;
    background-color: transparent;
    padding: 6px 8px;
    margin: 0;
}

:deep(.contact-phone-wrap .vue-tel-input .vti__dropdown:hover) {
    background-color: transparent;
}

:deep(.contact-phone-wrap .vue-tel-input .vti__selection) {
    font-size: 18px;
    padding: 0 8px;
}

:deep(.contact-phone-wrap .vue-tel-input .vti__input:not(.vti__search_box)) {
    border: none;
    border-radius: 0;
    padding: 8px 16px 20px 8px;
    font-size: 18px;
    line-height: 22px;
    background-color: transparent;
    box-shadow: none;
    color: inherit;
}

:deep(.contact-phone-wrap .vue-tel-input .vti__input:not(.vti__search_box)::placeholder) {
    font-size: 18px;
    font-weight: 400;
    line-height: 22px;
}

:deep(.contact-phone-wrap .vue-tel-input .vti__dropdown-list) {
    margin-top: 4px;
    border: 1px solid #e0e2e4;
    border-radius: 6px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    font-size: 14px;
    max-height: 260px;
    min-width: 240px;
    width: auto;
    overflow-y: auto;
    overflow-x: hidden;
    white-space: nowrap;
}

:deep(.contact-phone-wrap .vue-tel-input .vti__dropdown-list.below) {
    top: calc(100% + 4px);
}

:deep(.contact-phone-wrap .vue-tel-input .vti__dropdown-item) {
    padding: 8px 12px;
    cursor: pointer;
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: 8px;
}

:deep(.contact-phone-wrap .vue-tel-input .vti__dropdown-item:hover),
:deep(.contact-phone-wrap .vue-tel-input .vti__dropdown-item.highlighted) {
    background-color: #f5f5f5;
}

:deep(.contact-phone-wrap .vue-tel-input .vti__dial-code) {
    margin-left: auto;
}

:deep(.contact-phone-wrap .vue-tel-input .vti__search_box_container) {
    padding: 4px 8px;
    border-bottom: 1px solid #e0e2e4;
}

:deep(.contact-phone-wrap .vue-tel-input .vti__search_box) {
    width: calc(100% - 8px);
    padding: 8px 12px;
    margin: 4px;
    border: 1px solid #e0e2e4;
    border-radius: 6px;
    font-size: 14px;
    outline: none;
    box-sizing: border-box;
}

:deep(.contact-phone-wrap .vue-tel-input .vti__search_box:focus) {
    border-color: #4da528;
}
</style>
