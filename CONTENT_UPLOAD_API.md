# Content Upload API Documentation

## Overview
This API allows external systems (like browser extensions) to upload educational content including videos, courses, subtitles, and tool metadata to the platform.

---

## Endpoint

```
POST /api/external/content
```

---

## Authentication

**Required Header:**
```
X-API-Key: {your_openai_api_key}
```

**Note:** The API key is validated against the `OPENAI_API_KEY` in your `.env` file.

---

## Request Format

**Content-Type:** `application/json`

---

## Parameters

### ✅ Required Fields

| Parameter | Type | Description | Example |
|-----------|------|-------------|---------|
| `title` | string (max 255) | Video/lesson title | `"Learn ChatGPT Basics"` |
| `video_url` | string | Video URL (can be YouTube, S3, etc.) | `"https://s3.amazonaws.com/bucket/video.mp4"` |
| `skill_level` | enum | One of: `Beginner`, `Intermediate`, `Advanced` | `"Beginner"` |

### 📝 Content Information (Optional)

| Parameter | Type | Description | Example |
|-----------|------|-------------|---------|
| `author_name` | string (max 255) | Content creator/author name | `"John Doe"` |
| `description` | text | Detailed description of the content | `"This lesson teaches..."` |
| `tags` | string | Comma-separated tags | `"ai,automation,productivity"` |
| `duration_seconds` | integer | Video duration in seconds | `600` |
| `video_duration` | string | Alternative duration format | `"10m"` or `"PT10M"` |
| `type` | enum | One of: `video`, `article`, `course` | `"video"` |
| `status` | enum | One of: `active`, `inactive` | `"active"` |
| `is_featured` | boolean | Mark as featured content | `true` |
| `reference_url` | string | Original source URL | `"https://youtube.com/watch?v=..."` |

### 🖼️ Media Files

| Parameter | Type | Description | Example |
|-----------|------|-------------|---------|
| `thumbnail_base64` | string | Base64 encoded thumbnail image | `"data:image/jpeg;base64,/9j/4AAQ..."` |
| `video_url_ar` | string (URL) | Arabic version video URL | `"https://s3.amazonaws.com/bucket/video-ar.mp4"` |

### 📄 Subtitle Files

Subtitles can be provided in **3 formats**:

1. **File Upload** (multipart/form-data)
2. **URL String** (direct link to .srt file)
3. **Base64 String** (base64 encoded SRT content)

| Parameter | Type | Description | Example |
|-----------|------|-------------|---------|
| `srt_file_en` | string/file/url | English subtitle file | `"https://s3.amazonaws.com/bucket/subtitle.srt"` |
| `srt_file_ar` | string/file/url | Arabic subtitle file | `"data:text/plain;base64,MSAwMDowM..."` |

### 🏷️ Category & Classification

| Parameter | Type | Description | Example |
|-----------|------|-------------|---------|
| `category` | string | Category name (legacy) | `"AI Tools"` |
| `category_id` | integer | Category ID (preferred) | `5` |

### 🎓 Course Management

| Parameter | Type | Description | Example |
|-----------|------|-------------|---------|
| `is_individual` | boolean | If `false`, creates/links to course | `false` |
| `course_name` | string (max 255) | Course name (auto-creates if not exists) | `"Complete ChatGPT Mastery"` |
| `course_id` | integer | Existing course ID | `12` |
| `course_thumbnail` | string (URL) | Course thumbnail URL | `"https://example.com/course.jpg"` |
| `section_part_label` | string (max 255) | Section/part label within course | `"Section 1: Introduction"` |
| `sort_order` | integer | Order within course | `1` |

### 🛠️ Tool Integration

| Parameter | Type | Description | Example |
|-----------|------|-------------|---------|
| `connected_tools` | array/string | Tool names (array or comma-separated) | `["ChatGPT", "Notion"]` or `"ChatGPT,Notion"` |
| `tool_metadata` | array | Tool metadata with logos (see below) | See Tool Metadata section |

---

## 🛠️ Tool Metadata Structure

The `tool_metadata` parameter allows you to create/update tools with logos in a single request.

**Format:**
```json
{
  "tool_metadata": [
    {
      "name": "ChatGPT",
      "description": "ChatGPT lessons and workflows",
      "logo_base64": "data:image/png;base64,iVBORw0KGgoAAAANSUhEUg..."
    },
    {
      "name": "Notion",
      "description": "Notion productivity guides",
      "logo_base64": "data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0c..."
    }
  ]
}
```

**Tool Metadata Fields:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `name` | string | ✅ Yes | Tool name (used for matching) |
| `description` | string | ❌ Optional | Tool description |
| `logo_base64` | string | ❌ Optional | Base64 encoded logo image |

**Behavior:**
- If tool exists: Updates description and logo (if provided)
- If tool doesn't exist: Creates new tool with `status=active`
- Tool names are automatically added to `connected_tools` array
- Logo uploads to: `tool-logos/{slug}-{timestamp}.{ext}`
- Errors in tool processing are logged but don't stop content upload

---

## 📋 Complete Request Example

### Standalone Video (Individual Content)

```json
{
  "title": "Master Email Automation with ChatGPT",
  "author_name": "Jane Smith",
  "video_url": "https://s3.amazonaws.com/daleel-bucket/videos/chatgpt-email.mp4",
  "skill_level": "Intermediate",
  "description": "Learn how to automate your email responses using ChatGPT and save 2 hours daily.",
  "tags": "chatgpt,email,automation,productivity",
  "duration_seconds": 900,
  "type": "video",
  "status": "active",
  "is_featured": false,
  "is_individual": true,
  "category_id": 3,
  "thumbnail_base64": "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD...",
  "srt_file_en": "https://s3.amazonaws.com/daleel-bucket/subtitles/chatgpt-email-en.srt",
  "srt_file_ar": "https://s3.amazonaws.com/daleel-bucket/subtitles/chatgpt-email-ar.srt",
  "connected_tools": ["ChatGPT", "Gmail"],
  "tool_metadata": [
    {
      "name": "ChatGPT",
      "description": "ChatGPT AI assistant tutorials",
      "logo_base64": "data:image/png;base64,iVBORw0KGgo..."
    },
    {
      "name": "Gmail",
      "description": "Gmail email automation guides",
      "logo_base64": "data:image/svg+xml;base64,PHN2ZyB4bWxucz..."
    }
  ]
}
```

### Course Lesson (Part of a Course)

```json
{
  "title": "Introduction to ChatGPT",
  "author_name": "John Instructor",
  "video_url": "https://s3.amazonaws.com/daleel-bucket/courses/chatgpt-course/lesson-01.mp4",
  "skill_level": "Beginner",
  "description": "First lesson introducing ChatGPT fundamentals",
  "duration_seconds": 1200,
  "type": "video",
  "status": "active",
  "is_individual": false,
  "course_name": "Complete ChatGPT Mastery",
  "course_thumbnail": "https://example.com/chatgpt-course-thumbnail.jpg",
  "section_part_label": "Section 1: Getting Started",
  "sort_order": 1,
  "category_id": 3,
  "thumbnail_base64": "data:image/jpeg;base64,/9j/4AAQSkZJRg...",
  "srt_file_en": "https://s3.amazonaws.com/daleel-bucket/subtitles/lesson-01-en.srt",
  "srt_file_ar": "https://s3.amazonaws.com/daleel-bucket/subtitles/lesson-01-ar.srt",
  "connected_tools": ["ChatGPT"],
  "tool_metadata": [
    {
      "name": "ChatGPT",
      "description": "ChatGPT tutorials and guides",
      "logo_base64": "data:image/png;base64,iVBORw0KGgo..."
    }
  ]
}
```

---

## 📤 Response Format

### ✅ Success Response (201 Created)

```json
{
  "success": true,
  "message": "Content synchronized successfully.",
  "content_id": 42,
  "thumbnail_url": "https://s3.amazonaws.com/daleel-bucket/thumbnails/master-email-automation-1234567890.jpg",
  "view_url": "https://yourdomain.com/learn/watch/42"
}
```

### ❌ Error Responses

**401 Unauthorized**
```json
{
  "error": "Unauthorized content request."
}
```

**422 Validation Error**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "title": ["The title field is required."],
    "skill_level": ["The skill level must be one of: Beginner, Intermediate, Advanced."]
  }
}
```

**500 Internal Server Error**
```json
{
  "error": "An error occurred while processing the request."
}
```

---

## 🎯 Business Logic

### Course Auto-Creation

When `is_individual = false` and `course_name` is provided:

1. **Course doesn't exist**: Creates new course with:
   - `title` = `course_name`
   - `category_id` = provided category
   - `thumbnail` = `course_thumbnail` (if provided)
   - `status` = `active`
   - `connected_tools` = merged from all lessons

2. **Course exists**: 
   - Links content to existing course
   - Merges `connected_tools` with existing course tools

### Tool Processing

1. Processes `tool_metadata` array first
2. Creates/updates tools in database
3. Uploads logos to storage (S3 or public)
4. Merges tool names from metadata with `connected_tools`
5. Final `connected_tools` array is stored on content

### Subtitle Handling

Accepts 3 formats:
1. **Direct URL**: Stores URL as-is
2. **Base64 String**: Decodes, uploads to storage, stores URL
3. **File Upload**: Uploads to storage, stores URL

### Thumbnail Processing

- Accepts base64 encoded images
- Detects image type from data URI
- Uploads to: `thumbnails/{slug}-{timestamp}.{ext}`
- Stores final URL in database

---

## 🔧 Storage Configuration

Files are uploaded based on `filesystems.default` config:

- **S3 Mode**: Uploads to configured S3 bucket
- **Local Mode**: Uploads to `storage/app/public`

**Storage Paths:**
- Thumbnails: `thumbnails/`
- Subtitles: `subtitles/{YYYY-MM-DD}/`
- Tool Logos: `tool-logos/`

---

## 📊 Database Tables Affected

### Primary Tables:
1. **contents** - Main content record
2. **courses** - Auto-created/updated courses
3. **tools** - Created/updated tools with logos

### Related Tables:
- **categories** - Referenced via `category_id`

---

## 🚨 Important Notes

1. **API Key**: Uses `OPENAI_API_KEY` from `.env` for authentication
2. **Course Creation**: Courses are auto-created by `course_name` (not ID)
3. **Tool Deduplication**: Tool names are case-sensitive and deduplicated
4. **Slug Generation**: Auto-generates slug from title + random number
5. **Language Detection**: Sets `language = 'both'` if `video_url_ar` provided
6. **Status Default**: Defaults to `active` if not specified
7. **Type Default**: Defaults to `video` if not specified
8. **Base64 Size**: Be mindful of JSON payload size limits (thumbnails ~500KB)

---

## 🐛 Debugging

All requests are logged to Laravel logs:
```
storage/logs/laravel.log
```

Log entries include:
- Request title
- Subtitle file presence
- All input keys
- Content creation success
- Any upload errors

---

## 📞 Support

For issues or questions, check:
- Laravel logs: `storage/logs/laravel.log`
- Server logs for upload errors
- Database for created records

---

## 🔄 Migration Notes

**Current Implementation Status:**
- ✅ Content upload with author_name
- ✅ Course auto-creation
- ✅ Tool metadata processing
- ✅ Tool logo upload (base64)
- ✅ Subtitle upload (URL/base64)
- ✅ Thumbnail upload (base64)
- ⚠️ Tool metadata array validation added but processing code pending

**Recommended Next Steps:**
1. Add tool metadata processing in controller
2. Test tool logo uploads
3. Add course thumbnail base64 support (currently URL only)
