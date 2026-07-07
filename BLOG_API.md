# Blog API

Base URL: `http://localhost:8000/api`

---

## List Published Posts

```bash
curl -X GET "http://localhost:8000/api/blogs"
```

With filters:

```bash
# Search by title/content
curl -X GET "http://localhost:8000/api/blogs?search=laravel"

# Filter by category
curl -X GET "http://localhost:8000/api/blogs?category=Guides"

# Paginate (default 10 per page)
curl -X GET "http://localhost:8000/api/blogs?per_page=5&page=2"

# Combine filters
curl -X GET "http://localhost:8000/api/blogs?search=ai&category=Insights&per_page=3"
```

---

## Get Single Post (by ID or Slug)

```bash
# By ID
curl -X GET "http://localhost:8000/api/blogs/1"

# By slug
curl -X GET "http://localhost:8000/api/blogs/my-blog-post-slug"
```

---

## Create a New Post

```bash
# Minimal (JSON body)
curl -X POST "http://localhost:8000/api/blogs" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "My First API Post",
    "content": "<p>Hello from curl!</p>"
  }'

# Full payload
curl -X POST "http://localhost:8000/api/blogs" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Getting Started with Laravel",
    "slug": "getting-started-laravel",
    "content": "<p>Full blog post content here...</p>",
    "excerpt": "A short summary of the post.",
    "cover_image": "https://example.com/image.jpg",
    "category": "Tutorials",
    "tags": "laravel,php,backend",
    "author_name": "John Doe",
    "read_time_minutes": 8,
    "status": "published",
    "is_featured": true,
    "meta_title": "Getting Started with Laravel - My Blog",
    "meta_description": "Learn how to get started with Laravel.",
    "meta_keywords": "laravel, tutorial, php"
  }'

# Create as draft
curl -X POST "http://localhost:8000/api/blogs" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Draft Post",
    "content": "<p>Not published yet.</p>",
    "status": "draft"
  }'
```

---

## Example Responses

### List (200)

```json
{
  "success": true,
  "message": "Blog posts retrieved successfully.",
  "data": {
    "current_page": 1,
    "data": [ ... ],
    "total": 50,
    "per_page": 10,
    "last_page": 5
  }
}
```

### Single (200)

```json
{
  "success": true,
  "data": { ... }
}
```

### Not Found (404)

```json
{
  "success": false,
  "message": "Blog post not found."
}
```

### Validation Error (422)

```json
{
  "success": false,
  "errors": {
    "title": ["The title field is required."],
    "content": ["The content field is required."]
  }
}
```
