<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class GuideTransport extends Model {
    
    use HasFactory;
    
    protected $table = 'guideTransport';
    
    public $timestamps = false;
    
    protected $fillable = [
        'marka', 'nomer', 'phoneContacts', 'address', 'mercenary', 'mercenaryName'
    ];
    
}
