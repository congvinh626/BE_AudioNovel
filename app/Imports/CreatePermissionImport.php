<?php

namespace App\Imports;

use App\Models\Permission;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CreatePermissionImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $key => $row) {
            $permission = new Permission([
                'slug' => $row['slug'],
                'name' => $row['name'],
            ]);
            $permission->save();

            $role_id = 1;
            if($row['role'] == 'teacher'){
                $role_id = 2;
            }elseif($row['role'] == 'student'){
                $role_id = 3;
            }
            $permission->roles()->attach($role_id);
        }
        return 'ok';
        // return Xxx::all();
        // return new Test([
        //     'tl1'=> $row["STT"],
        //     // 'tl2'=> $row[1],
        // ]);
    }
}
