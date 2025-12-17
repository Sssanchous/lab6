<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Http\Controllers\ProfileController;

// Проверяем, существует ли класс
if (!class_exists(ProfileController::class)) {
    die("❌ Класс ProfileController не найден.\n");
}

$reflector = new ReflectionClass(ProfileController::class);

// Получаем все публичные методы
$methods = $reflector->getMethods(ReflectionMethod::IS_PUBLIC);

echo "✅ Найдено " . count($methods) . " публичных методов в ProfileController:\n\n";

$hasCreateClient = false;
foreach ($methods as $method) {
    echo "- " . $method->getName() . "\n";
    if ($method->getName() === 'createClient') {
        $hasCreateClient = true;
    }
}

echo "\n";

if ($hasCreateClient) {
    echo "✅ Метод createClient() НАЙДЕН.\n";
} else {
    echo "❌ Метод createClient() ОТСУТСТВУЕТ.\n";
    echo "\n➡️ Возможные причины:\n";
    echo "  • Метод не сохранён в файле\n";
    echo "  • Опечатка в названии (регистр букв!)\n";
    echo "  • Вы редактируете не тот файл\n";
}