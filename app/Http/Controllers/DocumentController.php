<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


class DocumentController extends Controller
{
    public string $apiUrl = '/api/v1/document';

    public function index()
    {
        $requestUri = $this->buildRequestUri($this->apiUrl, queryString: request()->getQueryString());
        $content = $this->requestContent($requestUri);

        return view('document.home', compact('content'));
    }

    public function show(string $id)
    {
        $content = $this->requestContent($this->buildRequestUri($this->apiUrl, $id));
        $document = $content['document'];

        return view('document.show', compact('document'));
    }

    private function requestContent($uri): array
    {
        $apiRequest = Request::create($uri, 'GET');
        $response = Route::dispatch($apiRequest);
        return json_decode($response->getContent(), true);
    }

    private function buildRequestUri($baseApi, $path = '', $queryString = ''): string
    {
        $queryString = empty($queryString) ? '' : "?$queryString";
        $path = empty($path) ? '' : "/$path";
        return "{$baseApi}{$path}{$queryString}";
    }
}
