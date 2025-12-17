<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\User;
use Illuminate\Database\Seeder;

class CardSeeder extends Seeder
{
    public function run(): void
    {
        // Получаем пользователей
        $admin = User::where('email', 'admin@example.com')->first();
        $user = User::where('email', 'test@example.com')->first();
        
        $cards = [
            [
                'user_id' => $admin->id, // Добавляем user_id
                'title' => 'BMW M5 Competition',
                'category' => 'Седан',
                'description' => 'BMW M5 Competition — это спортивный седан бизнес-класса с двигателем V8 мощностью 625 л.с. Автомобиль сочетает в себе роскошь, комфорт и невероятную динамику.',
                'image_path' => 'images/2.jpg',
                'fun_fact_content' => 'M5 Competition может переключаться между полным и задним приводом благодаря системе M xDrive.',
                'brand' => 'BMW',
                'model' => 'M5 Competition',
                'year' => 2023,
                'horsepower' => 625,
                'price' => 12500000,
            ],
            [
                'user_id' => $user->id, // Добавляем user_id
                'title' => 'Mercedes-AMG GT 63 S',
                'category' => 'Купе',
                'description' => 'Mercedes-AMG GT 63 S — четырёхдверное купе с двигателем V8 мощностью 639 л.с. Один из самых быстрых седанов в мире.',
                'image_path' => 'images/1.jpg',
                'fun_fact_content' => 'AMG GT 63 S может дрифтовать в режиме «Drift Mode» благодаря специальной настройке полного привода.',
                'brand' => 'Mercedes-Benz',
                'model' => 'AMG GT 63 S',
                'year' => 2023,
                'horsepower' => 639,
                'price' => 16500000,
            ],
            [
                'user_id' => $admin->id, // Добавляем user_id
                'title' => 'Audi RS7 Sportback',
                'category' => 'Хэтчбек',
                'description' => 'Audi RS7 Sportback — спортивный пятидверный хэтчбек с элегантным дизайном и мощным двигателем V8. Сочетание практичности и высокой производительности.',
                'image_path' => 'images/5.jpg',
                'fun_fact_content' => 'RS7 имеет систему снижения дорожного просвета на высоких скоростях для улучшения аэродинамики.',
                'brand' => 'Audi',
                'model' => 'RS7 Sportback',
                'year' => 2023,
                'horsepower' => 600,
                'price' => 13000000,
            ],
            [
                'user_id' => $user->id, // Добавляем user_id
                'title' => 'Toyota GR Supra',
                'category' => 'Купе',
                'description' => 'Toyota GR Supra — спортивное купе с задним приводом, разработанное совместно с BMW. Доступен с 3.0-литровым рядным шестицилиндровым двигателем.',
                'image_path' => 'images/3.jpg',
                'fun_fact_content' => 'GR Supra имеет скрытый спойлер, который автоматически выдвигается при скорости свыше 100 км/ч.',
                'brand' => 'Toyota',
                'model' => 'GR Supra',
                'year' => 2023,
                'horsepower' => 382,
                'price' => 550000,
            ],
            [
                'user_id' => $admin->id, // Добавляем user_id
                'title' => 'Porsche 911 Turbo S',
                'category' => 'Купе',
                'description' => 'Porsche 911 Turbo S — легендарное спортивное купе с оппозитным двигателем и полным приводом. Эталон в классе суперкаров.',
                'image_path' => 'images/4.jpg',
                'fun_fact_content' => '911 Turbo S имеет передний спойлер, который выдвигается на скорости 120 км/ч для увеличения прижимной силы.',
                'brand' => 'Porsche',
                'model' => '911 Turbo S',
                'year' => 2023,
                'horsepower' => 650,
                'price' => 21500000,
            ],
        ];

        foreach ($cards as $cardData) {
            Card::create($cardData);
        }
        
    
    }
}