<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ProfileVoter extends Voter
{
    public const PROFILE_EDIT = 'PROFILE_EDIT';
    public const PROFILE_DELETE = 'PROFILE_DELETE';

    protected function supports(string $attribute, $user): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::PROFILE_EDIT, self::PROFILE_DELETE])
            && $user instanceof \App\Entity\User;
    }

    protected function voteOnAttribute(string $attribute, $user, TokenInterface $token): bool
    {
        $userConnected = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$userConnected instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::PROFILE_EDIT:
                // logic to determine if the user can EDIT
                return $this->canEdit($user, $userConnected);
                break;
        }
        return false;
    }

    private function canEdit(User $user, UserInterface $userConnected){
        // The user can edit their profile
        return $userConnected->getUserIdentifier() === $user->getUserIdentifier();
    }
}
