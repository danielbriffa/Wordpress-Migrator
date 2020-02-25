<?php

//https://gist.github.com/jermity/af85cdcaabdb36f96173
function getUrlsFromString($string) 
{
    $regex = '#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#';
    preg_match_all($regex, $string, $match);
    
    return $match[0];
}

function getRelativeUrlsFromString($string)
{
    $regex = '#[\"|\'](\/)([^\/].+?)[\"|\']#';
    preg_match_all($regex, $string, $match);
    
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

function makeCurlCall($username, $password, $url, $data, $headers = [])
{
    try
    {
        $ch = curl_init();

        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_POST, 1 );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array_merge([
            'Authorization: Basic ' . base64_encode( $username . ':' . $password )
        ], $headers) );
    
        $result = curl_exec( $ch );

        curl_close( $ch );

        var_dump($result);        
        return json_decode($result);
        
    }
    catch(Exception $e)
    {
        var_dump($e->getMessage());
    }
}

  