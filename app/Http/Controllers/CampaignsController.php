<?php

namespace DataCollection\Http\Controllers;

use Auth;
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
        $campaigns = Auth::user()->campaigns;
        return view('campaign.index', compact('campaigns'));
    }

    public function indexJson()
    {
        $results = [];
        $campaigns = Campaign::with('user')->whereIsPrivate(false)->get();

        foreach ($campaigns as $campaign) {
            $results[] = ['id' => $campaign->id, 'name' => $campaign->name, 'user' => ($campaign->user ? $campaign->user->name : null)];
        }

        return $results;
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
        return redirect('campaigns');
    }


    public function showJson($id)
    {
        return Campaign::with(['sensors', 'questions', 'user'])->findOrFail($id);
    }

    public function show($id)
    {
        $campaign = Campaign::with(['sensors', 'questions', 'user'])->findOrFail($id);

        return view('campaign.show', compact('campaign'));
    }

    public function joinCampaign(Request $request)
    {
        $participant = Participant::firstOrCreate([
            'device_id' => $request->get('device_id'),
        ]);

        $campaign = Campaign::with(['sensors', 'questions', 'user'])->findOrFail($request->get('campaign_id'));
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

                $participant = Participant::where('device_id', '=', $request->get('device_id'))->firstOrFail();

                $snapshot->participant_id = $participant->id;

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

        $campaign->user()->associate(Auth::user());
        $campaign->save();

        if (array_has($attributes, 'sensors')) {
            foreach ($attributes['sensors'] as $sensor) {
                $sensorObj = Sensor::firstOrCreate(['name' => $sensor]);
                $campaign->sensors()->attach($sensorObj->id);
            }
        }

        return $campaign;
    }


}
