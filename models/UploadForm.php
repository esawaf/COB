<?php

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model {

    /**
     * @var UploadedFile[]
     */
    public $edocFiles;
    public $edocFilePaths;

    public function rules() {
        return [
            [['edocFiles'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg,pdf','maxFiles'=>4],
        ];
    }

    public function upload() {
//        if ($this->validate()) {
            foreach ($this->edocFiles as $file){
                $filePath = 'edocs/' . $this->uuid() . '.' . $file->extension;
                $file->saveAs($filePath);
                $this->edocFilePaths[] = $filePath;
            }
            return true;
//        } else {
//            return false;
//        }
    }

    private function uuid($prefix = '') {


        $chars = md5(uniqid(mt_rand(), true));


        $uuid = substr($chars, 0, 8) . '-';


        $uuid .= substr($chars, 8, 4) . '-';


        $uuid .= substr($chars, 12, 4) . '-';


        $uuid .= substr($chars, 16, 4) . '-';


        $uuid .= substr($chars, 20, 12);


        return $prefix . $uuid;
    }

}
?>