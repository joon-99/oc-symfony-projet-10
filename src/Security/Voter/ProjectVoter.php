<?php

namespace App\Security\Voter;

use App\Entity\Project;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class ProjectVoter extends Voter
{
    public const EDIT = 'PROJECT_EDIT';
    public const VIEW = 'PROJECT_VIEW';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof \App\Entity\Project;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            $vote?->addReason("L'utilisateur doit être connecté pour accéder à cette ressource.");
            return false;
        }
        if (!$subject instanceof Project) {
            $vote?->addReason("La ressource demandée n'est pas un projet valide.");
            return false;
        }
        $isAdmin = in_array('ROLE_ADMIN', $user->getRoles());
        if ($isAdmin) {
            return true;
        }

        switch ($attribute) {
            case self::EDIT:
                return false;
            case self::VIEW:
                return $subject->getUsers()->contains($user);
        }

        return false;
    }
}
