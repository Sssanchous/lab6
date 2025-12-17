@extends('layouts.app')

@section('title', 'Друзья')

@section('content')

<div class="row mb-4">
    <div class="col-12">
        <div class="card-header-custom">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-user-friends me-2"></i>Друзья
                    </h1>
                </div>
                <a href="{{ route('cards.index') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left me-2"></i>Назад
                </a>
            </div>
        </div>
    </div>
</div>

@if($incomingRequests->count())
    <h4 class="mb-3">Входящие заявки</h4>

    @foreach($incomingRequests as $user)
        <div class="card mb-2">
            <div class="card-body d-flex justify-content-between align-items-center">
                <strong>{{ $user->name }}</strong>

                <div class="d-flex gap-2">
                    <form method="POST" action="{{ route('friends.accept', $user) }}">
                        @csrf
                        <button class="btn btn-success btn-sm">Принять</button>
                    </form>

                    <form method="POST" action="{{ route('friends.remove', $user) }}">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-secondary btn-sm">Отклонить</button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <hr class="my-4">
@endif

@if($outgoingRequests->count())
    <h4 class="mb-3">Отправленные заявки</h4>

    @foreach($outgoingRequests as $user)
        <div class="card mb-2">
            <div class="card-body d-flex justify-content-between align-items-center">
                <strong>{{ $user->name }}</strong>
                <span class="badge bg-secondary">Ожидание</span>
            </div>
        </div>
    @endforeach

    <hr class="my-4">
@endif

@if($friends->count())
    <h4 class="mb-3">Мои друзья</h4>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @foreach($friends as $friend)
            <div class="col">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-user fa-3x text-primary mb-3"></i>

                        <h5 class="fw-bold">{{ $friend->name }}</h5>
                        <p class="text-muted small">Карточек: {{ $friend->cards_count }}</p>

                        <form method="POST" action="{{ route('friends.remove', $friend) }}">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-user-minus me-1"></i>Удалить
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    @if(!$incomingRequests->count() && !$outgoingRequests->count())
        <div class="text-center text-muted mt-5">
            <i class="fas fa-user-friends fa-3x mb-3"></i>
            <p>У вас пока нет друзей и заявок</p>
        </div>
    @endif
@endif

@endsection
