<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\PixKey;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        match (config('codepix.credential')) {
            'd349f80bacef812dac8f66bd0164ba17864a6a93' => $this->new2(),
            default => $this->new()
        };
    }

    private function new()
    {
        $account = Account::factory([
            'external_id' => 'a1629637-c5e9-4a83-bd07-454b6c0a1e6b',
        ])->create();

        PixKey::factory([
            'account_id' => $account->id,
            'external_id' => 'e2989ccf-eb1b-4766-a5f7-697af0236595',
            'kind' => 'random',
            'key' => 'cba4da01-a262-425c-a2d2-457319e8a666'
        ])->create();

        $account = Account::factory([
            'external_id' => '58234d11-78f1-45c2-ad40-7c255390b818',
        ])->create();

        PixKey::factory([
            'account_id' => $account->id,
            'external_id' => '064df7c3-c86e-4d05-a630-fb732698209e',
            'kind' => 'random',
            'key' => 'e0ab5f25-34a6-4c2d-89d7-a0f0ba52c0f0'
        ])->create();
    }

    private function new2()
    {
        $account = Account::factory([
            'external_id' => '013903b5-a12c-4981-8fe0-60d37758b359',
        ])->create();

        PixKey::factory([
            'account_id' => $account->id,
            'external_id' => 'db4af86e-35e8-4a07-9072-037b5717aa08',
            'kind' => 'random',
            'key' => 'bd07b339-a05f-408f-9640-c461c29cb243'
        ])->create();

        $account = Account::factory([
            'external_id' => '50809e5f-22dc-41e7-b95e-25e764bae71a',
        ])->create();

        PixKey::factory([
            'account_id' => $account->id,
            'external_id' => '064df7c3-c86e-4d05-a630-fb732698209e',
            'kind' => 'random',
            'key' => '33047865-fbb2-4a3e-a14d-4287f7abfd37'
        ])->create();
    }
}
