<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Subscribers\Achievements\KarmaAchieve;
use App\Subscribers\Achievements\Karma50Achieve;
use App\Subscribers\Achievements\Karma100Achieve;
use App\Subscribers\Achievements\Karma500Achieve;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        foreach(\App\Achieve::all() as $a) {
            if (strstr($a->name, 'KarmaAchieve')) {
                $a->name = KarmaAchieve::class;
            } else if (strstr($a->name, 'Karma50Achieve')) {
                $a->name = Karma50Achieve::class;
            } else if (strstr($a->name, 'Karma100Achieve')) {
                $a->name = Karma100Achieve::class;
            } else if (strstr($a->name, 'Karma500Achieve')) {
                $a->name = Karma500Achieve::class;
            }
            $a->save();
        }

        // $this->call(UserTableSeeder::class);

        Model::reguard();
    }
}
