<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = \App\Role::create([
            'name' => 'User',
            'slug' => 'user',
            'permissions' => [
                'create-comment' => true
            ]
        ]);

        $admin = \App\Role::create([
            'name' => 'Admin',
            'slug' => 'admin',
            'permissions' => [
                'create-place' => true,
                'edit-place' => true,
                'delete-place' => true,
                'create-tour' => true,
                'edit-tour' => true,
                'delete-tour' => true,
                'create-comment' => true,
                'disable-comment' => true,
                'edit-comment' => true
            ]
        ]);
    }
}
