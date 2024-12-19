<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Log;

class UploadService
{
    public function store($request)
    {

        if ($request->hasFile('file')) {
            try {
                $name = $request->file('file')->getClientOriginalName();
                $pathFull = 'images';

                $request->file('file')->storeAs(
                    'public/' . $pathFull,
                    $name
                );

                $hinhanh = $request->file('file');
                $getlinkhinh = $hinhanh->getClientOriginalName();
                $destinationPath = public_path('template/images');
                $hinhanh->move($destinationPath, $getlinkhinh);

                //$getlinkhinh_new = "template\\images\\".$getlinkhinh;
                //echo "".$request->file('upload');



                return '/template/' . $pathFull . '/' . $name;
            } catch (\Exception $error) {
                return false;
            }
        }
    }
}
