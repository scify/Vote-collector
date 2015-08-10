<?php namespace App\Http\Controllers;

use App\District;
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
use App\VotingItem;
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

        return view('votings.index', compact('votings'));
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

        // Get each member's vote (if there are any for this voting) and put them in an array
        $memberVotes = [];
        //todo: member votes are not gathered since the database changed
        if ($voting->votes->count() > 0) {
            /* old code
            $members = Member::orderBy('district_id')->orderBy('order')->get();  // get members sorted based on their district, then order

            foreach($members as $member) {
                $vote = Vote::where([
                    'member_id' => $member->id,
                    'voting_id' => $voting->id
                ])->first();                        // Not get(), because a member can only vote once in a voting

                if (count($vote) > 0) {             // If member has voted put member and answer to array
                    $answerId = $vote->answer_id;
                    if ($answerId != null) {
                        $vta = VoteTypeAnswer::findOrFail($answerId);
                        $answer = $vta->answer;
                    } else {
                        $answer = 'Απών';
                    }
                    $memberVotes[] = [
                        'member' => $member->first_name . ' ' . $member->last_name,
                        'answer' => $answer
                    ];
                }
            }*/
        }

        return view('votings.show', compact('voting', 'memberVotes'));
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
        $votingItems = $voting->votingItems;

        return view('votings.answers', compact('voting', 'groups', 'votingItems'));
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
        $v_id = $voting->id;

        // Delete this voting's default answers if there are any
        $this->deleteGroupVotes($v_id);

        // Save the new default answers
        $groups = Group::all();

        foreach($groups as $group) {
            // Check if there is an answer for this group
            $groupId = $group->id;
            if ($request->has('answer_' . $groupId)) {                  // If there is
                $groupAnswers = $request->get('answer_' . $groupId);    // Get the group answers
                $votingItemIds = $request->get('votingItems');          // Get the voting item ids (this and the groupAnswers array should have same length)

                $count = count($votingItemIds);
                for ($i = 0; $i < $count; $i++) {                       // Create new group vote
                    GroupVote::create([
                        'voting_id' => $v_id,
                        'voting_item_id' => $votingItemIds[$i],
                        'group_id' => $groupId,
                        'answer_id' => $groupAnswers[$i]
                    ]);
                }
            }
        }

        // Redirect
        Session::flash('message', 'Οι προεπιλεγμένες απαντήσεις της ψηφοφορίας αποθηκεύτηκαν με επιτυχία!');
        return Redirect::to('votings');
    }

    /**
     * Show the form to edit the default answers of a voting
     *
     * @param $id
     * @return \Illuminate\View\View
     */
    public function editAnswers($id)
    {
        $voting = Voting::findOrFail($id);
        $groups = Group::all();
        $votingItems = $voting->votingItems;

        return view('votings.editAnswers', compact('voting', 'groups', 'votingItems'));
    }

    /**
     * Deletes a voting's default answers
     *
     * @param $id   The id of the voting to delete answers for
     */
    private function deleteGroupVotes($id)
    {
        $prevVotes = GroupVote::ofVoting($id)->get();
        foreach($prevVotes as $gv) {
            $gv->delete();
        }
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

        // Check if the voting has default votes for each group
        if ($voting->defaultVotesSet() && !$voting->completed) {
            $votingid = $voting->id;

            // Get members sorted based on their district, then order
            $members = Member::orderBy('district_id')->orderBy('order')->get();

            // Gather all voting items with their object's title and answers
            $votingItems = VotingItem::ofVoting($votingid)->get();

            $myVotingItems = [];
            foreach($votingItems as $vItem) {
                $myVItem = [];

                // Get id and title of voting item
                $myVItem['id'] = $vItem->id;
                $myVItem['title'] = $vItem->voteObjective->title;

                // Get answers of this voting item's type
                $myVItem['answers'] = [];
                $answers = $vItem->voteType->answers;
                foreach($answers as $answer) {
                    $myVItem['answers'][$answer->id] = $answer->answer;
                }

                $myVotingItems[] = $myVItem;
            }

            // Gather all the info that the form needs about members
            $myMembers = [];
            foreach($members as $member) {
                $m = [];

                // Id and full name
                $m['id'] = $member->id;
                $m['full_name'] = $member->first_name . ' ' . $member->last_name;

                // Add default properties to show state of member
                $m['isSaved'] = 'true';
                $m['isAbsent'] = 'false';
                $m['labels'] = [];

                // Gather default answers for each voting item
                $answerIds = [];
                foreach($myVotingItems as $vi) {
                    $vItemId = $vi['id'];
                    $answerIds[$vItemId] = $member->vote($votingid, $vItemId);  // If member has voted already, the answer will be returned
                    $m['labels'][$vItemId] = '';                                // Initialize label as nothing (will be changed afterwards)
                }

                $m['answerIds'] = $answerIds;

                $tmpAnswerId = reset($m['answerIds']);  // reset() returns the first value of the array

                if ($tmpAnswerId == null) {         // Member has not voted or is saved as absent
                    if ($tmpAnswerId === null) {
                        $m['isSaved'] = 'false';    // If answer id is indeed NULL, then the member has not voted at all in this voting
                    } else {
                        $m['isAbsent'] = 'true';    // If it is not NULL, then it is '', so the member was absent!!!
                    }

                    // For each voting item in this voting, get this member's default group answer
                    foreach($myVotingItems as $vi) {
                        $vItemId = $vi['id'];

                        // Get default group answer
                        $tmpAnswer = $member->groupAnswer($votingid, $vItemId);

                        // If it's null (which means the member isn't in any groups) then select the first answer
                        if ($tmpAnswer == null) {
                            $tmpAnswer = $vi['answers'][0];
                        }

                        $m['answerIds'][$vItemId] = $tmpAnswer;
                    }
                } else {
                    // For each voting item, get the member's answer id and find that answer's text and put it in the labels array
                    foreach($myVotingItems as $vi) {
                        $vItemId = $vi['id'];                       // This voting item's id
                        $answerId = $m['answerIds'][$vItemId];      // The member's answer id
                        $answerText = $vi['answers'][$answerId];    // The text of the member's answer

                        $m['labels'][$vItemId] = $answerText;
                    }
                }

                $myMembers[] = $m;
            }

            $votingTitle = $voting->title;

            return view('votings.reading', compact('votingid', 'votingTitle', 'myMembers', 'myVotingItems'));
        } else {
            return 'ERROR: There are no default votes set for this voting or this voting has been completed';
        }
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

        // Get voting items of this voting and put their ids in another array to not make a lot of queries afterwards
        $votingItems = VotingItem::ofVoting($v_id)->get();
        $votingItemIds= [];
        foreach($votingItems as $vi) {
            $votingItemIds[] = $vi->id;
        }

        // Save votes to the database
        foreach($votes as $vote) {
            // Check if a vote already exists (which means the user is changing the vote from the form) and delete it
            $prevVotes = Vote::where([
                'voting_id' => $v_id,
                'member_id' => $vote['member_id']
            ])->get();

            if ($prevVotes->count() > 0) {
                foreach($prevVotes as $v) {
                    $v->delete();               // Delete vote for every voting item
                }
            }

            // Create the new vote
            foreach($votingItemIds as $vItem_id) {
                // Make string which is the key to this voting item's answer in the $vote[] array
                $str = 'answer_for_' . $vItem_id;

                // If the key exists in the $vote[] array, save the vote to the database
                if (isset($vote[$str]) || array_key_exists($str, $vote)) {
                    if ($vote[$str] == '') {
                        $answer = null;
                    } else {
                        $answer = $vote[$str];
                    }

                    // Save the vote to the database
                    Vote::create([
                        'voting_id' => $v_id,
                        'voting_item_id' => $vItem_id,
                        'member_id' => $vote['member_id'],
                        'answer_id' => $answer
                    ]);
                }
            }
        }

        // Return success json
        return Response::json('success', 200);
    }

    /**
     * Deletes a vote from the database based on a given voting id & member id.
     * Used for deleting votes of members who were saved by pressing the next button
     * but were marked as absent afterwards.
     *
     * @return mixed
     */
    public function deleteVote()
    {
        $voting_id = Input::get('v_id');
        $member_id = Input::get('m_id');

        $votes = Vote::where([
            'voting_id' => $voting_id,
            'member_id' => $member_id
        ])->get();

        if ($votes->count() > 0) {
            foreach($votes as $v) {
                $v->delete();
            }
        }

        // Return success json
        return Response::json('success', 200);
    }

    /**
     * Marks the specified voting as complete (sets the complete field to true instead of false)
     *
     * @return mixed    Success json message
     */
    public function markAsComplete()
    {
        $id = Input::get('v_id');
        $v = Voting::findOrFail($id);  // Find the voting
        $v->completed = true;
        $v->save();

        // Return success json
        return Response::json('success', 200);
    }

    /**
     * Returns the votes of the specified voting as json
     *
     * @param $id   The id of the voting
     * @return json
     */
    public function download($id)
    {
        $reply = [];

        $votes = Vote::where('voting_id', '=', $id)->get();
        foreach($votes as $vote) {
            // Get full name
            $m = $vote->member;
            $fullname = $m->first_name . ' ' . $m->last_name;

            // Get answer
            if ($vote->answer == null) {
                $answer = 'Απών';
            } else {
                $answer = $vote->answer->answer;
            }

            $tmp = [
                'member' => $fullname,
                'vote' => $answer
            ];

            $reply[] = $tmp;
        }

        return response()->json($reply);
    }

    /**
     * Save a new voting to the database
     *
     * @param VotingRequest $request
     */
    private function createVoting(VotingRequest $request)
    {
        // Make a voting and save it
        $v = Voting::create([
            'title' => $request->input('title'),
            'completed' => false                    // cannot create a voting that is complete immediately
        ]);

        // Make voting items and save them
        $objectives = $request->input('objectives');
        $types = $request->input('voting_types');

        if (count($objectives) != count($types)) {  // objectives and types should always have the same length because of the way the form is made
            return 'ERROR: Vote objectives and types length is different!';
        }

        $count = count($objectives);
        for($i = 0; $i < $count; $i++) {
            VotingItem::create([
                'voting_id' => $v->id,
                'vote_type_id' => $types[$i],
                'vote_objective_id' => $objectives[$i]
            ]);
        }
    }
}
