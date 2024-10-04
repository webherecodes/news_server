<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\NewsUtilities;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class NewsUtilController extends Controller
{
    public function likeNews(Request $request,News $news)
{

    $userId = $request->user()->id;  // Get the current user ID

    // Fetch or create the related NewsUtilities for the news post
    $utils = $news->utils ?: NewsUtilities::create(['news_id' => $news->id]);
    // Remove the user from the dislikes array if they have disliked before
    $dislikes = array_diff($utils->dislikes ?? [], [$userId]);

    // Add the user to the likes array if they haven't liked already
    $likes = $utils->likes ?? [];
    if (!in_array($userId, $likes)) {
        $likes[] = $userId;
    }
    else{
        $likes = array_diff($utils->likes ?? [], [$userId]);
    }

    // Update the utils with the new likes and dislikes
    $utils->update([
        'news_id' => $news->id,
        'likes' => $likes,
        'dislikes' => $dislikes,
    ]);

    return response()->json(['message' => 'Post liked successfully.', 'likes' => $likes, 'dislikes' => $dislikes]);
}

public function dislikeNews(Request $request,News $news)
{
    $userId = $request->user()->id;  // Get the current user ID

    // Fetch or create the related NewsUtilities for the news post
    $utils = $news->utils ?: NewsUtilities::create(['news_id' => $news->id]);
    // Remove the user from the likes array if they have liked before
    $likes = array_diff($utils->likes ?? [], [$userId]);

    // Add the user to the dislikes array if they haven't disliked already
    $dislikes = $utils->dislikes ?? [];
    if (!in_array($userId, $dislikes)) {
        $dislikes[] = $userId;
    }
    else{
        $dislikes = array_diff($utils->dislikes ?? [], [$userId]);
    }

    // Update the utils with the new likes and dislikes
    $utils->update([
        'news_id' => $news->id,
        'likes' => $likes,
        'dislikes' => $dislikes,
    ]);

    return response()->json(['message' => 'Post disliked successfully.', 'likes' => $likes, 'dislikes' => $dislikes]);
}

}
