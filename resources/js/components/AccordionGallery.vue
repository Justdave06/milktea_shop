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
        duration: 0.8,
        ease: 'power3.inOut',
        parallax: 0.4,
        tilt: 0,
        stagger: 0.06,
        trigger: 'hover',
        showLabels: true,
        grayscale: true,
        className: '',
    },
);

const rootRef = ref<HTMLElement | null>(null);
const panelRefs = ref<(HTMLElement | null)[]>([]);
const barRefs = ref<(HTMLElement | null)[]>([]);
const textRefs = ref<(HTMLElement | null)[]>([]);
const tlRef = ref<gsap.core.Timeline | null>(null);
const firstRunRef = ref(true);
const mediaSizeRef = ref(320);
const galleryHeight = ref(props.height);

const vertical = computed(() => props.orientation === 'vertical');
const count = computed(() => props.items.length);
const active = ref(Math.min(Math.max(props.defaultIndex, 0), count.value - 1));

const prefersReduced = computed(
    () =>
        typeof window !== 'undefined' &&
        window.matchMedia &&
        window.matchMedia('(prefers-reduced-motion: reduce)').matches,
);

/**
 * Compute the CSS transform for each media element based on its distance
 * from the active panel. This is reactive — Vue drives the values, CSS
 * transitions handle the animation. No GSAP needed for transforms.
 */
function mediaTransform(index: number): string {
    const isActive = index === active.value;
    const drift = Math.max(-1.5, Math.min(1.5, active.value - index));
    const shift = drift * props.parallax * mediaSizeRef.value * 0.18;
    const tx = isActive ? 0 : shift;
    const ty = vertical.value ? (isActive ? 0 : shift) : 0;
    const sc = isActive ? 1.05 : 1;
    return `translate(-50%, -50%) translate(${tx}px, ${ty}px) scale(${sc})`;
}

function mediaFilter(index: number): string {
    if (!props.grayscale) {
        return 'none';
    }
    return index === active.value ? 'grayscale(0%)' : 'grayscale(100%)';
}

function overlayOpacity(index: number): number {
    return index === active.value ? 0.1 : 0.55;
}

const transitionDuration = computed(() => {
    return prefersReduced.value ? '0ms' : `${props.duration * 1000}ms`;
});

const transitionTimingFunction = computed(() => {
    /* Map GSAP ease names to CSS cubic-bezier equivalents */
    const map: Record<string, string> = {
        'power1.inOut': 'cubic-bezier(0.42, 0, 0.58, 1)',
        'power2.inOut': 'cubic-bezier(0.455, 0.03, 0.515, 0.955)',
        'power3.inOut': 'cubic-bezier(0.77, 0, 0.175, 1)',
        'power2.out': 'cubic-bezier(0.25, 0.46, 0.45, 0.94)',
        'power3.out': 'cubic-bezier(0.215, 0.61, 0.355, 1)',
    };
    return map[props.ease] || 'cubic-bezier(0.77, 0, 0.175, 1)';
});

/* ── Panel flex-grow still uses GSAP (complex per-panel timeline) ── */

function applyPanelLayout(animate: boolean) {
    const panels = panelRefs.value;

    if (!panels.length) {
        return;
    }

    const r = Math.min(Math.max(props.expandRatio, 0.2), 0.9);
    const grow = count.value > 1 ? (r * (count.value - 1)) / (1 - r) : 1;

    tlRef.value?.kill();
    const dur = animate && !prefersReduced.value ? props.duration : 0;
    const tl = gsap.timeline();

    panels.forEach((panel, i) => {
        if (!panel) {
            return;
        }

        const isActive = i === active.value;

        tl.to(
            panel,
            {
                flexGrow: isActive ? grow : 1,
                duration: dur,
                ease: props.ease,
            },
            0,
        );

        if (props.showLabels) {
            const bar = barRefs.value[i];
            const text = textRefs.value[i];

            if (bar && text) {
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
    const usable = Math.max(rect.width - props.gap * (count.value - 1), 120);
    const r = Math.min(Math.max(props.expandRatio, 0.2), 0.9);
    const size = vertical.value
        ? Math.round(props.height * 1.6)
        : Math.max(140, Math.min(Math.round(r * usable), 700));
    galleryHeight.value = size;
    mediaSizeRef.value = size;
    el.style.setProperty('--ag-media-size', `${size}px`);
    applyPanelLayout(!firstRunRef.value);
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
    applyPanelLayout(false);
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
    applyPanelLayout(true);
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
            height: `${galleryHeight}px`,
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
                <!--
                    Image container: positioning via CSS (absolute centering),
                    animation via CSS transition (transform + filter).
                    GSAP is NOT used here — CSS transitions handle interpolation
                    natively, including filter which GSAP cannot interpolate.
                -->
                <span
                    class="absolute top-1/2 left-1/2"
                    :style="{
                        width: vertical
                            ? '100%'
                            : 'var(--ag-media-size, 320px)',
                        height: 'var(--ag-media-size, 320px)',
                        transform: mediaTransform(i),
                        filter: mediaFilter(i),
                        transition: `transform ${transitionDuration} ${transitionTimingFunction}, filter ${transitionDuration} ${transitionTimingFunction}`,
                    }"
                >
                    <img
                        :src="item.image"
                        :alt="item.alt || item.label || ''"
                        draggable="false"
                        class="block h-full w-full object-cover select-none [-webkit-user-drag:none]"
                    />
                </span>

                <!-- Overlay: opacity driven by CSS transition -->
                <span
                    class="pointer-events-none absolute inset-0 transition-opacity"
                    :style="{
                        background: `linear-gradient(180deg, transparent 30%, ${overlayColor} 100%)`,
                        opacity: overlayOpacity(i),
                        transitionDuration: transitionDuration,
                        transitionTimingFunction: transitionTimingFunction,
                    }"
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
