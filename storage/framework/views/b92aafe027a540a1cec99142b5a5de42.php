<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль пользователя - Лабораторная работа №6</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
        }
        .card {
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .card-header {
            border-radius: 10px 10px 0 0 !important;
            padding: 15px;
        }
        code {
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
            background-color: #f8f9fa;
            padding: 2px 4px;
            border-radius: 3px;
        }
        .token-code {
            background-color: #343a40;
            color: white;
            padding: 10px;
            border-radius: 5px;
            font-size: 0.85em;
            word-break: break-all;
        }
        .btn-copy {
            cursor: pointer;
        }
        .example-request {
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 15px;
            margin: 10px 0;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
            white-space: pre-wrap;
        }
        .monospace-font {
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
        }
        .credentials-badge {
            font-size: 0.8em;
            padding: 3px 8px;
        }
        .required-field {
            border-left: 4px solid #dc3545;
        }
        .badge-client-type {
            font-size: 0.75em;
            padding: 2px 6px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-4">Профиль пользователя</h1>
            </div>
        </div>

        <div class="row">
            <!-- Левая колонка - Основная информация -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Основная информация</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Имя:</strong></td>
                                <td><?php echo e($user->name); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td><?php echo e($user->email); ?></td>
                            </tr>
                            <tr>
                                <td><strong>ID пользователя:</strong></td>
                                <td><code><?php echo e($user->id); ?></code></td>
                            </tr>
                            <tr>
                                <td><strong>Роль:</strong></td>
                                <td>
                                    <?php if($user->is_admin): ?>
                                        <span class="badge bg-danger">Администратор</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Пользователь</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Правая колонка - OAuth2 информация -->
            <div class="col-md-8">
                <!-- Уведомление о новом токене -->
                <?php if(session('token_plain')): ?>
                    <div class="alert alert-success mb-4">
                        <h6>Новый токен создан!</h6>
                        <p class="mb-2"><strong>Сохраните этот токен:</strong></p>
                        <div class="token-code mb-2"><?php echo e(session('token_plain')); ?></div>
                        <small>Используйте в заголовках: <code>Authorization: Bearer <?php echo e(session('token_plain')); ?></code></small>
                    </div>
                <?php endif; ?>

                <!-- Уведомления -->
                <?php if(session('status') == 'client-created'): ?>
                    <div class="alert alert-success mb-4">
                        <h6>OAuth2 клиенты созданы!</h6>
                        <p class="mb-0">Passport клиенты успешно созданы. Теперь можно создавать токены.</p>
                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="alert alert-danger mb-4">
                        <h6>Ошибка:</h6>
                        <p class="mb-0"><?php echo e(session('error')); ?></p>
                    </div>
                <?php endif; ?>

                <!-- Блок с OAuth2 Client Credentials - ОБЯЗАТЕЛЬНЫЙ -->
                <div class="card required-field">
        
                    <div class="card-body">
                        <?php if($personalClient): ?>
                            <div class="alert alert-info mb-3">
                                <strong>Эти данные обязательны для работы с API. Сохраните их!</strong>
                            </div>
                            
                            <div class="row mb-4">
                                <!-- Client ID - ОБЯЗАТЕЛЬНЫЙ -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">
                                            <strong>Client ID (Обязательно)</strong>
                                            <span class="badge bg-primary credentials-badge">Обязательный</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="text" 
                                                   class="form-control monospace-font" 
                                                   value="<?php echo e($personalClient->id); ?>" 
                                                   readonly 
                                                   id="client-id">
                                            <button class="btn btn-outline-primary btn-copy" 
                                                    onclick="copyText('client-id')"
                                                    title="Скопировать Client ID">
                                                Копировать
                                            </button>
                                        </div>
                                        <div class="form-text">
                                            <small>Идентификатор для OAuth2 аутентификации</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Client Secret - ОБЯЗАТЕЛЬНЫЙ -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">
                                            <strong>Client Secret (Обязательно)</strong>
                                            <span class="badge bg-danger credentials-badge">Обязательный</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="text" 
                                                   class="form-control monospace-font" 
                                                   value="<?php echo e($personalClient->secret); ?>" 
                                                   readonly 
                                                   id="client-secret">
                                            <button class="btn btn-outline-danger btn-copy" 
                                                    onclick="copyText('client-secret')"
                                                    title="Скопировать Client Secret">
                                                Копировать
                                            </button>
                                        </div>
                                        <div class="form-text">
                                            <small class="text-danger">Секретный ключ для OAuth2 аутентификации</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Информация о клиенте -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h6>Информация о клиенте</h6>
                                            <table class="table table-sm">
                                                <tr>
                                                    <td><strong>ID:</strong></td>
                                                    <td><code><?php echo e($personalClient->id); ?></code></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Имя:</strong></td>
                                                    <td><?php echo e($personalClient->name); ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Тип:</strong></td>
                                                    <td>
                                                        <?php if($personalClient->personal_access_client): ?>
                                                            <span class="badge bg-info badge-client-type">Personal Access</span>
                                                        <?php elseif($personalClient->password_client): ?>
                                                            <span class="badge bg-warning text-dark badge-client-type">Password Grant</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-secondary badge-client-type">Другой</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Создан:</strong></td>
                                                    <td><?php echo e(date('d.m.Y H:i', strtotime($personalClient->created_at))); ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h6>Использование</h6>
                                            <div class="d-grid gap-2">
                                                <button class="btn btn-outline-info" 
                                                        onclick="showExampleRequest()">
                                                    Показать пример запроса
                                                </button>
                                                <a href="/docs/api" class="btn btn-outline-secondary">
                                                    Документация API
                                                </a>
                                            </div>
                                            <div class="mt-3">
                                                <p class="small text-muted mb-1">Пример .env файла:</p>
                                                <code class="d-block p-2 bg-light small">
PASSPORT_CLIENT_ID=<?php echo e($personalClient->id); ?><br>
PASSPORT_CLIENT_SECRET=<?php echo e($personalClient->secret); ?>

                                                </code>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        <?php else: ?>
                            <div class="alert alert-danger">
                                <form method="POST" action="<?php echo e(route('profile.client.create')); ?>" class="mt-3">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-primary">
                                        Создать Passport Clients
                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Форма создания токена -->
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Создать Personal Access Token</h5>
                    </div>
                    <div class="card-body">
                        <?php if($personalClient): ?>
                            <form method="POST" action="<?php echo e(route('profile.token.create')); ?>">
                                <?php echo csrf_field(); ?>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label for="token_name" class="form-label">Название токена</label>
                                            <input type="text" name="token_name" class="form-control" 
                                                   placeholder="Например: Мобильное приложение" 
                                                   value="<?php echo e(old('token_name')); ?>"
                                                   required>
                                            <?php if(session('token_name')): ?>
                                                <div class="form-text">
                                                    Последний введенный токен: "<?php echo e(session('token_name')); ?>"
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Действие</label>
                                            <button type="submit" class="btn btn-success w-100">Создать токен</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-text">
                                    Этот токен будет привязан к вашему аккаунту и предоставит доступ к API.
                                </div>
                            </form>
                        <?php else: ?>
                            <div class="alert alert-warning mb-0">
                                <p class="mb-0">Для создания токенов необходимо сначала создать OAuth2 клиента.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Мои токены -->
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">Мои токены</h5>
                    </div>
                    <div class="card-body">
                        <?php if($tokens->count() > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Название</th>
                                            <th>Создан</th>
                                            <th>Последнее использование</th>
                                            <th>Действия</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $tokens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $token): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <?php echo e($token->name); ?>

                                                <?php if($token->revoked): ?>
                                                    <span class="badge bg-danger">Отозван</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e($token->created_at->format('d.m.Y H:i')); ?></td>
                                            <td>
                                                <?php if($token->last_used_at): ?>
                                                    <?php echo e($token->last_used_at->format('d.m.Y H:i')); ?>

                                                <?php else: ?>
                                                    <span class="text-muted">Никогда</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <form method="POST" action="<?php echo e(route('profile.token.revoke', $token->id)); ?>" class="d-inline">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                            onclick="return confirm('Отозвать токен?')">
                                                        Отозвать
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info mb-0">
                                <strong>У вас нет созданных токенов.</strong>
                                <small>Создайте токен для доступа к REST API.</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Навигация -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-between">
                    <a href="/cards" class="btn btn-outline-primary">К карточкам</a>
                    <form method="POST" action="<?php echo e(route('logout')); ?>" class="d-inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-outline-danger">Выйти</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Информация -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="text-center text-muted">
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно с примером запроса -->
    <div class="modal fade" id="exampleRequestModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Пример запроса OAuth2</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Используйте эти данные для получения Access Token:</p>
                    
                    <div class="mb-3">
                        <label class="form-label"><strong>Client ID:</strong></label>
                        <code class="p-2 bg-light d-block"><?php echo e($personalClient ? $personalClient->id : 'YOUR_CLIENT_ID'); ?></code>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><strong>Client Secret:</strong></label>
                        <code class="p-2 bg-light d-block"><?php echo e($personalClient ? $personalClient->secret : 'YOUR_CLIENT_SECRET'); ?></code>
                    </div>
                    
                    <p>Пример запроса для получения Access Token (Password Grant):</p>
                    <div class="example-request" id="exampleRequestContent">
                        <!-- Контент будет заполнен JavaScript -->
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-sm btn-outline-primary" onclick="copyExampleRequest()">
                            Скопировать пример
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Функция копирования текста
        function copyText(elementId) {
            const element = document.getElementById(elementId);
            if (element) {
                element.select();
                element.setSelectionRange(0, 99999);
                
                try {
                    navigator.clipboard.writeText(element.value);
                    alert('Скопировано: ' + element.value);
                } catch (err) {
                    // Fallback для старых браузеров
                    element.select();
                    document.execCommand('copy');
                    alert('Скопировано!');
                }
            }
        }

        // Показать пример запроса
        function showExampleRequest() {
            const clientId = document.getElementById('client-id') ? document.getElementById('client-id').value : '<?php echo e($personalClient ? $personalClient->id : "YOUR_CLIENT_ID"); ?>';
            const clientSecret = document.getElementById('client-secret') ? document.getElementById('client-secret').value : '<?php echo e($personalClient ? $personalClient->secret : "YOUR_CLIENT_SECRET"); ?>';
            const baseUrl = window.location.origin;
            const userEmail = "<?php echo e(auth()->user()->email); ?>";
            
           
            document.getElementById('exampleRequestContent').textContent = example;
            const modal = new bootstrap.Modal(document.getElementById('exampleRequestModal'));
            modal.show();
        }

        // Копировать пример запроса
        function copyExampleRequest() {
            const content = document.getElementById('exampleRequestContent').textContent;
            navigator.clipboard.writeText(content);
            alert('Пример запроса скопирован!');
        }
    </script>
</body>
</html><?php /**PATH C:\Users\User\Desktop\laba6\resources\views/profile/edit.blade.php ENDPATH**/ ?>