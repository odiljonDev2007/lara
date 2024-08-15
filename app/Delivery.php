<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Delivery extends Model
{
    use HasFactory;

    protected $table = 'delivery';

    protected $fillable = [
        'title',
        'user',
        'user_original',
        'phoneContacts',
        'nomer2',
        'phoneContacts2',
        'partner',
        'phone_contacts_partner',
        'statusID',
        'loadingAddress',
        'location',
        'distance',
        'suppliers',
        'comment',
        'commentManager',
        'commentDelete',
        'km',
        'tone',
        'price',
        'amount',
        'contract',
        'BN',
        'mercenary',
        'urgent',
        'id_driver',
        'id_manager',
        'start',
        'end',
        'status'
    ];
}