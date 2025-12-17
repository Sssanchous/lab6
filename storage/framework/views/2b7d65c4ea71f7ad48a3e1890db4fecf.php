

<?php $__env->startSection('title', 'Друзья'); ?>

<?php $__env->startSection('content'); ?>

<div class="row mb-4">
    <div class="col-12">
        <div class="card-header-custom">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-user-friends me-2"></i>Друзья
                    </h1>
                </div>
                <a href="<?php echo e(route('cards.index')); ?>" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left me-2"></i>Назад
                </a>
            </div>
        </div>
    </div>
</div>

<?php if($incomingRequests->count()): ?>
    <h4 class="mb-3">Входящие заявки</h4>

    <?php $__currentLoopData = $incomingRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="card mb-2">
            <div class="card-body d-flex justify-content-between align-items-center">
                <strong><?php echo e($user->name); ?></strong>

                <div class="d-flex gap-2">
                    <form method="POST" action="<?php echo e(route('friends.accept', $user)); ?>">
                        <?php echo csrf_field(); ?>
                        <button class="btn btn-success btn-sm">Принять</button>
                    </form>

                    <form method="POST" action="<?php echo e(route('friends.remove', $user)); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button class="btn btn-outline-secondary btn-sm">Отклонить</button>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <hr class="my-4">
<?php endif; ?>

<?php if($outgoingRequests->count()): ?>
    <h4 class="mb-3">Отправленные заявки</h4>

    <?php $__currentLoopData = $outgoingRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="card mb-2">
            <div class="card-body d-flex justify-content-between align-items-center">
                <strong><?php echo e($user->name); ?></strong>
                <span class="badge bg-secondary">Ожидание</span>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <hr class="my-4">
<?php endif; ?>

<?php if($friends->count()): ?>
    <h4 class="mb-3">Мои друзья</h4>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php $__currentLoopData = $friends; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $friend): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-user fa-3x text-primary mb-3"></i>

                        <h5 class="fw-bold"><?php echo e($friend->name); ?></h5>
                        <p class="text-muted small">Карточек: <?php echo e($friend->cards_count); ?></p>

                        <form method="POST" action="<?php echo e(route('friends.remove', $friend)); ?>">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-user-minus me-1"></i>Удалить
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php else: ?>
    <?php if(!$incomingRequests->count() && !$outgoingRequests->count()): ?>
        <div class="text-center text-muted mt-5">
            <i class="fas fa-user-friends fa-3x mb-3"></i>
            <p>У вас пока нет друзей и заявок</p>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\alex1\Downloads\lab33 (2)\resources\views/friends/index.blade.php ENDPATH**/ ?>