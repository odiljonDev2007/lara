<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class StatusCall extends Model {
    
    use HasFactory;
    
    protected $table = 'statusCall';
    
    public $timestamps = false;
    
    protected $fillable = [
        'name', 'color'
    ];
    
}
