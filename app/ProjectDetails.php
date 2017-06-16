<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Illuminate\Support\Facades\Auth;

class ProjectDetails extends Model
{
    protected $table = 'projects';
    protected $fillable = ['project', 'programmer_id'];

    public function users()
    {
        return $this->hasMany(User::class, "id", "programmer_id");
    }

    public function scopeProjectsList($query, $id = null)
    {
         $query
		        ->join('users', 'users.id', '=', 'projects.programmer_id')
                ->select('users.name', 'projects.*')
                ->orderBy("projects.id", "desc");
         if($id == null) return $query->get(); 
         else {
            return $query
                    ->where("projects.programmer_id", $id)
                    ->get();
         }  
	 }

}
