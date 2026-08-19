<script setup lang="ts">
import { gsap } from 'gsap';
import {
    ref,
    computed,
    onMounted,
    onBeforeUnmount,
    watch,
    nextTick,
} from 'vue';

export interface AccordionGalleryItem {
    image: string;
    label?: string;
    details?: string;
    link?: string;
    alt?: string;
}

const props = withDefaults(
    defineProps<{
        items: AccordionGalleryItem[];
        defaultIndex?: number;
        accentColor?: string;
        overlayColor?: string;
        textColor?: string;
        height?: number;
        gap?: number;
        radius?: number;
        expandRatio?: number;
        orientation?: 'horizontal' | 'vertical';
        duration?: number;
        ease?: string;
        parallax?: number;
        tilt?: number;
        stagger?: number;
        trigger?: 'hover' | 'click';
        showLabels?: boolean;
        grayscale?: boolean;
        className?: string;
    }>(),
    {
        defaultIndex: 2,
        accentColor: '#ffffff',
        overlayColor: '#060010',
        textColor: '#ffffff',
        height: 460,
        gap: 10,
        radius: 16,
        expandRatio: 0.52,
        orientation: 'horizontal',
        duration: 0.6,
        ease: 'power3.out',
        parallax: 0.5,
        tilt: 8,
        stagger: 0.06,
        trigger: 'hover',
        showLabels: true,
        grayscale: true,
        className: '',
    },
);

const rootRef = ref<HTMLElement | null>(null);
const panelRefs = ref<(HTMLElement | null)[]>([]);
const mediaRefs = ref<(HTMLElement | null)[]>([]);
const barRefs = ref<(HTMLElement | null)[]>([]);
const textRefs = ref<(HTMLElement | null)[]>([]);
const tlRef = ref<gsap.core.Timeline | null>(null);
const firstRunRef = ref(true);
const mediaSizeRef = ref(320);

const vertical = computed(() => props.orientation === 'vertical');
const count = computed(() => props.items.length);
const active = ref(Math.min(Math.max(props.defaultIndex, 0), count.value - 1));

const prefersReduced = computed(
    () =>
        typeof window !== 'undefined' &&
        window.matchMedia &&
        window.matchMedia('(prefers-reduced-motion: reduce)').matches,
);

const overlayBg = computed(
    () =>
        `linear-gradient(180deg, transparent 45%, color-mix(in srgb, ${props.overlayColor} 78%, transparent) 100%), color-mix(in srgb, ${props.overlayColor} calc(var(--ag-dim, 0.35) * 100%), transparent)`,
);

function applyLayout(animate: boolean) {
    const panels = panelRefs.value;

    if (!panels.length) {
        return;
    }

    const r = Math.min(Math.max(props.expandRatio, 0.2), 0.9);
    const grow = count.value > 1 ? (r * (count.value - 1)) / (1 - r) : 1;
    const mediaSize = mediaSizeRef.value;

    tlRef.value?.kill();
    const dur = animate && !prefersReduced.value ? props.duration : 0;
    const tl = gsap.timeline();

    panels.forEach((panel, i) => {
        if (!panel) {
            return;
        }

        const isActive = i === active.value;
        const media = mediaRefs.value[i];
        const bar = barRefs.value[i];
        const text = textRefs.value[i];

        const rot = isActive ? 0 : i < active.value ? props.tilt : -props.tilt;
        const rotProp = vertical.value ? { rotateX: -rot } : { rotateY: rot };

        tl.to(
            panel,
            {
                flexGrow: isActive ? grow : 1,
                ...rotProp,
                duration: dur,
                ease: props.ease,
            },
            0,
        );

        if (media) {
            const drift = Math.max(-1.5, Math.min(1.5, active.value - i));
            const shift = drift * props.parallax * mediaSize * 0.06;
            const gray = props.grayscale ? (isActive ? 0 : 1) : 0;
            tl.to(
                media,
                {
                    xPercent: -50,
                    yPercent: -50,
                    x: vertical.value ? 0 : isActive ? 0 : shift,
                    y: vertical.value ? (isActive ? 0 : shift) : 0,
                    '--ag-gray': gray,
                    '--ag-dim': isActive ? 0 : 0.35,
                    duration: dur,
                    ease: props.ease,
                },
                0,
            );
        }

        if (props.showLabels && bar && text) {
            if (isActive) {
                tl.to(
                    [bar, text],
                    {
                        opacity: 1,
                        x: 0,
                        duration: dur,
                        ease: props.ease,
                        stagger: prefersReduced.value ? 0 : props.stagger,
                    },
                    0,
                );
            } else {
                tl.to(
                    [bar, text],
                    {
                        opacity: 0,
                        x: -14,
                        duration: dur * 0.6,
                        ease: props.ease,
                    },
                    0,
                );
            }
        }
    });

    tlRef.value = tl;
}

function measure() {
    const el = rootRef.value;

    if (!el) {
        return;
    }

    const rect = el.getBoundingClientRect();
    const total = vertical.value ? rect.height : rect.width;
    const usable = Math.max(total - props.gap * (count.value - 1), 120);
    const size = Math.max(
        140,
        usable * Math.min(Math.max(props.expandRatio, 0.2), 0.9) * 1.22,
    );
    mediaSizeRef.value = size;
    el.style.setProperty('--ag-media-size', `${size}px`);
    applyLayout(!firstRunRef.value);
}

let resizeObserver: ResizeObserver | null = null;

onMounted(() => {
    const el = rootRef.value;

    if (!el) {
        return;
    }

    measure();
    resizeObserver = new ResizeObserver(measure);
    resizeObserver.observe(el);
    applyLayout(false);
    firstRunRef.value = false;
});

onBeforeUnmount(() => {
    resizeObserver?.disconnect();
    tlRef.value?.kill();
});

watch(
    () => props.items,
    () => {
        nextTick(() => {
            if (active.value >= props.items.length) {
                active.value = Math.max(props.items.length - 1, 0);
            }

            firstRunRef.value = true;
            measure();
            firstRunRef.value = false;
        });
    },
);

watch(active, () => {
    applyLayout(true);
});

function handleEnter(i: number) {
    if (props.trigger === 'hover') {
        active.value = i;
    }
}

function handleClick(i: number, e: MouseEvent) {
    if (i !== active.value) {
        e.preventDefault();
        active.value = i;
    }
}

function handleKeyDown(i: number, e: KeyboardEvent) {
    if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
        e.preventDefault();
        active.value = (i + 1) % count.value;
    } else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
        e.preventDefault();
        active.value = (i - 1 + count.value) % count.value;
    }
}
</script>

<template>
    <div
        ref="rootRef"
        class="flex w-full max-w-full [perspective:1400px] max-[520px]:!flex-col max-[520px]:[perspective:none]"
        :class="[vertical ? 'flex-col' : 'flex-row', className]"
        :style="{
            gap: `${gap}px`,
            height: vertical ? `${Math.round(height * 1.6)}px` : `${height}px`,
        }"
        role="list"
        aria-label="Image accordion gallery"
    >
        <component
            :is="item.link ? 'a' : 'div'"
            v-for="(item, i) in items"
            :key="i"
            :href="item.link || undefined"
            :ref="(el: unknown) => (panelRefs[i] = el as HTMLElement | null)"
            class="group relative block min-h-0 min-w-0 flex-[1_1_0] [transform-origin:center] cursor-pointer overflow-hidden bg-[#0a0713] no-underline [box-shadow:0_10px_30px_-18px_rgba(0,0,0,0.8)] outline-none [transform-style:preserve-3d] focus-visible:[box-shadow:0_0_0_2px_var(--ag-accent),0_10px_30px_-18px_rgba(0,0,0,0.8)] max-[520px]:min-h-[84px] max-[520px]:!transform-none"
            :style="{
                borderRadius: `${radius}px`,
                '--ag-accent': accentColor,
                willChange: 'flex-grow, transform',
            }"
            role="listitem"
            tabindex="0"
            :aria-current="i === active ? 'true' : undefined"
            :aria-label="item.label"
            @click="(e: MouseEvent) => handleClick(i, e)"
            @mouseenter="handleEnter(i)"
            @focus="active = i"
            @keydown="(e: KeyboardEvent) => handleKeyDown(i, e)"
        >
            <span
                class="absolute inset-0 overflow-hidden [border-radius:inherit]"
            >
                <span
                    :ref="(el) => (mediaRefs[i] = el as HTMLElement | null)"
                    class="absolute top-1/2 left-1/2 [filter:grayscale(var(--ag-gray,1))]"
                    :style="{
                        width: vertical
                            ? '100%'
                            : 'var(--ag-media-size, 320px)',
                        height: vertical
                            ? 'var(--ag-media-size, 320px)'
                            : '100%',
                        willChange: 'transform, filter',
                    }"
                >
                    <img
                        :src="item.image"
                        :alt="item.alt || item.label || ''"
                        draggable="false"
                        class="block h-full w-full object-cover select-none [-webkit-user-drag:none]"
                    />
                </span>
                <span
                    class="pointer-events-none absolute inset-0"
                    :style="{ background: overlayBg }"
                    aria-hidden="true"
                />
            </span>
            <span
                v-if="showLabels"
                class="pointer-events-none absolute right-5 bottom-5 left-5 z-[2] flex items-center gap-3"
                aria-hidden="true"
            >
                <span
                    :ref="(el) => (barRefs[i] = el as HTMLElement | null)"
                    class="h-[26px] w-[3px] flex-none rounded-[3px] opacity-0"
                    :style="{
                        background: accentColor,
                        boxShadow: `0 0 12px color-mix(in srgb, ${accentColor} 60%, transparent)`,
                    }"
                />
                <span
                    :ref="(el) => (textRefs[i] = el as HTMLElement | null)"
                    class="flex min-w-0 flex-col opacity-0"
                    :style="{ color: textColor }"
                >
                    <span
                        class="truncate text-[clamp(1rem,1.4vw,1.4rem)] font-semibold tracking-[0.01em] [text-shadow:0_2px_14px_rgba(0,0,0,0.55)]"
                    >
                        {{ item.label }}
                    </span>
                    <span
                        v-if="item.details"
                        class="mt-1 truncate text-[12px] font-medium opacity-80 [text-shadow:0_2px_14px_rgba(0,0,0,0.55)]"
                    >
                        {{ item.details }}
                    </span>
                </span>
            </span>
        </component>
    </div>
</template>
