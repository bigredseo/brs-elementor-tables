# BRS Elementor Tables

Visual Table Editor for Elementor

## Requirements

- WordPress 6.5 or later
- PHP 8.3 or later

## Installation

1. Download the latest release ZIP from the [Releases page](https://github.com/bigredseo/brs-elementor-tables/releases).
2. In WordPress, go to **Plugins > Add New > Upload Plugin** and select the ZIP.
3. Activate the plugin.

Once activated, updates are delivered automatically from GitHub releases when
the repository is public. A **Changelog** link is added to the plugin row on the
Installed Plugins screen.

## Usage

Describe the plugin's settings and behaviour here. This file is what appears in
the WordPress **View details** modal, so write it for the person installing the
plugin rather than for the developer.

---

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
