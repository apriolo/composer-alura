<?php

namespace Alura\BuscadorDeCursos\Tests;

use Alura\BuscadorDeCursos\Buscador;
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\DomCrawler\Crawler;

class TestBuscadorDeCursos extends TestCase
{
    private $httpClientMock;
    private $url = 'url-test';


    protected function setUp(): void
    {
        $html = <<<FIM
        <html>
            <body>
                <span class="card-curso__nome">Curso Test 1</span>
                <span class="card-curso__nome">Curso Test 2</span>
                <span class="card-curso__nome">Curso Test 3</span>
            </body>
        </html>
        FIM;

        $stream = $this->createMock(StreamInterface::class);
        $stream
            ->expects($this->once())
            ->method('__toString')
            ->willReturn($html);

        $response = $this->createMock(ResponseInterface::class);
        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($stream);

        $httpClient = $this
            ->createMock(ClientInterface::class);
        $httpClient
            ->expects($this->once())
            ->method('request')
            ->with('GET', $this->url)
            ->willReturn($response);

        $this->httpClientMock = $httpClient;
    }

    public function testBuscadorDeveRetornarCursos()
    {
        $crawler = new Crawler();
        $buscador = new Buscador($this->httpClientMock, $crawler);
        $cursos = $buscador->buscar($this->url);

        $this->assertCount(3,$cursos);
        $this->assertEquals('Curso Test 1', $cursos[0]);
        $this->assertEquals('Curso Test 2', $cursos[1]);
        $this->assertEquals('Curso Test 3', $cursos[2]);

        //$this->fail();
    }
}