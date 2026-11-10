<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Contract;
use App\Models\Customer;
use App\Models\Organization;
use App\Models\OrganizationMembership;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

final class DemoSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            $this->command?->error('DemoSeeder não pode ser executado em produção.');
            return;
        }

        $password = (string) env('DEMO_PASSWORD', 'CurupiraDemo-2026!');
        $user = User::query()->updateOrCreate(['email' => 'demo@curupira.local'], ['name' => 'Rafael Mendes', 'password' => Hash::make($password), 'email_verified_at' => now()]);
        $organization = Organization::query()->updateOrCreate(['slug' => 'curupira-demo'], ['name' => 'Curupira Demo', 'status' => 'active']);
        OrganizationMembership::query()->updateOrCreate(['organization_id' => $organization->getKey(), 'user_id' => $user->getKey()], ['role' => 'organization_administrator', 'permissions' => [], 'joined_at' => now()]);
        Company::query()->updateOrCreate(['organization_id' => $organization->getKey(), 'document' => '04252011000110'], ['legal_name' => 'Curupira Tecnologia Demo LTDA', 'trade_name' => 'Curupira Demo', 'tax_regime' => 'simples_nacional']);
        $customer = Customer::query()->updateOrCreate(['organization_id' => $organization->getKey(), 'document' => '52998224725'], ['name' => 'TechFlow Solutions Demo', 'email' => 'cliente@demo.local', 'status' => 'active']);
        Contract::query()->updateOrCreate(['organization_id' => $organization->getKey(), 'customer_id' => $customer->getKey(), 'title' => 'Consultoria de produto — Demo'], ['contract_type' => 'indefinite', 'billing_model' => 'hourly', 'status' => 'active', 'starts_on' => now()->startOfMonth(), 'hourly_rate' => '100.00']);

        $this->command?->info('Demo criada: demo@curupira.local / '.$password);
    }
}
