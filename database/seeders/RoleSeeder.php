<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Role::create(['name' => 'owner', 'team_id' => 1]);
        Role::create(['name' => 'admin', 'team_id' => 1]);
        Role::create(['name' => 'staff', 'team_id' => 1]);
    }
}
