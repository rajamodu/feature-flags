<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MainControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, '/');
        self::assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $content = json_decode($client->getResponse()->getContent(), true);
        self::assertEquals(['status' => 'ok', 'service' => 'feature flag service'], $content);
    }
}
