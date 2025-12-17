<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Card $card)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $card->comments()->create([
            'content' => $request->content,
            'user_id' => auth()->id(),
        ]);

        return back();
    }
}
