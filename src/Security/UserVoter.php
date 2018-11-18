<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class UserVoter extends Voter
{
    /**
     * @var Security
     */
    private $security;

    /**
     * UserVoter constructor.
     *
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @inheritdoc
     */
    protected function supports($attribute, $subject): bool
    {

        if (false === in_array($attribute, [
                Actions::EDIT,
                Actions::DELETE,
                Actions::PROMOTE,
                Actions::BAN
            ])) {
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
            case Actions::PROMOTE:
                return $this->canChangeRole($subject, $user);
            case Actions::BAN:
                return $this->canBan($subject, $user);
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
        return $user->getId() === $subject->getId() || false !== $this->security->isGranted(Roles::ROLE_SUPER_ADMIN);
    }

    /**
     * @param User $subject
     * @param User $user
     *
     * @return bool
     */
    private function canDelete(User $subject, User $user): bool
    {
        return $user->getId() !== $subject->getId() && false !== $this->security->isGranted(Roles::ROLE_SUPER_ADMIN);
    }

    /**
     * @param User $subject
     * @param User $user
     *
     * @return bool
     */
    private function canChangeRole(User $subject, User $user): bool
    {
        return $user->getId() !== $subject->getId() && false !== $this->security->isGranted(Roles::ROLE_SUPER_ADMIN);
    }

    /**
     * @param User $subject
     * @param User $user
     *
     * @return bool
     */
    private function canBan(User $subject, User $user): bool
    {
        return $user->getId() !== $subject->getId() && false !== $this->security->isGranted(Roles::ROLE_ADMIN);
    }
}
