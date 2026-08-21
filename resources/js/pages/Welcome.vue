<script setup lang="ts">
import { ref, onMounted, onUnmounted, computed } from 'vue';
import AccordionGallery from '@/components/AccordionGallery.vue';
import CircularGallery from '@/components/CircularGallery.vue';

const activeFilter = ref('all');
const scrolled = ref(false);
const menuVisible = ref(false);
const aboutVisible = ref(false);
const storeVisible = ref(false);
const productsVisible = ref(false);
const newsVisible = ref(false);
const footerVisible = ref(false);
const fullStoryVisible = ref(false);
const mobileMenuOpen = ref(false);
const isMobile = ref(false);
const galleryIndex = ref(0);
const carouselRef = ref<HTMLDivElement | null>(null);
const touchStartX = ref(0);
const touchStartIndex = ref(0);
const isDragging = ref(false);

function checkMobile() {
    isMobile.value = window.innerWidth < 640;
}

function galleryPrev() {
    if (galleryIndex.value > 0) galleryIndex.value--;
    scrollToIndex(galleryIndex.value);
}

function galleryNext() {
    if (galleryIndex.value < galleryItems.value.length - 1) galleryIndex.value++;
    scrollToIndex(galleryIndex.value);
}

function scrollToIndex(index: number) {
    if (!carouselRef.value) return;
    const itemWidth = 192; // 180px item + 12px margin
    const containerPadding = 24; // px-12 = 24px each side
    const viewportWidth = carouselRef.value.clientWidth;
    const centerOffset = (viewportWidth - 180) / 2;
    const scrollLeft = index * itemWidth - centerOffset + containerPadding;
    carouselRef.value.scrollTo({ left: scrollLeft, behavior: 'smooth' });
}

function handleTouchStart(e: TouchEvent) {
    touchStartX.value = e.touches[0].clientX;
    touchStartIndex.value = galleryIndex.value;
    isDragging.value = true;
    if (carouselRef.value) {
        carouselRef.value.style.scrollBehavior = 'auto';
    }
}

function handleTouchMove(e: TouchEvent) {
    if (!isDragging.value || !carouselRef.value) return;
    const deltaX = touchStartX.value - e.touches[0].clientX;
    const itemWidth = 192;
    const containerPadding = 24;
    const viewportWidth = carouselRef.value.clientWidth;
    const centerOffset = (viewportWidth - 180) / 2;
    const baseScrollLeft = touchStartIndex.value * itemWidth - centerOffset + containerPadding;
    carouselRef.value.scrollLeft = baseScrollLeft + deltaX;
}

function handleTouchEnd() {
    if (!isDragging.value || !carouselRef.value) return;
    isDragging.value = false;
    carouselRef.value.style.scrollBehavior = 'smooth';
    const itemWidth = 192;
    const containerPadding = 24;
    const viewportWidth = carouselRef.value.clientWidth;
    const centerOffset = (viewportWidth - 180) / 2;
    const scrollLeft = carouselRef.value.scrollLeft;
    const calculatedIndex = Math.round((scrollLeft + centerOffset - containerPadding) / itemWidth);
    galleryIndex.value = Math.max(0, Math.min(calculatedIndex, galleryItems.value.length - 1));
    scrollToIndex(galleryIndex.value);
}

const drinks = [
    {
        name: 'Signature 3Q Milk Tea',
        description:
            "Milksha's bestselling signature milk tea layered with a trio of honey pearls, crystals, and pudding.",
        image: '/products/milktea.jpg',
        tags: ['bestseller', 'toppick'],
        priceM: '₱140',
        priceL: '₱160',
    },
    {
        name: 'Strawberry Milk Tea',
        description:
            'Creamy milk swirled with a rich strawberry coulis for an indulgent treat.',
        image: '/products/milktea red velvet.jpg',
        tags: ['bestseller', 'toppick'],
        priceM: '₱120',
        priceL: '₱140',
    },
    {
        name: 'Jasmine Milktea',
        description:
            'Light and fragrant Jasmine milktea topped with our lush Cloudy Cream.',
        image: '/products/milktea3.jpg',
        tags: ['bestseller', 'toppick'],
        priceM: '₱120',
        priceL: '₱140',
    },
    {
        name: 'Classic Milk Tea',
        description:
            "Our bestselling signature milk tea with honey pearls.",
        image: '/products/Bubble-Milk-Tea.webp',
        tags: ['bestseller'],
        priceM: '₱120',
        priceL: '₱140',
    },
    {
        name: 'Brown Sugar Boba',
        description:
            'Creamy milk sweetened with rich, caramelized brown sugar, topped with honey pearls.',
        image: '/products/brown-sugar-boba-milk-tea-with-cream-cheese-foam-close-up-bubble-tea-photo.jpg',
        tags: ['toppick'],
        priceM: '₱140',
        priceL: '₱160',
    },
    {
        name: 'Matcha Milktea',
        description: 'Pure Japanese Matcha blended into a creamy milktea.',
        image: '/products/Matcha-milk-tea-post-6.jpg',
        tags: ['bestseller', 'toppick'],
        priceM: '₱120',
        priceL: '₱140',
    },
    {
        name: 'Ube Milktea',
        description: 'Fresh crushed Ube blended with a creamy milk.',
        image: '/products/milktea ube.jpg',
        tags: ['toppick'],
        priceM: '₱160',
        priceL: '₱180',
    },
    {
        name: 'Okinawa Milktea',
        description:
            'Rich and caramelized Okinawa brown sugar blended into a creamy milktea.',
        image: '/products/milktea4.jpg',
        tags: ['bestseller'],
        priceM: '₱140',
        priceL: '₱160',
    },
];

const filteredDrinks = computed(() => {
    if (activeFilter.value === 'all') {
        return drinks;
    }

    return drinks.filter((d) => d.tags.includes(activeFilter.value));
});

/* Cinematic marketing / lifestyle shots for the accordion gallery */
const galleryItems = computed(() => [
    {
        image: '/products/feature.jpg',
        label: 'Artisan Craft',
        details: 'Handcrafted with passion',
    },
    {
        image: '/products/feature.webp',
        label: 'Morning Ritual',
        details: 'Start your day right',
    },
    {
        image: '/products/feature (2).jpg',
        label: 'Café Culture',
        details: 'Where moments are made',
    },
    {
        image: '/products/milktea.jpeg',
        label: 'Premium Ingredients',
        details: 'Sourced from the finest estates',
    },
    {
        image: '/products/milktea (2).jpg',
        label: 'Fresh & Pure',
        details: 'Quality you can taste',
    },
    {
        image: '/products/taiwan-milk-tea-with-bubble-free-photo.jpg',
        label: 'Latte Art',
        details: 'Every cup is a masterpiece',
    },
    {
        image: '/products/milktea ube.jpg',
        label: 'Signature Blends',
        details: 'Boteaque lifestyle',
    },
    {
        image: '/products/Vietnamese-Milk-Tea-1_1701613045.webp',
        label: 'Urban Vibes',
        details: 'Your daily escape',
    },
]);

/* Circular gallery items: products with prices */
const circularItems = computed(() =>
    filteredDrinks.value.map((drink) => ({
        image: drink.image,
        text: `${drink.name}  ·  ${drink.priceM}`,
    })),
);

const news = [
    {
        title: "Taiwan's 'best milk tea' now in the Philippines",
        source: 'The Manila Times',
    },
    {
        title: 'Newest milk tea brand takes off',
        source: 'Daily Tribune',
    },
    {
        title: "Chowking now offering Taiwan's 'best milk tea' in the Philippines",
        source: 'Philstar',
    },
];

function handleScroll() {
    scrolled.value = window.scrollY > 40;
}

function setupObserver() {
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    const id = entry.target.getAttribute('data-section');

                    if (id === 'menu') {
                        menuVisible.value = true;
                    }

                    if (id === 'about') {
                        aboutVisible.value = true;
                    }

                    if (id === 'store') {
                        storeVisible.value = true;
                    }

                    if (id === 'products') {
                        productsVisible.value = true;
                    }

                    if (id === 'news') {
                        newsVisible.value = true;
                    }

                    if (id === 'footer') {
                        footerVisible.value = true;
                    }
                }
            });
        },
        { threshold: 0.1 },
    );

    document
        .querySelectorAll('[data-section]')
        .forEach((el) => observer.observe(el));
}

onMounted(() => {
    setupObserver();
    window.addEventListener('scroll', handleScroll, { passive: true });
    window.addEventListener('resize', checkMobile, { passive: true });
    handleScroll();
    checkMobile();
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
    window.removeEventListener('resize', checkMobile);
});
</script>

<template>
    <div class="min-h-screen bg-[#FDFBF7] font-sans text-[#2C2318]">
        <!-- ==================== NAVIGATION ==================== -->
        <header
            class="fixed inset-x-0 top-0 z-50 transition-all duration-300"
            :class="
                scrolled
                    ? 'bg-white shadow-[0_1px_0_0_rgba(0,0,0,0.06)]'
                    : 'bg-transparent'
            "
        >
            <nav
                class="mx-auto flex max-w-[1200px] items-center justify-between px-6 py-4 lg:px-8"
            >
                <a href="/" class="flex items-center gap-2.5">
                    <svg
                        class="h-7 w-7"
                        :class="scrolled ? 'text-[#8B5E3C]' : 'text-white'"
                        viewBox="0 0 28 28"
                        fill="none"
                    >
                        <rect
                            x="4"
                            y="4"
                            width="14"
                            height="20"
                            rx="3"
                            stroke="currentColor"
                            stroke-width="1.6"
                            fill="none"
                        />
                        <path
                            d="M18 10h3a2 2 0 012 2v2a2 2 0 01-2 2h-3"
                            stroke="currentColor"
                            stroke-width="1.6"
                            fill="none"
                        />
                        <circle
                            cx="10"
                            cy="17"
                            r="1.2"
                            fill="currentColor"
                            opacity="0.4"
                        />
                        <circle
                            cx="13"
                            cy="15.5"
                            r="0.9"
                            fill="currentColor"
                            opacity="0.3"
                        />
                    </svg>
                    <span
                        class="text-[16px] font-bold tracking-[0.01em]"
                        :class="scrolled ? 'text-[#8B5E3C]' : 'text-white'"
                        >Boteaque</span>
                </a>

                <div class="hidden items-center gap-8 md:flex">
                    <a
                        href="#home"
                        class="text-[13px] font-medium transition-colors"
                        :class="scrolled ? 'text-[#2C2318]/50 hover:text-[#8B5E3C]' : 'text-white/70 hover:text-white'"
                        >Home</a
                    >
                    <a
                        href="#products"
                        class="text-[13px] font-medium transition-colors"
                        :class="scrolled ? 'text-[#2C2318]/50 hover:text-[#8B5E3C]' : 'text-white/70 hover:text-white'"
                        >Menu</a
                    >
                    <a
                        href="#about"
                        class="text-[13px] font-medium transition-colors"
                        :class="scrolled ? 'text-[#2C2318]/50 hover:text-[#8B5E3C]' : 'text-white/70 hover:text-white'"
                        >Our Story</a
                    >
                </div>

                <div class="flex items-center gap-3">
                    <a
                        href="#"
                        class="rounded-full px-4 py-2 text-[12px] font-semibold transition-all duration-300 sm:px-6 sm:py-2.5 sm:text-[13px]"
                        :class="scrolled ? 'bg-[#8B5E3C] text-white hover:bg-[#7A5234]' : 'border border-white/30 text-white hover:border-white/60'"
                    >
                        Sign-in
                    </a>

                    <!-- Mobile hamburger -->
                    <button
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        class="flex h-10 w-10 items-center justify-center rounded-full transition-colors md:hidden"
                        :class="scrolled ? 'text-[#2C2318] hover:bg-[#2C2318]/5' : 'text-white hover:bg-white/10'"
                    >
                        <svg v-if="!mobileMenuOpen" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                        <svg v-else class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </nav>

            <!-- Mobile menu -->
            <div
                class="overflow-hidden transition-all duration-300 md:hidden"
                :style="{ maxHeight: mobileMenuOpen ? '300px' : '0px', opacity: mobileMenuOpen ? 1 : 0 }"
            >
                <div class="border-t px-6 py-4" :class="scrolled ? 'border-[#2C2318]/[0.06] bg-white' : 'border-white/10 bg-[#2C2318]/80 backdrop-blur-md'">
                    <a
                        href="#home"
                        @click="mobileMenuOpen = false"
                        class="block py-2.5 text-[14px] font-medium transition-colors"
                        :class="scrolled ? 'text-[#2C2318]/60 hover:text-[#8B5E3C]' : 'text-white/70 hover:text-white'"
                    >Home</a>
                    <a
                        href="#products"
                        @click="mobileMenuOpen = false"
                        class="block py-2.5 text-[14px] font-medium transition-colors"
                        :class="scrolled ? 'text-[#2C2318]/60 hover:text-[#8B5E3C]' : 'text-white/70 hover:text-white'"
                    >Menu</a>
                    <a
                        href="#about"
                        @click="mobileMenuOpen = false"
                        class="block py-2.5 text-[14px] font-medium transition-colors"
                        :class="scrolled ? 'text-[#2C2318]/60 hover:text-[#8B5E3C]' : 'text-white/70 hover:text-white'"
                    >Our Story</a>
                </div>
            </div>
        </header>

        <!-- ==================== HERO ==================== -->
        <section
            id="home"
            class="relative flex min-h-screen overflow-hidden max-sm:items-end sm:items-center"
        >
            <!-- Background image -->
            <div class="absolute inset-0">
                <img
                    src="/images/hero.png"
                    alt="Milk tea"
                    class="h-full w-full object-cover [object-position:70%_center] sm:object-center"
                />
            </div>

            <!-- Dark gradient overlay for text readability (mobile only) -->
            <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-black/70 via-black/30 to-transparent max-sm:block sm:hidden"></div>

            <div
                class="relative mx-auto w-full max-w-[1200px] px-6 max-sm:pb-16 max-sm:pt-24 py-24 lg:px-8"
            >
                <div class="max-w-xl">
                    <h1
                        class="text-[2rem] leading-[1.1] font-bold tracking-[-0.02em] text-[#FDFBF7] sm:text-5xl lg:text-6xl"
                    >
                        Savour Pure
                        <br />
                        Elegance &amp; Freshness
                    </h1>
                    <p
                        class="mt-5 max-w-[320px] text-[14px] leading-relaxed text-[#FDFBF7]/75 sm:max-w-md sm:text-[15px]"
                    >
                        Designed for milk tea lovers, bringing handcrafted, world-class beverages straight to the heart of the Philippines.
                    </p>
                    <div class="mt-8 flex gap-3">
                        <a
                            href="#products"
                            class="inline-flex items-center rounded-full bg-[#8B5E3C] px-7 py-3 text-[13px] font-semibold text-white transition-all duration-300 hover:bg-[#7A5234]"
                        >
                            Explore our drinks
                        </a>

                    </div>
                </div>
            </div>
        </section>

        <!-- ==================== MENU ==================== -->
        <section id="menu" data-section="menu" class="py-16 sm:py-24">
            <div
                class="flex items-center justify-center py-8 transition-all duration-700 sm:py-20"
                :class="
                    menuVisible
                        ? 'translate-y-0 opacity-100'
                        : 'translate-y-6 opacity-0'
                "
            >
                <h2
                    class="text-center text-[2rem] font-bold tracking-[-0.03em] text-[#2C2318] sm:text-[3rem] lg:text-[5rem]"
                >
                    Signature Collection
                </h2>
            </div>

            <div
                class="transition-all duration-700"
                :class="
                    menuVisible
                        ? 'translate-y-0 opacity-100'
                        : 'translate-y-6 opacity-0'
                "
            >
                <!-- Desktop: Accordion gallery -->
                <div v-if="!isMobile">
                    <AccordionGallery
                        :items="galleryItems"
                        :default-index="0"
                        :height="480"
                        :gap="12"
                        :radius="16"
                        :expand-ratio="0.35"
                        accent-color="#F6E7C6"
                        overlay-color="#2C2318"
                        text-color="#FDFBF7"
                        :grayscale="true"
                    />
                </div>

                <!-- Mobile: simple carousel gallery -->
                <div v-else class="relative px-12">
                    <div
                        ref="carouselRef"
                        class="overflow-x-auto snap-x snap-mandatory pb-4 -mx-12 px-12 carousel-hide-scrollbar"
                        @touchstart="handleTouchStart"
                        @touchmove="handleTouchMove"
                        @touchend="handleTouchEnd"
                    >
                        <div class="flex gap-3">
                            <div
                                v-for="(item, i) in galleryItems"
                                :key="i"
                                class="relative h-[240px] w-[180px] flex-shrink-0 overflow-hidden rounded-2xl snap-center"
                            >
                                <img
                                    :src="item.image"
                                    :alt="item.label"
                                    class="h-full w-full object-cover"
                                />
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent" />
                                <div class="absolute bottom-3 left-3 right-3">
                                    <p class="text-[12px] font-semibold text-white">{{ item.label }}</p>
                                    <p class="text-[10px] text-white/60">{{ item.details }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Prev arrow -->
                    <button
                        @click="galleryPrev"
                        class="absolute left-1 top-1/2 z-10 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full border border-white/20 bg-white/80 shadow-md backdrop-blur-sm"
                    >
                        <svg class="h-4 w-4 text-[#2C2318]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                        </svg>
                    </button>

                    <!-- Next arrow -->
                    <button
                        @click="galleryNext"
                        class="absolute right-1 top-1/2 z-10 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full border border-white/20 bg-white/80 shadow-md backdrop-blur-sm"
                    >
                        <svg class="h-4 w-4 text-[#2C2318]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>
                </div>
            </div>
        </section>

        <!-- ==================== PRODUCTS / CIRCULAR GALLERY ==================== -->
        <section
            id="products"
            data-section="products"
            class="bg-[#F5F0E8] py-16 sm:py-24"
        >
            <div class="mx-auto max-w-[1200px] px-6 lg:px-8">
                <div
                    class="transition-all duration-700"
                    :class="
                        productsVisible
                            ? 'translate-y-0 opacity-100'
                            : 'translate-y-6 opacity-0'
                    "
                >
                    <div class="flex items-center justify-center py-8 sm:py-20">
                        <h2
                            class="text-center text-[2rem] font-bold tracking-[-0.03em] text-[#2C2318] sm:text-[3rem] lg:text-[5rem]"
                        >
                            Our Menu
                        </h2>
                    </div>
                </div>
            </div>

            <div
                class="transition-all duration-700"
                :class="
                    productsVisible
                        ? 'translate-y-0 opacity-100'
                        : 'translate-y-6 opacity-0'
                "
            >
                <!-- Desktop: Circular gallery -->
                <div v-if="!isMobile">
                    <CircularGallery
                        :items="circularItems"
                        :bend="3"
                        text-color="#2C2318"
                        :border-radius="12"
                        :scroll-speed="2"
                        :height="650"
                    />
                </div>

                <!-- Mobile: simple product card grid -->
                <div v-else class="px-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div
                            v-for="(item, i) in circularItems"
                            :key="i"
                            class="overflow-hidden rounded-xl bg-white shadow-sm"
                        >
                            <div class="h-36 overflow-hidden">
                                <img
                                    :src="item.image"
                                    :alt="item.text"
                                    class="h-full w-full object-cover"
                                />
                            </div>
                            <div class="p-3">
                                <p class="text-[11px] font-semibold leading-tight text-[#2C2318]">{{ item.text }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-center">
                        <a
                            href="/all-products"
                            class="inline-flex items-center rounded-full border border-[#8B5E3C]/20 px-8 py-3 text-[13px] font-semibold text-[#8B5E3C] transition-all duration-300 hover:border-[#8B5E3C]/40 hover:bg-[#8B5E3C]/5"
                        >
                            View All Products
                            <svg
                                class="ml-2 h-3.5 w-3.5"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"
                                />
                            </svg>
                        </a>
                    </div>
            </div>
        </section>

        <!-- ==================== ABOUT / DIFFERENCE ==================== -->
        <section
            id="about"
            data-section="about"
            class="bg-[#F5F0E8] py-16 sm:py-24"
        >
            <div class="mx-auto max-w-[1200px] px-6 lg:px-8">
                <div
                    class="transition-all duration-700"
                    :class="
                        aboutVisible
                            ? 'translate-y-0 opacity-100'
                            : 'translate-y-6 opacity-0'
                    "
                >
                    <div class="flex items-center justify-center py-12 sm:py-20">
                        <h2
                            class="text-center text-[1.75rem] font-bold tracking-[-0.03em] text-[#2C2318] sm:text-[2.5rem] lg:text-[4rem]"
                        >
                            Why Choose Us?
                        </h2>
                    </div>

                    <div
                        class="mt-10 grid gap-8 sm:mt-12 sm:grid-cols-3 sm:gap-12"
                    >
                        <div class="flex flex-col items-start gap-4">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-full bg-[#8B5E3C]/10"
                            >
                                <svg
                                    class="h-5 w-5 text-[#8B5E3C]"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"
                                    />
                                </svg>
                            </div>
                            <div>
                                <p
                                    class="text-[15px] font-semibold text-[#2C2318]"
                                >
                                    Freshly Made in the Philippines
                                </p>
                                <p
                                    class="mt-2 text-[13px] leading-relaxed text-[#2C2318]/45"
                                >
                                    Crafted using high-quality tea leaves and fresh milk, thoughtfully blended for local tastes.
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-col items-start gap-4">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-full bg-[#8B5E3C]/10"
                            >
                                <svg
                                    class="h-5 w-5 text-[#8B5E3C]"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"
                                    />
                                </svg>
                            </div>
                            <div>
                                <p
                                    class="text-[15px] font-semibold text-[#2C2318]"
                                >
                                    Proudly Handcrafted Quality
                                </p>
                                <p
                                    class="mt-2 text-[13px] leading-relaxed text-[#2C2318]/45"
                                >
                                    No artificial shortcuts. Every single cup is brewed fresh to order right here in the store.
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-col items-start gap-4">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-full bg-[#8B5E3C]/10"
                            >
                                <svg
                                    class="h-5 w-5 text-[#8B5E3C]"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"
                                    />
                                </svg>
                            </div>
                            <div>
                                <p
                                    class="text-[15px] font-semibold text-[#2C2318]"
                                >
                                    Your Everyday Pinoy Companion
                                </p>
                                <p
                                    class="mt-2 text-[13px] leading-relaxed text-[#2C2318]/45"
                                >
                                    Serving up daily happiness in every cup, perfect for sharing with family and friends.
                                </p>
                            </div>
                        </div>
                    </div>

                    <button
                        v-if="!fullStoryVisible"
                        @click="fullStoryVisible = true"
                        class="mt-10 inline-flex items-center rounded-full bg-[#8B5E3C] px-7 py-3 text-[13px] font-semibold text-white transition-all duration-300 hover:bg-[#7A5234] sm:mt-12"
                    >
                        Our full story
                        <svg
                            class="ml-2 h-3.5 w-3.5"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>

                    <div
                        class="overflow-hidden transition-all duration-500 ease-in-out"
                        :style="{ maxHeight: fullStoryVisible ? '900px' : '0px', opacity: fullStoryVisible ? 1 : 0 }"
                    >
                        <div class="mt-8 overflow-hidden rounded-2xl border border-[#2C2318]/[0.06] bg-white">
                            <img
                                src="/products/boteaque.jpg"
                                alt="How it all started"
                                class="h-56 w-full object-cover sm:h-72"
                            />
                            <div class="p-8 sm:p-10">
                            <h3 class="text-[18px] font-bold text-[#2C2318]">Our Story</h3>
                            <p class="mt-4 text-[14px] leading-relaxed text-[#2C2318]/60">
                                Boteaque was born from a simple love for milk tea. What started as a small kiosk in Manila quickly grew into a beloved destination for tea enthusiasts across the Philippines. Our founders traveled to Taiwan to study the art of bubble tea, learning from master tea blenders who perfected their craft over decades.
                            </p>
                            <p class="mt-4 text-[14px] leading-relaxed text-[#2C2318]/60">
                                Today, every cup we serve is a tribute to that journey. We source our tea leaves directly from high-altitude farms in Taiwan and Japan, blend them fresh daily, and pair them with handcrafted pearls made from natural tapioca. No artificial flavors, no shortcuts — just pure, honest ingredients.
                            </p>
                            <p class="mt-4 text-[14px] leading-relaxed text-[#2C2318]/60">
                                Our mission is simple: to bring the world-class milk tea experience closer to every Filipino. Whether you’re grabbing a quick drink on your commute or settling in for an afternoon with friends, Boteaque is here to make every moment a little sweeter.                            </p>
                            </div>
                        </div>
                    </div>

                    <div v-if="fullStoryVisible" class="mt-6 flex justify-center">
                        <button
                            @click="fullStoryVisible = false"
                            class="flex h-10 w-10 items-center justify-center rounded-full border border-[#2C2318]/10 bg-white shadow-md transition-all duration-300 hover:bg-[#2C2318]/5"
                        >
                            <svg
                                class="h-4 w-4 text-[#2C2318]"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </section>




        <!-- ==================== GRAB A DRINK IN STORE ==================== -->
        <section data-section="store" class="py-16 sm:py-24">
            <div class="mx-auto max-w-[1200px] px-6 lg:px-8">
                <div
                    class="transition-all duration-700"
                    :class="
                        storeVisible
                            ? 'translate-y-0 opacity-100'
                            : 'translate-y-6 opacity-0'
                    "
                >
                    <div class="flex items-center justify-center py-12 sm:py-20">
                        <h2
                            class="text-center text-[1.75rem] font-bold tracking-[-0.03em] text-[#2C2318] sm:text-[2.5rem] lg:text-[4rem]"
                        >
                            Visit Our Store Near You
                        </h2>
                    </div>

                    <div class="mt-8 grid gap-6 sm:mt-10 sm:grid-cols-2">
                        <!-- Concept Store Card -->
                        <div
                            class="overflow-hidden rounded-2xl border border-[#2C2318]/[0.06] bg-white"
                        >
                            <div class="h-40 overflow-hidden sm:h-48">
                                <img
                                    src="https://images.unsplash.com/photo-1554118811-1e0d58224f24?w=800&h=400&fit=crop&auto=format"
                                    alt="Boteaque Concept Store"
                                    class="h-full w-full object-cover"
                                />
                            </div>
                            <div class="p-6">
                                <h3
                                    class="text-[16px] font-semibold text-[#2C2318]"
                                >
                                    Boteaque Concept Store
                                </h3>
                                <p
                                    class="mt-2 text-[13px] leading-relaxed text-[#2C2318]/45"
                                >
                                    Drop by our flagship store! Experience your favorite milk teas freshly prepared
                                     with top-quality ingredients in a cozy space.
                                </p>
                                <a
                                    href="#"
                                    class="mt-4 inline-flex items-center text-[13px] font-semibold text-[#8B5E3C] transition-colors hover:text-[#7A5234]"
                                >
                                    Get directions
                                    <svg
                                        class="ml-1 h-3.5 w-3.5"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"
                                        />
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <!-- Find Store Card -->
                        <div
                            class="flex flex-col justify-center rounded-2xl border border-[#2C2318]/[0.06] bg-[#8B5E3C]/[0.04] p-8"
                        >
                            <h3
                                class="text-[16px] font-semibold text-[#2C2318]"
                            >
                                Find nearest store
                            </h3>
                            <p
                                class="mt-3 text-[13px] leading-relaxed text-[#2C2318]/45"
                            >
                                Enjoy your favorite flavors on the go, whenever,
                                and wherever you crave. Available at select
                                locations nationwide.
                            </p>
                            <a
                                href="#"
                                class="mt-6 inline-flex w-fit items-center rounded-full bg-[#8B5E3C] px-6 py-2.5 text-[13px] font-semibold text-white transition-all duration-300 hover:bg-[#7A5234]"
                            >
                                Find
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>





        <!-- ==================== FOOTER ==================== -->
        <footer
            data-section="footer"
            class="border-t border-[#2C2318]/[0.06] bg-[#F5F0E8] py-16 sm:py-20"
        >
            <div class="mx-auto max-w-[1200px] px-6 lg:px-8">
                <div
                    class="transition-all duration-700"
                    :class="
                        footerVisible
                            ? 'translate-y-0 opacity-100'
                            : 'translate-y-6 opacity-0'
                    "
                >
                    <div
                        class="grid grid-cols-2 gap-10 sm:grid-cols-4 lg:gap-12"
                    >
                        <div class="col-span-2 sm:col-span-1">
                            <div class="flex items-center gap-2">
                                <svg
                                    class="h-6 w-6 text-[#8B5E3C]"
                                    viewBox="0 0 28 28"
                                    fill="none"
                                >
                                    <rect
                                        x="4"
                                        y="4"
                                        width="14"
                                        height="20"
                                        rx="3"
                                        stroke="currentColor"
                                        stroke-width="1.6"
                                        fill="none"
                                    />
                                    <circle
                                        cx="10"
                                        cy="17"
                                        r="1.2"
                                        fill="currentColor"
                                        opacity="0.4"
                                    />
                                </svg>
                                <span
                                    class="text-[15px] font-bold text-[#2C2318]"
                                    >Boteaque</span
                                >
                            </div>
                            <p
                                class="mt-3 text-[13px] leading-relaxed text-[#2C2318]/40"
                            >
                                Premium milk tea, crafted with intention.
                            </p>
                        </div>

                        <div>
                            <h4
                                class="mb-4 text-[11px] font-semibold tracking-[0.15em] text-[#2C2318]/40 uppercase"
                            >
                                Quick Links
                            </h4>
                            <ul
                                class="space-y-2.5 text-[13px] text-[#2C2318]/45"
                            >
                                <li>
                                    <a
                                        href="#home"
                                        class="transition hover:text-[#8B5E3C]"
                                        >Home</a
                                    >
                                </li>
                                <li>
                                    <a
                                        href="#menu"
                                        class="transition hover:text-[#8B5E3C]"
                                        >Menu</a
                                    >
                                </li>
                                <li>
                                    <a
                                        href="#about"
                                        class="transition hover:text-[#8B5E3C]"
                                        >Our Story</a
                                    >
                                </li>
                                <li>
                                    <a
                                        href="#news"
                                        class="transition hover:text-[#8B5E3C]"
                                        >News</a
                                    >
                                </li>
                            </ul>
                        </div>

                        <div>
                            <h4
                                class="mb-4 text-[11px] font-semibold tracking-[0.15em] text-[#2C2318]/40 uppercase"
                            >
                                Hours
                            </h4>
                            <ul
                                class="space-y-2.5 text-[13px] text-[#2C2318]/45"
                            >
                                <li>Mon — Fri: 10 AM – 9 PM</li>
                                <li>Sat: 11 AM – 10 PM</li>
                                <li>Sun: 11 AM – 8 PM</li>
                            </ul>
                        </div>

                        <div>
                            <h4
                                class="mb-4 text-[11px] font-semibold tracking-[0.15em] text-[#2C2318]/40 uppercase"
                            >
                                Connect
                            </h4>
                            <ul
                                class="space-y-2.5 text-[13px] text-[#2C2318]/45"
                            >
                                <li>hello@boteaque.com</li>
                                <li>123 Tea Lane, Boba City</li>
                            </ul>
                            <div class="mt-5 flex gap-3">
                                <a
                                    href="#"
                                    class="flex h-8 w-8 items-center justify-center rounded-full border border-[#2C2318]/[0.08] text-[#2C2318]/30 transition-all duration-300 hover:border-[#8B5E3C]/30 hover:text-[#8B5E3C]"
                                >
                                    <svg
                                        class="h-3.5 w-3.5"
                                        fill="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"
                                        />
                                    </svg>
                                </a>
                                <a
                                    href="#"
                                    class="flex h-8 w-8 items-center justify-center rounded-full border border-[#2C2318]/[0.08] text-[#2C2318]/30 transition-all duration-300 hover:border-[#8B5E3C]/30 hover:text-[#8B5E3C]"
                                >
                                    <svg
                                        class="h-3.5 w-3.5"
                                        fill="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.11V9.01a6.28 6.28 0 00-.79-.05 6.28 6.28 0 00-6.28 6.28 6.28 6.28 0 006.28 6.28 6.28 6.28 0 006.28-6.28V8.87a8.18 8.18 0 004.77 1.52v-3.4a4.85 4.85 0 01-1.34-.3z"
                                        />
                                    </svg>
                                </a>
                                <a
                                    href="#"
                                    class="flex h-8 w-8 items-center justify-center rounded-full border border-[#2C2318]/[0.08] text-[#2C2318]/30 transition-all duration-300 hover:border-[#8B5E3C]/30 hover:text-[#8B5E3C]"
                                >
                                    <svg
                                        class="h-3.5 w-3.5"
                                        fill="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"
                                        />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div
                        class="mt-14 flex flex-col items-center justify-between gap-3 border-t border-[#2C2318]/[0.06] pt-8 text-[12px] text-[#2C2318]/25 sm:flex-row"
                    >
                        <p>
                            Copyright 2026 &copy; Boteaque. All rights reserved.
                        </p>
                        <div class="flex gap-5">
                            <a href="#" class="transition hover:text-[#8B5E3C]"
                                >Privacy Policy</a
                            >
                            <a href="#" class="transition hover:text-[#8B5E3C]"
                                >Terms &amp; Conditions</a
                            >
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
html {
    scroll-behavior: smooth;
}

.carousel-hide-scrollbar::-webkit-scrollbar {
    display: none;
}
.carousel-hide-scrollbar {
    scrollbar-width: none;
    -ms-overflow-style: none;
}
</style>
