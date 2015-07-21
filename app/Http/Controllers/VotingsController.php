<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\VotingRequest;
use App\VoteObjective;
use App\VoteType;
use App\VoteTypeAnswer;
use App\Voting;
use App\Vote;
use App\Group;
use App\GroupVote;
use App\Member;
use \Session;
use \Redirect;
use \Response;
use \Input;
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
        $type = VoteType::findOrFail($voting->voting_type);         // Get the type of the voting
        $objective = VoteObjective::findOrFail($voting->objective); // Get objective of voting

        //todo: show members in correct order!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        // Get each group member's vote (if there are any) and put them in an array
        $memberVotes = [];
        if ($voting->votes->count() > 0) {
            foreach($voting->votes as $vote) {
                $member = Member::findOrFail($vote->member_id);         // Get the member
                $answer = VoteTypeAnswer::findOrFail($vote->answer_id); // Get the answer

                $memberVotes[] = [                                      // Put member and answer to array
                    'member' => $member->first_name . ' ' . $member->last_name,
                    'answer' => $answer->answer
                ];
            }
        }

        return view('votings.show', compact('voting', 'type', 'objective', 'memberVotes'));
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
     * Shows the form to add default answers for a voting
     *
     * @param $id   The id of the voting to add answers to
     * @return \Illuminate\View\View
     */
    public function defaultAnswers($id)
    {
        $voting = Voting::findOrFail($id);
        $groups = Group::all();

        return view('votings.answers', compact('voting', 'groups'));
    }

    /**
     * Store default answers for a voting to the database
     *
     * @param Request $request
     * @return mixed
     */
    public function saveDefaultAnswers(Request $request)
    {
        $voting = Voting::findOrFail($request->get('voting_id'));   // Find the voting to store answers for

        // Delete this voting's default answers if there are any
        $prevVotes = GroupVote::where('voting_id', '=', $voting->id)->get();
        foreach($prevVotes as $gv) {
            $gv->delete();
        }

        // Save the new default answers
        $groups = Group::all();                                     // Get all groups

        foreach($groups as $group) {
            // Check if there is an answer for this group
            if ($request->has('answer_' . $group->id)) {
                // Save the group answer to the database
                GroupVote::create([
                    'voting_id' => $voting->id,
                    'group_id' => $group->id,
                    'answer_id' => $request->get('answer_' . $group->id)
                ]);
            }
        }

        // Redirect
        Session::flash('message', 'Οι προεπιλεγμένες απαντήσεις της ψηφοφορίας αποθηκεύτηκαν με επιτυχία!');
        return Redirect::to('votings');
    }

    /**
     * Shows the 1st & 2nd reading page
     *
     * @param $id   The id of the voting that the reading is for
     * @return \Illuminate\View\View
     */
    public function reading($id)
    {
        $voting = Voting::findOrFail($id);  // Get the voting to start the reading for

        if ($voting->defaultVotesSet()) {   // Check if the voting has default votes for each group
            $members = Member::orderBy('district_id')->orderBy('order')->get();  // get members sorted based on their district, then order
            $gvotes = GroupVote::where('voting_id', '=', $voting->id)->get();
            $answers = VoteTypeAnswer::where('type', '=', $voting->type->id)->get();

            return view('votings.reading', compact('voting', 'members', 'gvotes', 'answers'));
        }

        return 'ERROR';
    }

    /**
     * Saves the answers of a reading to the database
     *
     * @return mixed
     */
    public function saveAnswers()
    {
        // Get voting id (doesn't check if a voting with that id exists because if it doesn't, vote creation will fail)
        $v_id = Input::get('voting');

        // Get member/vote pairs
        $votes = Input::get('data');

        // Save votes to the database
        foreach($votes as $vote) {
            Vote::create([
                'voting_id' => $v_id,
                'member_id' => $vote['member_id'],
                'answer_id' => $vote['answer_id']
            ]);
        }

        // Return success json
        return Response::json('success', 200);
    }

    /**
     * Save a new voting to the database
     *
     * @param VotingRequest $request
     */
    private function createVoting(VotingRequest $request)
    {
        // Make a voting and save it
        Voting::create([
            'title' => $request->input('title'),
            'voting_type' => $request->input('voting_type'),
            'objective' => $request->input('objective')
        ]);
    }

}
