---
paths:
  - resources/js/components/AccordionGallery.vue
---

# Components

## AccordionGallery is a manual GSAP port
Vue Bits' registry does NOT ship an AccordionGallery component (only CircularGallery/DomeGallery). This component is a Vue 3 port of the React Bits accordion-gallery (GSAP-based, gsap@^3.13.0 dependency). It animates flex-grow, rotateY/X, CSS vars --ag-gray/--ag-dim, and re-measures on items change. Do not try `npx jsrepo add .../AccordionGallery.json` — it won't exist. The extended `details` caption line is a project addition (React Bits only has label).
