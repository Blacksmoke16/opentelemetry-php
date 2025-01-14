<?php

declare(strict_types=1);

namespace OpenTelemetry\Context\Propagation;

use function array_map;
use function array_merge;
use function array_unique;
use function array_values;
use OpenTelemetry\Context\Context;

final class MultiTextMapPropagator implements TextMapPropagatorInterface
{
    /**
     * @readonly
     *
     * @var list<TextMapPropagatorInterface>
     */
    private array $propagators = [];

    /**
     * @readonly
     *
     * @var list<string>
     */
    private array $fields;

    /**
     * @no-named-arguments
     *
     * @param list<TextMapPropagatorInterface> $propagators
     */
    public function __construct(array $propagators)
    {
        $this->propagators = $propagators;
        $this->fields = $this->extractFields($propagators);
    }

    public function fields(): array
    {
        return $this->fields;
    }

    public function inject(&$carrier, PropagationSetterInterface $setter = null, Context $context = null): void
    {
        foreach ($this->propagators as $propagator) {
            $propagator->inject($carrier, $setter, $context);
        }
    }

    public function extract($carrier, PropagationGetterInterface $getter = null, Context $context = null): Context
    {
        $context = $context ?? Context::getRoot();

        foreach ($this->propagators as $propagator) {
            $context = $propagator->extract($carrier, $getter, $context);
        }

        return $context;
    }

    /**
     * @param list<TextMapPropagatorInterface> $propagators
     * @return list<string>
     */
    private function extractFields(array $propagators): array
    {
        return array_values(
            array_unique(
                array_merge(
                    ...array_map(
                        static fn (TextMapPropagatorInterface $propagator) => $propagator->fields(),
                        $propagators
                    )
                )
            )
        );
    }
}
