<?php

namespace App\Http\Controllers;

use App\Group;
use App\GroupMember;
use App\Member;
use App\Http\Requests\GroupRequest;
use \Redirect;
use \Session;
use \DB;

use App\Http\Requests;

/**
 * Class GroupsController
 * @package App\Http\Controllers
 */
class GroupsController extends Controller
{
    /**
     * Display a list of all groups
     *
     * @return Response
     */
    public function index()
    {
        $groups = Group::all(); // Get groups for the list

        return view('groups.index', compact('groups'));
    }

    /**
     * Returns an array of all members' ids and full names
     * ('id' => 'full_name')
     *
     * @return array
     */
    private function membersFullNameList() {
        return Member::select(
            DB::raw("CONCAT(first_name, ' ', last_name) AS full_name, id")
        )->lists('full_name', 'id');
    }

    /**
     * Show the form for creating a new group.
     *
     * @return Response
     */
    public function create()
    {
        $members = $this->membersFullNameList();    // Get list of all members and their ids for the form

        return view('groups.create', compact('members'));
    }

    /**
     * Store a new group and redirect to groups list
     *
     * @param GroupRequest $request
     * @return Response
     */
    public function store(GroupRequest $request)
    {
        $this->createGroup($request);

        // Redirect
        Session::flash('message', 'Group created successfully!');
        return Redirect::to('groups');
    }

    /**
     * Display the specified group.
     *
     * @param  int  $id The id of the group to display
     * @return Response
     */
    public function show($id)
    {
        $group = Group::findOrFail($id);    // Find group
        $members = $group->members;         // Find members of group

        return view('groups.show', compact('group', 'members'));
    }

    /**
     * Show the form for editing the specified group.
     *
     * @param  int  $id The id of the group to edit
     * @return Response
     */
    public function edit($id)
    {
        $group = Group::findOrFail($id);            // Find group
        $members = $this->membersFullNameList();    // Get list of all members and their ids for the form

        return view('groups.edit', compact('group', 'members'));
    }

    /**
     * Update the specified group in storage.
     *
     * @param GroupRequest $request
     * @param $id   The id of the group to update
     * @return Response
     */
    public function update(GroupRequest $request, $id)
    {
        // Find group, update name and save
        $group = Group::findOrFail($id);
        $group->name = $request->input('name');
        $group->save();

        // Sync the selected members in database
        $members = $request->input('member_list');  // Get members that the user selected
        if (count($members) > 0) {
            $this->syncMembers($group, $members);   // If there are any, sync them
        } else {
            $group->members()->detach();            // If no members are selected, remove all of them
        }

        // Redirect
        Session::flash('message', 'Updated group successfully!');
        return Redirect::to('groups');
    }

    /**
     * Remove the specified member from storage.
     *
     * @param  int  $id The id of the member to delete
     * @return Response
     */
    public function destroy($id)
    {
        // Find and delete group
        $group = Group::findOrFail($id);
        $group->delete();

        // Redirect
        Session::flash('message', 'Group deleted successfully.');
        return Redirect::to('groups');
    }

    /**
     * Sync list of members in the database
     *
     * @param Group $group      The group to sync members with
     * @param array $members    The members that belong in the group
     */
    public function syncMembers(Group $group, array $members)
    {
        $group->members()->sync($members);
    }

    /**
     * Save a new group to the database
     *
     * @param GroupRequest $request
     */
    private function createGroup(GroupRequest $request)
    {
        // Make new group and save it
        $group = new Group;
        $group->name = $request->input('name');
        $group->save();

        // Get selected members and save them (if there are any)
        $members = $request->input('member_list');
        if (count($members) > 0) {
            $this->syncMembers($group, $members);
        }
    }
}