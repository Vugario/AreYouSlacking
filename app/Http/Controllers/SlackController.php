<?php namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Library\OAuth2\Provider\Slack;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use App\User;

use Frlnc\Slack\Http\SlackResponseFactory;
use Frlnc\Slack\Http\CurlInteractor;
use App\Library\Slack\Commander;

class SlackController extends Controller {

    public function getAsk()
    {
        if (Auth::guest())
        {
            return redirect('/');
        }

        $interactor = new CurlInteractor;
        $interactor->setResponseFactory(new SlackResponseFactory);

        $commander = new Commander(Auth::user()->token, $interactor);

        $response = $commander->execute('chat.postMessage', [
            'username'=> 'Mr. Boss man',
            'channel' => Auth::user()->user_id,
            'text'    => 'What are you doing?'
        ]);

        return redirect('/');
    }

    public function getStart()
    {
        if (Auth::guest())
        {
            return redirect('/');
        }

        $interactor = new CurlInteractor;
        $interactor->setResponseFactory(new SlackResponseFactory);

        $commander = new Commander(Auth::user()->token, $interactor);

        $response = $commander->execute('rtm.start');

        echo '<pre>'; var_dump($response); exit;
    }

    public function getAuth()
    {
        $provider = new Slack([
            'clientId'      => getenv('SLACK_KEY'),
            'clientSecret'  => getenv('SLACK_SECRET'),
            'redirectUri'   => getenv('SLACK_CALLBACK'),
            'scopes'        => ['identify', 'client', 'read', 'post']
        ]);

        if (Input::get('code') === null)
        {
            $authUrl = $provider->getAuthorizationUrl();
            Session::put('oauth2state', $provider->state);

            return redirect($authUrl);
        }
        elseif (Input::get('state') === null || (Input::get('state') !== Session::get('oauth2state')))
        {
            Session::forget('oauth2state');

            exit('Invalid state');

        } else {

            // Try to get an access token (using the authorization code grant)
            $token = $provider->getAccessToken('authorization_code', [
                'code' => Input::get('code')
            ]);

            $userDetails = $provider->getUserDetails($token);

            $user = User::firstOrNew(['user_id' => $userDetails['user_id']]);

            $user->token        = $token;
            $user->team_id      = $userDetails['team_id'];
            $user->user_id      = $userDetails['user_id'];
            $user->team         = $userDetails['team'];
            $user->name         = $userDetails['name'];
            $user->firstname    = $userDetails['firstname'];
            $user->lastname     = $userDetails['lastname'];
            $user->email        = $userDetails['email'];
            $user->image        = $userDetails['image'];
            $user->save();

            Auth::loginUsingId($user->id);

            return redirect('/');
        }
    }

}
