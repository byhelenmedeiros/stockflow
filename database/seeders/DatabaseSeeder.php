<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
   public function run()
{
    \App\Models\Product::factory()->count(50)->create();
    \App\Models\User::factory()->count(5)->create()->each(function ($user) {
        \App\Models\Order::factory()->count(3)->create(['user_id' => $user->id])
            ->each(function ($order) {
                \App\Models\OrderItem::factory()->count(2)->create(['order_id' => $order->id]);
            });
    });
}
}
