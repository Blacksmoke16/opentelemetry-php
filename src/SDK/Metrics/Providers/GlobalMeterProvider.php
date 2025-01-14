<?php

declare(strict_types=1);

namespace OpenTelemetry\SDK\Metrics\Providers;

use OpenTelemetry\API\Metrics as API;

/**
 * https://github.com/open-telemetry/opentelemetry-specification/blob/master/specification/metrics/api.md#global-meter-provider
 *
 * @method static API\MeterInterface getMeter(string $name)
 */
final class GlobalMeterProvider
{
    /**
     * @var API\MeterProviderInterface $globalProvider
     */
    protected static $globalProvider;

    /**
     * Returns a global instance MeterProvider.
     * If global instance is missing, new MeterProvider will be lazily created
     *
     * @access	public static
     * @return	API\MeterProviderInterface
     */
    public static function getGlobalProvider(): API\MeterProviderInterface
    {
        if (empty(static::$globalProvider)) {
            static::$globalProvider = new MeterProvider();
        }

        return static::$globalProvider;
    }

    /**
     * Sets a global instance of MeterProvider
     *
     * @access	public static
     * @param	API\MeterProviderInterface $globalProvider
     * @return	void
     */
    public static function setGlobalProvider(API\MeterProviderInterface $globalProvider): void
    {
        static::$globalProvider = $globalProvider;
    }

    /**
     * Accessor for the global provider
     */
    public static function __callStatic($name, $arguments)
    {
        return static::getGlobalProvider()->$name(...$arguments);
    }
}
