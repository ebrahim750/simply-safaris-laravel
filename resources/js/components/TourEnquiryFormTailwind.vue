<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { CircleCheck, Loader2Icon } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';
import { VueTelInput } from 'vue-tel-input';
import 'vue-tel-input/vue-tel-input.css';
import InputError from '@/components/InputError.vue';

const props = defineProps<{
    action: string;
    isLocal: boolean;
    turnstileSiteKey: string;
    status?: string;
}>();

const submitted = ref(props.status !== undefined && props.status !== '');

const phoneInputRef = ref<InstanceType<typeof VueTelInput>>();

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

function onDropdownOpen(): void {
    setTimeout(() => {
        const root = phoneInputRef.value?.$el as HTMLElement | undefined;
        const searchInput = root?.querySelector<HTMLInputElement>('.vti__search_box');
        searchInput?.focus();
    }, 150);
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
        <div v-if="submitted || status" class="flex flex-col items-center justify-center gap-4 py-8 text-center">
            <CircleCheck class="size-20 text-green-600" />
            <h4 class="text-lg font-semibold text-[#081e2a]">We have received your request</h4>
            <p class="text-sm text-[#6b7280]">{{ status || 'We will get back to you shortly.' }}</p>
        </div>

        <form v-else @submit.prevent="submit">
            <div class="mb-[30px]">
                <label class="mb-2 block text-[14px] font-semibold leading-5 text-[#081e2a]" for="name">Name</label>
                <input id="name" v-model="form.name" type="text" name="name" placeholder="Your name" required class="w-full rounded-md border border-[#e0e2e4] px-4 py-3 text-[14px] leading-5 focus:border-[#4da528] focus:outline-none" />
                <InputError :message="form.errors.name" />
            </div>

            <div class="mb-[30px]">
                <label class="mb-2 block text-[14px] font-semibold leading-5 text-[#081e2a]" for="email">Email</label>
                <input id="email" v-model="form.email" type="email" name="email" placeholder="Your email" required class="w-full rounded-md border border-[#e0e2e4] px-4 py-3 text-[14px] leading-5 focus:border-[#4da528] focus:outline-none" />
                <InputError :message="form.errors.email" />
            </div>

            <div class="mb-[30px]">
                <label class="mb-2 block text-[14px] font-semibold leading-5 text-[#081e2a]" for="phone">Phone Number</label>
                <VueTelInput
                    ref="phoneInputRef"
                    id="phone"
                    v-model="form.phone"
                    :input-options="{ required: true, placeholder: 'Your phone number' }"
                    :dropdown-options="{ showSearchBox: true, showDialCodeInSelection: true, showDialCodeInList: true, showFlags: true, searchBoxPlaceholder: 'Search...' }"
                    default-country="TZ"
                    mode="international"
                    @open="onDropdownOpen"
                />
                <InputError :message="form.errors.phone" />
            </div>

            <div class="mb-[30px]">
                <label class="mb-2 block text-[14px] font-semibold leading-5 text-[#081e2a]" for="booking-date">Preferred Date of Booking</label>
                <input id="booking-date" v-model="form.booking_date" type="date" name="booking_date" :min="minDate" required class="w-full rounded-md border border-[#e0e2e4] px-4 py-3 text-[14px] leading-5 focus:border-[#4da528] focus:outline-none" @click="openDatePicker" />
                <InputError :message="form.errors.booking_date" />
            </div>

            <div class="-mx-2 mb-[30px] flex flex-wrap">
                <div class="w-full px-2 md:w-1/2">
                    <div>
                        <label class="mb-2 block text-[14px] font-semibold leading-5 text-[#081e2a]" for="adults">Number of Adults</label>
                        <select id="adults" v-model="form.adults" name="adults" class="w-full rounded-md border border-[#e0e2e4] px-4 py-3 text-[14px] leading-5 focus:border-[#4da528] focus:outline-none" required>
                            <option v-for="n in 9" :key="n" :value="String(n)">{{ n }}</option>
                            <option value="10+">10+</option>
                        </select>
                        <InputError :message="form.errors.adults" />
                    </div>
                </div>
                <div class="mt-[30px] w-full px-2 md:mt-0 md:w-1/2">
                    <div>
                        <label class="mb-2 block text-[14px] font-semibold leading-5 text-[#081e2a]" for="children">Number of Children</label>
                        <select id="children" v-model="form.children" name="children" class="w-full rounded-md border border-[#e0e2e4] px-4 py-3 text-[14px] leading-5 focus:border-[#4da528] focus:outline-none">
                            <option value="0">0</option>
                            <option v-for="n in 9" :key="n" :value="String(n)">{{ n }}</option>
                            <option value="10+">10+</option>
                        </select>
                        <InputError :message="form.errors.children" />
                    </div>
                </div>
            </div>

            <div class="mb-[30px]">
                <label class="mb-2 block text-[14px] font-semibold leading-5 text-[#081e2a]" for="message">
                    Your Message
                    <span class="text-[0.85em] lowercase text-[#6b7280]">(optional)</span>
                </label>
                <textarea id="message" v-model="form.message" name="message" rows="4" placeholder="Tell us more about your request" class="w-full resize-y rounded-md border border-[#e0e2e4] px-4 py-3 text-[14px] leading-5 focus:border-[#4da528] focus:outline-none"></textarea>
                <InputError :message="form.errors.message" />
            </div>

            <div v-if="!isLocal" class="mb-[30px]">
                <div class="cf-turnstile" :data-sitekey="turnstileSiteKey" data-callback="turnstileCallback" data-expired-callback="turnstileExpiredCallback"></div>
                <InputError :message="form.errors['cf-turnstile-response']" />
            </div>

            <div class="flex justify-center">
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-md bg-[#4da528] px-6 py-3 text-sm font-semibold text-white transition-opacity hover:opacity-90 disabled:cursor-not-allowed disabled:opacity-60" :disabled="form.processing">
                    <Loader2Icon v-if="form.processing" class="size-4 animate-spin" />
                    {{ form.processing ? 'Submitting...' : 'Submit' }}
                </button>
            </div>
        </form>
    </div>
</template>

<style scoped>
.vue-tel-input {
    width: 100%;
    height: 46px;
    border: 1px solid #e0e2e4;
    border-radius: 6px;
    background-color: #ffffff;
    box-shadow: none;
}

.vue-tel-input:focus-within {
    border-color: #4da528;
    box-shadow: none;
}

:deep(.vue-tel-input .vti__input:not(.vti__search_box)) {
    height: 100%;
    padding: 12px 16px;
    font-size: 14px;
    line-height: 20px;
    border: none;
    border-radius: 0 6px 6px 0;
    background-color: transparent;
    box-shadow: none;
    color: inherit;
}

:deep(.vue-tel-input .vti__dropdown) {
    height: 100%;
    border: none;
    border-right: 1px solid #e0e2e4;
    border-radius: 6px 0 0 6px;
    background-color: transparent;
    display: flex;
    align-items: center;
    margin: 0;
    padding: 0;
}

:deep(.vue-tel-input .vti__dropdown:hover) {
    background-color: #f5f5f5;
}

:deep(.vue-tel-input .vti__dropdown.open) {
    border-radius: 6px 0 0 0;
}

:deep(.vue-tel-input .vti__selection) {
    font-size: 14px;
    line-height: 20px;
    padding: 0 8px;
    display: flex;
    align-items: center;
    gap: 4px;
}

:deep(.vue-tel-input .vti__dropdown-arrow) {
    margin-left: 2px;
    opacity: 0.5;
}

:deep(.vue-tel-input .vti__dropdown-list) {
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

:deep(.vue-tel-input .vti__dropdown-list.below) {
    top: calc(100% + 4px);
}

:deep(.vue-tel-input .vti__dropdown-item) {
    padding: 8px 12px;
    cursor: pointer;
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: 8px;
}

:deep(.vue-tel-input .vti__dropdown-item:hover),
:deep(.vue-tel-input .vti__dropdown-item.highlighted) {
    background-color: #f5f5f5;
}

:deep(.vue-tel-input .vti__dial-code) {
    margin-left: auto;
}

:deep(.vue-tel-input .vti__search_box_container) {
    padding: 4px 8px;
    border-bottom: 1px solid #e0e2e4;
}

:deep(.vue-tel-input .vti__search_box) {
    width: calc(100% - 8px);
    padding: 8px 12px;
    margin: 4px;
    border: 1px solid #e0e2e4;
    border-radius: 6px;
    font-size: 14px;
    outline: none;
    box-sizing: border-box;
}

:deep(.vue-tel-input .vti__search_box:focus) {
    border-color: #4da528;
}
</style>
