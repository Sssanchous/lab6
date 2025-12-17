<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Laravel\Passport\Client;
use Laravel\Passport\ClientRepository;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $personalClient = Client::where('personal_access_client', true)->first();
        $tokens = $request->user()->tokens()->orderBy('created_at', 'desc')->get();

        return view('profile.edit', [
            'user' => $request->user(),
            'personalClient' => $personalClient,
            'tokens' => $tokens,
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function createToken(Request $request): RedirectResponse
    {
        $request->validate(['token_name' => 'required|string|max:255']);

        $token = $request->user()->createToken($request->token_name);

        return redirect()->route('profile.edit')
            ->with('token_plain', $token->plainTextToken);
    }

    public function revokeToken(Request $request, $tokenId): RedirectResponse
    {
        $request->user()->tokens()->where('id', $tokenId)->delete();

        return redirect()->route('profile.edit');
    }

    
}