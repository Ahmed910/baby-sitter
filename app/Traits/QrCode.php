<?php

namespace App\Traits;

trait QrCode
{
    protected static function generateQrCode($dir_name,$data)
    {

        if (!\File::isDirectory(storage_path('app/public/images/'.$dir_name.'/'))) {
            \File::makeDirectory(storage_path('app/public' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . $dir_name . DIRECTORY_SEPARATOR), 0777, true);
        }

        $file_name = time() . "_" . $data->id . "_qr_code.png";

        \QrCode::errorCorrection('H')
            ->format('png')
            ->encoding('UTF-8')
            //  ->merge(public_path('dashboardAssets/images/cover/cover_sm.png'), .2 ,true)
            ->size(500)
            ->color(0, 0, 0)
            ->generate((string)$data->id, storage_path('app/public/images/'.$dir_name.'/' . $file_name));
       
        $data['qr_code'] = $file_name;
        $data->save();

    }
}

?>
