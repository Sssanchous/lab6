<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Card $card)
    {
        $comments = $card->comments()
            ->with(['user', 'card.user'])
            ->latest()
            ->get()
            ->map(function ($comment) {
                $isFriend = auth()->user()
                    ->acceptedFriends()
                    ->where('users.id', $comment->user_id)
                    ->exists();

                return [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'created_at' => $comment->created_at,
                    'user' => $comment->user,
                    'card' => $comment->card,
                    'is_friend' => $isFriend,
                ];
            });

        return response()->json($comments);
    }

    public function store(Request $request, Card $card)
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $comment = $card->comments()->create([
            'content' => $validated['content'],
            'user_id' => auth()->id(),
        ]);

        return response()->json($comment, 201);
    }

    public function update(Request $request, Comment $comment)
    {
        abort_unless(
            auth()->id() === $comment->user_id,
            403
        );

        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $comment->update($validated);

        return response()->json($comment);
    }
}
