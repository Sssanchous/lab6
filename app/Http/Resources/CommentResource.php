<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class CommentResource extends JsonResource
{
    public function toArray($request)
    {
        $commentAuthor = $this->user;
        $cardOwner = $this->card->user ?? null;
        $currentUser = Auth::user();

        $isFriend = false;
        if ($currentUser && $commentAuthor) {
            $isFriend = $currentUser->isFriendWith($commentAuthor);
        }

        return [
            'id' => $this->id,
            'content' => $this->content,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'user' => [
                'id' => $commentAuthor->id,
                'name' => $commentAuthor->name,
            ],

            'is_friend' => $isFriend,

            'card' => [
                'id' => $this->card->id,
                'title' => $this->card->title,
                'brand' => $this->card->brand,
                'model' => $this->card->model,
                'category' => $this->card->category,
                'user' => $cardOwner ? [
                    'id' => $cardOwner->id,
                    'name' => $cardOwner->name,
                ] : null,
                'is_friend_with_owner' => $currentUser && $cardOwner
                    ? $currentUser->isFriendWith($cardOwner)
                    : false,
            ],
        ];
    }
}