<?php

namespace DataCollection\Http\Controllers;

use DataCollection\Campaign;
use Illuminate\Http\Request;

use DataCollection\Http\Requests;

class SnapshotsController extends Controller
{
    public function index($campaignId)
    {
        $campaign = Campaign::findOrFail($campaignId);


        return $campaign->snapshots;
    }
}
