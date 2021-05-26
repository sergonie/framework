<?php declare(strict_types=1);

namespace Sergonie\Tests\Functional\Application;

use Sergonie\Application\Application;
use Sergonie\Application\Config;
use Sergonie\Application\ControllerAggregator;
use Sergonie\Application\Exception\ApplicationException;
use Sergonie\Application\Http\MiddlewareAggregator;
use Sergonie\Application\Listeners\OnBootListener;
use Sergonie\Application\Providers\ControllerProvider;
use Sergonie\Application\Providers\MiddlewareProvider;
use Sergonie\Tests\Fixtures\NullApplication;
use PHPUnit\Framework\TestCase;

final class ApplicationTest extends TestCase
{
    public function testCanInstantiate(): void
    {
        $application = new NullApplication();

        self::assertInstanceOf(Application::class, $application);
    }

    public function testExtend(): void
    {
        $application = new NullApplication();
        $application->extend(new class implements OnBootListener {
            public function onBoot(Application $application): void
            {
                $application->onBoot = true;
            }
        });
        $application->extend(ApplicationModule::class);
        $application->extend(MiddlewareModule::class);
        $application->run();

        $middleware = &$application->getMiddlewareAggregator()->middleware;

        self::assertTrue(isset($middleware[0]));
        self::assertSame('called', $middleware[0]());
        self::assertTrue($application->onBoot);
        self::assertFalse($application->onRun);
        self::assertFalse($application->onShutDown);
    }

    public function testExtendWithInvalidModule(): void
    {
        $this->expectException(ApplicationException::class);
        $application = new NullApplication();
        $application->extend('t1');
    }

    public function testGetDefaultConfig(): void
    {
        $application = new NullApplication();
        self::assertInstanceOf(Config::class, $application->getConfig());
    }
}


class ApplicationModule implements ControllerProvider
{
    public function provideControllers(ControllerAggregator $controllers): void
    {
        $controllers->register(function() {}, 'test_controller');
    }
}

class MiddlewareModule implements MiddlewareProvider
{
    public function provideMiddleware(MiddlewareAggregator $aggregate): void
    {
        $aggregate->use(function () {
            return 'called';
        });
    }
}
