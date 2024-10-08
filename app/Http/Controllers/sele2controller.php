<?php
// dd($import->getRowCount());
        // dd($headings);
        // dd($import->failures());
        // dd($import->errors());
        // if ($import->failures()->isNotEmpty()) {
        //     $failures = $import->failures();
        //     return back()->with(compact('failures'));
        // }]
        // $data = Excel::Import(new pekerjaanImport($id_keg), $file);

        // collect(head($data))->each(function ($row, $key) {
        //     $validator = Validator::make($row, [
        //         'nip' => 'required|digits:18',
        //         'nama_pegawai' => 'required',
        //         'email' => 'required'
        //     ]);

        //     //NOT BLANK
        //     if ($row['nip'] !== null) {
        //         //Check the validation
        //         if ($validator->fails()) {
        //             return back()
        //                 ->withErrors($validator);
        //         } else {
        //             $user = Pekerjaan::whereRaw('LENGTH(id)=18')->get()->where('id', $row['nip'])->first();
        //             if ($user !== null) {
        //                 //IF EXISTED
        //                 $user->update([
        //                     'nama' => $row['nama_pegawai'],
        //                     'email' => $row['email'],
        //                     'updated_at' => date('Y-m-d h:m:s')
        //                 ]);
        //             } else {
        //                 //IF NOT EXISTED
        //                 $user = Pekerjaan::insert([
        //                     'id' => $row['nip'],
        //                     'email' => $row['email'],
        //                     'nama' => $row['nama_pegawai'],
        //                     'password'=>bcrypt("123456"),
        //                     'created_at' => date('Y-m-d h:m:s')
        //                 ]);
        //             }
        //             return back()->with('status_import_pekerjaan', 'Excel file has succesfully imported.');
        //         }
        //     }
        // });


        $request->validate([
                'file' => 'required|mimes:xlsx, csv, xls'
            ]);
    
            // try {
                $pekImport = new pekerjaanImport($id_keg);
                $file = $request->file('file')->store('import');
                $pekImport->import($file);
                $ketemu = false;
                $sheetName = $pekImport->getSheetNames();
                // dd($sheetName);
                foreach ($sheetName as $key => $value) {
                    if ($value == 'Template Import') {
                        // (new pekerjaanSheetImport($id_keg))->import($request->file('file')->store('import'));
                        $ketemu = true;
                    }
                    break;
                }
                if ($ketemu) {
                    return back()->with('status_import_pekerjaan', 'Excel file imported successfully.');
                } else {
                    return back()->with('status_error_import_pekerjaan', 'Nama Sheet harus: "Template Import"');
                }
            } catch (Exception $e) {
                return back()->with('status_error_import_pekerjaan', $e->getMessage());
            }