<?php
class WordpressImporter implements ImporterInterface {

    protected $origin_url;
    protected $content_raw;
    protected $content_parsed;

    function __construct($_origin_url)
    {
       $this->origin_url = $_origin_url;
       $this->content_raw = stripcslashes(file_get_contents($this->origin_url));
    }

    function getContentData()
    {
       return $this->content_raw;
    }

    function getSlug()
    {
        return (json_decode($this->content_raw))->slug;
    }
    
    function getContent()
    {
        return (json_decode($this->content_raw))->content;
    }
    
    function getDate()
    {
        return (json_decode($this->content_raw))->date;
    }
    
    function replaceContent($_array_replace)
    {
        foreach ($_array_replace as $old_term=>$new_term)
        {
            $data = str_replace($old_term, $new_term, $this->content_raw);
            $this->content_raw = $data;
        }
    }
}