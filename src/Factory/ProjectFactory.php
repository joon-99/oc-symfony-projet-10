<?php

namespace App\Factory;

use App\Entity\Project;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Project>
 */
final class ProjectFactory extends PersistentObjectFactory
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
        return Project::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     */
    #[\Override]
    protected function defaults(): array|callable
    {
        // Keep a smaller pool of project names so multiple tasks can be associated
        $choices = [
            'Website Redesign',
            'Mobile App',
            'E‑commerce Platform',
            'Marketing Automation',
            'Internal Dashboard',
            'Customer Portal',
            'API Development',
            'Data Migration',
            'Analytics Platform',
            'CMS Integration',
        ];

        return [
            'title' => self::faker()->randomElement($choices),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Project $project): void {})
        ;
    }
}
