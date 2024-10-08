<?php
public function getUser($role)
    {
        $user = DB::table('alokasikegiatan')
            ->leftJoin('pekerjaan', function ($join) {
                $join->on('pekerjaan.id_anggota', '=', 'alokasikegiatan.id_anggota')
                    ->on('pekerjaan.id_keg', '=', 'alokasikegiatan.id_keg');
            })
            ->join('kegiatan', 'kegiatan.id_keg', '=', 'alokasikegiatan.id_keg', 'left');
        $unique_keg = (object) array();
        // choose role
        if ($role == 'pegawai') {
            $user = $user->join('users', 'users.nip', '=', 'alokasikegiatan.id_anggota', 'left')->whereNotNull('users.nip')
                ->selectSub(function ($query) {
                    $query->selectRaw(1);
                }, 'pegawai');
            $unique_keg = $user->select('kegiatan.id_keg', 'kegiatan.kegiatan')->distinct()->get();
        } else if ($role == 'mitra') {
            $user = $user->join('mitra', 'mitra.sobatid', '=', 'alokasikegiatan.id_anggota', 'left')->whereNotNull('mitra.sobatid')
                ->selectSub(function ($query) {
                    $query->selectRaw(0);
                }, 'pegawai');
            $unique_keg = $user->select('kegiatan.id_keg', 'kegiatan.kegiatan')->distinct()->get();
        }
        $user = $user->select('alokasikegiatan.id_anggota', 'nama', DB::raw('count(id_pekerjaan) as total_pekerjaan'))
            ->groupBy('alokasikegiatan.id_anggota');
        $user = $user->orderBy('id_anggota', 'ASC');

        // TEST
        $result = (object) array();
        for ($i = 0; $i < count($unique_keg); $i++) {
            $id_keg = $unique_keg[$i]->id_keg;
            $id_keg2 = 'keg_' . $unique_keg[$i]->id_keg;

            $temp = DB::table($user, 't2')->select(DB::raw("t2.id_anggota,t2.nama, IFNULL(query.jlh_pekerjaan,0) as '$id_keg2',t2.total_pekerjaan"))
                ->leftJoin(DB::raw(
                    "( SELECT id_anggota, COUNT(id_pekerjaan) as jlh_pekerjaan 
                    FROM pekerjaan
                    WHERE id_keg='$id_keg'
                    GROUP BY id_anggota
                ) as query"
                ), function ($join) {
                    $join->on('query.id_anggota', '=', 't2.id_anggota');
                })->orderBy('t2.id_anggota', 'ASC')->get();
            if ($i == 0) {
                $result = $temp;
            }
            $result = $result->map(function ($elem, $index) use ($id_keg2, $temp) {
                $elem->$id_keg2 = $temp[$index]->$id_keg2;
                return $elem;
            });
        }
        return [$result, $unique_keg];
    }

    public function index_matkeg(Request $request)
    {
        $tahun = DB::table('alokasikegiatan')->join('kegiatan', 'kegiatan.id_keg', '=', 'alokasikegiatan.id_keg', 'left')->select('tahun')->distinct()->get();

        [$result_p, $unique_keg_p] = $this->getUser('pegawai');
        [$result_m, $unique_keg_m] = $this->getUser('mitra');

        // dd($unique_keg_m);

        if ($request->ajax()) {
            // FILTER HERE
            if ($request->tahun_filter != null) {
                // pending dulu
            }

            // Draw Pegawai
            $dt_p = Datatables::of($result_p);
            $dt_p->addColumn('id_anggota', function ($data) {
                return $data->id_anggota;
            })
                ->addColumn('nama', function ($data) {
                    return $data->nama;
                });
            for ($i = 0; $i < count($unique_keg_p); $i++) {
                $idk = 'keg_' . $unique_keg_p[$i]->id_keg;
                $dt_p->addColumn($idk, function ($data) use ($idk) {
                    return $data->$idk;
                });
            }
            $dt_p->addColumn('total_pekerjaan', function ($data) {
                return $data->total_pekerjaan;
            });
            return $dt_p->make(true);
        }
        return view('/view-matkeg', compact('tahun', 'result_p', 'unique_keg_p', 'result_m', 'unique_keg_m'));
    }


    public function index_matkeg_mitra(Request $request)
    {
        [$result_m, $unique_keg_m] = $this->getUser('mitra');

        if ($request->ajax()) {
            // FILTER HERE
            if ($request->tahun_filter != null) {
                // pending dulu
            }
            // Draw Mitra
            $dt_m = Datatables::of($result_m);
            $dt_m->addColumn('id_anggota', function ($data) {
                return $data->id_anggota;
            })
                ->addColumn('nama', function ($data) {
                    return $data->nama;
                });
            for ($i = 0; $i < count($unique_keg_m); $i++) {
                $idk = 'keg_' . $unique_keg_m[$i]->id_keg;
                $dt_m->addColumn($idk, function ($data) use ($idk) {
                    return $data->$idk;
                });
            }
            $dt_m->addColumn('total_pekerjaan', function ($data) {
                return $data->total_pekerjaan;
            });
            return $dt_m->make(true);
        }
        return view('/view-matkeg');
    }
?>