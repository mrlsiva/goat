<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {

            DB::beginTransaction();
            $user = User::create([
                'name' => 'Super Admin',
                'email' => 'super_admin@admin.com',
                'password' => \Hash::make('Admin@2025'),
            ]);

            DB::commit();
        }
        catch (Exception $e) {

            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
