<?php

use Illuminate\Contracts\View\View;

class HomeService
{

    public function getIndexPage(): View
    {
        $data = [
          ''
        ];
        return view('dashboard', $data);
    }

}