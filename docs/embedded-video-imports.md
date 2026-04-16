# Embedded Videos Import Format

The admin importer expects an array of video records with these fields:

- `title` (required)
- `embed_url` (required)
- `slug`, `description`, `thumbnail_url`, `source_name`, `source_video_id`, `category`, `tags`, `status`, `published_at` (optional)

## CSV
Use header names exactly as below:

```csv
title,slug,description,thumbnail_url,embed_url,source_name,source_video_id,category,tags,status,published_at
Sample Video,sample-video,Description,https://partner1.com/thumb.jpg,https://partner1.com/embed/abc,Partner 1,abc,Education,"tutorial,beginner",published,2026-04-15 12:00:00
```

## JSON
Use either an array payload or an object with `data` key containing the array:

```json
[
  {
    "title": "Sample Video",
    "embed_url": "https://partner1.com/embed/abc",
    "status": "draft"
  }
]
```

## Queue
Imports are queued. Run a worker in production:

```bash
php artisan queue:work --queue=default --tries=3
```
