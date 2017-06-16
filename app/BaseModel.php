<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public function selectQuery($sql){
    	return DB::select($sql);
    }

    public function sqlStatement($sql){
    	DB::statement($sql);
    }
}
