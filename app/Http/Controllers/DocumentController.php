<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;


class DocumentController extends Controller
{
    public string $apiUrl = '/api/v1/document';

    public function index()
    {
        $content = $this->handleRequest();
        cookie()->queue('query-params', request()->getQueryString());
        return view('document.home', compact('content'));
    }

    public function show(string $id)
    {
        $content = $this->handleRequest($id);
        $document = $content['document'];
        $indexQueryString = request()->cookie('query-params');
        return view('document.show', compact('document', 'indexQueryString'));
    }

    private function handleRequest(string $path = ''): ?array
    {
        $requestUri = $this->buildUri($this->apiUrl, $path);
        $responseCode = $this->getContent($this->request($requestUri), $content);
        if ($responseCode) {
            abort($responseCode);
        }
        return $content;
    }

    /**
     * Create request and perform it
     *
     * @param $uri
     * @return Response
     */
    private function request($uri): Response
    {
        $apiRequest = Request::create($uri,'GET');
        return Route::dispatch($apiRequest);
    }

    /**
     * Get content from response
     *
     * @param Response $response
     * @param array|null $content
     * @return int|null
     */
    private function getContent(Response $response, ?array &$content): ?int
    {
        if ($response->getStatusCode() != 200) {
            return $response->getStatusCode();
        }
        $content = json_decode($response->getContent(), true);
        return $content !== null ? null : 429;
    }

    private function buildUri(string $baseApi, string $path = ''): string
    {
        $path = empty($path) ? '' : "/$path";
        return "{$baseApi}{$path}";
    }
}
