@extends('layouts.app')

@section('title', $isEdit ? 'Редактирование машины' : 'Добавление новой машины')

@section('content')

    <div class="row mb-4">
        <div class="col-12">
            <div class="card-header-custom">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-0">
                            <i class="fas fa-car me-2"></i>
                            {{ $isEdit ? 'Редактирование машины' : 'Добавление новой машины' }}
                        </h1>
                    </div>
                    <a href="{{ route('cards.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left me-2"></i>Назад
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        <strong>Ошибки в форме:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif


    <div class="card shadow">
        <div class="card-body p-4">
            <form method="POST" 
                  action="{{ $isEdit ? route('cards.update', $card) : route('cards.store') }}" 
                  enctype="multipart/form-data"
                  class="needs-validation"
                  novalidate>
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif

                <div class="mb-4">
                    <label for="image" class="form-label fw-bold">Изображение машины</label>
                    <input type="file" class="form-control" id="image" name="image" 
                           accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                           {{ !$isEdit ? 'required' : '' }}>
                    <div class="form-text">
                        Максимальный размер: 50MB. Допустимые форматы: JPEG, PNG, JPG, GIF, WEBP
                    </div>
                    
                    @if($isEdit && $card->image_path && file_exists(public_path($card->image_path)))
                    <div class="mt-3">
                        <small class="text-muted">Текущее изображение:</small>
                        <div class="mt-2">
                            <img src="{{ asset($card->image_path) }}" 
                                 id="image-preview"
                                 alt="Current image" 
                                 class="img-thumbnail rounded" style="max-width: 300px;">
                        </div>
                    </div>
                    @else
                        <img src="" id="image-preview" class="img-thumbnail rounded mt-3 d-none" style="max-width: 300px;">
                    @endif
                    @error('image')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

      
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="title" class="form-label fw-bold">Название машины</label>
                        <input type="text" class="form-control" id="title" name="title" 
                               value="{{ old('title', $card->title) }}" 
                               placeholder="Например: Ferrari 458 Italia" required>
                        @error('title')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="category" class="form-label fw-bold">Тип кузова</label>
                        <select class="form-select" id="category" name="category" required>
                            <option value="">Выберите тип кузова</option>
                            @foreach($categories as $key => $value)
                                <option value="{{ $key }}" {{ old('category', $card->category) == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                        @error('category')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="brand" class="form-label fw-bold">Марка</label>
                        <input type="text" class="form-control" id="brand" name="brand" 
                               value="{{ old('brand', $card->brand) }}" 
                               placeholder="Например: BMW, Mercedes, Audi" required>
                        @error('brand')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="model" class="form-label fw-bold">Модель</label>
                        <input type="text" class="form-control" id="model" name="model" 
                               value="{{ old('model', $card->model) }}" 
                               placeholder="Например: X5, A8, Corolla" required>
                        @error('model')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="year" class="form-label fw-bold">Год выпуска</label>
                        <input type="number" class="form-control" id="year" name="year" 
                               value="{{ old('year', $card->year) }}" 
                               min="1900" max="{{ date('Y') + 1 }}"
                               placeholder="2020" required>
                        @error('year')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="horsepower" class="form-label fw-bold">Мощность (л.с.)</label>
                        <input type="number" class="form-control" id="horsepower" name="horsepower" 
                               value="{{ old('horsepower', $card->horsepower) }}" 
                               min="1" max="5000"
                               placeholder="250" required>
                        @error('horsepower')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label fw-bold">Цена ($)</label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price" 
                               value="{{ old('price', $card->price) }}" 
                               min="0"
                               placeholder="50000">
                        @error('price')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="fun_fact_content" class="form-label fw-bold">Интересный факт</label>
                        <textarea class="form-control" id="fun_fact_content" name="fun_fact_content" 
                                  rows="3" placeholder="Интересная информация о машине">{{ old('fun_fact_content', $card->fun_fact_content) }}</textarea>
                    </div>

                    <div class="col-md-12 mb-4">
                        <label for="description" class="form-label fw-bold">Краткое описание</label>
                        <textarea class="form-control" id="description" name="description" 
                                  rows="4" placeholder="Краткое описание машины" 
                                  required>{{ old('description', $card->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

   
                <div class="d-flex justify-content-end gap-3 mt-4 pt-3 border-top">
                    <a href="{{ route('cards.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Отмена
                    </a>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-{{ $isEdit ? 'save' : 'plus' }} me-2"></i>
                        {{ $isEdit ? 'Обновить машину' : 'Добавить машину' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Предпросмотр изображения
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('image-preview');
        
        if (imageInput && imagePreview) {
            imageInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreview.classList.remove('d-none');
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }
    });
</script>
@endpush