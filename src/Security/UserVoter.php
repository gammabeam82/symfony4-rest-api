<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{
    /**
     * @inheritdoc
     */
    protected function supports($attribute, $subject): bool
    {

        if (false === in_array($attribute, [Actions::EDIT, Actions::DELETE])) {
            return false;
        }

        if (false === $subject instanceof User) {
            return false;
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        switch ($attribute) {
            case Actions::EDIT:
                return $this->canEdit($subject, $user);
            case Actions::DELETE:
                return $this->canDelete($subject, $user);
        }

        throw new \LogicException('Undefined action');
    }

    /**
     * @param User $subject
     * @param User $user
     *
     * @return bool
     */
    private function canEdit(User $subject, User $user): bool
    {
        return $user->getId() === $subject->getId() || false !== in_array(Roles::ROLE_SUPER_ADMIN, $user->getRoles());
    }

    /**
     * @param User $subject
     * @param User $user
     *
     * @return bool
     */
    private function canDelete(User $subject, User $user): bool
    {
        return $user->getId() !== $subject->getId() && false !== in_array(Roles::ROLE_SUPER_ADMIN, $user->getRoles());
    }
}
