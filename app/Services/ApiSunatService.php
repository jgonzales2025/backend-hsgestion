<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ApiSunatService
{
    protected $baseUrl;
    protected $token;

    public function __construct()
    {
        $this->baseUrl = config('services.external_api.document_api_url');
        $this->token = config('services.external_api.sunat_api_token');
    }

    public function getDataByDocument(string $numberDocument)
    {
        return Http::withToken($this->token)
            ->get("{$this->baseUrl}/{$numberDocument}")
            ->throw()
            ->json();
    }
}
