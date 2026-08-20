<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

interface AddOn {
    name: string;
    price: number;
}

const props = defineProps<{
    product: {
        name: string;
        description: string;
        image: string;
        prices: Record<string, number>;
    };
    addOns: AddOn[];
}>();

const selectedSize = ref<string>('M');
const selectedAddOns = ref<string[]>([]);
const notes = ref('');
const quantity = ref(1);
const addedToCart = ref(false);

const basePrice = computed(() => props.product.prices[selectedSize.value] ?? 0);

const addOnTotal = computed(() => {
    return props.addOns
        .filter((a) => selectedAddOns.value.includes(a.name))
        .reduce((sum, a) => sum + a.price, 0);
});

const totalPrice = computed(() => {
    return (basePrice.value + addOnTotal.value) * quantity.value;
});

function toggleAddOn(name: string) {
    const idx = selectedAddOns.value.indexOf(name);
    if (idx === -1) {
        selectedAddOns.value.push(name);
    } else {
        selectedAddOns.value.splice(idx, 1);
    }
}

function incrementQty() {
    quantity.value++;
}

function decrementQty() {
    if (quantity.value > 1) {
        quantity.value--;
    }
}

function addToCart() {
    addedToCart.value = true;
    setTimeout(() => {
        addedToCart.value = false;
    }, 2000);
}
</script>

<template>
    <div class="min-h-screen bg-[#FDFBF7]">
        <!-- Back button -->
        <div class="fixed left-0 top-0 z-50 p-4">
            <Link
                href="/"
                class="flex h-10 w-10 items-center justify-center rounded-full bg-white/80 shadow-md backdrop-blur-sm transition-all duration-300 hover:bg-white hover:shadow-lg"
            >
                <svg
                    class="h-5 w-5 text-[#2C2318]"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15.75 19.5L8.25 12l7.5-7.5"
                    />
                </svg>
            </Link>
        </div>

        <div class="mx-auto max-w-[600px] px-6 py-6">
            <!-- Product Image -->
            <div class="overflow-hidden rounded-2xl">
                <img
                    :src="product.image"
                    :alt="product.name"
                    class="h-[320px] w-full object-cover sm:h-[400px]"
                />
            </div>

            <!-- Product Info -->
            <div class="mt-6">
                <h1
                    class="text-[1.75rem] font-bold tracking-[-0.02em] text-[#2C2318]"
                >
                    {{ product.name }}
                </h1>
                <p class="mt-2 text-[14px] leading-relaxed text-[#2C2318]/50">
                    {{ product.description }}
                </p>
            </div>

            <!-- Size Selection -->
            <div class="mt-8">
                <h3
                    class="mb-3 text-[12px] font-semibold tracking-[0.1em] text-[#2C2318]/40 uppercase"
                >
                    Size
                </h3>
                <div class="flex gap-3">
                    <button
                        v-for="(price, size) in product.prices"
                        :key="size"
                        @click="selectedSize = size"
                        class="flex flex-1 items-center justify-between rounded-xl border-2 px-5 py-4 transition-all duration-300"
                        :class="
                            selectedSize === size
                                ? 'border-[#8B5E3C] bg-[#8B5E3C]/5'
                                : 'border-[#2C2318]/[0.08] hover:border-[#2C2318]/20'
                        "
                    >
                        <div>
                            <span
                                class="text-[14px] font-semibold text-[#2C2318]"
                            >
                                {{ size }}
                            </span>
                            <span
                                class="ml-1 text-[13px] text-[#2C2318]/40"
                            >
                                Size
                            </span>
                        </div>
                        <span
                            class="text-[14px] font-bold text-[#8B5E3C]"
                        >
                            ₱{{ price }}
                        </span>
                    </button>
                </div>
            </div>

            <!-- Add-ons -->
            <div class="mt-8">
                <h3
                    class="mb-3 text-[12px] font-semibold tracking-[0.1em] text-[#2C2318]/40 uppercase"
                >
                    Add-ons
                </h3>
                <div class="grid grid-cols-2 gap-2">
                    <button
                        v-for="addOn in addOns"
                        :key="addOn.name"
                        @click="toggleAddOn(addOn.name)"
                        class="flex items-center justify-between rounded-xl border px-4 py-3 text-left transition-all duration-300"
                        :class="
                            selectedAddOns.includes(addOn.name)
                                ? 'border-[#8B5E3C] bg-[#8B5E3C]/5'
                                : 'border-[#2C2318]/[0.08] hover:border-[#2C2318]/20'
                        "
                    >
                        <span class="text-[13px] text-[#2C2318]">{{
                            addOn.name
                        }}</span>
                        <span
                            class="text-[12px] font-medium text-[#2C2318]/40"
                        >
                            +₱{{ addOn.price }}
                        </span>
                    </button>
                </div>
            </div>

            <!-- Notes -->
            <div class="mt-8">
                <h3
                    class="mb-3 text-[12px] font-semibold tracking-[0.1em] text-[#2C2318]/40 uppercase"
                >
                    Notes
                </h3>
                <textarea
                    v-model="notes"
                    placeholder="Any special requests? Less sugar, extra ice, etc."
                    class="w-full resize-none rounded-xl border border-[#2C2318]/[0.08] bg-white px-4 py-3 text-[14px] text-[#2C2318] placeholder-[#2C2318]/30 outline-none transition-all duration-300 focus:border-[#8B5E3C]/40 focus:ring-2 focus:ring-[#8B5E3C]/10"
                    rows="3"
                ></textarea>
            </div>

            <!-- Quantity & Price -->
            <div class="mt-8 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <button
                        @click="decrementQty"
                        class="flex h-10 w-10 items-center justify-center rounded-full border border-[#2C2318]/[0.08] text-[#2C2318] transition-all duration-300 hover:border-[#2C2318]/20"
                    >
                        <svg
                            class="h-4 w-4"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M19.5 12h-15"
                            />
                        </svg>
                    </button>
                    <span
                        class="w-8 text-center text-[16px] font-semibold text-[#2C2318]"
                    >
                        {{ quantity }}
                    </span>
                    <button
                        @click="incrementQty"
                        class="flex h-10 w-10 items-center justify-center rounded-full border border-[#2C2318]/[0.08] text-[#2C2318] transition-all duration-300 hover:border-[#2C2318]/20"
                    >
                        <svg
                            class="h-4 w-4"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 4.5v15m7.5-7.5h-15"
                            />
                        </svg>
                    </button>
                </div>
                <div class="text-right">
                    <p
                        class="text-[12px] text-[#2C2318]/40"
                    >
                        Total
                    </p>
                    <p
                        class="text-[1.5rem] font-bold text-[#8B5E3C]"
                    >
                        ₱{{ totalPrice }}
                    </p>
                </div>
            </div>

            <!-- Add to Cart -->
            <button
                @click="addToCart"
                class="mt-6 w-full rounded-full bg-[#8B5E3C] py-4 text-[15px] font-semibold text-white shadow-lg transition-all duration-300 hover:bg-[#7A5234] hover:shadow-xl"
                :class="{ 'bg-green-600 hover:bg-green-600': addedToCart }"
            >
                {{ addedToCart ? '✓ Added to Cart' : 'Add to Cart' }}
            </button>
        </div>
    </div>
</template>
