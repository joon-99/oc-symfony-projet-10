<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Project;

/**
 * @extends ServiceEntityRepository<Task>
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

        public function findAllByCategoryProject(string $category, Project $project): array
        {
            return $this->createQueryBuilder('t')
                ->andWhere('t.category = :category')
                ->andWhere('t.project = :project')
                ->setParameter('category', $category)
                ->setParameter('project', $project)
                ->orderBy('t.id', 'ASC')
                ->getQuery()
                ->getResult()
            ;
        }
}
