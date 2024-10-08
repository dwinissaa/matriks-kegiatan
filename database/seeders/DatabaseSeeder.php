<?php

namespace Database\Seeders;

use App\Models\Users;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ViewErrorBag;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\Users::factory(10)->create();
        // Users::create([
        //     'nip' => '340000000',
        //     'email'=> 'budi.yuniarto@bps.go.id',
        //     'nama' => 'Budi Yuniarto',
        //     'jabatan' => 'Prakom',
        //     'password' => Hash::make(123456),
        //     'admin' => '0',
        // ]);

        // Role:
        // 1: admin
        // 2: ketua tim
        // 3: viewer
        
        // 0: anggota

        // 0: ANGGOTA

        // 3: ROLE VIEWERS
        $viewers = [
            '340011421', // p'herman
            // '340015564' //p'jai
        ];
        foreach ($viewers as $v) {
            $admin_app = Users::findOrFail($v);
            $admin_app->update([
                'password' => Hash::make($v),
                'admin' => 3,
            ]);
        };

        // 2: ROLE KETUA TIM
        $ketims = DB::table('tim')->join('users', 'users.nip', '=', 'tim.nip_ketim', 'left')->get();
        foreach ($ketims as $k) {
            $admin_app = Users::findOrFail($k->nip);
            $admin_app->update([
                'password' => Hash::make($k->nip),
                'admin' => 2,
            ]);
        };
        
        // 1: ADMIN
        $admins = [
            '340060601', // dwi
            '340015564', // p'jai
        ];
        foreach ($admins as $a) {
            $admin_app = Users::findOrFail($a);
            $admin_app->update([
                'password' => Hash::make($a),
                'admin' => 1,
            ]);
        };

        
    }
}
