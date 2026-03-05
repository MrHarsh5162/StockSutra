<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use App\Models\MorningStock;
use App\Models\NightClosing;
use App\Models\Order;
use App\Models\Unit;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Units
        $kg = Unit::firstOrCreate(['name' => 'KG'], ['fullname' => 'Kilogram']);
        $ltr = Unit::firstOrCreate(['name' => 'Liter'], ['fullname' => 'Liter']);
        $pcs = Unit::firstOrCreate(['name' => 'PCS'], ['fullname' => 'Pieces']);
        $pkt = Unit::firstOrCreate(['name' => 'PKT'], ['fullname' => 'Packet']);

        // 2. Create Categories
        $veg = Category::firstOrCreate(['name' => 'Vegetables']);
        $dairy = Category::firstOrCreate(['name' => 'Dairy']);
        $grocery = Category::firstOrCreate(['name' => 'Grocery']);
        $bakery = Category::firstOrCreate(['name' => 'Bakery']);

        // 3. Create Vendors
        Vendor::firstOrCreate(['name' => 'Sutra Wholesale'], ['phone' => '9988776655']);
        Vendor::firstOrCreate(['name' => 'Gopal Dairy'], ['phone' => '9900881122']);
        Vendor::firstOrCreate(['name' => 'Fresh Farm Veg'], ['phone' => '8811223344']);

        // 4. Create Items
        $itemsData = [
            ['name' => 'Potato (Aalu)', 'category_id' => $veg->id, 'unit_id' => $kg->id, 'opening_stock' => 50, 'current_stock' => 50],
            ['name' => 'Onion (Pyaaz)', 'category_id' => $veg->id, 'unit_id' => $kg->id, 'opening_stock' => 40, 'current_stock' => 40],
            ['name' => 'Tomato (Tamatar)', 'category_id' => $veg->id, 'unit_id' => $kg->id, 'opening_stock' => 20, 'current_stock' => 20],
            ['name' => 'Milk (Full Cream)', 'category_id' => $dairy->id, 'unit_id' => $ltr->id, 'opening_stock' => 10, 'current_stock' => 10],
            ['name' => 'Bakery Paneer', 'category_id' => $dairy->id, 'unit_id' => $kg->id, 'opening_stock' => 5, 'current_stock' => 5],
            ['name' => 'Curd (Dahi)', 'category_id' => $dairy->id, 'unit_id' => $kg->id, 'opening_stock' => 8, 'current_stock' => 8],
            ['name' => 'Basmati Rice', 'category_id' => $grocery->id, 'unit_id' => $kg->id, 'opening_stock' => 100, 'current_stock' => 100],
            ['name' => 'Refined Oil', 'category_id' => $grocery->id, 'unit_id' => $ltr->id, 'opening_stock' => 30, 'current_stock' => 30],
            ['name' => 'Brown Bread', 'category_id' => $bakery->id, 'unit_id' => $pkt->id, 'opening_stock' => 15, 'current_stock' => 15],
            ['name' => 'Buns', 'category_id' => $bakery->id, 'unit_id' => $pcs->id, 'opening_stock' => 24, 'current_stock' => 24],
        ];

        $items = [];
        foreach ($itemsData as $data) {
            $items[] = Item::firstOrCreate(['name' => $data['name']], $data);
        }

        // 5. Generate Historical Data for the last 3 days
        for ($i = 2; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->toDateString();
            
            foreach ($items as $item) {
                // Morning addition
                $morningQty = rand(5, 20);
                MorningStock::updateOrCreate(
                    ['item_id' => $item->id, 'entry_date' => $date],
                    ['quantity_received' => $morningQty]
                );

                // Night Closing
                $totalAvail = (float)$item->opening_stock + $morningQty;
                $consumed = rand(5, floor(min(15, $totalAvail)));
                $closing = $totalAvail - $consumed;

                NightClosing::updateOrCreate(
                    ['item_id' => $item->id, 'entry_date' => $date],
                    [
                        'opening_quantity' => $totalAvail,
                        'closing_quantity' => $closing,
                        'consumed_quantity' => $consumed,
                    ]
                );

                // Save for tomorrow step
                if ($i > 0) {
                   // This is for simulation, we update the ACTUAL current opening/stock for next iterations
                   // Actually, item->opening_stock in DB may not match simulation logic if we rerun multiple times
                   // for historical dates.
                }
                
                // Actual DB update for consistency in UI
                $item->update([
                    'opening_stock' => $closing,
                    'current_stock' => $closing
                ]);

                // Order history
                Order::updateOrCreate(
                    ['item_id' => $item->id, 'date' => $date],
                    ['quantity' => rand(10, 30), 'received' => true]
                );
            }
        }
    }
}
