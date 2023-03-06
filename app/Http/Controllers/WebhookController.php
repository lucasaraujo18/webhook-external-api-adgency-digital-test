<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\RequestException;

class WebhookController extends Controller
{
    public function webhook(Request $request)
    {
        try {

            $response = Http::get('http://localhost:8000/api/servers/' .$request->data['server'] . '/sites');

            $response->throw();
            $response = json_decode($response->body());

            $this->deploy($response, $request);

            return response()->json(['ok' => 'ok'],200);

        } catch (RequestException $e) {
            Log::error('HTTP Request Failed', ['message' => $e->getMessage()]);
        }


    }

    public function deploy($sites, $request)
    {
        foreach ($sites as $key => $site) {
            if($request->data['site'] == $site->url) {
                try {
                    $url = $site->deployment_url;

                    $responseDeploy = Http::post($url, [
                        'deploy' => 'deploy',
                    ]);
                    
                    $responseDeploy->throw();
                    #if success
                    $responseDeploy = json_decode($responseDeploy->body());
                    if($responseDeploy->sucess){
                        dump("email sent to users sucessfully");
                        #send email for clients
                    };
                } catch (RequestException $e) {
                    Log::error('HTTP Request Failed - Deploy', ['message' => $e->getMessage()]);
                }
                break;
            }
        }
    }

}