<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\City;
use App\Models\Country;
use App\Models\InvestorProfile;
use App\Models\State;
use App\Models\User;
use App\Models\VendorProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure dependent profiles are truncated before users if FK checks are enabled
        // or if DatabaseSeeder doesn't handle global FK_CHECKS=0
        VendorProfile::truncate();
        InvestorProfile::truncate();
        User::truncate(); // Truncate users after dependent tables

        // --- Create/Get default location data ---
        $country = Country::firstOrCreate(['name' => 'United States']);
        $state = State::firstOrCreate(['name' => 'California', 'country_id' => $country->id]);
        $city = City::firstOrCreate(['name' => 'Los Angeles', 'state_id' => $state->id, 'country_id' => $country->id]);

        // --- 1. Customer User ---
        $customerUser = User::create([
            'name' => 'John Customer',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'), // 'password'
            'avatar' => 'users/avatars/customer_avatar.jpg',
            'phone' => '123-456-7890',
            'address' => '123 Main St',
            'zip_code' => '90001',
            'country_id' => $country->id,
            'state_id' => $state->id,
            'city_id' => $city->id,
            'role' => UserRole::Customer,
            'is_active' => true,
            'date_of_birth' => '1990-05-15',
            'gender' => 'Male',
            'slug' => Str::slug('John Customer'),
            'email_verified_at' => now(),
        ]);
        // No profile needed for customer

        // --- 2. Vendor User ---
        $vendorUser = User::create([
            'name' => 'Alice Vendor',
            'email' => 'vendor@example.com',
            'password' => Hash::make('password'), // 'password'
            'avatar' => 'users/avatars/vendor_avatar.jpg',
            'phone' => '987-654-3210',
            'address' => '456 Market St',
            'zip_code' => '90210',
            'country_id' => $country->id,
            'state_id' => $state->id,
            'city_id' => $city->id,
            'role' => UserRole::Vendor,
            'is_active' => true,
            'date_of_birth' => '1985-11-20',
            'gender' => 'Female',
            'slug' => Str::slug('Alice Vendor'),
            'email_verified_at' => now(),
        ]);

        // Create Vendor Profile for Alice Vendor
        $vendorUser->vendorProfile()->create([
            'shop_name' => 'Alice\'s Amazing Shop',
            'shop_slug' => Str::slug('Alices Amazing Shop'),
            'shop_description' => 'Your one-stop shop for unique handcrafted items and more!',
            'shop_logo' => 'vendors/logos/alice_shop_logo.png',
            'shop_banner' => 'vendors/banners/alice_shop_banner.jpg',
            'phone' => $vendorUser->phone,
            'email' => $vendorUser->email,
            'address' => 'Suite 100, 456 Market St',
            'zip_code' => $vendorUser->zip_code,
            'country_id' => $country->id,
            'state_id' => $state->id,
            'city_id' => $city->id,
            'bank_name' => 'First National Bank',
            'bank_account_number' => '1234567890',
            'bank_account_holder' => 'Alice Vendor',
            'commission_rate' => 10.00, // 10% commission
            'is_approved' => true,
            'is_active' => true,
        ]);


        // --- 3. Investor User ---
        $investorUser = User::create([
            'name' => 'Bob Investor',
            'email' => 'investor@example.com',
            'password' => Hash::make('password'), // 'password'
            'avatar' => 'users/avatars/investor_avatar.jpg',
            'phone' => '111-222-3333',
            'address' => '789 Capital Ave',
            'zip_code' => '10001',
            'country_id' => $country->id,
            'state_id' => $state->id,
            'city_id' => $city->id,
            'role' => UserRole::Investor,
            'is_active' => true,
            'date_of_birth' => '1978-01-01',
            'gender' => 'Male',
            'slug' => Str::slug('Bob Investor'),
            'email_verified_at' => now(),
        ]);

        // Create Investor Profile for Bob Investor
        $investorUser->investorProfile()->create([
            'company_name' => 'Global Ventures Inc.',
            'investment_focus' => 'E-commerce, Tech Startups',
            'website' => 'https://www.globalventures.com',
            'contact_person_name' => 'Bob Investor',
            'contact_person_email' => $investorUser->email,
            'contact_person_phone' => $investorUser->phone,
            'address' => 'Suite 500, 789 Capital Ave',
            'zip_code' => $investorUser->zip_code,
            'country_id' => $country->id,
            'state_id' => $state->id,
            'city_id' => $city->id,
            'min_investment_amount' => 100000.00,
            'max_investment_amount' => 5000000.00,
            'notes' => 'Looking for scalable and innovative e-commerce platforms.',
            'is_approved' => true,
            'is_active' => true,
        ]);


        // Re-enable foreign key checks (if DatabaseSeeder doesn't handle globally)
        // \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
