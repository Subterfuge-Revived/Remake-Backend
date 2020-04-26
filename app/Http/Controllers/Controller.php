<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\PlayerSession;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Request as RequestFacade;
use function GuzzleHttp\Psr7\parse_query;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /** @var PlayerSession|null */
    protected $session;

    public function __construct()
    {
        $sessionId = '';
        if (RequestFacade::get('session_id')) {
            $sessionId = RequestFacade::get('session_id');
        }
        else {
            $queryParameters = parse_query(RequestFacade::getContent());
            $sessionId = $queryParameters['session_id'] ?? '';
        }

        $this->session = PlayerSession::findByToken($sessionId);
    }
}
