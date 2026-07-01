<?php

namespace App\Http\Controllers\Api\Extension;

use App\Http\Controllers\Controller;
use App\Models\BrowsingHistory;
use App\Models\Content;
use App\Models\ExtensionDevice;
use App\Models\UserRoadmap;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SuggestionController extends Controller
{
    public function suggest(Request $request): JsonResponse
    {
        $user = null;
        $deviceId = $request->header('X-Extension-Device-Id') ?? $request->query('device_id');

        if ($deviceId) {
            $device = ExtensionDevice::where('device_id', $deviceId)
                ->whereNull('revoked_at')
                ->with('user')
                ->first();
            if ($device && $device->user) {
                $user = $device->user;
            }
        }

        if ($user) {
            $roadmap = UserRoadmap::where('user_id', $user->id)
                ->inRandomOrder()
                ->first();

            if ($roadmap) {
                return response()->json([
                    'type' => 'roadmap',
                    'data' => [
                        'id'         => $roadmap->id,
                        'title'      => $roadmap->title,
                        'goal'       => $roadmap->goal,
                        'focus'      => $roadmap->focus,
                        'level'      => $roadmap->level,
                        'progress'   => $roadmap->progress,
                        'curriculum' => $roadmap->curriculum,
                        'created_at' => $roadmap->created_at,
                        'url'        => url('/roadmap/' . $roadmap->id),
                    ],
                ]);
            }

            $videos = $this->personalizedVideos($user);

            if ($videos->isNotEmpty()) {
                return $this->videosResponse($videos);
            }
        }

        $videos = Content::active()->where('type', 'video')->inRandomOrder()->limit(6)->get();

        if ($videos->isEmpty()) {
            return response()->json(['message' => 'No content available yet.'], 404);
        }

        return $this->videosResponse($videos);
    }

    private function personalizedVideos($user)
    {
        $visitedDomains = BrowsingHistory::where('user_id', $user->id)
            ->distinct()
            ->pluck('domain')
            ->toArray();

        $domainToToolsMap = [
            'chatgpt.com'        => ['chatgpt', 'openai'],
            'youtube.com'        => ['youtube'],
            'slack.com'          => ['slack'],
            'notion.so'          => ['notion'],
            'github.com'         => ['github'],
            'figma.com'          => ['figma'],
            'canva.com'          => ['canva'],
            'linkedin.com'       => ['linkedin'],
            'gmail.com'          => ['gmail', 'google'],
            'sheets.google.com'  => ['sheets', 'google sheets'],
            'instagram.com'      => ['instagram'],
            'facebook.com'       => ['facebook', 'meta'],
        ];

        $behaviorTags = [];
        foreach ($visitedDomains as $domain) {
            $domainClean = strtolower(trim($domain));
            if (isset($domainToToolsMap[$domainClean])) {
                $behaviorTags = array_merge($behaviorTags, $domainToToolsMap[$domainClean]);
            }
        }
        $behaviorTags = array_unique($behaviorTags);

        $watchedIds = $user->videoProgress()->pluck('content_id');

        $videos = collect();

        if (!empty($behaviorTags)) {
            $videos = Content::active()
                ->where('type', 'video')
                ->where(function ($q) use ($behaviorTags) {
                    foreach ($behaviorTags as $tag) {
                        $q->orWhere('tags', 'like', "%{$tag}%")
                          ->orWhere('connected_tools', 'like', "%{$tag}%");
                    }
                })
                ->whereNotIn('id', $watchedIds)
                ->inRandomOrder()
                ->limit(6)
                ->get();
        }

        if ($videos->count() < 6) {
            $interests = $user->interests ?? [];
            if (!empty($interests)) {
                $existingIds = $videos->pluck('id');
                $interestVideos = Content::active()
                    ->where('type', 'video')
                    ->where(function ($q) use ($interests) {
                        foreach ($interests as $interest) {
                            $q->orWhere('category', 'like', "%{$interest}%")
                              ->orWhere('tags', 'like', "%{$interest}%");
                        }
                    })
                    ->whereNotIn('id', $watchedIds)
                    ->whereNotIn('id', $existingIds)
                    ->inRandomOrder()
                    ->limit(6 - $videos->count())
                    ->get();

                $videos = $videos->merge($interestVideos);
            }
        }

        if ($videos->count() < 6) {
            $existingIds = $videos->pluck('id');
            $fallback = Content::active()
                ->where('type', 'video')
                ->whereNotIn('id', $watchedIds)
                ->whereNotIn('id', $existingIds)
                ->inRandomOrder()
                ->limit(6 - $videos->count())
                ->get();

            $videos = $videos->merge($fallback);
        }

        if ($videos->count() < 6) {
            $existingIds = $videos->pluck('id');
            $fallback = Content::active()
                ->where('type', 'video')
                ->whereNotIn('id', $existingIds)
                ->inRandomOrder()
                ->limit(6 - $videos->count())
                ->get();

            $videos = $videos->merge($fallback);
        }

        return $videos;
    }

    private function videosResponse($videos): JsonResponse
    {
        return response()->json([
            'type'  => 'videos',
            'count' => $videos->count(),
            'data'  => $videos->map(fn($v) => [
                'id'          => $v->id,
                'title'       => $v->title,
                'description' => $v->description,
                'category'    => $v->category,
                'skill_level' => $v->skill_level,
                'tags'        => $v->tags,
                'thumbnail'   => $v->thumbnail_url,
                'youtube_id'  => $v->youtube_id,
                'duration'    => $v->duration_label,
                'url'         => url('/learn/' . $v->id),
            ]),
        ]);
    }
}
