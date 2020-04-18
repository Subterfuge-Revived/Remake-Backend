<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GoalController extends Controller
{
    /**
     * Get the list of goals.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $goals = Goal::all();
        return new Response($goals);
    }
}
