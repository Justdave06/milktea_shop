<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
    products: {
        name: string;
        description: string;
        image: string;
        prices: Record<string, number>;
    }[];
}>();
</script>

<template>
    <div class="min-h-screen bg-[#FDFBF7] font-sans text-[#2C2318]">
        <!-- ==================== HEADER ==================== -->
        <section class="relative overflow-hidden bg-[#F5F0E8] py-16 sm:py-24">
            <div class="mx-auto max-w-[1200px] px-6 lg:px-8">
                <Link
                    href="/"
                    class="mb-6 inline-flex items-center gap-2 text-[13px] font-medium text-[#2C2318]/50 transition-colors hover:text-[#8B5E3C]"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                    Back to home
                </Link>
                <h1 class="text-[3rem] font-bold tracking-[-0.03em] text-[#2C2318] sm:text-[5rem]">
                    All Products
                </h1>
                <p class="mt-4 max-w-lg text-[15px] leading-relaxed text-[#2C2318]/50">
                    Browse our full menu of handcrafted milk teas, made fresh with premium ingredients.
                </p>
            </div>
        </section>

        <!-- ==================== PRODUCT GRID ==================== -->
        <section class="py-12 sm:py-16">
            <div class="mx-auto max-w-[1200px] px-6 lg:px-8">
                <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    <div
                        v-for="product in products"
                        :key="product.name"
                        class="overflow-hidden rounded-2xl border border-[#2C2318]/[0.06] bg-white"
                    >
                        <div class="h-64 overflow-hidden">
                            <img
                                :src="product.image"
                                :alt="product.name"
                                class="h-full w-full object-cover"
                            />
                        </div>
                        <div class="p-6">
                            <h3 class="text-[16px] font-semibold text-[#2C2318]">
                                {{ product.name }}
                            </h3>
                            <p class="mt-2 text-[13px] leading-relaxed text-[#2C2318]/45">
                                {{ product.description }}
                            </p>
                            <div class="mt-4 flex items-center gap-3">
                                <span class="text-[14px] font-bold text-[#8B5E3C]">
                                    ₱{{ product.prices['M'] }}
                                </span>
                                <span class="text-[12px] text-[#2C2318]/30">Medium</span>
                                <span class="text-[14px] font-bold text-[#8B5E3C]">
                                    ₱{{ product.prices['L'] }}
                                </span>
                                <span class="text-[12px] text-[#2C2318]/30">Large</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
