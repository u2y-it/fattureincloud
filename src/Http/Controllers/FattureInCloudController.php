<?php

namespace U2y\FattureInCloud\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use U2y\FattureInCloud\Services\FattureInCloud;
use U2y\FattureInCloud\Models\FattureInCloudToken;
use U2y\FattureInCloud\Services\FattureInCloudService;
use FattureInCloud\OAuth2\OAuth2AuthorizationCodeManager;

class FattureInCloudController extends Controller
{
    public function index()
    {
        $token = FattureInCloudToken::orderBy('expire_at', 'desc')->first();
        $oauth = new OAuth2AuthorizationCodeManager(config("fattureincloud.client_id"), config("fattureincloud.client_secret"), route('fattureincloud.auth_callback'));
        return view('fattureincloud::oauth')
            ->with('fattureincloud_url', $oauth->getAuthorizationUrl(config("fattureincloud.scopes"), csrf_token()))
            ->with('token', $token);
    }

    public function callback(Request $request)
    {

        $code = $request->get('code');
        $state = $request->get('state');
        if ($state !== csrf_token()) { // TODO: middleware
            return json_encode(['error' => 'Invalid CSRF token']);
        }
        try {
        $token = FattureInCloudService::requestAndSaveToken($code);
        } catch (\Exception $e) {
            return json_encode(['error' => $e->getMessage()]);
        }

         Session::flash('message', 'Token generato: ' . $token->access_token);

        return redirect()->route('hubspot.auth');
    }
}
