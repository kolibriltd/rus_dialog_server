<?php

class OneSignal extends CController {

    private $demoAppID = "6307d53a-2b11-4026-9825-47e2e44da0eb";
    private $demoAppRestKey = "Y2Y5MGYyNzYtY2M0OS00M2Y1LWIwY2ItMWJkNzBhNGMzMmY3";
    
    function OneSignal($t, $m){
        $content = array(
                "en" => $m,
                );

        $fields = array(
            'app_id' => $this->demoAppID,
            'included_segments' => array('All'),
           // 'include_player_ids' => array($playerId),
            'data' => array("foo" => "bar"),
            'headings' => array (
                'en' => $t,
            ),
            'contents' => $content
        );

        $fields = json_encode($fields);
//        print("\nJSON sent:\n");
//        print($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
                        'Authorization: Basic ' . $this->demoAppRestKey));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
	}

}
