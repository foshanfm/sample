<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Status;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //为前三个用户生成共 100 条微博假数据。
        $user_ids = ['1','2','3'];
        //通过 app() 方法来获取一个 Faker 容器 的实例
       $faker = app(Faker\Generator::class);

       $statuses = factory(Status::class)->times(100)
                                         ->make()
                                         ->each(function ($status) use ($faker, $user_ids) {
           $status->user_id = $faker->randomElement($user_ids);
       });

       Status::insert($statuses->toArray());
    }
}
