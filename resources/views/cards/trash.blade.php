@extends('layouts.app')

@section('title', 'Корзина - Удалённые автомобили')

@section('content')
    @if(!auth()->user()->is_admin)
        <div class="alert alert-warning text-center">
            <i class="fas fa-exclamation-triangle me-2"></i>
            У вас нет доступа к корзине.
        </div>
        <div class="text-center">
            <a href="{{ route('cards.index') }}" class="btn btn-primary">Вернуться в каталог</a>
        </div>
    @else
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="display-5 fw-bold mb-0">
                        <i class="fas fa-trash me-2"></i>Корзина
                    </h1>
                    <a href="{{ route('cards.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Вернуться в каталог
                    </a>
                </div>
                <p class="text-muted">Удалённые автомобили. Восстановите или удалите навсегда.</p>
            </div>
        </div>

        <!-- Алерты -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($cards->count() > 0)
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach($cards as $card)
                    <div class="col">
                        <div class="card shadow-sm border-danger d-flex flex-column" style="min-height: 440px; height: 100%;">
                            <div style="height: 180px; overflow: hidden; border-radius: 0.375rem 0.375rem 0 0; opacity: 0.75;">
                                @if($card->image_path && file_exists(public_path($card->image_path)))
                                    <img src="{{ asset($card->image_path) }}" 
                                         alt="{{ $card->title }}"
                                         class="w-100 h-100"
                                         style="object-fit: cover; object-position: center;">
                                @else
                                    <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                                        <i class="fas fa-car fa-2x text-muted"></i>
                                    </div>
                                @endif
                            </div>

                            <div class="card-body d-flex flex-column" style="flex: 1; overflow: hidden;">
                                <h5 class="card-title fw-bold text-center text-truncate mb-2" title="{{ $card->title }}">
                                    {{ $card->title }}
                                </h5>

                                <p class="card-text text-muted mb-3 flex-grow-1" style="font-size: 0.875rem; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                                    {{ $card->description }}
                                </p>

                                <div class="mb-3 small">
                                    <p class="mb-1"><strong>Марка:</strong> {{ $card->brand }}</p>
                                    <p class="mb-1"><strong>Модель:</strong> {{ $card->model }}</p>
                                    <p class="mb-0"><strong>Год:</strong> {{ $card->year }}</p>
                                </div>

                                <div class="d-flex gap-2 mt-auto">
                                    <form action="{{ route('cards.restore', $card->id) }}" method="POST" class="flex-grow-1">
                                        @csrf
                                        <button type="submit" class="btn btn-success w-100 py-2"
                                                onclick="return confirm('Восстановить?')">
                                            <i class="fas fa-undo me-2"></i>Восстановить
                                        </button>
                                    </form>
                                    <form action="{{ route('cards.forceDelete', $card->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger py-2 px-3"
                                                onclick="return confirm('Удалить навсегда?')">
                                            <i class="fas fa-skull"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="alert alert-info mt-4">
                <div class="d-flex justify-content-between">
                    <div><i class="fas fa-info-circle me-2"></i> В корзине: <strong>{{ $cards->count() }}</strong></div>
                    <a href="{{ route('cards.index') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-list me-1"></i>Каталог
                    </a>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <div class="card shadow-sm border-0">
                    <div class="card-body py-5">
                        <i class="fas fa-trash-alt fa-4x text-muted mb-3"></i>
                        <h3 class="mb-3">Корзина пуста</h3>
                        <p class="text-muted">
                            Удалите автомобиль из каталога, чтобы он появился здесь.
                        </p>
                        <a href="{{ route('cards.index') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-2"></i>Вернуться в каталог
                        </a>
                    </div>
                </div>
            </div>
        @endif
    @endif
@endsection
