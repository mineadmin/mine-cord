<?php

declare(strict_types=1);
/**
 * This file is part of MineAdmin.
 *
 * @link     https://www.mineadmin.com
 * @document https://doc.mineadmin.com
 * @contact  root@imoi.cn
 * @license  https://github.com/mineadmin/MineAdmin/blob/master/LICENSE
 */

namespace Mine\HttpServer\Tests\Cases;

use FastRoute\Dispatcher;
use Hyperf\Context\ApplicationContext;
use Hyperf\Context\RequestContext;
use Hyperf\Framework\Event\BootApplication;
use Hyperf\HttpMessage\Uri\Uri;
use Hyperf\HttpServer\Request;
use Hyperf\HttpServer\Router\Dispatched;
use Hyperf\HttpServer\Router\Handler;
use Mine\HttpServer\Listener\BootApplicationListener;
use Mine\HttpServer\Response;
use PHPUnit\Framework\TestCase;
use Swow\Psr7\Message\ServerRequestPlusInterface;

/**
 * @internal
 * @coversNothing
 */
class HttpRequestTest extends TestCase
{
    protected function setUp(): void
    {
        $listener = new BootApplicationListener(
            ApplicationContext::getContainer()->get(Response::class)
        );
        $listener->process(new BootApplication());
        parent::setUp(); // TODO: Change the autogenerated stub
    }

    public function testIp()
    {
        $request = new Request();
        $interface = \Mockery::mock(ServerRequestPlusInterface::class);
        $interface->allows('getHeaders')->andReturns(
            [
                'x-real-ip' => ['127.0.0.1'],
                'x-forwarded-for' => ['127.0.0.2'],
            ],
            [
                'x-forwarded-for' => ['127.0.0.2'],
                'http_x_forwarded_for' => ['127.0.0.3'],
            ],
            [
                'http_x_forwarded_for' => ['127.0.0.3'],
                'remote_host' => ['127.0.0.4'],
            ],
            [
                'remote_host' => ['127.0.0.4'],
            ],
        );
        $interface->allows('getServerParams')->andReturns([]);
        RequestContext::set($interface);
        self::assertSame($request->ip(), '127.0.0.1');
        self::assertSame($request->ip(), '127.0.0.2');
        self::assertSame($request->ip(), '127.0.0.3');
        self::assertSame($request->ip(), '127.0.0.4');
    }

    public function testSchema()
    {
        $request = new Request();
        $interface = \Mockery::mock(ServerRequestPlusInterface::class);
        $interface->allows('getUri')->andReturns(
            new Uri('https://baidu.com/?q=xxx'),
            new Uri('http://baidu.com/?q=xxx'),
        );
        RequestContext::set($interface);
        $this->assertSame($request->getUri()->getScheme(), 'https');
        $this->assertSame($request->getUri()->getScheme(), 'http');
    }

    public function testGetAction()
    {
        $request = new Request();
        $interface = \Mockery::mock(ServerRequestPlusInterface::class);
        $dispatch = new Dispatched([
            Dispatcher::FOUND,
            new Handler([
                'error', 'index',
            ], 'test'),
            [],
        ]);
        $dispatch1 = new Dispatched([
            Dispatcher::FOUND,
            new Handler('index::index', 'test'),
            [],
        ]);

        $dispatch2 = new Dispatched([
            Dispatcher::FOUND,
            new Handler('index@index', 'test'),
            [],
        ]);
        $interface
            ->allows('getAttribute')
            ->andReturn(new Dispatched([
                Dispatcher::NOT_FOUND,
            ]), $dispatch, $dispatch1, $dispatch2);
        RequestContext::set($interface);
        $this->assertNull($request->getAction());
        $this->assertSame($request->getAction(), 'index');
        $this->assertSame($request->getAction(), 'index');
        $this->assertSame($request->getAction(), 'index');
    }
}
