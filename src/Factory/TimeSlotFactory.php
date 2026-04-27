<?php

namespace App\Factory;

use App\Entity\TimeSlot;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<TimeSlot>
 */
final class TimeSlotFactory extends PersistentObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     */
    public function __construct()
    {
    }

    #[\Override]
    public static function class(): string
    {
        return TimeSlot::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     */
    #[\Override]
    protected function defaults(): array|callable
    {
        return [
            'endDate' => self::faker()->dateTime(),
            'startDate' => self::faker()->dateTime(),
            'task' => TaskFactory::new(),
            'worker' => UserFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(TimeSlot $timeSlot): void {})
        ;
    }
}
