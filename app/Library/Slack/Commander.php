<?php namespace App\Library\Slack;

use Frlnc\Slack\Contracts\Http\Interactor;

class Commander extends \Frlnc\Slack\Core\Commander
{
    /**
     * @param string $token
     * @param \Frlnc\Slack\Contracts\Http\Interactor $interactor
     */
    public function __construct($token, Interactor $interactor)
    {
        // Support additional commands
        self::$commands['rtm.start'] = [
            'token'    => true,
            'endpoint' => '/rtm.start'
        ];

        parent::__construct($token, $interactor);
    }

}