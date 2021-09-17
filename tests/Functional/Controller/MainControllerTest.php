<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Tests\DataFixtures\EmptyFixture;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MainControllerTest extends AbstractControllerTest
{
    public function testIndex(): void
    {
        $this->client->request(Request::METHOD_GET, '/');
        self::assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $content = json_decode($this->client->getResponse()->getContent(), true);
        self::assertEquals([
            'status' => 'ok',
            'service' => 'feature flag service'
        ], $content);
    }

    protected function getFixtures(): array
    {
        return [
            EmptyFixture::class,
        ];
    }
}
