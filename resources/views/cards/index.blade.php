@extends('layouts.app')

@section('title', 'Каталог автомобилей')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <a href="{{ route('cards.index') }}" class="h5 text-decoration-none text-primary fw-bold">
        <i class="fas fa-car me-2"></i>Каталог
    </a>

    <form method="POST" action="{{ route('logout') }}" class="d-inline">
        @csrf
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); this.closest('form').submit();"
           class="text-decoration-none text-muted fw-medium">
            <i class="fas fa-sign-out-alt me-1"></i>Выйти
        </a>
    </form>
</div>

<div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h1 class="display-5 fw-bold mb-0">
            <i class="fas fa-car me-2"></i>Каталог автомобилей
        </h1>

        <a href="{{ route('cards.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Добавить
        </a>
    </div>
</div>

<div class="d-flex gap-2 mb-4">
    <a href="{{ route('cards.index') }}"
       class="btn {{ $mode === 'mine' ? 'btn-primary' : 'btn-outline-primary' }}">
        <i class="fas fa-user me-1"></i>Мои карточки
    </a>

    <a href="{{ route('cards.friends') }}"
       class="btn {{ $mode === 'friends' ? 'btn-primary' : 'btn-outline-primary' }}">
        <i class="fas fa-users me-1"></i>Карточки друзей
    </a>

    <a href="{{ route('cards.all') }}"
       class="btn {{ $mode === 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
        <i class="fas fa-globe me-1"></i>Все карточки
    </a>

    <a href="{{ route('friends.index') }}"
       class="btn btn-outline-success ms-auto">
        <i class="fas fa-user-friends me-1"></i>Друзья
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($cards->count())
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @foreach($cards as $card)
            <div class="col">
                <div class="card shadow-sm d-flex flex-column" style="min-height: 440px;">

                    <div style="height: 180px; overflow: hidden;">
                        @if($card->image_path && file_exists(public_path($card->image_path)))
                            <img src="{{ asset($card->image_path) }}"
                                 class="w-100 h-100"
                                 style="object-fit: cover;">
                        @else
                            <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                                <i class="fas fa-car fa-2x text-muted"></i>
                            </div>
                        @endif
                    </div>

                    <div class="card-body d-flex flex-column">

                        <div class="row g-2 mb-2 small text-center">
                            <div class="col-4">
                                <div class="bg-light rounded p-1">
                                    <strong>{{ $card->year }}</strong><br>
                                    <small>Год</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="bg-light rounded p-1">
                                    <strong>{{ $card->horsepower }}</strong><br>
                                    <small>л.с.</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="bg-light rounded p-1">
                                    <strong>{{ $card->formatted_price }}</strong><br>
                                    <small>Цена</small>
                                </div>
                            </div>
                        </div>

                        <h5 class="fw-bold text-center text-truncate mb-1">
                            {{ $card->title }}
                        </h5>

                        @if($mode === 'all')
                            <div class="text-center text-muted small mb-2">
                                Автор: {{ $card->user->name }}
                            </div>
                        @endif

                        @if($card->fun_fact_content)
                            <div class="alert alert-info py-1 px-2 small mb-2">
                                <i class="fas fa-lightbulb me-1"></i>
                                {{ Str::limit($card->fun_fact_content, 70) }}
                            </div>
                        @endif

                        <p class="text-muted small flex-grow-1"
                           style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $card->description }}
                        </p>

                        <div class="d-flex gap-2 mt-auto">
                            <a href="{{ route('cards.show', $card) }}"
                               class="btn btn-primary btn-sm flex-grow-1">
                                <i class="fas fa-eye me-1"></i>Подробнее
                            </a>

                            @if(auth()->user()->is_admin || $card->user_id === auth()->id())
                                <a href="{{ route('cards.edit', $card) }}"
                                   class="btn btn-outline-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form method="POST"
                                      action="{{ route('cards.destroy', $card) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm"
                                            onclick="return confirm('Переместить в корзину?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if(auth()->user()->is_admin)
        <div class="text-center mt-4">
            <a href="{{ route('cards.trash') }}" class="btn btn-outline-danger">
                <i class="fas fa-trash me-1"></i>Корзина
            </a>
        </div>
    @endif
@else
    <div class="text-center py-5">
        <i class="fas fa-car fa-4x text-muted mb-3"></i>
        <h3>Машин пока нет</h3>
        <p class="text-muted">Добавьте первую машину</p>
        <a href="{{ route('cards.create') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-plus me-2"></i>Добавить
        </a>
    </div>
@endif

@endsection
