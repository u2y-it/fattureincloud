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
        $oauth = new OAuth2AuthorizationCodeManager("CLIENT_ID", "CLIENT_SECRET", $redirectUri);
        return view('hubspot::oauth')
            ->with('hubspot_url', OAuth2::getAuthUrl(
                config('hubspot.client_id'),
                route('hubspot.auth_callback'),
                config('hubspot.scopes')
            ))
            ->with('token', $token);
    }

    public function callback(Request $request)
    {
        // try {
        //     $token = HubspotService::requestAndSaveToken($request->code);
        // } catch (\Exception $e) {
        //     echo "Exception when calling access_tokens_api->get_access_token: ", $e->getMessage();
        // }

        // Session::flash('message', 'Token generato: ' . $token->access_token);

        // return redirect()->route('hubspot.auth');
    }
}
