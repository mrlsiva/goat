<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Role;
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
                'role_id' => 1,
                'name' => 'Super Admin',
                'email' => 'super_admin@admin.com',
                'phone' => '1234567890',
                'password' => \Hash::make('Admin@2025'),
                'is_active' => 1
            ]);

            $role = Role::where('id',1)->first()->name;
            $user->assignRole($role);

            DB::commit();
        }
        catch (Exception $e) {

            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
