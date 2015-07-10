<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\VoteType;
use App\Http\Requests\VoteTypeRequest;
use App\VoteTypeAnswer;
use \Redirect;
use \Session;

use Illuminate\Http\Request;

/**
 * Class VoteTypesController
 * @package App\Http\Controllers
 */
class VoteTypesController extends Controller {

	/**
	 * Display a listing of the vote types
	 *
	 * @return Response
	 */
	public function index()
	{
        $voteTypes = VoteType::all();   // Get vote types for the list

		return view('votetypes.index', compact('voteTypes'));
	}

	/**
	 * Show the form for creating a new vote type
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('votetypes.create');
	}

	/**
	 * Store a newly created vote type in storage.
	 *
	 * @return Response
	 */
	public function store(VoteTypeRequest $request)
	{
        $this->createVoteType($request);

        // Redirect
        Session::flash('message', 'Vote type created successfully!');
        return Redirect::to('/votetypes');
	}

    /**
     * Save a new vote type and its answers to the database
     *
     * @param VoteTypeRequest $request
     */
    public function createVoteType(VoteTypeRequest $request) {
        // Make new vote type and save it
        $vt = new VoteType;
        $vt->title = $request->input('title');
        $vt->save();

        // Save the answers of the vote type
        if (count($request->input('answers')) > 1) {
            foreach($request->input('answers') as $answer) {
                if ($answer != '') {
                    $vta = new VoteTypeAnswer;
                    $vta->type = $vt->id;
                    $vta->answer = $answer;
                    $vta->save();
                }

            }
        }
    }

	/**
	 * Display the specified vote type
	 *
	 * @param  int  $id The id of the vote type to display
	 * @return Response
	 */
	public function show($id)
	{
		$vt = VoteType::findOrFail($id);    // Find the vote type to display

        return view('votetypes.show', compact('vt'));
	}

	/**
	 * Show the form for editing the specified vote type
	 *
	 * @param  int  $id The id of the vote type to edit
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified vote type in storage.
	 *
	 * @param  int  $id The id of the vote type to update
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified vote type from storage.
	 *
	 * @param  int  $id The id of the vote type to delete
	 * @return Response
	 */
	public function destroy($id)
	{
        // Find and delete vote type
        $vt = VoteType::findOrFail($id);
        $vt->delete();

        // Redirect
        Session::flash('message', 'Vote type deleted successfully!');
        return Redirect::to('votetypes');
	}

}
