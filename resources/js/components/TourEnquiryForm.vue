<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import { onMounted } from 'vue';

defineProps<{
    action: string;
    isLocal: boolean;
    turnstileSiteKey: string;
    status?: string;
}>();

onMounted(() => {
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
    <Form method="post" :action="action" class="form-book-tour">
        <div v-if="status" class="mb-20 text-sm text-green-600">
            {{ status }}
        </div>

        <div class="input-wrap mb-20">
            <label for="name">Name</label>
            <input
                id="name"
                type="text"
                name="name"
                placeholder="Your name"
                required
            />
        </div>

        <div class="input-wrap mb-20">
            <label for="email">Email</label>
            <input
                id="email"
                type="email"
                name="email"
                placeholder="Your email"
                required
            />
        </div>

        <div class="input-wrap mb-20">
            <label for="phone">Phone Number</label>
            <input
                id="phone"
                type="tel"
                name="phone"
                placeholder="Your phone number"
                required
            />
        </div>

        <div class="input-wrap mb-20">
            <label for="booking-date">Preferred Date of Booking</label>
            <input id="booking-date" type="date" name="booking_date" required />
        </div>

        <div class="row mb-30">
            <div class="col-6">
                <div class="input-wrap">
                    <label for="adults">Number of Adults</label>
                    <select
                        id="adults"
                        name="adults"
                        class="form-select"
                        required
                    >
                        <option v-for="n in 10" :key="n" :value="n">
                            {{ n }}
                        </option>
                        <option value="10+">10+</option>
                    </select>
                </div>
            </div>
            <div class="col-6">
                <div class="input-wrap">
                    <label for="children">Number of Children</label>
                    <select id="children" name="children" class="form-select">
                        <option value="0">0</option>
                        <option v-for="n in 10" :key="n" :value="n">
                            {{ n }}
                        </option>
                        <option value="10+">10+</option>
                    </select>
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
                name="message"
                rows="4"
                placeholder="Tell us more about your request"
            ></textarea>
        </div>

        <div v-if="!isLocal" class="mb-20">
            <div class="cf-turnstile" :data-sitekey="turnstileSiteKey"></div>
        </div>

        <div class="flex justify-center">
            <button type="submit">Submit</button>
        </div>
    </Form>
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
