<?php

declare(strict_types=1);

namespace OpenTelemetry\API\Trace;

use OpenTelemetry\Context\Context;
use OpenTelemetry\Context\ImplicitContextKeyedInterface;
use Throwable;

/**
 * @see https://github.com/open-telemetry/opentelemetry-specification/blob/v1.6.1/specification/trace/api.md#span-operations
 */
interface SpanInterface extends ImplicitContextKeyedInterface
{
    /**
     * Returns the {@see SpanInterface} from the provided *$context*,
     * falling back on {@see SpanInterface::getInvalid()} if there is no span in the provided context.
     */
    public static function fromContext(Context $context): SpanInterface;

    /**
     * Returns the current {@see SpanInterface} from the current {@see Context},
     * falling back on {@see SpanInterface::getEmpty()} if there is no span in the current context.
     */
    public static function getCurrent(): SpanInterface;

    /**
     * Returns an invalid {@see SpanInterface} that is used when tracing is disabled, such s when there is no available SDK.
     */
    public static function getInvalid(): SpanInterface;

    /**
     * Returns a non-recording {@see SpanInterface} that hold the provided *$spanContext* but has no functionality.
     * It will not be exported and al tracing operations are no-op, but can be used to propagate a valid {@see SpanContext} downstream.
     *
     * @see https://github.com/open-telemetry/opentelemetry-specification/blob/v1.6.1/specification/trace/api.md#wrapping-a-spancontext-in-a-span
     */
    public static function wrap(SpanContextInterface $spanContext): SpanInterface;

    /**
     * @see https://github.com/open-telemetry/opentelemetry-specification/blob/v1.6.1/specification/trace/api.md#get-context
     */
    public function getContext(): SpanContextInterface;

    /**
     * @see https://github.com/open-telemetry/opentelemetry-specification/blob/v1.6.1/specification/trace/api.md#isrecording
     */
    public function isRecording(): bool;

    /**
     * @see https://github.com/open-telemetry/opentelemetry-specification/blob/v1.6.1/specification/trace/api.md#set-attributes
     *
     * @param non-empty-string $key
     * @param bool|int|float|string|array|null $value Note: the array MUST be homogeneous, i.e. it MUST NOT contain values of different types.
     */
    public function setAttribute(string $key, $value): SpanInterface;

    /**
     * @see https://github.com/open-telemetry/opentelemetry-specification/blob/v1.6.1/specification/trace/api.md#set-attributes
     */
    public function setAttributes(AttributesInterface $attributes): SpanInterface;

    /**
     * @see https://github.com/open-telemetry/opentelemetry-specification/blob/v1.6.1/specification/trace/api.md#add-events
     */
    public function addEvent(string $name, ?AttributesInterface $attributes = null, int $timestamp = null): SpanInterface;

    /**
     * @see https://github.com/open-telemetry/opentelemetry-specification/blob/v1.6.1/specification/trace/api.md#record-exception
     */
    public function recordException(Throwable $exception, AttributesInterface $attributes = null): SpanInterface;

    /**
     * @see https://github.com/open-telemetry/opentelemetry-specification/blob/v1.6.1/specification/trace/api.md#updatename
     *
     * @param non-empty-string $name
     */
    public function updateName(string $name): SpanInterface;

    /**
     * @see https://github.com/open-telemetry/opentelemetry-specification/blob/v1.6.1/specification/trace/api.md#set-status
     *
     * @psalm-param StatusCode::STATUS_* $code
     */
    public function setStatus(string $code, string $description = null): SpanInterface;

    /**
     * @see https://github.com/open-telemetry/opentelemetry-specification/blob/v1.6.1/specification/trace/api.md#end
     */
    public function end(int $endEpochNanos = null): void;
}
