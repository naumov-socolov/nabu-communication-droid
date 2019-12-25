<?php

use App\Domain\Users\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        factory(User::class)->create();
    }
}
