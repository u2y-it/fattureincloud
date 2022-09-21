<?php

namespace U2y\FattureInCloud\Services;

use HubSpot\Factory;
use Illuminate\Support\Str;
use FattureInCloud\Configuration;
use Illuminate\Support\Facades\Http;
use U2y\FattureInCloud\Models\FattureInCloudToken;

class FattureInCloudService
{
    private $config;
    // public function __construct(FattureInCloudToken $token,$config = null)
    // {
    //     if (!$config) {
    //         $config = Configuration::getDefaultConfiguration()->setAccessToken($token->access_token);
    //     }
    //     $this->config = $config;
    // }
    public function __construct($client = null)
    {
        if (!$client) {
            $client = $this->initClient();
        }
        // $this->client = $client;
    }

    public function __call($name, $arguments = null)
    {
        $classname = 'U2y\\FattureInCloud\\Services\\Resources\\' . Str::ucfirst(Str::camel($name));
        if (class_exists($classname)) {
            return new $classname($this->config);
        }

        return $this->$name($arguments);
    }

    public function initClient()
    {
        $last_token = FattureInCloudToken::orderBy('expire_at', 'desc')->first();
        if (!$last_token) {
            throw new \Exception('No Fatture in cloud token found. Please generate one');
        }

        if ($last_token->expire_at <= now()->subMinute()) {
            // refresh del token
            $response = self::refreshToken($last_token->refresh_token);
            $last_token = $this->saveTokenByResponse($response);
        }
        $this->config = Configuration::getDefaultConfiguration()->setAccessToken($last_token->access_token);
        return;
        // return Factory::createWithAccessToken($last_token->access_token);
    }

    public static function requestAndSaveToken(string $code)
    {
        $response = self::requestToken($code);
        $token = self::saveTokenByResponse($response);
        return $token;
    }

    public static function requestToken(string $code)
    {
        try {
            $response = Http::asJson()->post('https://api-v2.fattureincloud.it/oauth/token', [
                'grant_type' => 'authorization_code',
                'client_id' => config('fattureincloud.client_id'),
                'client_secret' => config('fattureincloud.client_secret'),
                'redirect_uri' => route('fattureincloud.auth_callback'),
                'code' => $code
            ]);

            $response->throwIf($response->failed());
        } catch (\Throwable $th) {
            throw $th;
        }

        return $response;
    }

    public static function saveTokenByResponse($response)
    {
        $resp = (object) $response->json();
        return FattureInCloudToken::create([
            'access_token' => $resp->access_token,
            'refresh_token' => $resp->refresh_token,
            'expire_at' => now()->addSeconds($resp->expires_in)
        ]);
    }

    public static function refreshToken(string $refresh_token)
    {
        try {
            $response = Http::asForm()->post('https://api-v2.fattureincloud.it/oauth/token', [
                'grant_type' => 'refresh_token',
                'client_id' => config('fattureincloud.client_id'),
                'client_secret' => config('fattureincloud.client_secret'),
                'refresh_token' => $refresh_token
            ]);

            $response->throwIf($response->failed());
        } catch (\Throwable $th) {
            throw $th;
        }

        return $response;
    }
}
