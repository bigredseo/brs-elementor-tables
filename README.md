# BRS Elementor Tables

An Elementor widget for building accessible, responsive HTML tables with standard Elementor sidebar controls. Developed by [Big Red SEO](https://www.bigredseo.com/).

## Requirements

- WordPress 6.5+
- PHP 8.3+
- Elementor plugin activated

## Installation

Upload the `brs-elementor-tables-1.0.0.zip` release archive in **Plugins → Add New → Upload Plugin**, then activate. Open an Elementor page and locate the **BRS Table** widget under General.

## Using the editor

### Content → Table Setup

Select **Number of Columns** (1–6; defaults to 3). This restored release intentionally uses the approved 1.7.0 column-count workflow, **not** the experimental Add Column editor introduced in 1.8.0. Reducing the count hides unused fields without deleting their saved values.

Additional controls:

- **Table Caption (optional)** — visible table caption.
- **First column is a row heading** — outputs `<th scope="row">` for the first cell in each body row.
- **Small Screens** — horizontal scrolling (default), or allow content wrapping within the table.

### Content → Header Row

Edit **Column 1**, **Column 2**, etc. Only fields for the selected number of columns appear. Their default text is the corresponding column name.

### Content → Table Rows

Each body row is an Elementor repeater item: expand to edit its cells, reorder by dragging, duplicate or delete using Elementor's repeater controls, and use **Add Row** to insert rows. The first two rows default to **Row 1** and **Row 2**; these are editor-only names. Individual inputs are labeled **Item 1**, **Item 2**, etc. and start blank. The included panel-only script hides unused cell fields according to the current column count. Output is limited to 250 body rows.

## Styling

### Style → Layout & Columns

Choose fixed widths for each currently active column, in percentages. For two columns, enter `25` and `75`, or `50` and `50`. **Every active column must have a positive width and the total must equal 100%**; otherwise the table uses automatic widths. These width settings are **not** device-specific in this restored version.

### Style → Table Appearance

- Header background and text colors
- Body text color
- Alternating row background color
- Border color
- Responsive cell padding (px, em, rem)
- Cell text alignment
- Separate header/body typography

### Small-screen presentation

The Content setting **Small Screens** controls horizontal overflow or wrapping. The restored 1.7.0 implementation **does not** include stacked mobile cards or independent desktop/tablet/mobile column widths. These were introduced in the later, unapproved custom-editor implementation and have not been retained.

## Front-end output

The widget renders an HTML `<table>` inside a scrollable/wrapping container with `<thead>`, `<tbody>`, column heading scopes, optional row heading scopes, and an optional `<caption>`. Cell content is escaped as plain text. The container is keyboard focusable to assist navigating scrollable tables.

## Plugin management

The standard BRS helper classes provide a **Changelog** link in WordPress's Installed Plugins screen, a plugin details modal, and GitHub-based updates for a **public** GitHub repository with a published versioned ZIP release. The `Update URI` points to `https://github.com/bigredseo/brs-elementor-tables`.

## Development and GitHub release

Development dependencies are declared in Composer; `composer install` restores the local `vendor` directory. `vendor`, `stubs`, tests, and development configuration are excluded by `.github/workflows/release-plugin.yml` from the WordPress installation ZIP.

1. Commit source files to the `main` branch.
2. Verify the plugin `Version` header and `BRS_ELEMENTOR_TABLES_VERSION` constant match, and update `CHANGELOG.md`.
3. Run **Actions → Release BRS Elementor Tables → Run workflow**.
4. Confirm GitHub creates tag `v1.0.0` with asset `brs-elementor-tables-1.0.0.zip`.

**Release QA:** PHP/JS syntax checks are not a substitute for testing widget editing, save/reopen, and responsive presentation in a real Elementor installation.

## License

GPL-2.0-or-later (consistent with standard BRS helper headers).
