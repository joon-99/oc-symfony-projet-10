<?php

namespace App\Factory;

use App\Entity\Task;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;
use App\Enum\TaskCategoryEnum;

/**
 * @extends PersistentObjectFactory<Task>
 */
final class TaskFactory extends PersistentObjectFactory
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
        return Task::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     */
    #[\Override]
    protected function defaults(): array|callable
    {
                $choices = [
                    'Design UI',
                    'Implement authentication',
                    'Create database schema',
                    'Integrate payment gateway',
                    'Write unit tests',
                    'Set up CI/CD',
                    'Migrate legacy data',
                    'Optimize performance',
                    'Add logging and monitoring',
                    'Implement search',
                    'Create admin panel',
                    'Deploy to staging',
                    'Document API endpoints',
                    'Fix critical bugs',
                    'Add localization',
                    'Implement notifications',
                    'Write end-to-end tests',
                    'Refactor module',
                    'Integrate analytics',
                    'Finalize UX flows',
                ];

        return [
            'project' => null,
            'title' => self::faker()->randomElement($choices),
            'category' => self::faker()->randomElement(TaskCategoryEnum::cases()),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Task $task): void {})
        ;
    }
}
