<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class CardResource extends JsonResource
{
    public function toArray($request)
    {
        $owner = $this->user;
        $currentUser = Auth::user();

        $isFriend = false;
        if ($currentUser && $owner) {
            $isFriend = $currentUser->isFriendWith($owner);
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'brand' => $this->brand,
            'model' => $this->model,
            'year' => $this->year,
            'category' => $this->category,
            'description' => $this->description,
            'horsepower' => $this->horsepower,
            'price' => $this->price,
            'image_path' => $this->image_path,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'user' => [
                'id' => $owner->id,
                'name' => $owner->name,
                'email' => $owner->email,
            ],

            'is_friend' => $isFriend,

            'comments' => CommentResource::collection($this->whenLoaded('comments')),
        ];
    }
}