<?php $__env->startSection('title', $card->title . ' - Детали автомобиля'); ?>

<?php $__env->startSection('content'); ?>

<?php
    $authUser = auth()->user();
    $isOwner = $authUser && $authUser->id === $card->user_id;
    $isFriend = $authUser && $authUser->isFriendWith($card->user);
    $sentRequest = $authUser && $authUser->hasSentFriendRequestTo($card->user);
    $incomingRequest = $authUser && $authUser->hasIncomingFriendRequestFrom($card->user);
?>

<div class="row mb-4">
    <div class="col-12">
        <div class="card-header-custom">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-car me-2"></i>Детали автомобиля
                    </h1>
                </div>
                <a href="<?php echo e(url()->previous() ?? route('cards.index')); ?>" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left me-2"></i>Назад
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-lg mb-5">
    <div class="row g-0">
        <div class="col-lg-6">
            <?php if($card->image_path && file_exists(public_path($card->image_path))): ?>
                <img src="<?php echo e(asset($card->image_path)); ?>" class="img-fluid w-100" style="height: 500px; object-fit: cover;">
            <?php else: ?>
                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 500px;">
                    <i class="fas fa-car fa-5x text-muted"></i>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-lg-6">
            <div class="p-4">
                <h1 class="fw-bold"><?php echo e($card->title); ?></h1>
                <h5 class="text-muted mb-3"><?php echo e($card->brand); ?> <?php echo e($card->model); ?> (<?php echo e($card->year); ?>)</h5>

                <div class="mb-4">
                    <strong>Автор:</strong> <?php echo e($card->user->name); ?>


                    <?php if(auth()->guard()->check()): ?>
                        <?php if(!$isOwner): ?>
                            <div class="mt-2">
                                <?php if($isFriend): ?>
                                    <form method="POST" action="<?php echo e(route('friends.remove', $card->user)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button class="btn btn-outline-danger btn-sm">Удалить из друзей</button>
                                    </form>

                                <?php elseif($incomingRequest): ?>
                                    <div class="d-flex gap-2">
                                        <form method="POST" action="<?php echo e(route('friends.accept', $card->user)); ?>">
                                            <?php echo csrf_field(); ?>
                                            <button class="btn btn-success btn-sm">Принять</button>
                                        </form>

                                        <form method="POST" action="<?php echo e(route('friends.remove', $card->user)); ?>">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button class="btn btn-outline-secondary btn-sm">Отклонить</button>
                                        </form>
                                    </div>

                                <?php elseif($sentRequest): ?>
                                    <span class="badge bg-secondary">Заявка отправлена</span>

                                <?php else: ?>
                                    <form method="POST" action="<?php echo e(route('friends.add', $card->user)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <button class="btn btn-outline-success btn-sm">Добавить в друзья</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <p><?php echo e($card->description); ?></p>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <h4>Комментарии</h4>

        <?php $__currentLoopData = $card->comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $fromFriend = $authUser && $authUser->isFriendWith($comment->user);
            ?>

            <div class="border rounded p-2 mb-2 <?php echo e($fromFriend ? 'bg-warning bg-opacity-25' : ''); ?>">
                <strong><?php echo e($comment->user->name); ?></strong>
                <div><?php echo e($comment->content); ?></div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <?php if(auth()->guard()->check()): ?>
            <form method="POST" action="<?php echo e(route('comments.store', $card)); ?>">
                <?php echo csrf_field(); ?>
                <textarea name="content" class="form-control mb-2" required></textarea>
                <button class="btn btn-primary">Отправить</button>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\alex1\Downloads\lab33 (2)\resources\views/cards/show.blade.php ENDPATH**/ ?>