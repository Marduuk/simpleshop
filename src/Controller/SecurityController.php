<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityController
 * @package App\Controller
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        if (empty($error)){

            $response = new JsonResponse(
                [
                    'message' => 'Successfully logged in.',
                    'success' => true
                ],
                200);

        } else {
            $response = new JsonResponse(
                [
                    'error' => $error->getMessageKey(),
                    'success' => false
                ],
                401);
        }
        return $response;
    }

    /**
     * @Route("/logout", name="app_logout" , methods={"GET"})
     */
    public function logout(){}
}
