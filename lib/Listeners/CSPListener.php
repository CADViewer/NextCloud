<?php

declare(strict_types=1);

namespace OCA\Cadviewer\Listeners;

use OCP\AppFramework\Http\EmptyContentSecurityPolicy;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\Security\CSP\AddContentSecurityPolicyEvent;

class CSPListener implements IEventListener {
	public function handle(Event $event): void {
		if (!$event instanceof AddContentSecurityPolicyEvent) {
			return;
		}

		$csp = new EmptyContentSecurityPolicy();
        $csp->allowEvalScript(true);
		$csp->addAllowedConnectDomain('*');
		$event->addPolicy($csp);
	}
}
