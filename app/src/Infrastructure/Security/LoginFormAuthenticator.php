<?php

declare(strict_types=1);

namespace App\Infrastructure\Security;

use InvalidArgumentException;
use Override;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

use function is_string;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    #[Override]
    public function authenticate(Request $request): Passport
    {
        $username = $request->request->get(key: 'username');
        $password = $request->request->get(key: 'password');

        if (!is_string(value: $username) || $username === '') {
            throw new InvalidArgumentException(message: 'Username must be a non-empty string');
        }
        if (!is_string(value: $password) || $password === '') {
            throw new InvalidArgumentException(message: 'Password must be a non-empty string');
        }

        return new Passport(
            userBadge: new UserBadge(userIdentifier: $username),
            credentials: new PasswordCredentials(password: $password),
        );
    }

    #[Override]
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath(session: $request->getSession(), firewallName: $firewallName)) {
            return new RedirectResponse(url: $targetPath);
        }

        return new RedirectResponse(url: '/');
    }

    #[Override]
    protected function getLoginUrl(Request $request): string
    {
        return '/login';
    }
}
