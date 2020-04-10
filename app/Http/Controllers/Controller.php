<?php

namespace App\Http\Controllers;

use App\Models\PlayerSession;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Request as RequestFacade;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /** @var PlayerSession|null */
    protected $session;

    public function __construct()
    {
        $this->session = PlayerSession::findByToken(RequestFacade::input('session_id'));
    }
}
