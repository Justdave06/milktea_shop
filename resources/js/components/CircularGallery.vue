<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount, computed, watch } from 'vue';

export interface CircularGalleryItem {
    image: string;
    text?: string;
    link?: string;
    buttonText?: string;
    buttonLink?: string;
}

const props = withDefaults(
    defineProps<{
        items: CircularGalleryItem[];
        bend?: number;
        textColor?: string;
        borderRadius?: number;
        scrollSpeed?: number;
        height?: number;
    }>(),
    {
        bend: 3,
        textColor: '#ffffff',
        borderRadius: 12,
        scrollSpeed: 2,
        height: 500,
    },
);

const containerRef = ref<HTMLElement | null>(null);
const scrollTarget = ref(0);
const scrollCurrent = ref(0);
const isDragging = ref(false);
const startX = ref(0);
const scrollPosition = ref(0);
const itemWidth = ref(300);
const itemCount = computed(() => props.items.length);
const totalWidth = computed(() => itemWidth.value * itemCount.value);

const normalizedCurrent = computed(() => {
    const cw = totalWidth.value;
    if (cw === 0) return 0;
    let v = ((scrollCurrent.value % cw) + cw) % cw;
    return v;
});

/** Index of the card currently centered in the viewport */
const activeIndex = computed(() => {
    const iw = itemWidth.value;
    if (iw === 0) return 0;
    const raw = Math.round(normalizedCurrent.value / iw);
    return ((raw % itemCount.value) + itemCount.value) % itemCount.value;
});

function getItemStyle(index: number) {
    const iw = itemWidth.value;
    const nc = normalizedCurrent.value;
    let offset = index * iw - nc;

    /* wrap around */
    const half = totalWidth.value / 2;
    if (offset > half) offset -= totalWidth.value;
    if (offset < -half) offset += totalWidth.value;

    const containerHalf = containerRef.value
        ? containerRef.value.clientWidth / 2
        : 600;
    const normalisedX = offset / containerHalf;

    /* arc bend */
    const B = Math.abs(props.bend);
    const R = (containerHalf * containerHalf + B * B) / (2 * B);
    const effectiveX = Math.min(Math.abs(offset), containerHalf);
    const arc = R - Math.sqrt(Math.max(0, R * R - effectiveX * effectiveX));

    const yPos = props.bend > 0 ? -arc : arc;
    const rotateZ =
        Math.sign(offset) * Math.asin(Math.min(effectiveX / R, 1));

    /* scale based on distance from centre */
    const distFromCenter = Math.abs(offset) / containerHalf;
    const scale = 1 - distFromCenter * 0.3;

    /* opacity */
    const opacity = 1 - distFromCenter * 0.6;

    /* z-index: centre items on top */
    const zIndex = Math.round((1 - distFromCenter) * 100);

    return {
        transform: `translateX(${offset}px) translateY(${yPos}px) rotateZ(${rotateZ}rad) scale(${Math.max(scale, 0.5)})`,
        opacity: Math.max(opacity, 0),
        zIndex,
        width: `${itemWidth.value}px`,
    };
}

function getImageStyle() {
    return {
        borderRadius: `${props.borderRadius}px`,
    };
}

/* ── Drag / Touch / Wheel handling ── */

let rafId: number | null = null;
let lastTime = 0;

function lerp(a: number, b: number, t: number): number {
    return a + (b - a) * t;
}

function tick() {
    scrollCurrent.value = lerp(scrollCurrent.value, scrollTarget.value, 0.08);
    rafId = requestAnimationFrame(tick);
}

function onPointerDown(e: PointerEvent) {
    isDragging.value = true;
    scrollPosition.value = scrollTarget.value;
    startX.value = e.clientX;
    (e.target as HTMLElement)?.setPointerCapture?.(e.pointerId);
}

function onPointerMove(e: PointerEvent) {
    if (!isDragging.value) return;
    const distance = (startX.value - e.clientX) * (props.scrollSpeed * 0.35);
    scrollTarget.value = scrollPosition.value + distance;
}

function onPointerUp() {
    isDragging.value = false;
    snapToNearest();
}

function onWheel(e: WheelEvent) {
    /* Only intercept Shift+Wheel (horizontal intent) — let normal vertical scroll pass through */
    if (!e.shiftKey) return;
    e.preventDefault();
    scrollTarget.value += (e.deltaY > 0 ? 1 : -1) * props.scrollSpeed * 40;
    snapToNearest();
}

function snapToNearest() {
    const iw = itemWidth.value;
    if (iw === 0) return;
    const itemIndex = Math.round(Math.abs(scrollTarget.value) / iw);
    const snapped = iw * itemIndex;
    scrollTarget.value = scrollTarget.value < 0 ? -snapped : snapped;
}

function measure() {
    if (!containerRef.value) return;
    const w = containerRef.value.clientWidth;
    /* item width ≈ 50% of container for big cinematic cards */
    itemWidth.value = Math.max(320, Math.min(Math.round(w * 0.5), 600));
}

function goNext() {
    scrollTarget.value += itemWidth.value;
    snapToNearest();
}

function goPrev() {
    scrollTarget.value -= itemWidth.value;
    snapToNearest();
}

let ro: ResizeObserver | null = null;

onMounted(() => {
    measure();
    rafId = requestAnimationFrame(tick);
    ro = new ResizeObserver(measure);
    if (containerRef.value) ro.observe(containerRef.value);
});

onBeforeUnmount(() => {
    if (rafId !== null) cancelAnimationFrame(rafId);
    ro?.disconnect();
});
</script>

<template>
    <div
        ref="containerRef"
        class="circular-gallery relative w-full overflow-hidden select-none"
        :style="{ height: `${height}px` }"
        tabindex="0"
        role="region"
        aria-label="Circular product gallery. Drag or scroll to browse."
        @pointerdown="onPointerDown"
        @pointermove="onPointerMove"
        @pointerup="onPointerUp"
        @pointercancel="onPointerUp"
        @wheel="onWheel"
    >
        <!-- 3D stage -->
        <div
            class="absolute inset-0 flex items-center justify-center"
            style="perspective: 1200px"
        >
            <div
                class="relative"
                style="transform-style: preserve-3d"
            >
                <div
                    v-for="(item, i) in items"
                    :key="i"
                    class="absolute top-1/2 left-1/2 transition-[opacity] duration-150"
                    :style="{
                        ...getItemStyle(i),
                        marginLeft: `-${itemWidth / 2}px`,
                        marginTop: '-220px',
                        willChange: 'transform, opacity',
                    }"
                >
                    <div
                        class="group relative overflow-hidden bg-black/20 shadow-2xl"
                        :style="getImageStyle()"
                    >
                        <img
                            :src="item.image"
                            :alt="item.text || ''"
                            draggable="false"
                            class="block h-[380px] w-full object-cover select-none [-webkit-user-drag:none]"
                        />
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"
                        />
                    </div>
                    <p
                        v-if="item.text"
                        class="mt-4 text-center text-base font-semibold tracking-wide"
                        :style="{ color: textColor }"
                    >
                        {{ item.text }}
                    </p>

                </div>
            </div>
        </div>

        <!-- Navigation arrows -->
        <button
            class="absolute left-4 top-1/2 z-50 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full border border-[#2C2318]/10 bg-white/80 shadow-lg backdrop-blur-sm transition-all duration-300 hover:bg-white hover:shadow-xl"
            aria-label="Previous"
            @click.stop="goPrev"
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
        </button>
        <button
            class="absolute right-4 top-1/2 z-50 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full border border-[#2C2318]/10 bg-white/80 shadow-lg backdrop-blur-sm transition-all duration-300 hover:bg-white hover:shadow-xl"
            aria-label="Next"
            @click.stop="goNext"
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
                    d="M8.25 4.5l7.5 7.5-7.5 7.5"
                />
            </svg>
        </button>

        <!-- Drag hint -->
        <div
            class="pointer-events-none absolute bottom-4 left-1/2 -translate-x-1/2 text-xs font-medium tracking-wider uppercase"
            :style="{ color: `${textColor}80` }"
        >
            ← drag to explore →
        </div>
    </div>
</template>

<style scoped>
.circular-gallery {
    cursor: grab;
}
.circular-gallery:active {
    cursor: grabbing;
}
.circular-gallery:focus-visible {
    outline: 2px solid #8b5e3c;
    outline-offset: 4px;
}
</style>
