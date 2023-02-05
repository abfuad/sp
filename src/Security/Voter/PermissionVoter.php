<?php

namespace App\Security\Voter;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class PermissionVoter extends Voter
{
    public const EDIT = 'POST_EDIT';
    public const VIEW = 'POST_VIEW';
    private $requestStack;

    private $security;
    public function __construct(RequestStack $requestStack, Security $security)
    {
        $this->security = $security;
        $this->requestStack = $requestStack;
    }
    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!$this->security->getUser())
        return false;

        $session = $this->requestStack->getSession();

    $permission = $session->get("PERMISSION");
    if ($this->security->getUser() &&  in_array("rlspad", $this->security->getUser()->getRoles()))
        return true;
    if (!$permission)
        $permission = array();

        return in_array($attribute, $permission);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

       
        return true;
    }
}
