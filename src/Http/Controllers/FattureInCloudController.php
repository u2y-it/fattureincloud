<?php

namespace U2y\FattureInCloud\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use U2y\FattureInCloud\Models\FattureInCloudToken;
use FattureInCloud\OAuth2\OAuth2AuthorizationCodeManager;

class FattureInCloudController extends Controller
{
    public function index()
    {
        $token = FattureInCloudToken::orderBy('expire_at', 'desc')->first();
        $oauth = new OAuth2AuthorizationCodeManager(config("fattureincloud.client_id"), config("fattureincloud.client_secret"), route('fattureincloud.auth_callback'));
        return view('fattureincloud::oauth')
            ->with('hubspot_url', $oauth->getAuthorizationUrl(config("fattureincloud.scopes"), csrf_token()))
            ->with('token', $token);
    }

    public function callback(Request $request)
    {

        $code = $request->get('code');
        $state = $request->get('state');
        if ($state !== csrf_token()) {
            return redirect()->route('fattureincloud.index')->with('error', 'Invalid state');
        }
        // try {
        //     $token = HubspotService::requestAndSaveToken($request->code);
        // } catch (\Exception $e) {
        //     echo "Exception when calling access_tokens_api->get_access_token: ", $e->getMessage();
        // }

        // Session::flash('message', 'Token generato: ' . $token->access_token);

        // return redirect()->route('hubspot.auth');
    }
}
