<?php

namespace DataCollection\Http\Controllers;

use DataCollection\Campaign;
use DataCollection\Http\Requests\StoreCampaignRequest;
use DataCollection\Participant;
use DataCollection\Sensor;
use DataCollection\Snapshot;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CampaignsController extends Controller
{

    public function index()
    {
        return Campaign::whereIsPrivate(false)->get(['id', 'name']);
    }

    /**
     * Gets the create view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('campaign.create');
    }

    /**
     * Stores a campaign and redirects to ???
     *
     * @param StoreCampaignRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreCampaignRequest $request)
    {
        $this->saveCampaign($request->all());
        return redirect('/');
    }

    public function show($id, Request $request)
    {
        $campaign = Campaign::with(['sensors', 'questions'])->findOrFail($id);

        if (!$campaign) {
            throw (new ModelNotFoundException())->setModel(Campaign::class);
        }

        if ($request->ajax()) {
            return $campaign->toJson();
        } else {
            return view('campaign.show', compact('campaign'));
        }
    }

    public function joinCampaign(Request $request)
    {
        $participant = Participant::firstOrCreate([
            'device_id' => $request->get('device_id'),
        ]);

        $campaign = Campaign::with(['sensors', 'questions'])->findOrFail($request->get('campaign_id'));
        $participant->campaigns()->attach($campaign->id);

        return $campaign;
    }

    public function addSnapshots($id, Request $request)
    {
        $campaign = Campaign::findOrFail($id);

        if (!empty($request->all())) {
            $snapshotJsonString = $request->get('snapshots');
            $snapshots = json_decode($snapshotJsonString, true);

            if (!$snapshots) {
                return Response::json(['message' => 'Cannot decode json'], 400);
            }

            foreach ($snapshots['snapshots'] as $snapshotAsArray) {
                $sensor_data_json = json_encode($snapshotAsArray);
                $snapshot = new Snapshot();
                $snapshot->fill(['sensor_data_json' => $sensor_data_json]);

                $campaign->snapshots()->save($snapshot);
            }
        } else {
            return Response::json(['message' => 'No json provided'], 400);
        }


    }

    /**
     * Saves a campaign from an array of attributes
     *
     * @param array $attributes
     * @return Campaign
     */
    private function saveCampaign(array $attributes)
    {
        $campaign = Campaign::create($attributes);


        if (array_has($attributes, 'sensors')) {
            foreach ($attributes['sensors'] as $sensor) {
                $sensorObj = Sensor::firstOrCreate(['name' => $sensor]);
                $campaign->sensors()->attach($sensorObj->id);
            }
        }

        return $campaign;
    }


}
