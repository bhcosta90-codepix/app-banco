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
            'ef8e3bfa3d4e2af40c519487f19451f19d09f0a3' => $this->new2(),
            '31a2cbedfb091510d8a18e3031dff49bbd9c729c' => $this->new3(),
            default => $this->new()
        };
    }

    private function new()
    {
        $account = Account::factory([
            'uuid' => '0204734b-3f3f-4356-8a30-359d01326e95',
            'external_id' => 'a1629637-c5e9-4a83-bd07-454b6c0a1e6b',
        ])->create();

        PixKey::factory([
            'account_id' => $account->id,
            'external_id' => 'e2989ccf-eb1b-4766-a5f7-697af0236595',
            'kind' => 'random',
            'key' => 'cba4da01-a262-425c-a2d2-457319e8a666'
        ])->create();

        $account = Account::factory([
            'uuid' => '451eabe2-b334-4a45-a816-4ea4dfd20727',
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
            'uuid' => 'f5a3a134-2fc9-4ad3-89ec-3cd097f7ff5f',
            'external_id' => '013903b5-a12c-4981-8fe0-60d37758b359',
        ])->create();

        PixKey::factory([
            'account_id' => $account->id,
            'external_id' => 'db4af86e-35e8-4a07-9072-037b5717aa08',
            'kind' => 'random',
            'key' => 'bd07b339-a05f-408f-9640-c461c29cb243'
        ])->create();

        $account = Account::factory([
            'uuid' => '7cb9c838-f5e7-4335-8eea-a78932fcbdaf',
            'external_id' => '50809e5f-22dc-41e7-b95e-25e764bae71a',
        ])->create();

        PixKey::factory([
            'account_id' => $account->id,
            'external_id' => '064df7c3-c86e-4d05-a630-fb732698209e',
            'kind' => 'random',
            'key' => '33047865-fbb2-4a3e-a14d-4287f7abfd37'
        ])->create();
    }

    private function new3()
    {
        $account = Account::factory([
            'uuid' => 'f3db6eb0-0983-472e-911d-714b0b7d03db',
            'number' => str_pad('1', 6, '0', STR_PAD_LEFT),
        ])->create();

        PixKey::factory([
            'account_id' => $account->id,
            'uuid' => '147e0759-4b99-4bd8-a941-d0b5d1ce2a45',
            'kind' => 'random',
            'key' => '49806ffe-c8c0-4a73-9083-18b6e94a408e'
        ])->create();

        $account = Account::factory([
            'uuid' => 'becb8df8-d12c-49d1-ba29-acc2e66ba372',
            'number' => str_pad('2', 6, '0', STR_PAD_LEFT),
        ])->create();

        PixKey::factory([
            'account_id' => $account->id,
            'uuid' => '7dc0789d-c0ab-465a-b281-0f01cf858ce7',
            'kind' => 'random',
            'key' => '60bcbdfa-03ce-406a-9686-6a3d018a692c'
        ])->create();
    }
}
