<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\MembershipCategory;
use App\Models\MembershipLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $categories = ['Student', 'Regular', 'Senior Citizen', 'Corporate'];
        foreach ($categories as $category) {
            MembershipCategory::create(['name' => $category]);
        }

        $levels = [
            ['name' => 'Basic', 'fee' => 399.00, 'validity_period' => 12, 'benefits' => 'Standard system access and general community support.'],
            ['name' => 'Silver', 'fee' => 499.00, 'validity_period' => 12, 'benefits' => 'Advanced features, priority support, and monthly activity reports.'],
            ['name' => 'Gold', 'fee' => 999.00, 'validity_period' => 12, 'benefits' => 'All Silver features, plus exclusive premium content, and 1-on-1 consultations.'],
            ['name' => 'Platinum', 'fee' => 1499.00, 'validity_period' => 12, 'benefits' => 'Full lifetime-tier access, 24/7 dedicated support, and VIP event invites.'],
        ];
        foreach ($levels as $level) {
            MembershipLevel::create($level);
        }
    }
}