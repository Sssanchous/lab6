<?php $__env->startSection('title', $card->title . ' - Детали автомобиля'); ?>

<?php $__env->startSection('content'); ?>
    <!-- ДЕТАЛЬНАЯ КАРТОЧКА -->
    <div class="card shadow-lg">
        <div class="row g-0">
            <div class="col-lg-6">
                <div class="position-relative">
                    <?php if($card->image_path && file_exists(public_path($card->image_path))): ?>
                        <img src="<?php echo e(asset($card->image_path)); ?>" 
                             class="img-fluid w-100" 
                             alt="<?php echo e($card->title); ?>"
                             style="height: 500px; object-fit: cover;">
                    <?php else: ?>
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 500px;">
                            <i class="fas fa-car fa-5x text-muted"></i>
                        </div>
                    <?php endif; ?>
                    
                    <!-- БЕЙДЖ КАТЕГОРИИ ИЛИ СТАТУСА -->
                    <?php if($card->trashed()): ?>
                        <span class="position-absolute top-0 end-0 m-3 badge bg-danger fs-6">
                            <i class="fas fa-trash me-1"></i>Удалён
                        </span>
                    <?php else: ?>
                        <span class="position-absolute top-0 end-0 m-3 badge bg-primary fs-6">
                            <i class="fas fa-tag me-1"></i><?php echo e($card->category_name ?? $card->category); ?>

                        </span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="p-4">
                    <!-- НАЗВАНИЕ МАШИНЫ -->
                    <h1 class="display-5 fw-bold mb-2"><?php echo e($card->title); ?></h1>
                    <h3 class="text-muted mb-4"><?php echo e($card->brand); ?> <?php echo e($card->model); ?> (<?php echo e($card->year); ?>)</h3>

                    <!-- СПЕЦИФИКАЦИИ -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="bg-light p-3 rounded">
                                <div class="text-muted small">Год выпуска</div>
                                <div class="fw-bold fs-5"><?php echo e($card->year); ?> г.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light p-3 rounded">
                                <div class="text-muted small">Мощность</div>
                                <div class="fw-bold fs-5"><?php echo e($card->horsepower); ?> л.с.</div>
                            </div>
                        </div>
                        <?php if($card->price): ?>
                        <div class="col-md-12">
                            <div class="bg-primary text-white p-3 rounded">
                                <div class="small">Стоимость</div>
                                <div class="fw-bold fs-4"><?php echo e($card->formatted_price ?? '$' . number_format($card->price, 0, '.', ' ')); ?></div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- ИНТЕРЕСНЫЙ ФАКТ И ОПИСАНИЕ В РАМКЕ -->
                    <div class="border rounded p-4 mb-4">
                        <?php if($card->fun_fact_content): ?>
                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-lightbulb text-warning me-2"></i>
                                <h5 class="mb-0 fw-bold">Интересный факт</h5>
                            </div>
                            <p class="mb-0"><?php echo e($card->fun_fact_content); ?></p>
                        </div>
                        <?php endif; ?>
                        
                        <?php if($card->description): ?>
                        <div>
                            <h5 class="mb-2 fw-bold">Описание</h5>
                            <p class="mb-0"><?php echo e($card->description); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- КНОПКИ ДЕЙСТВИЙ -->
                    <div class="d-flex flex-wrap gap-2 mt-4 pt-4 border-top">
                        <a href="<?php echo e(route('cards.index')); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Назад к списку
                        </a>

                        <?php if(auth()->user()->is_admin || $card->user_id === auth()->id()): ?>
                            <?php if(!$card->trashed()): ?>
                                <a href="<?php echo e(route('cards.edit', $card)); ?>" class="btn btn-primary">
                                    <i class="fas fa-edit me-1"></i> Редактировать
                                </a>
                                
                                <form action="<?php echo e(route('cards.destroy', $card)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('Переместить в корзину?')">
                                        <i class="fas fa-trash me-1"></i> Удалить
                                    </button>
                                </form>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\Desktop\lab33\resources\views/cards/show.blade.php ENDPATH**/ ?>