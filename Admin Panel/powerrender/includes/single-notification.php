<!-- This is for single mobile notification -->
<?php
require "includes/variables.php";
if (isset($_POST['submit'])) {
    if (!empty($_POST['title']) && !empty($_POST['message'])) {
        $title =  $_POST['title'];
        $message = $_POST['message'];
        $path_to_fcm = "https://fcm.googleapis.com/fcm/send";
        $server_key = "AAAA3wwIYD0:APA91bGf86jLEJ0I9nwL-ZgrTJxswnZCmjguP0QLpFRhqtrO-DinUtoVkYWP4bGmt2yt-NIiHj95swnyV-W8TenGr9t2ks8f5pGtS71lv0EWsY8ebNG3H11_qjD1PqzNLDf9rTgxFLsUgp-y6siPQGWcSAJRHrxl6w";
        $sql = "select id, fcm_token from tbl_fcm_info";
        $result = mysqli_query($connect, $sql);
        // $row = mysqli_fetch_row($result);
        // $key = $row[1];
        
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $key[] = $row['fcm_token'];
            }
        }

        $headers = array('Content-Type: application/json',
                         'Authorization: key=' .$server_key);

        $fields = array("to"=>$key, "notification" => array("title" => $title,"body" => $message));

         $payload=json_encode($fields);                          

         $curl_session = curl_init();
            curl_setopt($curl_session, CURLOPT_URL, $path_to_fcm);
            curl_setopt($curl_session, CURLOPT_POST, true);
            curl_setopt($curl_session, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl_session, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl_session, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl_session, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl_session, CURLOPT_POSTFIELDS, $payload);

        $result = curl_exec($curl_session);

        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($curl_session));
        }

        curl_close($curl_session);
        mysqli_close($connect);
    }

    echo $payload;
}
?>