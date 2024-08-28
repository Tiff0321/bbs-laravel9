<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // 生成数据集合
        User::factory()->count(13)->create();

        // 处理第一个用户的数据
        $user = User::find(1);
        $user->name = 'tiff';
        $user->email = '2325287709@qq.com';
        $user->password = bcrypt('11111111');
        $user->avatar = 'http://localhost:8000/uploads/images/avatars/202408/26/12_1724600634_366E4rwBBr.png';
        $user->save();
    }
}
