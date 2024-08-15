<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class StatusID extends Model {
    
    use HasFactory;
    
    protected $table = 'statusID';
    
    public $timestamps = false;
    
    protected $fillable = [
        'name', 'color'
    ];
    
}
