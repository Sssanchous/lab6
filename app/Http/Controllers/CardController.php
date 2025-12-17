<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CardController extends Controller
{
    public function index()
    {
        $cards = auth()->user()
            ->cards()
            ->latest()
            ->get();

        if (request()->wantsJson()) {
            return response()->json($cards);
        }

        return view('cards.index', [
            'cards' => $cards,
            'mode' => 'mine',
        ]);
    }

    public function all()
    {
        $cards = Card::with('user')->latest()->get();

        if (request()->wantsJson()) {
            return response()->json($cards);
        }

        return view('cards.index', [
            'cards' => $cards,
            'mode' => 'all',
        ]);
    }

    public function friendsFeed()
    {
        $friendIds = auth()->user()
            ->acceptedFriends()
            ->pluck('users.id');

        $cards = Card::whereIn('user_id', $friendIds)
            ->with('user')
            ->latest()
            ->get();

        if (request()->wantsJson()) {
            return response()->json($cards);
        }

        return view('cards.index', [
            'cards' => $cards,
            'mode' => 'friends',
        ]);
    }

    public function create()
    {
        return view('cards.form', [
            'card' => new Card(),
            'isEdit' => false,
            'categories' => Card::CATEGORIES,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|in:' . implode(',', array_keys(Card::CATEGORIES)),
            'description' => 'required|string',
            'image' => request()->wantsJson() ? 'nullable|image|max:51200' : 'required|image|max:51200',
            'fun_fact_content' => 'nullable|string',
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'horsepower' => 'required|integer|min:1',
            'price' => 'nullable|numeric|min:0',
        ]);

        if ($request->hasFile('image')) {
            $path = public_path('images');
            if (!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
            }

            $filename = time() . '_' . Str::slug(
                $request->file('image')->getClientOriginalName(),
                '_'
            );

            $request->file('image')->move($path, $filename);
            $validated['image_path'] = 'images/' . $filename;
        }

        $card = auth()->user()->cards()->create($validated);

        if (request()->wantsJson()) {
            return response()->json($card, 201);
        }

        return redirect()->route('cards.index')->with('success', 'Машина успешно добавлена!');
    }

    public function show(Card $card)
    {
        $card->load(['user', 'comments.user']);

        if (request()->wantsJson()) {
            return response()->json($card);
        }

        return view('cards.show', compact('card'));
    }

    public function edit(Card $card)
    {
        if (!auth()->user()->is_admin && $card->user_id !== auth()->id()) {
            abort(403);
        }

        return view('cards.form', [
            'card' => $card,
            'isEdit' => true,
            'categories' => Card::CATEGORIES,
        ]);
    }

    public function update(Request $request, Card $card)
    {
        if (!auth()->user()->is_admin && $card->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|in:' . implode(',', array_keys(Card::CATEGORIES)),
            'description' => 'required|string',
            'image' => 'nullable|image|max:51200',
            'fun_fact_content' => 'nullable|string',
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'horsepower' => 'required|integer|min:1',
            'price' => 'nullable|numeric|min:0',
        ]);

        if ($request->hasFile('image')) {
            if ($card->image_path && File::exists(public_path($card->image_path))) {
                File::delete(public_path($card->image_path));
            }

            $filename = time() . '_' . Str::slug(
                $request->file('image')->getClientOriginalName(),
                '_'
            );

            $request->file('image')->move(public_path('images'), $filename);
            $validated['image_path'] = 'images/' . $filename;
        }

        $card->update($validated);

        if (request()->wantsJson()) {
            return response()->json($card);
        }

        return redirect()->route('cards.index')->with('success', 'Машина успешно обновлена!');
    }

    public function destroy(Card $card)
    {
        if (!auth()->user()->is_admin && $card->user_id !== auth()->id()) {
            abort(403);
        }

        $card->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'deleted']);
        }

        return redirect()->route('cards.index')->with('success', 'Машина перемещена в корзину.');
    }

    public function trash()
    {
        abort_unless(auth()->user()->is_admin, 403);

        $cards = Card::onlyTrashed()->with('user')->get();

        return view('cards.trash', compact('cards'));
    }

    public function restore($id)
    {
        abort_unless(auth()->user()->is_admin, 403);

        Card::onlyTrashed()->findOrFail($id)->restore();

        return redirect()->route('cards.index');
    }

    public function forceDelete($id)
    {
        abort_unless(auth()->user()->is_admin, 403);

        $card = Card::onlyTrashed()->findOrFail($id);

        if ($card->image_path && File::exists(public_path($card->image_path))) {
            File::delete(public_path($card->image_path));
        }

        $card->forceDelete();

        return redirect()->route('cards.trash');
    }
}
