<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
           User::truncate();
           $users = [
            [
              'last_name' => 'Admin',
              'first_name' => 'Admin First',
              'email' => 'admin@gmail.com',
              'password' => '12345678',
              'phone' => '77777777',
              'is_admin' => '1',
            ],
          ];

          foreach($users as $user)
          {
              User::create([
               'last_name' => $user['last_name'],
               'first_name' => $user['first_name'],
               'email' => $user['email'],
               'password' => Hash::make($user['password']),
               'phone' => $user['phone'],
               'is_admin' => $user['is_admin'],
             ]);
           }
    }
}
