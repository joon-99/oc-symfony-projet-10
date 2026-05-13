<?php

namespace App\Security\Voter;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class TaskVoter extends Voter
{
    public const EDIT = 'TASK_EDIT';
    public const VIEW = 'TASK_VIEW';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof Task;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            $vote?->addReason("L'utilisateur doit être connecté pour accéder à cette ressource.");
            return false;
        }
        if (!$subject instanceof Task) {
            $vote?->addReason("La ressource demandée n'est pas une tâche valide.");
            return false;
        }
        $isAdmin = in_array('ROLE_ADMIN', $user->getRoles());
        if ($isAdmin) {
            return true;
        }

        switch ($attribute) {
            case self::EDIT:
            case self::VIEW:
                return $subject->getProject()->getUsers()->contains($user);
        }

        return false;
    }
}
