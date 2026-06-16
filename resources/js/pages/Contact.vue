<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { CircleCheck, Loader2Icon } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';
import { VueTelInput } from 'vue-tel-input';
import 'vue-tel-input/vue-tel-input.css';
import InputError from '@/components/InputError.vue';
import { useScrollAnimate } from '@/composables/useScrollAnimate';
import { home } from '@/routes';
import { store as contactStore } from '@/routes/contact';

defineProps<{
    turnstileSiteKey: string;
    isLocal: boolean;
    status?: string;
}>();

useScrollAnimate();

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
    <section class="breadcumb-section">
        <div class="tf-container">
            <div class="row">
                <div class="col-lg-12 center z-index1">
                    <h1 class="title">Contact Us</h1>
                    <ul class="breadcumb-list flex-five">
                        <li><Link :href="home()">Home</Link></li>
                        <li><span>Contact Us</span></li>
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

    <!-- Contact Info -->
    <section class="contact-us pd-main">
        <div class="tf-container">
            <div class="row mb-60">
                <div class="col-md-4 wow fadeInUp">
                    <div class="box-contact center">
                        <div class="icon">
                            <i
                                class="icon-Layer-19"
                                style="font-size: 48px; color: #4da528"
                            ></i>
                        </div>
                        <span>Address</span>
                        <p class="des">
                            6391 Elgin St. Celina<br />
                            New York City, USA
                        </p>
                    </div>
                </div>
                <div class="col-md-4 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="box-contact center">
                        <div class="icon">
                            <i
                                class="icon-Group-9"
                                style="font-size: 48px; color: #4da528"
                            ></i>
                        </div>
                        <span>Phone Number</span>
                        <p class="des">
                            +1 684 555-0102<br />
                            +1 684 555-0103
                        </p>
                    </div>
                </div>
                <div class="col-md-4 wow fadeInUp" data-wow-delay="0.2s">
                    <div class="box-contact center">
                        <div class="icon">
                            <i
                                class="icon-noun-mail-5780740-1"
                                style="font-size: 48px; color: #4da528"
                            ></i>
                        </div>
                        <span>Mail Address</span>
                        <p class="des">
                            info@simplysafaris.com<br />
                            support@simplysafaris.com
                        </p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 wow fadeInLeft">
                    <div class="contact-us-map">
                        <div class="inner-header mb-45">
                            <h2 class="title">Get in touch</h2>
                            <p class="des">
                                Have questions about our safari tours? Reach out
                                and our team will be happy to help you plan your
                                perfect adventure.
                            </p>
                        </div>
                        <div class="map relative">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.2!2d-73.98!3d40.75!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNDDCsDQ1JzAwLjAiTiA3M8KwNTgnNDguMCJX!5e0!3m2!1sen!2sus!4v1"
                                width="100%"
                                height="400"
                                style="border: 0; border-radius: 12px"
                                :allowfullscreen="true"
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                            ></iframe>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 wow fadeInRight">
                    <div class="contact-us-wrap bg-4">
                        <div
                            v-if="submitted || status"
                            class="flex flex-col items-center justify-center gap-4 py-8 text-center"
                        >
                            <CircleCheck class="size-20 text-green-600" />
                            <h3 class="title" style="margin-bottom: 0">
                                We Have Received Your Message
                            </h3>
                            <p class="des">
                                {{ status || "We'll get back to you shortly." }}
                            </p>
                        </div>

                        <form
                            v-else
                            id="form-contact-us"
                            @submit.prevent="submit"
                        >
                            <div class="input-wrap relative">
                                <i class="icon-user-1-1"></i>
                                <input
                                    v-model="form.name"
                                    type="text"
                                    name="name"
                                    placeholder="Your Name*"
                                    required
                                />
                                <InputError :message="form.errors.name" />
                            </div>
                            <div class="input-wrap relative">
                                <i class="icon-Group-51"></i>
                                <input
                                    v-model="form.email"
                                    type="email"
                                    name="email"
                                    placeholder="Email Address*"
                                    required
                                />
                                <InputError :message="form.errors.email" />
                            </div>
                            <div class="contact-phone-wrap">
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
                                <InputError :message="form.errors.phone" />
                            </div>
                            <div class="input-wrap relative">
                                <i class="icon-content"></i>
                                <textarea
                                    v-model="form.message"
                                    name="message"
                                    rows="7"
                                    cols="30"
                                    placeholder="Enter Your Message here*"
                                    required
                                ></textarea>
                                <InputError :message="form.errors.message" />
                            </div>

                            <div v-if="!isLocal" class="mb-20">
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

                            <button
                                type="submit"
                                class="btn-submit-contact"
                                :disabled="form.processing"
                            >
                                <Loader2Icon
                                    v-if="form.processing"
                                    class="mr-2 inline-block size-4 animate-spin"
                                />
                                <i v-else class="icon-Group-121"></i>
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
    <section class="mb--93 bg-1">
        <div class="tf-container">
            <div
                class="callt-to-action flex-two z-index3 wow fadeInUp relative"
            >
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
                            Explore the world's most breathtaking safari
                            destinations with us
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
</template>

<style scoped>
.contact-phone-wrap {
    margin-bottom: 50px;
}

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
