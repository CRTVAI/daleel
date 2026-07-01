
## `GET /extension/suggest`

Returns personalized suggestions (roadmap or videos) for the authenticated extension user.

### Headers

| Header | Required | Description |
|--------|----------|-------------|
| `X-Extension-Device-Id` | No* | Device UUID linked to a user account |

> `X-Extension-Device-Id` or `?device_id=` query param is needed for personalized results. Without it, random public videos are returned.

### Response — Roadmap

```json
{
  "type": "roadmap",
  "data": {
    "id": 1,
    "title": "Learn JavaScript",
    "goal": "Become proficient in JavaScript",
    "focus": "Frontend Development",
    "level": "beginner",
    "progress": 45,
    "curriculum": "[]",
    "url": "https://your-domain.com/roadmap/1"
  }
}
```

### Response — Videos

```json
{
  "type": "videos",
  "count": 6,
  "data": [
    {
      "id": 10,
      "title": "Getting Started with React",
      "description": "Learn the basics of React",
      "category": "frontend",
      "skill_level": "beginner",
      "tags": "react, javascript, frontend",
      "thumbnail": "https://img.youtube.com/vi/abc123/default.jpg",
      "youtube_id": "abc123",
      "duration": "12:34",
      "url": "https://your-domain.com/learn/10"
    }
  ]
}
```

### Error — 404

```json
{
  "message": "No content available yet."
}
```

### Personalization Priority

1. **Roadmap** — if user has a roadmap, return it
2. **Behavior-based videos** — match browsing history domains to tool tags
3. **Interest-based videos** — fill remaining slots from user interests
4. **Fallback** — random active videos
5. **No device** — 6 random public videos
