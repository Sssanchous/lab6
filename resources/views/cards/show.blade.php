@extends('layouts.app')

@section('title', $card->title . ' - Детали автомобиля')

@section('content')

@php
    $authUser = auth()->user();
    $isOwner = $authUser && $authUser->id === $card->user_id;
    $isFriend = $authUser && $authUser->isFriendWith($card->user);
    $sentRequest = $authUser && $authUser->hasSentFriendRequestTo($card->user);
    $incomingRequest = $authUser && $authUser->hasIncomingFriendRequestFrom($card->user);
@endphp

<div class="row mb-4">
    <div class="col-12">
        <div class="card-header-custom">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-car me-2"></i>Детали автомобиля
                    </h1>
                </div>
                <a href="{{ url()->previous() ?? route('cards.index') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left me-2"></i>Назад
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-lg mb-5">
    <div class="row g-0">
        <div class="col-lg-6">
            @if($card->image_path && file_exists(public_path($card->image_path)))
                <img src="{{ asset($card->image_path) }}" class="img-fluid w-100" style="height: 500px; object-fit: cover;">
            @else
                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 500px;">
                    <i class="fas fa-car fa-5x text-muted"></i>
                </div>
            @endif
        </div>

        <div class="col-lg-6">
            <div class="p-4">
                <h1 class="fw-bold">{{ $card->title }}</h1>
                <h5 class="text-muted mb-3">{{ $card->brand }} {{ $card->model }} ({{ $card->year }})</h5>

                <div class="mb-4">
                    <strong>Автор:</strong> {{ $card->user->name }}

                    @auth
                        @if(!$isOwner)
                            <div class="mt-2">
                                @if($isFriend)
                                    <form method="POST" action="{{ route('friends.remove', $card->user) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm">Удалить из друзей</button>
                                    </form>

                                @elseif($incomingRequest)
                                    <div class="d-flex gap-2">
                                        <form method="POST" action="{{ route('friends.accept', $card->user) }}">
                                            @csrf
                                            <button class="btn btn-success btn-sm">Принять</button>
                                        </form>

                                        <form method="POST" action="{{ route('friends.remove', $card->user) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-outline-secondary btn-sm">Отклонить</button>
                                        </form>
                                    </div>

                                @elseif($sentRequest)
                                    <span class="badge bg-secondary">Заявка отправлена</span>

                                @else
                                    <form method="POST" action="{{ route('friends.add', $card->user) }}">
                                        @csrf
                                        <button class="btn btn-outline-success btn-sm">Добавить в друзья</button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    @endauth
                </div>

                <p>{{ $card->description }}</p>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <h4>Комментарии</h4>

        @foreach($card->comments as $comment)
            @php
                $fromFriend = $authUser && $authUser->isFriendWith($comment->user);
            @endphp

            <div class="border rounded p-2 mb-2 {{ $fromFriend ? 'bg-warning bg-opacity-25' : '' }}">
                <strong>{{ $comment->user->name }}</strong>
                <div>{{ $comment->content }}</div>
            </div>
        @endforeach

        @auth
            <form method="POST" action="{{ route('comments.store', $card) }}">
                @csrf
                <textarea name="content" class="form-control mb-2" required></textarea>
                <button class="btn btn-primary">Отправить</button>
            </form>
        @endauth
    </div>
</div>

@endsection
