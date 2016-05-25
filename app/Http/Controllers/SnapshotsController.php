<?php

namespace DataCollection\Http\Controllers;

use Auth;
use DataCollection\Campaign;
use DataCollection\Http\Requests;

class SnapshotsController extends Controller
{
    public function index($campaignId)
    {
        $campaign = Campaign::findOrFail($campaignId);

        if ($campaign->user_id != Auth::user()->id) {
            abort(403);
        }

        return $campaign->snapshots()->paginate(15);
    }
}
