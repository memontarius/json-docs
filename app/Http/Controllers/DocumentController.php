<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;


class DocumentController extends Controller
{
    public string $api = '/api/v1/document';

    public function index()
    {
        $content = $this->handleRequest();
        cookie()->queue('query-params', request()->getQueryString());
        return view('document.home', compact('content'));
    }

    public function show(string $documentId)
    {
        $content = $this->handleRequest($documentId);
        $document = $content['document'];
        $indexQueryString = request()->cookie('query-params');
        return view('document.show', compact('document', 'indexQueryString'));
    }

    private function handleRequest(string $urn = ''): ?array
    {
        $requestUrl = "{$this->api}/{$urn}";
        $responseCode = $this->getContent($this->request($requestUrl), $content);
        if ($responseCode) {
            abort($responseCode);
        }
        return $content;
    }

    /**
     * Create request and perform it
     *
     * @param $url
     * @return Response
     */
    private function request($url): Response
    {
        $apiRequest = Request::create($url,'GET');
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
}
