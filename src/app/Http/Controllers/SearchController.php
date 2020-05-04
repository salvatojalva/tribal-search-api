<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tribal\Interfaces\DataHandlerInterface;

class SearchController extends Controller
{

    public function search($search_tring, DataHandlerInterface $dataHandler){
        return $dataHandler->get($search_tring);
    }
    
}
