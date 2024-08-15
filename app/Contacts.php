<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Contacts extends Model {
    
    use HasFactory;
    
    protected $table = 'contacts';
    
    public $timestamps = false;
    
    protected $fillable = [
        'phone_contacts', 'name_contacts', 'partner'
    ];
    
}
