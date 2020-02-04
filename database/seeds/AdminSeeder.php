<?php

use App\Role;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try{
            $admin = User::create([
                'name' => 'Admin',
                'email' => 'Admin@gmail.com',
                'password' => Hash::make('admin'),
            ]);
            $role = Role::where('slug', 'admin')->first();
            $admin -> roles() -> attach($role);
        } catch (\Exception $exception){
            log($exception->getMessage());
        }
    }
}
