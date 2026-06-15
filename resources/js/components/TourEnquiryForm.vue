<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { CircleCheck, Loader2Icon } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';
import InputError from '@/components/InputError.vue';

const props = defineProps<{
    action: string;
    isLocal: boolean;
    turnstileSiteKey: string;
    status?: string;
}>();

const submitted = ref(props.status !== undefined && props.status !== '');

const form = useForm({
    name: '',
    email: '',
    phone: '',
    booking_date: '',
    adults: '',
    children: '',
    message: '',
    'cf-turnstile-response': '',
});

const minDate = (() => {
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);

    return tomorrow.toISOString().split('T')[0];
})();

declare global {
    interface Window {
        turnstileCallback: (token: string) => void;
        turnstileExpiredCallback: () => void;
    }
}

function openDatePicker(event: MouseEvent): void {
    const input = event.currentTarget as HTMLInputElement;

    if ('showPicker' in input && typeof input.showPicker === 'function') {
        input.showPicker();
    }
}

function submit(): void {
    form.post(props.action, {
        preserveScroll: true,
        onSuccess: () => {
            submitted.value = true;
            form.reset();
        },
    });
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
    <div class="form-book-tour">
        <div
            v-if="submitted || status"
            class="flex flex-col items-center justify-center gap-4 py-8 text-center"
        >
            <CircleCheck class="size-20 text-green-600" />
            <h4 class="text-lg font-semibold text-[#081e2a]">
                We have received your request
            </h4>
            <p class="text-sm text-[#6b7280]">
                {{ status || 'We will get back to you shortly.' }}
            </p>
        </div>

        <form v-else @submit.prevent="submit">
            <div class="input-wrap mb-20">
                <label for="name">Name</label>
                <input
                    id="name"
                    v-model="form.name"
                    type="text"
                    name="name"
                    placeholder="Your name"
                    required
                />
                <InputError :message="form.errors.name" />
            </div>

            <div class="input-wrap mb-20">
                <label for="email">Email</label>
                <input
                    id="email"
                    v-model="form.email"
                    type="email"
                    name="email"
                    placeholder="Your email"
                    required
                />
                <InputError :message="form.errors.email" />
            </div>

            <div class="input-wrap mb-20">
                <label for="phone">Phone Number</label>
                <input
                    id="phone"
                    v-model="form.phone"
                    type="tel"
                    name="phone"
                    placeholder="Your phone number"
                    required
                />
                <InputError :message="form.errors.phone" />
            </div>

            <div class="input-wrap mb-20">
                <label for="booking-date">Preferred Date of Booking</label>
                <input
                    id="booking-date"
                    v-model="form.booking_date"
                    type="date"
                    name="booking_date"
                    :min="minDate"
                    required
                    @click="openDatePicker"
                />
                <InputError :message="form.errors.booking_date" />
            </div>

            <div class="row mb-30">
                <div class="col-6">
                    <div class="input-wrap">
                        <label for="adults">Number of Adults</label>
                        <select
                            id="adults"
                            v-model="form.adults"
                            name="adults"
                            class="form-select"
                            required
                        >
                            <option v-for="n in 9" :key="n" :value="String(n)">
                                {{ n }}
                            </option>
                            <option value="10+">10+</option>
                        </select>
                        <InputError :message="form.errors.adults" />
                    </div>
                </div>
                <div class="col-6">
                    <div class="input-wrap">
                        <label for="children">Number of Children</label>
                        <select
                            id="children"
                            v-model="form.children"
                            name="children"
                            class="form-select"
                        >
                            <option value="0">0</option>
                            <option v-for="n in 9" :key="n" :value="String(n)">
                                {{ n }}
                            </option>
                            <option value="10+">10+</option>
                        </select>
                        <InputError :message="form.errors.children" />
                    </div>
                </div>
            </div>

            <div class="input-wrap mb-30">
                <label for="message">
                    Your Message
                    <span class="optional-label">(optional)</span>
                </label>
                <textarea
                    id="message"
                    v-model="form.message"
                    name="message"
                    rows="4"
                    placeholder="Tell us more about your request"
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
                <InputError :message="form.errors['cf-turnstile-response']" />
            </div>

            <div class="flex justify-center">
                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-md bg-[#4da528] px-6 py-3 text-sm font-semibold text-white transition-opacity hover:opacity-90 disabled:cursor-not-allowed disabled:opacity-60"
                    :disabled="form.processing"
                >
                    <Loader2Icon
                        v-if="form.processing"
                        class="size-4 animate-spin"
                    />
                    {{ form.processing ? 'Submitting...' : 'Submit' }}
                </button>
            </div>
        </form>
    </div>
</template>

<style scoped>
.form-book-tour > .input-wrap {
    margin-bottom: 20px;
}

.form-book-tour .row .input-wrap {
    margin-bottom: 0;
}

.form-book-tour label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    line-height: 20px;
    color: #081e2a;
    margin-bottom: 8px;
}

.form-book-tour input,
.form-book-tour select,
.form-book-tour textarea {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #e0e2e4;
    border-radius: 6px;
    font-size: 14px;
    line-height: 20px;
    background-color: #ffffff;
}

.form-book-tour input:focus,
.form-book-tour select:focus,
.form-book-tour textarea:focus {
    outline: none;
    border-color: #4da528;
}

.form-book-tour textarea {
    resize: vertical;
}

.optional-label {
    text-transform: lowercase;
    font-size: 0.85em;
    color: #6b7280;
}
</style>
