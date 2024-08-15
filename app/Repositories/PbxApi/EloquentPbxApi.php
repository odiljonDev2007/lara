<?php

namespace Vanguard\Repositories\PbxApi;

//use Vanguard\CalendarPixel;

use Carbon\Carbon;
use DB;

class EloquentPbxApi implements PbxApiRepository
{
    /**
     * {@inheritdoc}
     */
    public function create($data)
    {
        
        dd($data);
        
        //return CalendarPixel::create($data);
    }
    
    
}