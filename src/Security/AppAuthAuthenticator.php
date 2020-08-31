<?php
declare(strict_types=1);

namespace App\Security;

use App\Service\ExceptionService;
use App\Validator\ApiBasicValidatorService;
use App\Exception\ValidationException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Guard\PasswordAuthenticatedInterface;

/**
 * Class AppAuthAuthenticator
 * @package App\Security
 */
class AppAuthAuthenticator extends AbstractFormLoginAuthenticator implements PasswordAuthenticatedInterface
{
    public const LOGIN_ROUTE = 'app_login';

    /** @var UrlGeneratorInterface  */
    private $urlGenerator;

    /** @var UserPasswordEncoderInterface  */
    private $passwordEncoder;

    /** @var FormFactoryInterface  */
    private $formFactory;

    /** @var ExceptionService  */
    private $exceptionService;

    /** @var ApiBasicValidatorService  */
    private $apiValidator;

    /**
     * AppAuthAuthenticator constructor.
     * @param UrlGeneratorInterface $urlGenerator
     * @param FormFactoryInterface $formFactory
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param ExceptionService $exceptionService
     * @param ApiBasicValidatorService $apiValidator
     */
    public function __construct(UrlGeneratorInterface $urlGenerator,
                                FormFactoryInterface $formFactory,
                                UserPasswordEncoderInterface $passwordEncoder,
                                ExceptionService $exceptionService,
                                ApiBasicValidatorService  $apiValidator)
    {
        $this->formFactory = $formFactory;
        $this->exceptionService = $exceptionService;
        $this->urlGenerator = $urlGenerator;
        $this->passwordEncoder = $passwordEncoder;
        $this->apiValidator = $apiValidator;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function supports(Request $request): bool
    {
        return self::LOGIN_ROUTE === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    /**
     * @param Request $request
     * @return array|mixed
     * @throws ValidationException
     */
    public function getCredentials(Request $request)
    {
        $violations = $this->apiValidator->validate([
            'username' => $request->request->get('username'),
            'password' => $request->request->get('password')
        ]);

        if (!empty($violations))
            throw new ValidationException($violations);

        $credentials = [
            'username' => $request->request->get('username'),
            'password' => $request->request->get('password'),
        ];

        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['username']
        );

        return $credentials;
    }

    /**
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     * @return UserInterface
     */
    public function getUser($credentials, UserProviderInterface $userProvider): UserInterface
    {
        $user = $userProvider->loadUserByUsername($credentials['username']);

        if (!$user)
            throw new CustomUserMessageAuthenticationException('Username could not be found.');

        return $user;
    }

    /**
     * @param mixed $credentials
     * @param UserInterface $user
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function getPassword($credentials): ?string
    {
        return $credentials['password'];
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey
     * @return Response|void|null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey){}

    /**
     * @return string
     */
    protected function getLoginUrl(): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }

    /**
     * Override to control what happens when the user hits a secure page
     * but isn't logged in yet.
     * @param Request $request
     * @param AuthenticationException|null $authException
     */
    public function start(Request $request, AuthenticationException $authException = null): void
    {
        throw new HttpException(Response::HTTP_UNAUTHORIZED, 'Unauthorized.');
    }
}
