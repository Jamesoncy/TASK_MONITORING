<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectDetailsItem extends Model
{
    protected $table = 'project_details';
    protected $fillable = ['project_details_id', 'project_name', 'programmer_id', 'time_allocation_status0', 'overtime_final_status', 'start_date' ,'end_date', 'remarks', 'overtime_end_date'];
    public $timestamps = true;
    public function scopeGetProjectItems($query, $id)
    {
        return $query->where('project_details_id', $id)->get();
	}
}
