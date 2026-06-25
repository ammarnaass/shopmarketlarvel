## Brand & Style
The design system is engineered for high-performance retail management, emphasizing clarity, efficiency, and reliability. The visual language follows a **Corporate / Modern** aesthetic, prioritizing data density without sacrificing readability. 

The personality is professional and systematic. It uses a structured layout to instill confidence in store owners managing inventory, orders, and customer data. The interface utilizes high-contrast navigation elements to provide clear wayfinding, while the content area remains neutral to let data and product imagery take center stage.

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