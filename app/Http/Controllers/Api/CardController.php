<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CardResource;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CardController extends Controller
{
    public function index()
    {
        $cards = Card::with(['user', 'comments.user'])->latest()->paginate(10);
        return CardResource::collection($cards);
    }

    public function show(Card $card)
    {
        $card->load(['user', 'comments.user']);
        return new CardResource($card);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:sedan,hatchback,suv,coupe,other',
            'description' => 'required|string',
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'horsepower' => 'required|integer|min:1',
            'price' => 'nullable|numeric|min:0',
        ]);

        $validated['user_id'] = Auth::id();
        $card = Card::create($validated);

        return new CardResource($card->load(['user']));
    }

    public function update(Request $request, Card $card)
    {
        if ($card->user_id !== Auth::id()) {
            return response()->json(['error' => 'Доступ запрещён'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'category' => 'sometimes|required|in:sedan,hatchback,suv,coupe,other',
            'description' => 'sometimes|required|string',
            'brand' => 'sometimes|required|string|max:100',
            'model' => 'sometimes|required|string|max:100',
            'year' => 'sometimes|required|integer|min:1900|max:' . (date('Y') + 1),
            'horsepower' => 'sometimes|required|integer|min:1',
            'price' => 'nullable|numeric|min:0',
        ]);

        $card->update($validated);
        return new CardResource($card->load(['user']));
    }

    public function destroy(Card $card)
    {
        if ($card->user_id !== Auth::id()) {
            return response()->json(['error' => 'Доступ запрещён'], 403);
        }
        $card->delete();
        return response()->json(['message' => 'Карточка удалена']);
    }

    public function myCards()
    {
        $cards = Card::where('user_id', Auth::id())
            ->with(['user', 'comments.user'])
            ->latest()
            ->paginate(10);
        return CardResource::collection($cards);
    }

    public function byCategory($category)
    {
        if (!in_array($category, ['sedan', 'hatchback', 'suv', 'coupe', 'other'])) {
            return response()->json(['message' => 'Категория не найдена'], 404);
        }
        $cards = Card::where('category', $category)
            ->with(['user', 'comments.user'])
            ->latest()
            ->paginate(10);
        return CardResource::collection($cards);
    }

    public function search(Request $request)
    {
        $q = $request->input('q');
        if (!$q) {
            return response()->json(['message' => 'Укажите параметр q'], 400);
        }

        $cards = Card::where(function ($query) use ($q) {
            $query->where('title', 'LIKE', "%$q%")
                  ->orWhere('brand', 'LIKE', "%$q%")
                  ->orWhere('model', 'LIKE', "%$q%")
                  ->orWhere('description', 'LIKE', "%$q%");
        })->with(['user', 'comments.user'])->latest()->paginate(10);

        return CardResource::collection($cards);
    }
}