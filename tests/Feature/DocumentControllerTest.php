<?php

namespace Tests\Feature;

use App\Enums\DocumentStatus;
use App\Http\Resources\DocumentResource;
use App\Models\Document;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class DocumentControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $baseUrl = '/api/v1/document';

    public function testIndex(): void
    {
        $response = $this->getJson($this->baseUrl);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'document' => [],
                'pagination' => [
                    'page',
                    'perPage',
                    'total'
                ]
            ]);
    }

    public function testPublish(): void
    {
        $document = Document::factory()->create();
        $url = "{$this->baseUrl}/{$document->id}/publish";
        $response = $this->postJson($url);

        $response->assertStatus(200)
            ->assertJsonPath('document.status', DocumentStatus::Published->value);
    }

    public function testShow(): void
    {
        $document = Document::factory()->create();
        $response = $this->getJson("{$this->baseUrl}/{$document->id}");

        $docRes =  new DocumentResource($document);
        $expect = json_decode($docRes->toResponse(new Request())->getContent(), true);

        $response->assertStatus(200)
            ->assertExactJson($expect);
    }

    public function testStore(): void
    {
        $response = $this->postJson("{$this->baseUrl}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'document' => [
                    'id',
                    'status',
                    'payload',
                    'createAt',
                    'modifyAt'
                ]
            ])
            ->assertJson(fn (AssertableJson $json) =>
                $json->whereAllType([
                        'document.id' => 'string',
                        'document.status' => 'string',
                        'document.payload' => 'array',
                    ])
            );
    }

    public function testUpdate(): void
    {
        $json = json_decode('{
            "a":1,
            "b":2,
            "c":"string"
        }');

        $patchJson = json_decode('{
            "a":1,
            "b":3,
            "c":null
        }');

        $document = Document::factory()->create([
            'payload' => $json
        ]);

        $url = "{$this->baseUrl}/{$document->id}";
        $response = $this->patchJson($url, [
           "document" => [
               "payload" => $patchJson
           ]
        ]);

        $response->assertJsonPath('document.status', DocumentStatus::Draft->value);

        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('document.payload', fn (AssertableJson $json) =>
                $json->where('a', 1)
                    ->where('b', 3)
            )
        );
    }

    public function testNotFound(): void
    {
        $response = $this->getJson("{$this->baseUrl}/test");
        $response->assertStatus(404);
    }
}
