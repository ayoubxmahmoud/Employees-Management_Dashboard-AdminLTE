<?php
 
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run() {
        User::create([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'lastname' => 'Mr',
            'firstname' => 'admin',
            'password' => Hash::make('admin'),
        ]);
    }
}
