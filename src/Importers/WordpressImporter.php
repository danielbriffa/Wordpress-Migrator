<?php

namespace DannyBriff\importers;

use DannyBriff\objects\Blog;
use DannyBriff\importers\ImporterInterface;

class WordpressImporter implements ImporterInterface {

    protected $origin_url;
    protected $content_raw;
    protected $blogs = [];

    function __construct($_origin_url)
    {
        $this->origin_url = $_origin_url;
        $this->content_raw = json_decode(file_get_contents($this->origin_url), true);  

        $this->extractBlogsFromRawContent();
    }

    function getBlogs()
    {
        return $this->blogs;
    }

    private function extractBlogsFromRawContent()
    {
        $posts = $this->content_raw;

        foreach($posts as $post)
        {
            $blog = new Blog();
            $blog->setTitle($post['title']['rendered']);
            $blog->setSlug($post['slug']);
            $blog->setFeaturedimage($post['_embedded']['wp:featuredmedia'][0]['source_url']);
            $blog->setDate($post['date']);
            $blog->setStatus($post['status']);
            $blog->setContent(stripcslashes($post['content']['rendered']));
            $blog->setExcerpt(stripcslashes($post['excerpt']['rendered']));
            
            array_push($this->blogs, $blog);
        }
    }
}