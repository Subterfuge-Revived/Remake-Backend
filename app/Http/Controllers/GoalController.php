<?php

namespace App\Http\Controllers;

use App\Http\Responses\UnauthorizedResponse;
use App\Models\Goal;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GoalController extends Controller
{
    /**
     * Get the list of goals.
     *
     * @return Response
     */
    public function index()
    {
        return new Response(Goal::all());
    }

    /**
     * @param Request $request
     * @return UnauthorizedResponse
     */
    public function store(Request $request)
    {
        // Players can't make up their own goals.
        // We may expose this endpoint to admin users only.
        return new UnauthorizedResponse();
    }

    /**
     * @param Goal $goal
     * @param Request $request
     * @return UnauthorizedResponse
     */
    public function update(Goal $goal, Request $request)
    {
        // Players can't go and edit an existing goal!
        // Perhaps we could expose this endpoint to admin users to update the description.
        return new UnauthorizedResponse();
    }

    /**
     * @param Goal $goal
     * @param Request $request
     * @return UnauthorizedResponse
     */
    public function destroy(Goal $goal, Request $request)
    {
        // Players can't go and delete a goal!
        // Due to foreign key constraints, if an admin were to delete a goal,
        // all the games (and data related to them) will cascade as well!
        // We should (probably) not allow this under any circumstance!
        return new UnauthorizedResponse();
    }

    /**
     * @param Goal $goal
     * @return Response
     */
    public function show(Goal $goal)
    {
        return new Response($goal);
    }
}
