<?php

namespace App\Security;

use App\Entity\Post;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PostVoter extends Voter
{
    /**
     * @inheritdoc
     */
    protected function supports($attribute, $subject): bool
    {

        if (false === in_array($attribute, Actions::getAllActions())) {
            return false;
        }

        if (false === $subject instanceof Post) {
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
            case Actions::CREATE:
                return $this->canCreate($user);
        }

        throw new \LogicException('Undefined action');
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    private function canCreate(User $user): bool
    {
        return false !== in_array('ROLE_ADMIN', $user->getRoles());
    }

    /**
     * @param Post $subject
     * @param User $user
     *
     * @return bool
     */
    private function canEdit(Post $subject, User $user): bool
    {
        return $user->getId() === $subject->getUser()->getId() || false !== in_array('ROLE_SUPER_ADMIN', $user->getRoles());
    }

    /**
     * @param Post $subject
     * @param User $user
     *
     * @return bool
     */
    private function canDelete(Post $subject, User $user): bool
    {
        return $user->getId() === $subject->getUser()->getId() || false !== in_array('ROLE_SUPER_ADMIN', $user->getRoles());
    }
}
