<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class ContactsDelivery extends Model {
    
    use HasFactory;
    
    protected $table = 'contactsDelivery';
    
    public $timestamps = false;
    
    protected $fillable = [
        'nameContacts', 'phoneContacts', 'partner'
    ];
    
}
