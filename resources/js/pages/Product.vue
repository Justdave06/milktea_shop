<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
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
    <div class="min-h-screen bg-[#FDFBF7] font-sans text-[#2C2318]">
        <!-- ==================== HERO COVER (Boteaque store brand) ==================== -->
        <section class="relative min-h-[50vh] overflow-hidden">
            <div class="absolute inset-0">
                <img
                    src="/products/feature.jpg"
                    alt="Boteaque store"
                    class="h-full w-full object-cover"
                />
                <div class="absolute inset-0 bg-gradient-to-t from-[#2C2318]/80 via-[#2C2318]/30 to-transparent" />
            </div>

            <div class="relative flex min-h-[50vh] items-end">
                <div class="mx-auto w-full max-w-[1200px] px-6 pb-12 lg:px-8">
                    <Link
                        href="/"
                        class="mb-6 inline-flex items-center gap-2 text-[13px] font-medium text-white/70 transition-colors hover:text-white"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                        </svg>
                        Back to menu
                    </Link>
                    <h1 class="text-[2.5rem] font-bold tracking-[-0.02em] text-white sm:text-[3.5rem]">
                        {{ product.name }}
                    </h1>
                    <p class="mt-3 max-w-lg text-[15px] leading-relaxed text-white/70">
                        {{ product.description }}
                    </p>
                </div>
            </div>
        </section>

        <!-- ==================== ORDER SECTION ==================== -->
        <section class="bg-[#FDFBF7] py-12 sm:py-16">
            <div class="mx-auto max-w-[1200px] px-6 lg:px-8">
                <div class="grid gap-12 lg:grid-cols-[1fr_420px]">
                    <!-- Left: Product image + details -->
                    <div>
                        <div class="overflow-hidden rounded-2xl">
                            <img
                                :src="product.image"
                                :alt="product.name"
                                class="h-[280px] w-full object-cover sm:h-[360px]"
                            />
                        </div>

                        <!-- Size Selection -->
                        <div class="mt-10">
                            <h3 class="mb-4 text-[12px] font-semibold tracking-[0.1em] text-[#2C2318]/40 uppercase">
                                Select Size
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
                                        <span class="text-[14px] font-semibold text-[#2C2318]">{{ size }}</span>
                                        <span class="ml-1 text-[13px] text-[#2C2318]/40">Size</span>
                                    </div>
                                    <span class="text-[14px] font-bold text-[#8B5E3C]">₱{{ price }}</span>
                                </button>
                            </div>
                        </div>

                        <!-- Add-ons -->
                        <div class="mt-10">
                            <h3 class="mb-4 text-[12px] font-semibold tracking-[0.1em] text-[#2C2318]/40 uppercase">
                                Add-ons
                            </h3>
                            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
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
                                    <span class="text-[13px] text-[#2C2318]">{{ addOn.name }}</span>
                                    <span class="text-[12px] font-medium text-[#2C2318]/40">+₱{{ addOn.price }}</span>
                                </button>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mt-10">
                            <h3 class="mb-4 text-[12px] font-semibold tracking-[0.1em] text-[#2C2318]/40 uppercase">
                                Special Instructions
                            </h3>
                            <textarea
                                v-model="notes"
                                placeholder="Less sugar, extra ice, no pearls, etc."
                                class="w-full resize-none rounded-xl border border-[#2C2318]/[0.08] bg-white px-4 py-3 text-[14px] text-[#2C2318] placeholder-[#2C2318]/30 outline-none transition-all duration-300 focus:border-[#8B5E3C]/40 focus:ring-2 focus:ring-[#8B5E3C]/10"
                                rows="3"
                            ></textarea>
                        </div>
                    </div>

                    <!-- Right: Order summary (sticky) -->
                    <div class="lg:sticky lg:top-6 lg:self-start">
                        <div class="rounded-2xl border border-[#2C2318]/[0.06] bg-white p-6 shadow-sm">
                            <h3 class="text-[16px] font-semibold text-[#2C2318]">Order Summary</h3>

                            <div class="mt-5 space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-[14px] text-[#2C2318]/60">{{ product.name }} ({{ selectedSize }})</span>
                                    <span class="text-[14px] font-medium text-[#2C2318]">₱{{ basePrice }}</span>
                                </div>

                                <div v-if="selectedAddOns.length > 0" class="space-y-2 border-t border-[#2C2318]/[0.06] pt-2">
                                    <div v-for="name in selectedAddOns" :key="name" class="flex items-center justify-between">
                                        <span class="text-[13px] text-[#2C2318]/50">{{ name }}</span>
                                        <span class="text-[13px] text-[#2C2318]/50">+₱{{ addOns.find(a => a.name === name)?.price }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Quantity -->
                            <div class="mt-6 flex items-center justify-between rounded-xl bg-[#F5F0E8] px-4 py-3">
                                <span class="text-[13px] font-medium text-[#2C2318]/60">Quantity</span>
                                <div class="flex items-center gap-3">
                                    <button
                                        @click="decrementQty"
                                        class="flex h-8 w-8 items-center justify-center rounded-full bg-white text-[#2C2318] shadow-sm transition-all duration-300 hover:shadow"
                                    >
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                        </svg>
                                    </button>
                                    <span class="w-6 text-center text-[15px] font-semibold text-[#2C2318]">{{ quantity }}</span>
                                    <button
                                        @click="incrementQty"
                                        class="flex h-8 w-8 items-center justify-center rounded-full bg-white text-[#2C2318] shadow-sm transition-all duration-300 hover:shadow"
                                    >
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Total -->
                            <div class="mt-5 flex items-center justify-between border-t border-[#2C2318]/[0.06] pt-5">
                                <span class="text-[14px] font-medium text-[#2C2318]/60">Total</span>
                                <span class="text-[1.5rem] font-bold text-[#8B5E3C]">₱{{ totalPrice }}</span>
                            </div>

                            <!-- Add to Cart -->
                            <button
                                @click="addToCart"
                                class="mt-5 w-full rounded-full bg-[#8B5E3C] py-4 text-[15px] font-semibold text-white shadow-lg transition-all duration-300 hover:bg-[#7A5234] hover:shadow-xl"
                                :class="{ '!bg-green-600 hover:!bg-green-600': addedToCart }"
                            >
                                {{ addedToCart ? '✓ Added to Cart' : 'Add to Cart' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
