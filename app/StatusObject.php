<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class StatusObject extends Model {
    
    use HasFactory;
    
    protected $table = 'statusObject';
    
    public $timestamps = false;
    
    protected $fillable = [
        'name'
    ];
    
}
