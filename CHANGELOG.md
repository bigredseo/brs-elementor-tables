# Changelog

All notable changes to BRS Elementor Tables are recorded here. The first public version is **1.0.0**; development builds were not publicly released.

## 1.0.1 - 2026-10-19 - Elementor Editor Appearance Fix

### Fixed

- Corrected unreadable accordion headings, icon buttons, and Add Row / Add Column buttons in Elementor dark mode.
- Improved field and action-button contrast, dimensions, and wrapping in the narrow editor sidebar.
- Editor-only changes; existing table settings and front-end rendering are unchanged.

## 1.0.0 - 2026-10-19 - Initial Release

### Added

- Elementor **BRS Table** widget using a custom accordion-based table structure editor.
- Header Row editing with add, delete, and move-column controls; changes synchronize body cells automatically.
- Collapsible body rows with add, move up/down, duplicate, and delete controls.
- Two-column and two-row starting table; generic **Column 1 / Column 2**, **Row 1 / Row 2**, and **Item 1 / Item 2** field defaults.
- Automatic per-row field counts matching the defined header columns (up to 12 columns and 250 rows).
- Optional caption and semantic first-column row headers.
- Responsive percentage column widths for Elementor desktop, tablet, and mobile controls.
- Tablet and mobile display choices: horizontally scrollable table or stacked, full-width labeled cells.
- Responsive cell padding and text alignment; table header/body typography and color settings; alternate row backgrounds and borders.
- Semantic HTML table output with column headers, optional caption, and keyboard-focusable scroll container.
- Big Red SEO shared WordPress admin changelog viewer, GitHub plugin-information details, and public GitHub release updater.
- GitHub Actions release workflow building a versioned ZIP and publishing a matching GitHub release.
- Project documentation and initial public release packaging.
