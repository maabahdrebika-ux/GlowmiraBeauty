# Blog Fix Tasks

## Completed Tasks
- [x] Update Blog model: Change fillable to include description_ar, description_en; set $timestamps = true.
- [x] Create new migration: Add updated_at column to blogs table.
- [x] Update BlogController: Change validation to description_ar and description_en; update store/update methods to assign them separately.
- [x] Update create.blade.php: Add separate textareas for description_ar and description_en.
- [x] Update edit.blade.php: Add separate textareas for description_ar and description_en.

## Completed Followup Steps
- [x] Run php artisan migrate to apply new migration.
- [x] Test creating/editing blogs to ensure descriptions save correctly.
