<?php
declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Event\LogoutEvent;

/**
 * Class LogoutListener
 * @package App\EventListener
 */
class LogoutListener
{
    /**
     * @param LogoutEvent $event
     */
    public function onSymfonyComponentSecurityHttpEventLogoutEvent(LogoutEvent $event): void
    {
        $event->setResponse(
            new JsonResponse([
                'message' => 'Successfully logged out',
                'success' => true
            ])
        );
    }
}