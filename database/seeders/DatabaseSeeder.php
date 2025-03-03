<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Cart;
use App\Models\User;
use App\Models\Rider;
use App\Models\Store;
use App\Models\Product;
use App\Models\Category;
use App\Models\Location;
use App\Models\RiderStore;
use App\Models\UserVoucher;
use App\Models\Voucher;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

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

        $locations = $this->createLocations();
        $admin = $this->createTestAdmin();
        $riders = $this->setupRiders(locations: $locations);
        $categories = $this->createCategories();
        $stores = $this->setupStores(locations: $locations, categories: $categories);
        $this->setupStoreRiders(stores: $stores, riders: $riders);

        $customers = $this->setupCustomers(locations: $locations);
        $this->createVouchers(customers: $customers);
    }

    private function createTestAdmin(): User
    {
        $admin = User::create([
            'location_id' => 1,
            "last_name" => "Admin",
            "first_name" => "The Admin",
            "middle_name" => "Min",
            "email" => "palengke.admin@example.com",
            "password" => "helloworld",
            "contact" => "09087654321",
            "role" => "admin",
            'email_verified_at' => now(),
        ]);

        return $admin;
    }

    private function createCategories(): Collection
    {
        $categoriesArray = [
            'wet' => [
                'seafoods',
                'meat',
                'vegetables',
                'fruits',
                'tofu',
            ],
            'dry' => [
                'rice',
                'canned goods',
                'flour',
                'noodles',
                'sugar',
            ],
        ];

        $categories = [];

        foreach ($categoriesArray as $parent => $children) {
            $parentCategory = Category::create([
                'name' => $parent,
            ]);

            $categories[] = $parentCategory;

            foreach ($children as $child) {
                $categories[] = Category::create([
                    'parent_id' => $parentCategory->id,
                    'name' => $child,
                ]);
            }
        }

        return collect($categories);
    }

    private function createLocations(): Collection
    {
        $locationsArray = [
            [
                'province' => 'BATAAN',
                'city' => 'MARIVELES',
                'city_code' => '030807',
                'barangay' => 'SAN ISIDRO',
                'barangay_code' => '030807',
                'shipping_fee' => '49',
            ],
            [
                'province' => 'BATAAN',
                'city' => 'ABUCAY',
                'city_code' => '030801',
                'barangay' => 'BANGKAL',
                'barangay_code' => '030801',
                'shipping_fee' => '59',
            ],
        ];

        $locations = [];

        foreach ($locationsArray as $loc) {
            $locations[] = Location::create($loc);
        }

        return collect($locations);
    }

    private function createRider(Location $location, string $email): Rider
    {
        $user = User::create([
            'location_id' => $location->id,
            "last_name" => fake()->lastName(),
            "first_name" => fake()->firstName(),
            "middle_name" => fake()->lastName(),
            "email" => $email,
            "password" => "password",
            "contact" => "09087654321",
            "role" => "rider",
            'email_verified_at' => now(),
        ]);

        return Rider::create([
            'user_id' => $user->id,
            'license_number' => fake()->numberBetween(100000, 999999),
            'plate_number' => fake()->randomLetter() . fake()->randomLetter() . fake()->randomLetter() . ' ' . fake()->randomNumber(1, 9999),
        ]);
    }

    private function setupRiders(Collection $locations): Collection
    {
        $riderEmails = [
            'rider1@example.com',
            'rider2@example.com',
            'rider3@example.com',
            'rider4@example.com',
            'rider5@example.com',
        ];

        $riders = [];
        foreach ($riderEmails as $riderEmail) {
            $riders[] = $this->createRider(location: $locations->random(), email: $riderEmail);
        }

        return collect($riders);
    }

    private function createStore(Location $location, string $storeName): Store
    {
        $email = Str::snake("{$storeName}.admin@example.com");
        $vendorAdmin = User::create([
            'location_id' => $location->id,
            "last_name" => 'Admin',
            "first_name" => "$storeName Admin",
            "middle_name" => 'Vendor',
            "email" => $email,
            "password" => "password",
            "contact" => "09087654321",
            "role" => "vendor",
            'email_verified_at' => now(),
        ]);

        $store = Store::create([
            'vendor_id' => $vendorAdmin->id,
            'location_id' => $location->id,
            'store_name' => $storeName,
            'image' => null,
            'street' => fake()->streetName(),
            'contact_number' => "09087654321",
            'is_verified' => true,
        ]);

        return $store;
    }

    private function setupStores(Collection $locations, Collection $categories): Collection
    {
        $storesList = [
            'Aling Nena' => $locations->random(),
            'Tita Bebe' => $locations->random(),
            'Mareng Juana' => $locations->random(),
            'Lita General Goods' => $locations->random(),
            'Caloy Wholesale' => $locations->random(),
        ];

        $stores = [];
        foreach ($storesList as $storeName => $location) {
            $stores[] = $this->createStore(location: $location, storeName: $storeName);
        }

        foreach ($stores as $store) {
            for ($i = 0; $i < 10; $i++) {
                $this->createProduct(store: $store, category: $categories->random());
            }
        }

        return collect($stores);
    }

    private function createProduct(Store $store, Category $category): Product
    {
        $name = fake()->word();

        return  Product::create([
            'category_id' => $category->id,
            'store_id' => $store->id,
            'name' => "{$store->store_name} Product {$name}",
            'measurement' => fake()->randomElement(['piece', '1 kg', '1/2 kg', '1/4 kg', '200ml', '500ml', '1 Liter']),
            'price' => random_int(25, 400),
            'quantity' => random_int(1, 3000),
            'image' => null,
        ]);
    }

    private function setupCustomers(Collection $locations): Collection
    {
        $customersList = [
            'customer1@example.com',
            'customer2@example.com',
            'customer3@example.com',
            'customer4@example.com',
            'customer5@example.com',
        ];

        $customers = [];
        foreach ($customersList as $index => $email) {
            $customers[] = User::create([
                "location_id" => $locations->random()->id,
                "last_name" => "Doe",
                "first_name" => "Customer {$index}",
                "middle_name" => "John",
                "email" => $email,
                "password" => "password",
                "contact" => "09087654321",
                "role" => "customer",
                'email_verified_at' => now(),
            ]);
        }

        return collect($customers);
    }

    private function setupStoreRiders(Collection $stores, Collection $riders): void
    {
        foreach ($stores as $store) {
            $rider = $riders->shift();
            RiderStore::create([
                'store_id' => $store->id,
                'rider_id' => $rider->id,
            ]);
        }
    }

    private function createVouchers(Collection $customers): void
    {
        $voucherList = [
            'bigbente' => [
                'value' => 20,
                'is_percent' => false,
                'min_order_price' => 199,
                'description' => "Get 20 pesos off on your next order.",
            ],
            'supersale' => [
                'value' => 30,
                'is_percent' => true,
                'min_order_price' => 799,
                'description' => "Get 30% off in our mega super sale!!!.",
            ],
        ];

        $vouchers = [];

        foreach ($voucherList as $name => $data) {
            $vouchers[] = Voucher::create([
                'code' => $name,
                'min_order_price' => $data['min_order_price'],
                'value' => $data['value'],
                'description' => $data['description'],
                'is_percent' => $data['is_percent'],
                'is_deleted' => false,
            ]);
        }

        $expiration = now()->addDays(10);
        foreach ($customers as $customer) {
            foreach ($vouchers as $voucher) {
                for ($i = 0; $i < 3; $i++) {
                    UserVoucher::create([
                        'user_id' => $customer->id,
                        'voucher_id' => $voucher->id,
                        'used_at' => null,
                        'expired_at' => $expiration,
                    ]);
                }
            }
        }
    }
}