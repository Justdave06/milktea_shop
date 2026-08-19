---
paths:
  - resources/js/components/AccordionGallery.vue
---

# Components

## AccordionGallery is a manual GSAP port
Vue Bits' registry does NOT ship an AccordionGallery component (only CircularGallery/DomeGallery). This component is a Vue 3 port of the React Bits accordion-gallery (GSAP-based, gsap@^3.13.0 dependency). It animates flex-grow, rotateY/X, CSS vars --ag-gray/--ag-dim, and re-measures on items change. Do not try `npx jsrepo add .../AccordionGallery.json` — it won't exist. The extended `details` caption line is a project addition (React Bits only has label).

## AccordionGallery full-bleed tuning
Settled tuning: full-bleed edge-to-edge row (gallery sits OUTSIDE the max-w container in Welcome.vue); media is a 1:1 square sized to `height` (--ag-media-size), panels use radius 16. For smooth "sliding" expansion use duration 0.8 + ease power3.inOut + tilt 0 (rotation caused jank with full-width flex-grow) + force3D. Don't re-add tilt/expd ease for this full-width layout.

## AccordionGallery expanded panel is a square
The ACTIVE panel is a true square: gallery height is computed dynamically in measure() as expandRatio * (container width - gaps), capped at 700px (min 140), and --ag-media-size = that height so the square media fills the expanded panel exactly. `height` prop is only the initial/fallback value. Collapsed panels are tall strips by design. For this full-bleed layout use expandRatio ~0.35 so the square stays reasonable.
