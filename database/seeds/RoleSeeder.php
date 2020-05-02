<?php

use Illuminate\Database\Seeder;
use App\Models\Roles;
class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Roles::create([
            'name'=>'Admin',
            'description'=>'this role is for admin'
        ]);
        Roles::create([
            'name'=>'Customer',
            'description'=>'this role is for customer'
        ]);
        Roles::create([
            'name'=>'Delivery',
            'description'=>'this role is for delivery'
        ]);
    }
}
