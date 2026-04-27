<?php

namespace App\DataFixtures;

use App\Entity\Project;
use App\Factory\ProjectFactory;
use App\Factory\TagFactory;
use App\Factory\TaskFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Command\LoadDataFixturesDoctrineCommand;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $users = UserFactory::new()->createMany(10);
        $projects = ProjectFactory::new()->createMany(5);

        $tags = TagFactory::new()->createMany(5);
        // For each project: attach 1-2 users, create 3-4 tasks, and assign one task to each attached user
        foreach ($projects as $project) {

            foreach ($tags as $tag) {
                $project->addTag($tag);
            }
            // pick 1 or 2 users randomly
            $userPool = $users;
            shuffle($userPool);
            $assignedUsers = array_slice($userPool, 0, rand(1, 2));

            // attach users to project (keep both sides in sync)
            foreach ($assignedUsers as $user) {
                $project->addUser($user);
            }

            // create 3-4 tasks for this project
            $taskCount = rand(3, 4);
            $tasks = TaskFactory::new()->createMany($taskCount, ['project' => $project]);

            // assign one distinct task to each attached user
            shuffle($tasks);
            foreach ($assignedUsers as $i => $user) {
                $task = $tasks[$i % count($tasks)];
                $task->addUser($user);
                
            }
        }

        $manager->flush();
    }
}
