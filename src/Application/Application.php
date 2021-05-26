<?php declare(strict_types=1);

namespace Sergonie\Application;

use Sergonie\Application\Exception\ApplicationException;
use Sergonie\Application\Http\MiddlewareAggregator;
use Sergonie\Application\Listeners\OnBootListener;
use Sergonie\Application\Listeners\OnErrorListener;
use Sergonie\Application\Listeners\OnRunListener;
use Sergonie\Application\Listeners\OnShutDownListener;
use Sergonie\Application\Providers\ConfigProvider;
use Sergonie\Application\Providers\ControllerProvider;
use Sergonie\Application\Providers\ServiceProvider;
use Sergonie\Container\DependencyResolver;
use Sergonie\Container\ServiceLocator;
use Psr\Container\ContainerInterface;
use Throwable;

/**
 * Main glue between all components.
 *
 * @package Sergonie\Application
 */
abstract class Application
{
    /**
     * @var ServiceLocator|ContainerInterface
     */
    private ContainerInterface $container;
    protected DependencyResolver $resolver;
    private Config $config;
    private bool $initialized = false;

    /**
     * @var object[]|string[]
     */
    protected array $modules = [];

    /**
     * Application constructor.
     *
     * @param ContainerInterface|null $container
     */
    public function __construct(ContainerInterface $container = null)
    {
        $this->container = $container ?? new ServiceLocator();
        $this->resolver = new DependencyResolver($this->container);

        $this->config = $this->container->has(Config::class)
            ? $this->container->get(Config::class)
            : new Config([]);
    }

    /**
     * Allows for application extension by modules.
     * Module can be any valid object or class name.
     *
     * @param object|string $module
     */
    public function extend($module): void
    {
        if (is_object($module) || (is_string($module) && class_exists($module))) {
            $this->modules[] = $module;
        } else {
            throw ApplicationException::forInvalidModule($module);
        }
    }

    /**
     * Starts the application.
     * Initialize modules. Performs tasks to generate response for the client.
     *
     * @return mixed
     */
    abstract public function run();

    /**
     * Controller aggregator is used to register application's controllers.
     * @return ControllerAggregator
     */
    abstract public function getControllerAggregator(): ControllerAggregator;

    /**
     * Middleware aggregator is used to register application's middlewares.
     * @return MiddlewareAggregator
     */
    abstract public function getMiddlewareAggregator(): MiddlewareAggregator;

    public function getConfig(): Config
    {
        return $this->config;
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /** @todo: get rid of iterating over all modules */
    protected function handleOnBootListeners(): void
    {
        foreach ($this->modules as $module) {
            if ($module instanceof OnBootListener) {
                $module->onBoot($this);
            }
        }
    }

    /** @todo: get rid of iterating over all modules */
    protected function handleOnShutDownListeners(): void
    {
        foreach ($this->modules as $module) {
            if ($module instanceof OnShutDownListener) {
                $module->onShutDown($this);
            }
        }
    }

    /** @todo: get rid of iterating over all modules */
    protected function handleOnErrorListeners(Throwable $exception): Throwable
    {
        foreach ($this->modules as $module) {
            if ($module instanceof OnErrorListener) {
                $exception = $module->onError($this, $exception);
            }
        }

        return $exception;
    }

    /** @todo: get rid of iterating over all modules */
    protected function handleOnRunListeners(): void
    {
        foreach ($this->modules as $module) {
            if ($module instanceof OnRunListener) {
                $module->onRun($this);
            }
        }
    }

    protected function initialize(): void
    {
        if ($this->initialized) {
            return;
        }

        foreach ($this->modules as &$module) {
            $this->initializeModule($module);
        }

        $this->initialized = true;
    }

    /**
     * @param object|string $module
     */
    protected function initializeModule(&$module): void
    {
        if (is_string($module)) {
            $module = $this->resolver->resolve($module);
        }

        if ($module instanceof ConfigProvider) {
            $module->provideConfig($this->getConfig());
        }

        if ($module instanceof ControllerProvider) {
            $module->provideControllers($this->getControllerAggregator());
        }

        if ($module instanceof ServiceProvider) {
            $module->provideServices($this->getContainer());
        }
    }
}
