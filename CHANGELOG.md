# Changelog

## 1.0.0 - 2026-10-19 - Initial Release

### Elementor table widget

- Native Elementor **Table Setup**, **Header Row**, and collapsible **Table Rows** sections, based on the approved 1.7.0 interface.
- Select 1–6 columns, with header fields and body-row input visibility following the selected column count.
- Generic column headings and two initial, editable, collapsible body rows; add, duplicate, reorder, and remove rows with Elementor repeater controls.
- Optional visible caption and first-column row headings for semantic HTML output.
- Small-screen horizontal-scroll or wrap-content presentation.
- Percentage-based column widths (auto-sized when unset or invalid), header/body typography, colors, borders, alignment, and responsive cell padding.

### Plugin infrastructure

- Standard BRS GitHub release updater, plugin details support, and local admin changelog viewer.
- GitHub Actions workflow to build and publish versioned release assets.
- `README.md`, `CHANGELOG.md`, and Composer development configuration.

This initial release restores the 1.7.0 widget implementation rather than the experimental 1.8.0 custom table editor.
