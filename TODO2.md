# Task: Make Receipts Design Like Categories

## Overview
Update the receipts views (index.blade.php, create.blade.php, info.blade.php) to match the design structure of the categories views. This includes changing HTML classes, layout structure, and ensuring consistency in Bootstrap components like cards, page-title-box, etc., while keeping the content and translations intact.

## Steps
1. Update `resources/views/dashbord/receipts/index.blade.php` to use container-fluid, page-title-box, card layout like categories/index.blade.php.
2. Update `resources/views/dashbord/receipts/create.blade.php` to use container-fluid, row card, page-title-box like categories/create.blade.php.
3. Update `resources/views/dashbord/receipts/info.blade.php` to use container-fluid, row card, page-title-box like categories/create.blade.php (since it's a detail view).

## Dependent Files
- resources/views/dashbord/receipts/index.blade.php
- resources/views/dashbord/receipts/create.blade.php
- resources/views/dashbord/receipts/info.blade.php

## Followup Steps
- Test the views in the browser to ensure the design matches categories.
- If any language keys need adjustment for consistency, update the receipt.php language files.
