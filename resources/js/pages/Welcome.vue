<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted, computed } from 'vue';
import AccordionGallery from '@/components/AccordionGallery.vue';

const activeFilter = ref('all');
const scrolled = ref(false);
const menuVisible = ref(false);
const aboutVisible = ref(false);
const storeVisible = ref(false);
const orderVisible = ref(false);
const newsVisible = ref(false);
const footerVisible = ref(false);

const filters = [
    { key: 'all', label: 'All' },
    { key: 'bestseller', label: 'Bestseller' },
    { key: 'toppick', label: 'Top Picks' },
];

const drinks = [
    {
        name: 'Signature 3Q Milk Tea',
        description:
            "Milksha's bestselling signature milk tea layered with a trio of honey pearls, crystals, and pudding.",
        image: 'https://images.unsplash.com/photo-1558857563-b371033873b8?w=600&h=600&fit=crop&auto=format',
        tags: ['bestseller', 'toppick'],
        priceM: '₱140',
        priceL: '₱160',
    },
    {
        name: 'Strawberry Coulis Milk',
        description:
            'Creamy milk swirled with a rich strawberry coulis for an indulgent treat.',
        image: 'https://images.unsplash.com/photo-1576092768241-dec231879fc3?w=600&h=600&fit=crop&auto=format',
        tags: ['bestseller', 'toppick'],
        priceM: '₱120',
        priceL: '₱140',
    },
    {
        name: 'Jasmine Green Tea with Cloudy Cream',
        description:
            'Light and fragrant Jasmine green tea topped with our lush Cloudy Cream.',
        image: 'https://images.unsplash.com/photo-1563911892437-1feda0179e1b?w=600&h=600&fit=crop&auto=format',
        tags: ['bestseller', 'toppick'],
        priceM: '₱120',
        priceL: '₱140',
    },
    {
        name: 'Signature Milk Tea with Honey Pearls',
        description:
            "Milksha's bestselling signature milk tea with honey pearls.",
        image: 'https://images.unsplash.com/photo-1541658016709-82535e94bc69?w=600&h=600&fit=crop&auto=format',
        tags: ['bestseller'],
        priceM: '₱120',
        priceL: '₱140',
    },
    {
        name: 'Brown Sugar Milk with Honey Pearls',
        description:
            'Creamy milk sweetened with rich, caramelized brown sugar, topped with honey pearls.',
        image: 'https://images.unsplash.com/photo-1618160702438-9b02ab6515c9?w=600&h=600&fit=crop&auto=format',
        tags: ['toppick'],
        priceM: '₱140',
        priceL: '₱160',
    },
    {
        name: 'Japanese Matcha with Cloudy Cream',
        description: 'Pure Japanese Matcha topped with our lush Cloudy Cream.',
        image: 'https://images.unsplash.com/photo-1527661591475-527312dd65f5?w=600&h=600&fit=crop&auto=format',
        tags: ['bestseller', 'toppick'],
        priceM: '₱120',
        priceL: '₱140',
    },
    {
        name: 'Fresh Taro Milk',
        description: 'Fresh crushed Taro blended with a creamy milk.',
        image: 'https://images.unsplash.com/photo-1558857563-b371033873b8?w=600&h=600&fit=crop&auto=format',
        tags: ['toppick'],
        priceM: '₱160',
        priceL: '₱180',
    },
    {
        name: 'Honey Lemonade with Green Tea Jelly',
        description:
            'Light and fresh lemon juice with honey, paired with Green Tea Jelly.',
        image: 'https://images.unsplash.com/photo-1541658016709-82535e94bc69?w=600&h=600&fit=crop&auto=format',
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

const galleryItems = computed(() =>
    filteredDrinks.value.map((drink) => ({
        image: drink.image,
        label: drink.name,
        details: `M ${drink.priceM} · L ${drink.priceL}`,
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

                    if (id === 'order') {
                        orderVisible.value = true;
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
    handleScroll();
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>

<template>
    <div class="min-h-screen bg-[#FDFBF7] font-sans text-[#2C2318]">
        <!-- ==================== NAVIGATION ==================== -->
        <header
            class="fixed inset-x-0 top-0 z-50 transition-all duration-300"
            :class="
                scrolled
                    ? 'bg-[#F6E7C6]/90 shadow-[0_1px_0_0_rgba(0,0,0,0.06)] backdrop-blur-md'
                    : 'bg-[#F6E7C6]'
            "
        >
            <nav
                class="mx-auto flex max-w-[1200px] items-center justify-between px-6 py-4 lg:px-8"
            >
                <Link href="/" class="flex items-center gap-2.5">
                    <svg
                        class="h-7 w-7 text-[#8B5E3C]"
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
                        class="text-[16px] font-bold tracking-[0.01em] text-[#2C2318]"
                        >Boteaque</span
                    >
                </Link>

                <div class="hidden items-center gap-8 md:flex">
                    <a
                        href="#home"
                        class="text-[13px] font-medium text-[#2C2318]/50 transition-colors hover:text-[#8B5E3C]"
                        >Home</a
                    >
                    <a
                        href="#menu"
                        class="text-[13px] font-medium text-[#2C2318]/50 transition-colors hover:text-[#8B5E3C]"
                        >Menu</a
                    >
                    <a
                        href="#about"
                        class="text-[13px] font-medium text-[#2C2318]/50 transition-colors hover:text-[#8B5E3C]"
                        >Our Story</a
                    >
                    <a
                        href="#news"
                        class="text-[13px] font-medium text-[#2C2318]/50 transition-colors hover:text-[#8B5E3C]"
                        >News</a
                    >
                </div>

                <Link
                    href="/register"
                    class="rounded-full bg-[#8B5E3C] px-6 py-2.5 text-[13px] font-semibold text-white transition-all duration-300 hover:bg-[#7A5234]"
                >
                    Order Now
                </Link>
            </nav>
        </header>

        <!-- ==================== HERO ==================== -->
        <section
            id="home"
            class="relative flex min-h-[88vh] items-center overflow-hidden"
        >
            <!-- Background image -->
            <div class="absolute inset-0">
                <img
                    src="/images/hero.png"
                    alt="Milk tea"
                    class="h-full w-full object-cover"
                />
            </div>

            <div
                class="relative mx-auto w-full max-w-[1200px] px-6 py-24 lg:px-8"
            >
                <div class="max-w-xl">
                    <h1
                        class="text-[2.75rem] leading-[1.08] font-bold tracking-[-0.02em] text-[#FDFBF7] sm:text-6xl"
                    >
                        Experience pure
                        <br />
                        indulgence &amp; freshness
                    </h1>
                    <p
                        class="mt-5 max-w-md text-[15px] leading-relaxed text-[#FDFBF7]/75"
                    >
                        Fresh milk and tea beverages, crafted with high quality
                        ingredients and brewed using traditional Taiwanese
                        methods.
                    </p>
                    <div class="mt-8 flex gap-3">
                        <a
                            href="#menu"
                            class="inline-flex items-center rounded-full bg-[#8B5E3C] px-7 py-3 text-[13px] font-semibold text-white transition-all duration-300 hover:bg-[#7A5234]"
                        >
                            Discover the drinks
                        </a>
                        <a
                            href="#about"
                            class="inline-flex items-center rounded-full border border-[#FDFBF7]/30 px-7 py-3 text-[13px] font-semibold text-[#FDFBF7]/80 transition-all duration-300 hover:border-[#FDFBF7]/60 hover:text-[#FDFBF7]"
                        >
                            Order now
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- ==================== MENU ==================== -->
        <section id="menu" data-section="menu" class="py-16 sm:py-24">
            <div class="mx-auto max-w-[1200px] px-6 lg:px-8">
                <div
                    class="mb-8 transition-all duration-700 sm:mb-12"
                    :class="
                        menuVisible
                            ? 'translate-y-0 opacity-100'
                            : 'translate-y-6 opacity-0'
                    "
                >
                    <h2
                        class="text-[2rem] font-bold tracking-[-0.02em] text-[#2C2318] sm:text-[2.5rem]"
                    >
                        Drinks
                    </h2>

                    <!-- Filter Tabs -->
                    <div class="mt-6 flex gap-2">
                        <button
                            v-for="filter in filters"
                            :key="filter.key"
                            @click="activeFilter = filter.key"
                            class="rounded-full px-5 py-2 text-[13px] font-medium transition-all duration-300"
                            :class="
                                activeFilter === filter.key
                                    ? 'bg-[#8B5E3C] text-white'
                                    : 'bg-[#8B5E3C]/8 text-[#2C2318]/60 hover:bg-[#8B5E3C]/15'
                            "
                        >
                            {{ filter.label }}
                        </button>
                    </div>
                </div>
            </div>

            <div
                class="transition-all duration-700"
                :class="
                    menuVisible
                        ? 'translate-y-0 opacity-100'
                        : 'translate-y-6 opacity-0'
                "
            >
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
                    :grayscale="false"
                />
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
                    <h2
                        class="text-[2rem] font-bold tracking-[-0.02em] text-[#2C2318] sm:text-[2.5rem]"
                    >
                        Taste the Boteaque difference
                    </h2>

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
                                    Authentic Taiwanese freshness
                                </p>
                                <p
                                    class="mt-2 text-[13px] leading-relaxed text-[#2C2318]/45"
                                >
                                    Sourced directly from family estates in
                                    Taiwan and Japan.
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
                                    Dedication to crafting world-class beverages
                                </p>
                                <p
                                    class="mt-2 text-[13px] leading-relaxed text-[#2C2318]/45"
                                >
                                    No premixes. No shortcuts. Every cup made to
                                    order.
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
                                    Your milk tea companion
                                </p>
                                <p
                                    class="mt-2 text-[13px] leading-relaxed text-[#2C2318]/45"
                                >
                                    A moment of joy in every sip, crafted with
                                    care.
                                </p>
                            </div>
                        </div>
                    </div>

                    <a
                        href="#"
                        class="mt-10 inline-flex items-center rounded-full bg-[#8B5E3C] px-7 py-3 text-[13px] font-semibold text-white transition-all duration-300 hover:bg-[#7A5234] sm:mt-12"
                    >
                        Our full story
                    </a>
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
                    <h2
                        class="text-[2rem] font-bold tracking-[-0.02em] text-[#2C2318] sm:text-[2.5rem]"
                    >
                        Grab a drink in store
                    </h2>

                    <div class="mt-8 grid gap-6 sm:mt-10 sm:grid-cols-2">
                        <!-- Concept Store Card -->
                        <div
                            class="overflow-hidden rounded-2xl border border-[#2C2318]/[0.06] bg-white"
                        >
                            <div class="h-48 overflow-hidden">
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
                                    Step into Boteaque's first ever Philippines
                                    concept store! We offer fresh milk and tea
                                    beverages, crafted with high quality
                                    ingredients.
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
                                Find nearest store
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ==================== ORDER CTA ==================== -->
        <section data-section="order" class="bg-[#F5F0E8] py-16 sm:py-24">
            <div class="mx-auto max-w-[1200px] px-6 lg:px-8">
                <div
                    class="overflow-hidden rounded-2xl bg-white transition-all duration-700"
                    :class="
                        orderVisible
                            ? 'translate-y-0 opacity-100'
                            : 'translate-y-6 opacity-0'
                    "
                >
                    <div class="grid items-center gap-0 sm:grid-cols-2">
                        <div class="p-8 sm:p-12">
                            <h2
                                class="text-[1.75rem] leading-tight font-bold tracking-[-0.02em] text-[#2C2318] sm:text-[2rem]"
                            >
                                Order your drink online
                            </h2>
                            <p
                                class="mt-4 text-[14px] leading-relaxed text-[#2C2318]/45"
                            >
                                Get your Boteaque fix delivered straight to your
                                door. Available for pickup and delivery across
                                the city.
                            </p>
                            <a
                                href="#"
                                class="mt-6 inline-flex items-center rounded-full bg-[#8B5E3C] px-7 py-3 text-[13px] font-semibold text-white transition-all duration-300 hover:bg-[#7A5234]"
                            >
                                Order now
                            </a>
                        </div>
                        <div
                            class="h-64 overflow-hidden sm:h-auto sm:min-h-[320px]"
                        >
                            <img
                                src="https://images.unsplash.com/photo-1558857563-b371033873b8?w=800&h=600&fit=crop&auto=format"
                                alt="Boteaque drinks"
                                class="h-full w-full object-cover"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ==================== NEWS ==================== -->
        <section data-section="news" class="py-16 sm:py-24">
            <div class="mx-auto max-w-[1200px] px-6 lg:px-8">
                <div
                    class="transition-all duration-700"
                    :class="
                        newsVisible
                            ? 'translate-y-0 opacity-100'
                            : 'translate-y-6 opacity-0'
                    "
                >
                    <div class="flex items-center justify-between">
                        <h2
                            class="text-[2rem] font-bold tracking-[-0.02em] text-[#2C2318] sm:text-[2.5rem]"
                        >
                            The latest tea
                        </h2>
                        <a
                            href="#"
                            class="text-[13px] font-semibold text-[#8B5E3C] transition-colors hover:text-[#7A5234]"
                        >
                            View all
                        </a>
                    </div>

                    <div class="mt-8 grid gap-4 sm:grid-cols-3">
                        <a
                            v-for="(item, i) in news"
                            :key="i"
                            href="#"
                            class="group rounded-2xl border border-[#2C2318]/[0.06] bg-white p-6 transition-all duration-300 hover:border-[#8B5E3C]/20 hover:shadow-[0_8px_30px_rgba(139,94,60,0.08)]"
                        >
                            <h3
                                class="text-[14px] leading-snug font-semibold text-[#2C2318] transition-colors group-hover:text-[#8B5E3C]"
                            >
                                {{ item.title }}
                            </h3>
                            <p
                                class="mt-3 text-[12px] font-medium text-[#2C2318]/35"
                            >
                                {{ item.source }}
                            </p>
                        </a>
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
</style>
