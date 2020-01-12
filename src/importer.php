<?php

Class WordpressMigrator {

    function run()
    {
        
        echo getenv('ORIGIN_BLOG_URL');
        echo '123';
    }
}



/*
include_once('helpers.php');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

const ORIGIN_BLOG_URL = '###/wp-json/wp/v2/posts';
const DESTINATION_WORDPRESS_REST_URL = 'http://localhost:8080/wp-json/';

const OLD_DOMAIN = '###';
const NEW_DOMAIN = 'localhost:8080';

const WP_MEDIA_URL = DESTINATION_WORDPRESS_REST_URL.'wp/v2/media/';
const WP_POST_URL = '';

const USERNAME = '###';
const PASSWORD = '###';

$urls_to_replace = [];

//Get Posts
$json = stripcslashes(file_get_contents(ORIGIN_BLOG_URL));

//Extract URLs
$urls = getUrlsFromString($json);
//Remove dupliactes
$unique_urls = array_unique($urls);

foreach($unique_urls as $url) 
{

  //Check if URL is of image
  if(urlIsImage($url))
  {
    try
    {
      //get image and call destination api to store image
      $image = file_get_contents($url);
      
      $response = makeCurlCall(USERNAME, PASSWORD, WP_MEDIA_URL, $image);

      $uploaded_image_url = $response->guid->rendered; //TO DO -- GET THE URL OF THE NEWLY UPLOADED IMAGE

      $urls_to_replace[$url] = $uploaded_image_url;

      break;
    }
    catch(Exception $e){
      var_dump($e);
    }        
  }
  else 
  {
    //if url is not an image, in case it is an internal link, replace with new domain
    $urls_to_replace[$url] = str_replace(OLD_DOMAIN, NEW_DOMAIN, $url);
  }
}

//replace urls in JSON response
foreach ($urls_to_replace as $old_url=>$new_url)
{
  $json = str_replace($old_url, $new_url, $json);
}

//insert posts
var_dump($json);



//plan

//go through array and extract URLs

//Loop through array 
  //if attachment
    //Retrieve attachements 
    //store them locally
    //update old domain to new
  //if link
    //update old domain to new

//update json with new references

//insert post
*/