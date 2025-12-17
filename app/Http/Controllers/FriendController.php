<?php

namespace App\Http\Controllers;

use App\Models\User;

class FriendController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $friends = $user->acceptedFriends()
            ->withCount('cards')
            ->get();

        $incomingRequests = $user->incomingFriendRequests()
            ->whereDoesntHave('friends', function ($q) use ($user) {
                $q->where('friend_id', $user->id)
                ->where('status', 'accepted');
            })
            ->get();

        $outgoingRequests = $user->outgoingFriendRequests()->get();

        return view('friends.index', compact(
            'friends',
            'incomingRequests',
            'outgoingRequests'
        ));
    }


    public function add(User $user)
    {
        $auth = auth()->user();

        if ($auth->id === $user->id) return back();

        if ($auth->isFriendWith($user)) return back();

        if ($auth->hasSentFriendRequestTo($user)) return back();

        if ($auth->hasIncomingFriendRequestFrom($user)) {
            $auth->friends()->updateExistingPivot($user->id, [
                'status' => 'accepted'
            ]);

            $user->friends()->syncWithoutDetaching([
                $auth->id => ['status' => 'accepted']
            ]);

            return back();
        }

        $auth->friends()->syncWithoutDetaching([
            $user->id => ['status' => 'pending']
        ]);

        return back();
    }

    public function accept(User $user)
    {
        $auth = auth()->user();

        if (!$auth->hasIncomingFriendRequestFrom($user)) {
            return back();
        }

        // обновляем заявку: user -> auth
        $user->friends()->updateExistingPivot($auth->id, [
            'status' => 'accepted'
        ]);

        // создаём или обновляем обратную связь: auth -> user
        $auth->friends()->syncWithoutDetaching([
            $user->id => ['status' => 'accepted']
        ]);

        return back();
    }




    public function remove(User $user)
    {
        $auth = auth()->user();

        $auth->friends()->detach($user->id);
        $user->friends()->detach($auth->id);

        return back();
    }
}
