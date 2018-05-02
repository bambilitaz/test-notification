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

    //99 full 66 basic

	public function genToken($userId)
	{
		if(!isset($userId)) {
			return 'error';
		}

		$feed = FeedManager::getNotificationFeed($userId);
		return $feed->getToken();
	}

    public function pushNotification(Request $request)
    {
        $notificationData = Request::except('_token');

        // // $object = explode(":", $notificationData->data->object);
        // // $target = explode(":", $notificationData->data->target);
        $userFeedData = [
            'actor' => $notificationData->data->actor,
            'verb' => $notificationData->data->verb,
            'object' => $notificationData->data->object,
            'target' => $notificationData->data->target
        ];

        if (isset($notificationData->full->actorImg) && isset($notificationData->full->actorName) && isset($notificationData->full->targetTitle)) {
            $userFeedData['actorImg'] = $notificationData->full->actorImg;
            $userFeedData['actorName'] = $notificationData->full->actorName;
            $userFeedData['targetTitle'] = $notificationData->full->targetTitle;
        }

        try {
            $userFeed = FeedManager::getClient()->feed('notification', $notificationData->notiId);
            $userFeed->addActivity($userFeedData);

            dd($userFeed);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function getNotification()
    {
        // $user = Auth::user();
        $target = \App\User::find(2);

		$enricher = new Enrich();

		$feed = FeedManager::getNotificationFeed($target->id);
		$activities = $feed->getActivities(0,25)['results'];
		$activities = $enricher->enrichActivities($activities);

        dd($feed, $activities);
    }

    public function getData(Request $request)
    {
        $data = $request->except(['_token']);

    }
}
