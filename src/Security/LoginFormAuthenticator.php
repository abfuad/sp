<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
// use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    private UrlGeneratorInterface $urlGenerator;
    private $user;
    private $entityManager;
    public const LOGIN_ROUTE = 'app_login';

    private $security;
    public function __construct(UrlGeneratorInterface $urlGenerator,EntityManagerInterface $entityManager,Security $security)
    {
        $this->urlGenerator = $urlGenerator;
        $this->entityManager = $entityManager;
        $this->security = $security;
    }
    public function authenticate(Request $request): Passport
    {
        $username = $request->request->get('username', '');

        $request->getSession()->set(Security::LAST_USERNAME, $username);
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $request->request->get('username')]);

        if($user && !$user->isIsActive()){
            throw new CustomUserMessageAuthenticationException('Deactivated user');
        }

        return new Passport(
            new UserBadge($username),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $user=$this->security->getUser();
        ;        $permissions = [];
                foreach ($user->getRoles() as $role) {
                    $permissions[] = $role;
                }
        
                $groups = $user->getUserGroups();
                foreach ($groups as $key => $value) {
        
                    $permission = $value->getPermission();
                    foreach ($permission as $key => $value1) {
                        $permissions[] = $value1->getCode();
                    }
                }
                $request->getSession()->set(
                    "PERMISSION",
                    $permissions
                );
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // For example:
        return new RedirectResponse($this->urlGenerator->generate('app_home_index'));
        throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
