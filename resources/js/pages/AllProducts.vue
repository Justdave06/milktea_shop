<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

const products = [
    {
        name: 'Signature 3Q Milk Tea',
        description: "Milksha's bestselling signature milk tea layered with a trio of honey pearls, crystals, and pudding.",
        image: '/products/milktea.jpg',
        prices: { M: 140, L: 160 },
    },
    {
        name: 'Strawberry Milk Tea',
        description: 'Creamy milk swirled with a rich strawberry coulis for an indulgent treat.',
        image: '/products/milktea red velvet.jpg',
        prices: { M: 120, L: 140 },
    },
    {
        name: 'Jasmine Milktea',
        description: 'Light and fragrant Jasmine milktea topped with our lush Cloudy Cream.',
        image: '/products/milktea3.jpg',
        prices: { M: 120, L: 140 },
    },
    {
        name: 'Classic Milk Tea',
        description: 'Our bestselling signature milk tea with honey pearls.',
        image: '/products/Bubble-Milk-Tea.webp',
        prices: { M: 120, L: 140 },
    },
    {
        name: 'Brown Sugar Boba',
        description: 'Creamy milk sweetened with rich, caramelized brown sugar, topped with honey pearls.',
        image: '/products/brown-sugar-boba-milk-tea-with-cream-cheese-foam-close-up-bubble-tea-photo.jpg',
        prices: { M: 140, L: 160 },
    },
    {
        name: 'Matcha Milktea',
        description: 'Pure Japanese Matcha blended into a creamy milktea.',
        image: '/products/Matcha-milk-tea-post-6.jpg',
        prices: { M: 120, L: 140 },
    },
    {
        name: 'Ube Milktea',
        description: 'Fresh crushed Ube blended with a creamy milk.',
        image: '/products/milktea ube.jpg',
        prices: { M: 160, L: 180 },
    },
    {
        name: 'Okinawa Milktea',
        description: 'Rich and caramelized Okinawa brown sugar blended into a creamy milktea.',
        image: '/products/milktea4.jpg',
        prices: { M: 140, L: 160 },
    },
];
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
                <h1 class="text-[2rem] font-bold tracking-[-0.03em] text-[#2C2318] sm:text-[3rem] lg:text-[5rem]">
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
                        <div class="h-48 overflow-hidden sm:h-64">
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