<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\VotingRequest;
use App\VoteObjective;
use App\VoteType;
use App\Voting;
use \Session;
use \Redirect;
use Illuminate\Http\Request;

class VotingsController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $votings = Voting::all();                           // Get all votings for the list
        $types = VoteType::lists('title', 'id');            // Get all vote types to show their titles for the list
        $objectives = VoteObjective::lists('title', 'id');  // Get all vote objectives to show their titles for the list

		return view('votings.index', compact('votings', 'types', 'objectives'));
	}

	/**
	 * Show the form for creating a new voting.
	 *
	 * @return Response
	 */
	public function create()
	{
		$types = VoteType::lists('title', 'id');            // Get all vote types for the form
        $objectives = VoteObjective::lists('title', 'id');  // Get all vote objectives for the form

        return view('votings.create', compact('types', 'objectives'));
	}

    /**
     * Store a newly created voting in storage.
     *
     * @param VotingRequest $request
     * @return Response
     */
	public function store(VotingRequest $request)
	{
        $this->createVoting($request);

        // Redirect
        Session::flash('message', 'Η ψηφοφορία δημιουργήθηκε με επιτυχία!');
        return Redirect::to('votings');
	}

	/**
	 * Display the specified voting.
	 *
	 * @param  int  $id The id of the voting to show
	 * @return Response
	 */
	public function show($id)
	{
		$voting = Voting::findOrFail($id);                          // Find the voting or fail
        $type = VoteType::findOrFail($voting->voting_type);       // Get the type of the voting
        $objective = VoteObjective::findOrFail($voting->objective); // Get objective of voting

        return view('votings.show', compact('voting', 'type', 'objective'));
	}

	/**
	 * Remove the specified voting from storage.
	 *
	 * @param  int  $id The id of the voting to delete
	 * @return Response
	 */
	public function destroy($id)
	{
		// Find and delete voting
        $voting = Voting::findOrFail($id);
        $voting->delete();

        // Redirect
        Session::flash('message', 'Η ψηφοφορία διαγράφηκε με επιτυχία!');
        return Redirect::to('votings');
	}

    /**
     * Save a new voting to the database
     *
     * @param VotingRequest $request
     */
    private function createVoting(VotingRequest $request) {
        // Make a voting and save it
        Voting::create([
            'title' => $request->input('title'),
            'voting_type' => $request->input('voting_type'),
            'objective' => $request->input('objective')
        ]);
    }

}
