<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model {
    public static function whereCrypt($column, $value) {
        return self::whereRaw('MD5(CONCAT("--",'. $column .',"--")) = ?', [$value]);
    }
}