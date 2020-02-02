<?php

//https://gist.github.com/jermity/af85cdcaabdb36f96173
function getUrlsFromString($string) 
{
    $regex = '/https?\:\/\/[^\" \n]+/i';
    preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $string, $match);
    
    return $match[0];
}

function urlIsImage($url)
{
    if (in_array(substr($url, strlen($url)-3), ['jpg', 'png']))
    {
        return true;
    }
    
    return false;
}

function makeCurlCall($username, $password, $url, $data)
{
    try
    {
        $ch = curl_init();

        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_POST, 1 );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt( $ch, CURLOPT_HTTPHEADER, [
            //'Content-Disposition: form-data;',
            'Content-Disposition: form-data; filename="example.jpg"',
            'Authorization: Basic ' . base64_encode( $username . ':' . $password ),
        ] );
    
        $result = curl_exec( $ch );

        curl_close( $ch );

        return json_decode($result);
        
    }
    catch(Exception $e)
    {
        var_dump($e);
    }
}

  