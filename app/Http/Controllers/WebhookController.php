<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\WebhookService;

class WebhookController extends Controller
{
    protected $webhookService;

    
    public function __construct(WebhookService $webhookService)
    {       
        $this->webhookService = $webhookService;
    }

    public function webhook(Request $request)
    {
        $response = $this->webhookService->webhook($request);
        return $response;
    }

}