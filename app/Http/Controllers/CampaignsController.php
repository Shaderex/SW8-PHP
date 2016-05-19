<?php

namespace DataCollection\Http\Controllers;

use Auth;
use DataCollection\Campaign;
use DataCollection\Http\Requests\StoreCampaignRequest;
use DataCollection\Participant;
use DataCollection\Question;
use DataCollection\Snapshot;
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
        return view('campaign.create2');
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
        $snapshotCount = $campaign->snapshots()->count();
        $participantsCount = $campaign->participants()->count();

        return view('campaign.show2', compact('campaign', 'snapshotCount', 'participantsCount'));
    }

    public function joinCampaign(Request $request, $id)
    {
        $participant = Participant::firstOrCreate([
            'device_id' => $request->get('device_id'),
        ]);

        $campaign = Campaign::with(['sensors', 'questions', 'user'])->findOrFail($id);
        $participant->campaigns()->attach($campaign->id);

        return $campaign;
    }

    public function addSnapshots($id, Request $request)
    {
        $campaign = Campaign::findOrFail($id);
        $compressedSnapshot = $request->get('snapshots');

        if (!empty($request->all()) && !empty($compressedSnapshot)) {

            $snapshotJsonString = gzdecode($compressedSnapshot);

            if (! $snapshotJsonString ) {
                return Response::json(['message' => 'Decompression failed'], 400);
            }

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
        if (!array_has($attributes, 'is_public')) {
            $attributes['is_private'] = true;
        }

        $attributes['sample_duration'] = $attributes['measurement_frequency'] * $attributes['measurements_per_sample'];
        $attributes['sample_frequency'] = $attributes['sample_duration'] + $attributes['sample_delay'];
        $attributes['snapshot_length'] = $attributes['sample_frequency'] * $attributes['samples_per_snapshot'];

        $campaign = Campaign::create($attributes);
        $campaign->user()->associate(Auth::user());
        $campaign->save();
        if (array_has($attributes, 'sensors')) {
            foreach ($attributes['sensors'] as $id => $value) {
                $campaign->sensors()->attach($id);
            }
        }

        if (array_has($attributes, 'questions')) {
            foreach ($attributes['questions'] as $question) {
                $questionObject = new Question();
                $questionObject->question = $question;
                $questionObject->campaign_id = $campaign->id;
                $questionObject->save();
            }
        }

        return $campaign;
    }

    public function destroy($id)
    {
        Campaign::findOrFail($id)->delete();

        return redirect('/campaigns');
    }
}
