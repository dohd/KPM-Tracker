<?php

namespace App\Http\Controllers\pdf;

use App\Models\agenda\Agenda;

trait PdfDetailsTrait
{
    protected function pdf_details($request)
    {
        if (session()->token() != $request->token) 
            abort(403, 'Invalid Token');
        
        switch (true) {
            case $this->has_key('agenda'):
                return new Agenda;
            default:
                # code...
                break;
        }
        

    }

    protected function has_key($key='')
    {
        return boolval(request($key));
    }
}