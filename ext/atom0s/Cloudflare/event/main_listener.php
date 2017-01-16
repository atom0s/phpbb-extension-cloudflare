<?php
/**
 *
 * Cloudflare. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2017, atom0s, http://atom0s.com/
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace atom0s\Cloudflare\event;

/**
 * @ignore
 */
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Cloudflare Event listener.
 */
class main_listener implements EventSubscriberInterface
{
    /**
     * Assign functions defined in this class to event listeners in the core
     *
     * @return array
     */
	static public function getSubscribedEvents()
	{
        return array(
            'core.session_ip_after' => 'session_ip_after'
        );
	}

    /**
     * Override the users real IP from the Cloudflare header.
     *
     * @param \phpbb\event\data $event The event object
     */
    public function session_ip_after($event)
    {
        global $request;
        
        // Obtain the users real IP from the known Cloudflare header value..
        $real_ip = (!empty($request->server('HTTP_CF_CONNECTING_IP'))) 
            ? htmlspecialchars((string) $request->server('HTTP_CF_CONNECTING_IP')) 
            : $event['ip'];
        
        // Override the IP of the session..
        $event['ip'] = $real_ip;
    }
}
