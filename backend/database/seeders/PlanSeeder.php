<?php

namespace Database\Seeders;

use App\Models\PlanPay;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PlanPay::create([
            'title' => 'Free',
            'price' => '$0',
            'description' => 'Freer for you to post the post of 10 produt first',
            'recommended' => false
        ]);

        PlanPay::create([
            'title' => 'Standard',
            'price' => '$10 per 10 posts',
            'description' => 'Unlimited product posts, Charge if posts exceed 10, Easy and continuous posting still 20 posts',
            'recommended' => true
        ]);

        PlanPay::create([
            'title' => 'Pro',
            'price' => '$20 per month',
            'description' => 'Unlimited product posts, No additional charges for unlimited posts, 2.5% discount every month, Additional premium features',
            'recommended' => false
        ]);
    }
}
