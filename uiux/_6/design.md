---
name: Khaled Store Admin
colors:
  surface: '#faf8ff'
  surface-dim: '#d9d9e5'
  surface-bright: '#faf8ff'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f3f3fe'
  surface-container: '#ededf9'
  surface-container-high: '#e7e7f3'
  surface-container-highest: '#e1e2ed'
  on-surface: '#191b23'
  on-surface-variant: '#434655'
  inverse-surface: '#2e3039'
  inverse-on-surface: '#f0f0fb'
  outline: '#737686'
  outline-variant: '#c3c6d7'
  surface-tint: '#0053db'
  primary: '#004ac6'
  on-primary: '#ffffff'
  primary-container: '#2563eb'
  on-primary-container: '#eeefff'
  inverse-primary: '#b4c5ff'
  secondary: '#545f73'
  on-secondary: '#ffffff'
  secondary-container: '#d5e0f8'
  on-secondary-container: '#586377'
  tertiary: '#943700'
  on-tertiary: '#ffffff'
  tertiary-container: '#bc4800'
  on-tertiary-container: '#ffede6'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#dbe1ff'
  primary-fixed-dim: '#b4c5ff'
  on-primary-fixed: '#00174b'
  on-primary-fixed-variant: '#003ea8'
  secondary-fixed: '#d8e3fb'
  secondary-fixed-dim: '#bcc7de'
  on-secondary-fixed: '#111c2d'
  on-secondary-fixed-variant: '#3c475a'
  tertiary-fixed: '#ffdbcd'
  tertiary-fixed-dim: '#ffb596'
  on-tertiary-fixed: '#360f00'
  on-tertiary-fixed-variant: '#7d2d00'
  background: '#faf8ff'
  on-background: '#191b23'
  surface-variant: '#e1e2ed'
typography:
  display-lg:
    fontFamily: IBM Plex Sans
    fontSize: 32px
    fontWeight: '700'
    lineHeight: 40px
  headline-md:
    fontFamily: IBM Plex Sans
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
  headline-sm:
    fontFamily: IBM Plex Sans
    fontSize: 20px
    fontWeight: '600'
    lineHeight: 28px
  title-lg:
    fontFamily: IBM Plex Sans
    fontSize: 18px
    fontWeight: '600'
    lineHeight: 24px
  body-md:
    fontFamily: IBM Plex Sans
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  body-sm:
    fontFamily: IBM Plex Sans
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
  label-md:
    fontFamily: IBM Plex Sans
    fontSize: 13px
    fontWeight: '500'
    lineHeight: 18px
  label-sm:
    fontFamily: IBM Plex Sans
    fontSize: 11px
    fontWeight: '600'
    lineHeight: 16px
    letterSpacing: 0.05em
  headline-md-mobile:
    fontFamily: IBM Plex Sans
    fontSize: 20px
    fontWeight: '600'
    lineHeight: 28px
rounded:
  sm: 0.125rem
  DEFAULT: 0.25rem
  md: 0.375rem
  lg: 0.5rem
  xl: 0.75rem
  full: 9999px
spacing:
  sidebar-width: 260px
  gutter: 1.5rem
  margin-page: 2rem
  stack-sm: 0.5rem
  stack-md: 1rem
  stack-lg: 1.5rem
---

## Brand & Style
The design system is engineered for high-performance retail management, emphasizing clarity, efficiency, and reliability. The visual language follows a **Corporate / Modern** aesthetic, prioritizing data density without sacrificing readability. 

The personality is professional and systematic. It uses a structured layout to instill confidence in store owners managing inventory, orders, and customer data. The interface utilizes high-contrast navigation elements to provide clear wayfinding, while the content area remains neutral to let data and product imagery take center stage.

## Colors
The color palette is functional and semantic. 
- **Primary Blue** is used for action-oriented elements and brand presence.
- **Dark Navy** defines the structural navigation, providing a sophisticated backdrop for administrative tools.
- **Semantic Colors** (Green, Red, Orange) are strictly reserved for status indicators: Success for fulfilled orders, Danger for stock-outs or cancellations, and Warning for pending actions.
- **Background Tones** utilize a subtle light gray to reduce eye strain during long working sessions while maintaining a crisp, clean feel.

## Typography
This design system uses **IBM Plex Sans** for its exceptional multi-language support, specifically its high-quality Arabic glyphs. The typeface is systematic and technical, making it ideal for data-heavy admin panels.

For RTL (Arabic) contexts:
- Ensure line heights are slightly increased (approx 10-15%) if the script appears crowded.
- Maintain a clear hierarchy where titles are bold and prominent.
- Numerical data should remain legible; use tabular lining figures for tables to ensure columns of numbers align correctly.

## Layout & Spacing
The layout follows a **Fixed-Fluid hybrid model** optimized for RTL:
- **Navigation (Right Side):** A fixed 260px sidebar anchored to the right. 
- **Content Area (Left Side):** A fluid main container that fills the remaining viewport width.
- **Grid:** Use a 12-column grid within the main content area for dashboard widgets and tables.
- **Breakpoints:** 
  - *Desktop (1280px+):* Full sidebar visible.
  - *Tablet (768px - 1279px):* Sidebar collapses to icons or a hidden drawer; margins reduce to 1rem.
  - *Mobile (<768px):* Single column layout; sidebar accessed via hamburger menu.

## Elevation & Depth
Depth is used sparingly to maintain a modern, flat-profile aesthetic.
- **Level 1 (Cards):** Use `shadow-sm` (0 1px 2px 0 rgba(0, 0, 0, 0.05)) to subtly lift content containers from the light gray background.
- **Level 2 (Modals/Popovers):** Use a more pronounced shadow with a wider blur to indicate temporary overlay status.
- **Sidebar:** No shadow; depth is established through the high-contrast color shift between the Navy sidebar and Light Gray content area.
- **Interactive Elements:** Buttons utilize a slight inset shadow on active states to simulate a physical press.

## Shapes
The design system employs a **Soft** shape language. 
- **Cards & Inputs:** 0.25rem (4px) corner radius for a professional, crisp appearance.
- **Status Badges:** 9999px (Pill-shaped) to distinguish them from interactive buttons or data fields.
- **KPI Containers:** Circular (50% radius) containers for icons to create focal points within dashboard summaries.

## Components
- **Sidebar Items:** Default state is transparent text; Active state features a #334155 background with a 3px #2563eb border on the *left* side (for RTL, this border sits on the inner edge of the sidebar).
- **Cards:** White background (#FFFFFF), `shadow-sm`, and 0.25rem padding. Header sections within cards should have a subtle bottom border.
- **Pill Badges:** Small, all-caps text with a light tinted background and dark text color (e.g., Success Badge: Light Green bg, Dark Green text).
- **Buttons:** 
  - *Primary:* Solid Blue with white text.
  - *Ghost:* No background, blue border, used for secondary actions.
- **Input Fields:** White background, 1px border (#e2e8f0), focuses to a 2px blue ring.
- **KPI Widgets:** Features a circular icon container on the right (RTL), followed by the metric title and the large numeric value.
- **Data Tables:** Clean rows with 1px horizontal dividers; hover states should utilize a very faint gray (#f1f5f9) highlight.