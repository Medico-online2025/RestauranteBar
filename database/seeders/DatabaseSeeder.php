<?php

namespace Database\Seeders;

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
        // \App\Models\User::factory(10)->create();
        $this->call(ProfileSeeder::class);
        $this->call(TypeDocumentSeeder::class);
        $this->call(CurrencySeeder::class);
        $this->call(UnitSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(IdentityDocumentTypeSeeder::class);
        $this->call(IgvTypeAffectionSeeder::class);
        $this->call(CreditNoteTypeSeeder::class);
        $this->call(DebitNoteTypeSeeder::class);
        $this->call(DepartmentSeeder::class);
        $this->call(ProvinceSeeder::class);
        $this->call(PurchaseDescriptionSeeder::class);
        $this->call(ProviderSeeder::class);
        $this->call(DistrictSeeder::class);
        $this->call(SunatUserSeeder::class);
        $this->call(BusineSeeder::class);
        $this->call(ClientSeeder::class);
        $this->call(SerieSeeder::class);
        $this->call(PayModeSeeder::class);
        $this->call(TableSeeder::class);
        $this->call(RoomSeeder::class);
        $this->call(CashSeeder::class);
        $this->call(ArchingCashSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
    }
}
