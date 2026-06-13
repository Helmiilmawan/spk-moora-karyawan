<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alternative;
use App\Models\Criterion;
use App\Models\Rating;
use App\Models\Result;

class MooraSeeder extends Seeder
{
    public function run()
    {
      
        Result::truncate();
        Rating::truncate();
        Criterion::truncate();
        Alternative::truncate();

        $alternatives = [
            ['code' => 'A1', 'name' => 'Waduk Jatiluhur'],
            ['code' => 'A2', 'name' => 'Kolam Renang Giri Tirta'],
            ['code' => 'A3', 'name' => 'Situ Buleud'],
            ['code' => 'A4', 'name' => 'Gunung Parang'],
            ['code' => 'A5', 'name' => 'Taman Batu'],
        ];

        foreach ($alternatives as $a) {
            Alternative::create($a);
        }

        $criteria = [
            ['code'=>'C1','name'=>'Harga Tiket','type'=>'cost','weight'=>0.30,'order'=>1],
            ['code'=>'C2','name'=>'Jarak','type'=>'cost','weight'=>0.20,'order'=>2],
            ['code'=>'C3','name'=>'Fasilitas','type'=>'benefit','weight'=>0.20,'order'=>3],
            ['code'=>'C4','name'=>'Keamanan','type'=>'benefit','weight'=>0.15,'order'=>4],
            ['code'=>'C5','name'=>'Kebersihan','type'=>'benefit','weight'=>0.15,'order'=>5],
        ];

        foreach ($criteria as $c) {
            Criterion::create($c);
        }

        $values = [
            // A1
            ['A1','C1',4], ['A1','C2',3], ['A1','C3',3], ['A1','C4',4], ['A1','C5',3],
            // A2
            ['A2','C1',5], ['A2','C2',5], ['A2','C3',3], ['A2','C4',4], ['A2','C5',4],
            // A3
            ['A3','C1',3], ['A3','C2',4], ['A3','C3',4], ['A3','C4',5], ['A3','C5',4],
            // A4
            ['A4','C1',5], ['A4','C2',2], ['A4','C3',2], ['A4','C4',1], ['A4','C5',5],
            // A5
            ['A5','C1',4], ['A5','C2',3], ['A5','C3',4], ['A5','C4',3], ['A5','C5',4],
        ];

        foreach ($values as $v) {
            $alternative = Alternative::where('code', $v[0])->first();
            $criterion = Criterion::where('code', $v[1])->first();

            Rating::create([
                'alternative_id' => $alternative->id,
                'criterion_id' => $criterion->id,
                'value' => $v[2], 
            ]);
        }
    }
}
