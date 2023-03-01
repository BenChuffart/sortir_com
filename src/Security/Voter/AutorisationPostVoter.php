<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\Trip;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class AutorisationPostVoter extends Voter
{
    public const EDIT = 'POST_EDIT';
    public const VIEW = 'POST_VIEW';
    public const DELETE ='POST_DELETE';

    private $security;

    //Permet de créer un user pour savoir si plus tard il est admin ou non
    public function __construct(Security $security)
    {
        $this -> security = $security;
    }

    protected function supports(string $attribute, $trip): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE])
            && $trip instanceof \App\Entity\Trip;
    }

    protected function voteOnAttribute(string $attribute, $trip, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        //On verif si l'utilisateur est admin
        if($this -> security-> IsGranted('ROLE_ADMIN')) return true;

        //Vérifie qui a créer le trip
        if(null === $trip ->getCreator()) return false;

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {

            case self::EDIT:
                //Vérif si on peut modifier
                /** @var User $user */
                 return $this->canEdit($trip,$user);
                // return true or false
                break;
            case self::VIEW:
                //Vérif si on peut voir
                /** @var User $user */
                return $this -> canView($trip,$user);
                // return true or false
                break;
                case self::DELETE:
                    //Vérif si on peut supprimer
                    /** @var User $user */
                    return $this->canDelete($trip,$user);
                   // return true or false
                   break;
        }

        return false;
    }

    private function canEdit(Trip $trip, User $user) 
    {
        // Le créateur de l'annonce peut la modifier 
        return $user === $trip->getCreator();
    }

    private function canView(Trip $trip, User $user)
    {
        /*if($this -> canEdit($trip,$user))
        {
            return true;
        }*/
        
        return true;
    }
    private function canDelete(Trip $trip, User $user)
    {
         // Le créateur de l'annonce peut la supprimer 
         return $user === $trip->getCreator();
    }
}