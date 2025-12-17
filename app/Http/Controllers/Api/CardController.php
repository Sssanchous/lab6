<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function index()
    {
        return response()->json(
            auth()->user()->cards()->latest()->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|in:' . implode(',', array_keys(\App\Models\Card::CATEGORIES)),
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

        $card->update($request->all());

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
