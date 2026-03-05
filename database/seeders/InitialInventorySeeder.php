<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Order;
use App\Models\Vendor;
use Illuminate\Database\Seeder;

class InitialInventorySeeder extends Seeder
{
    public function run(): void
    {
        // Add Vendors
        Vendor::create(['name' => 'Main Wholesale Market', 'phone' => '9888877777']);
        Vendor::create(['name' => 'Local Dairy Farm', 'phone' => '9666655555']);
        Vendor::create(['name' => 'Sutra Veg Store', 'phone' => '9111122222']);

        // Items
        $aalu = Item::create(['name' => 'Aalu (Potato)', 'unit' => 'KG', 'current_stock' => 100, 'opening_stock' => 100]);
        $paneer = Item::create(['name' => 'Paneer', 'unit' => 'KG', 'current_stock' => 20, 'opening_stock' => 20]);
        Item::create(['name' => 'Egg', 'unit' => 'Tray', 'current_stock' => 10, 'opening_stock' => 10]);

        // Mock Orders
        Order::create(['item_id' => $aalu->id, 'quantity' => 50, 'date' => now()->subDay()->toDateString(), 'received' => false]);
        Order::create(['item_id' => $paneer->id, 'quantity' => 10, 'date' => now()->subDay()->toDateString(), 'received' => false]);
    }
}
