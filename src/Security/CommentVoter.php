<?php

namespace App\Security;

use App\Entity\Comment;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CommentVoter extends Voter
{
    /**
     * @inheritdoc
     */
    protected function supports($attribute, $subject): bool
    {
        if (false === in_array($attribute, [
                Actions::CREATE,
                Actions::EDIT,
                Actions::DELETE
            ])) {
            return false;
        }

        if (false === $subject instanceof Comment) {
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

        if (false !== in_array(Roles::ROLE_SUPER_ADMIN, $user->getRoles())) {
            return true;
        }

        if (false !== $user->isBanned()) {
            return false;
        }

        switch ($attribute) {
            case Actions::CREATE:
                return $this->canCreate($user);
            case Actions::EDIT:
                return $this->canEdit($subject, $user);
            case Actions::DELETE:
                return $this->canDelete($subject, $user);
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
        return true;
    }

    /**
     * @param Comment $subject
     * @param User $user
     *
     * @return bool
     */
    private function canEdit(Comment $subject, User $user): bool
    {
        return $user->getId() === $subject->getUser()->getId();
    }

    /**
     * @param Comment $subject
     * @param User $user
     *
     * @return bool
     */
    private function canDelete(Comment $subject, User $user): bool
    {
        return $user->getId() === $subject->getUser()->getId() || false !== in_array(Roles::ROLE_ADMIN, $user->getRoles());
    }
}
