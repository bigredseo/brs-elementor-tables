# BRS Elementor Tables

A responsive table widget for Elementor by [Big Red SEO](https://www.bigredseo.com/).

## Requirements

- WordPress 6.5 or newer
- PHP 8.3 or newer
- Elementor active (the widget relies on Elementor's widget, controls, and editor APIs)
- JavaScript enabled in the Elementor editor

## Installation

1. In WordPress, go to **Plugins → Add New Plugin → Upload Plugin**.
2. Upload `brs-elementor-tables-1.0.0.zip` and activate it.
3. Edit a page with Elementor, find **BRS Table** under General, and add it to the canvas.
4. Edit the table using the widget's **Content → Table Content** section.

## Editing tables

### Header Row

- Starts with **Column 1** and **Column 2**.
- Edit the text in each header field.
- **Add Column** adds a heading and a matching empty cell to every existing body row.
- Move a column left using the arrow; body cells move with their column.
- Delete a column (at least one column is required). Deleting a column also deletes its associated cell data in every row.
- Maximum **12 columns**.

### Table Rows

- Starts with two rows; body cell defaults are **Item 1** and **Item 2**.
- Each **Row 1**, **Row 2**, etc. section expands to show one text input per active column.
- **Add Row** creates one blank cell per active column.
- Move a row up/down, duplicate a row, or delete it.
- Maximum **250 body rows**.
- The table data control is custom; saved edits and preview behavior should be tested in your Elementor installation before production use.

### Other content options

- **Table Caption**: Optional visible caption above the table.
- **First column is row heading**: Outputs `<th scope="row">` rather than `<td>` for the first cell of each body row.
- Cell values are plain text (HTML markup is escaped).

## Styling

### Style → Column Widths

- Up to 12 responsive percentage controls, **Column 1 (%)** through **Column 12 (%)**. Only fields corresponding to active columns appear in the editor.
- Use Elementor's device icon for desktop, tablet, and mobile settings.
- For two columns, enter `25` and `75`, or `50` and `50`.
- Leave fields empty for automatic table layout. When explicitly assigning widths, use values totaling 100% for that device.
- Column widths are table-column percentages, **not independent 100%-width stacked cards**. Use the stacked layout option below for full-width mobile cells.

### Style → Responsive Layout

- **Tablet Display**: horizontal scrolling (default) or vertically stacked cells.
- **Mobile Display**: vertically stacked cells (default) or horizontal scrolling.
- In stacked mode the column heading is displayed as a label for its corresponding body cell and each cell occupies the full available row width.
- Default CSS thresholds: **1024px** for tablet and **767px** for mobile; these are fixed CSS media queries and do not automatically follow custom Elementor breakpoints.
- **Cell Padding** is responsive with px, em, and rem units.

### Style → Appearance

- Header background and text colors
- Body text color
- Alternating row background
- Border color
- Responsive text alignment
- Separate header and body typography controls

## Front-end output and accessibility

Outputs a semantic HTML `<table>` with `<thead>`, `<tbody>`, column heading scopes and an optional `<caption>`. The container is keyboard-focusable to support horizontal overflow, with an accessible region label. In stacked mode the visual header row is hidden and body cells display header-derived labels. Verify reading order and responsive presentation with your site's accessibility requirements.

## Plugin management and updates

The installed plugin includes the shared BRS GitHub updater, helper bootloader, and an admin changelog viewer. The **Changelog** link appears under the plugin on the WordPress Plugins screen. The **View details** modal uses GitHub README information when available and otherwise local `README.md`; release notes fall back to local `CHANGELOG.md`. Updates require a **public** GitHub repository and a published release containing the exact asset `brs-elementor-tables-{version}.zip`.

Source repository: https://github.com/bigredseo/brs-elementor-tables

## Development
This repository contains the source code for the `brs-elementor-tables` WordPress
plugin.

Development dependencies are installed with Composer:

```powershell
composer install
```

The `vendor` directory is development-only. It is not committed and is excluded
from the release ZIP.

Release packaging is handled by `.github/workflows/release-plugin.yml`. Tag a
release as `X.Y.Z` and the workflow builds `brs-elementor-tables-X.Y.Z.zip`.

## Release workflow

1. Update the plugin `Version` header and `BRS_ELEMENTOR_TABLES_VERSION` constant to the same version.
2. Update `CHANGELOG.md` with a matching `## X.Y.Z - YYYY-MM-DD - Title` heading.
3. Commit to `main`, then run **Actions → Release BRS Elementor Tables → Run workflow**.
4. Verify that the workflow published tag `vX.Y.Z` and the release ZIP `brs-elementor-tables-X.Y.Z.zip`.

The workflow automatically creates the tag and GitHub release; do not create the tag manually beforehand.

## License

GPL-2.0-or-later. Copyright 2026 Big Red SEO.
