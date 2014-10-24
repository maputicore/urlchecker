<?php
    ini_set("max_execution_time", 0);
    $urls_path = "./urls.csv";
    $result_path = "./result.csv";

    $urls = file_get_contents($urls_path);
    $urls = explode("\n", $urls);

    if (count($urls) > 0){
        $result = [];
        foreach ($urls as $url) {
            $url = mb_convert_encoding($url, "utf8");
            //$url = "http://hogehugapiyo.com";
            $context = stream_context_create(array(
                'http' => array('ignore_errors' => true)
            ));
            //$response = file_get_contents($url, false, $context);

            //$status = $http_response_header[0];
            $status = curl($url);
            if ($status == 0)
                $status = 404;
            $result[] = "{$url} [{$status}]";
        }    

        file_put_contents($result_path, implode("\n", $result));
        echo count($result) . "件のURLを調べました";
    }
    

function curl($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_exec($ch);
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close ($ch);
    return $statusCode;
}

?>