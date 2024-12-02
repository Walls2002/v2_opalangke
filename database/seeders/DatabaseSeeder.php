<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Cart;
use App\Models\Location;
use App\Models\Product;
use App\Models\Rider;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        if (app()->isProduction()) {
            return;
        }

        $admin = User::create([
            "name" => "Palengke Admin",
            "email" => "palengke.admin@example.com",
            "password" => "helloworld",
            "contact" => "09087654321",
            "plate_number" => "",
            "role" => "admin"
        ]);

        $locationsArray = [
            [
                'province' => 'BATAAN',
                'city' => 'MARIVELES',
                'city_code' => '030807',
                'barangay' => 'SAN ISIDRO',
                'barangay_code' => '030807',
            ],
            [
                'province' => 'BATAAN',
                'city' => 'MARIVELES',
                'city_code' => '030807',
                'barangay' => 'ALAS-ASIN',
                'barangay_code' => '030807',
            ],
        ];

        $locations = [];

        foreach ($locationsArray as $loc) {
            $locations[] = Location::create($loc);
        }

        $vendorsPerLocation = 2;
        $productsPerVendor = 5;
        foreach ($locations as $location) {

            for ($vendorNumber = 1; $vendorNumber <= $vendorsPerLocation; $vendorNumber++) {
                $storeName = "{$location->province} Vendor {$vendorNumber}";

                $vendorAdmin = User::create([
                    "name" => "{$storeName} Admin",
                    "email" => "{$location->city}.store.{$vendorNumber}.admin@example.com",
                    "password" => "password",
                    "contact" => "09087654321",
                    "plate_number" => "",
                    "role" => "vendor"
                ]);

                $store = Store::create([
                    'vendor_id' => $vendorAdmin->id,
                    'location_id' => $location->id,
                    'store_name' => $storeName,
                    'image' => null,
                    'street' => fake()->streetName(),
                    'contact_number' => 87000,
                ]);

                $rider = Rider::create([
                    'vendor_id' => $vendorAdmin->id,
                    "name" => "{$storeName} Rider",
                    "email" => "{$location->city}.store.{$vendorNumber}.rider@example.com",
                    "password" => "password",
                    "license_number" => random_int(1000, 9999) . ' ' . random_int(1000, 9999),
                    "contact_number" => "09087654321",
                    "plate_number" => "",
                ]);

                for ($productNumber = 1; $productNumber <= $productsPerVendor; $productNumber++) {
                    Product::create([
                        'store_id' => $store->id,
                        'name' => "{$storeName} Product {$productNumber}",
                        'price' => random_int(25, 400),
                        'quantity' => random_int(1, 3000),
                        'image' => null,
                    ]);
                }
            }
        }


        $customer = User::create([
            "name" => "Customer Doe",
            "email" => "customerdoe@example.com",
            "password" => "password",
            "contact" => "09087654321",
            "plate_number" => "",
            "role" => "customer"
        ]);

        Cart::create([
            'user_id' => $customer->id,
            'store_id' => 1,
            'product_id' => 1,
            'quantity' => 1,
        ]);
        Cart::create([
            'user_id' => $customer->id,
            'store_id' => 1,
            'product_id' => 2,
            'quantity' => 3,
        ]);
        Cart::create([
            'user_id' => $customer->id,
            'store_id' => 1,
            'product_id' => 3,
            'quantity' => 2,
        ]);

        Cart::create([
            'user_id' => $customer->id,
            'store_id' => 2,
            'product_id' => 7,
            'quantity' => 10,
        ]);
        Cart::create([
            'user_id' => $customer->id,
            'store_id' => 2,
            'product_id' => 8,
            'quantity' => 1,
        ]);
        Cart::create([
            'user_id' => $customer->id,
            'store_id' => 2,
            'product_id' => 9,
            'quantity' => 1,
        ]);

        Cart::create([
            'user_id' => $customer->id,
            'store_id' => 3,
            'product_id' => 12,
            'quantity' => 10,
        ]);
        Cart::create([
            'user_id' => $customer->id,
            'store_id' => 3,
            'product_id' => 14,
            'quantity' => 1,
        ]);
        Cart::create([
            'user_id' => $customer->id,
            'store_id' => 3,
            'product_id' => 15,
            'quantity' => 1,
        ]);
    }
}
