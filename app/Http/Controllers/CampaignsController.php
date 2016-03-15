<?php

namespace DataCollection\Http\Controllers;

use Illuminate\Http\Request;

class CampaignsController extends Controller
{
    public function create()
    {
        return view('campaign.create');
    }

    public function store(Request $request)
    {
        dd($request);
    }
}
