<?php

Class Migrator {

  protected $importer;

  protected $origin_blog_url;
  protected $destination_wordpress_rest_url;
  protected $old_domain;
  protected $new_domain;
  protected $wp_media_url;
  protected $wp_post_url;
  protected $username;
  protected $password;
  

  function __construct($_importer) {
    $this->origin_blog_url = getenv('ORIGIN_BLOG_URL'); 

    $this->loadImporter($_importer, $this->origin_blog_url);

    $this->destination_wordpress_rest_url = getenv('DESTINATION_WORDPRESS_REST_URL'); 
    $this->old_domain = getenv('OLD_DOMAIN'); 
    $this->new_domain = getenv('NEW_DOMAIN'); 
    $this->wp_media_url = getenv('WP_MEDIA_URL'); 
    $this->wp_post_url = getenv('WP_POST_URL'); 
    $this->username = 'admin'; //getenv('USERNAME');
    $this->password = getenv('PASSWORD');
  }

  function loadImporter($_importer, $_origin_url)
  {
    switch($_importer) 
    {
      case 'wordpress':
          $this->importer = new WordpressImporter($_origin_url);
        break;
      default:
          throw new Exception('Importer does not exist');
        break;
    }
  }

  function run()
  {        
    //get data
    $data = $this->importer->getContentData();

    //if setting is on, change urls and update content before inserting new post
    if (getenv('CHANGE_URLS'))
    {
      $urls_to_replace = $this->extractUrlsFromStringToReplace($data);

      $this->importer->replaceContent($urls_to_replace);
    }

    //insert posts
    $this->insertPost($json);
  }

  /**
   * Extract urls from string, import images to new application and overwrite urls to point to new domain
   */
  function extractUrlsFromStringToReplace($_data)
  {
    //Extract URLs
    $urls = getUrlsFromString($data);

    //Remove dupliactes
    $unique_urls = array_unique($urls);

    foreach($unique_urls as $url) 
    {      
      //Check if URL is of image
      if(urlIsImage($url))
      {
        try
        {
          //if we shall not import images from a third party source, skip this iteration
          if (getenv('CHANGE_EXTERNAL_URL') == false && strpos($url, $this->old_domain) == false )
          {
            continue;
          }

          $uploaded_image_url = $this->transferMedia($url);
          $urls_to_replace[$url] = $uploaded_image_url;;
        }
        catch(Exception $e){
          var_dump($e);
        }        
      }
      else 
      {
        //if url is not an image, in case it is an internal link, replace with new domain
        $urls_to_replace[$url] = str_replace($this->old_domain, $this->new_domain, $url);
      }
    } 

    return $urls_to_replace;
  }

  function transferMedia($_url)
  {
    //get image and call destination api to store image
    $image = file_get_contents($_url);
    //store it
    $response = makeCurlCall($this->username, $this->password, $this->wp_media_url, $image);

    //GET THE URL OF THE NEWLY UPLOADED IMAGE
    return $response->guid->rendered; 
  }

  function insertPost($json)
  {
    
    $json = json_decode($json);
    var_dump($json);

    $data = [
      'date' => $json->date,
      'slug' => $json->slug,
      'status' => $json->status,
      'title' => $json->title,
      'content' => $json->content,
      'excerpt' => '',
      'featured_media' => '',
      'categories' => '',
      'tags' => ''
    ];

    $response = makeCurlCall($this->username, $this->password, $this->wp_post_url, $data);
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