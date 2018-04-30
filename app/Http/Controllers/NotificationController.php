<?php

namespace App\Http\Controllers;

// use Auth;
use GetStream\StreamLaravel\Enrich;
use FeedManager, Request, Response, View;

class NotificationController extends Controller
{
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

	public function _idValidator(array $data)
	{
		$validator = [
			'userId'=> 'required|integer'
		];

		return Validator::make($data, $validator);
	}

	public function genToken($userId)
	{
		if(!isset($userId)) {
			return 'error';
		}

		$feed = FeedManager::getNotificationFeed($userId);
        return $feed->getToken();
	}

    public function createNotification()
    {
        $user = Auth::user();
        $target = \App\User::find(2);

        $contentType = 'Topic';
        $contentId = '123';

        $userFeed = FeedManager::getClient()->feed('notification', $target->id);
        $userFeed->addActivity([
            "actor"      => "App\User:{$target->id}",
            "verb"       => "love",
            "object"     => "App\$contentType:$contentId",
        ]);

        dd($userFeed);
    }

    public function getNotification()
    {
        $user = Auth::user();
        $target = \App\User::find(2);

        $enricher = new Enrich();

        $feed = FeedManager::getNotificationFeed($target->id);
        $activities = $feed->getActivities(0,25)['results'];
        $activities = $enricher->enrichActivities($activities);

        dd($feed, $activities);
    }
}
