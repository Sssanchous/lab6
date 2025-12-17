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
        $user = auth()->user();

        $comments = $card->comments()
            ->with(['user', 'card'])
            ->get()
            ->map(function ($comment) use ($user) {
                return [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'created_at' => $comment->created_at,
                    'user' => $comment->user,
                    'card' => $comment->card,
                    'is_friend' => $user->isFriendWith($comment->card->user),
                ];
            });

        return response()->json($comments);
    }

    public function store(Request $request, Card $card)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $comment = $card->comments()->create([
            'content' => $request->content,
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

        $comment->update([
            'content' => $request->content,
        ]);

        return response()->json($comment);
    }
}
