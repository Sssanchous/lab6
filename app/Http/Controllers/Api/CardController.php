<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $cards = Card::with('user')
            ->latest()
            ->get()
            ->map(function ($card) use ($user) {
                return [
                    'id' => $card->id,
                    'title' => $card->title,
                    'brand' => $card->brand,
                    'model' => $card->model,
                    'year' => $card->year,
                    'category' => $card->category,
                    'description' => $card->description,
                    'horsepower' => $card->horsepower,
                    'price' => $card->price,
                    'user' => $card->user,
                    'is_friend' => $user->isFriendWith($card->user),
                ];
            });

        return response()->json($cards);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|in:' . implode(',', array_keys(Card::CATEGORIES)),
            'description' => 'required|string',
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer',
            'horsepower' => 'required|integer',
            'price' => 'nullable|numeric',
        ]);

        $card = auth()->user()->cards()->create($validated);

        return response()->json($card, 201);
    }

    public function update(Request $request, Card $card)
    {
        abort_unless(
            auth()->id() === $card->user_id || auth()->user()->is_admin,
            403
        );

        $card->update($request->only([
            'title',
            'category',
            'description',
            'brand',
            'model',
            'year',
            'horsepower',
            'price',
        ]));

        return response()->json($card);
    }

    public function destroy(Card $card)
    {
        abort_unless(
            auth()->id() === $card->user_id || auth()->user()->is_admin,
            403
        );

        $card->delete();

        return response()->json([
            'message' => 'Card deleted'
        ]);
    }
}
