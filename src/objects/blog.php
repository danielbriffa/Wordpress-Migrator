<?php

namespace DannyBriff\objects;

class Blog {

    protected $title; 
    protected $slug; 
    protected $date; 
    protected $featured_image;
    protected $status; 
    protected $content; 
    protected $excerpt; 

    public function getTitle()
    {
        return $this->title;
    }
    public function setTitle($value)
    {
        $this->title = $value;
    }

    public function getSlug()
    {
        return $this->slug;
    }
    public function setSlug($value)
    {
        $this->slug = $value;
    }

    public function getDate()
    {
        return $this->date;
    }
    public function setDate($value)
    {
        $this->date = $value;
    }

    public function getFeaturedImage()
    {
        return $this->featured_image;
    }

    public function setFeaturedImage($value)
    {
        $this->featured_image = $value;
    }

    public function getStatus()
    {
        return $this->status;
    }
    public function setStatus($value)
    {
        $this->status = $value;
    }

    public function getContent()
    {
        return $this->content;
    }
    public function setContent($value)
    {
        $this->content = $value;
    }

    public function getExcerpt()
    {
        return $this->excerpt;
    }
    public function setExcerpt($value)
    {
        $this->excerpt = $value;
    }

}