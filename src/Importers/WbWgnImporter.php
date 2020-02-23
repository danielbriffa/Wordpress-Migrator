<?php

namespace importers;

use objects\Blog;

class WordpressImporter implements ImporterInterface 
{

    protected $pdo;
    protected $origin_url;
    protected $content_raw;
    protected $blogs = [];

    function __construct()
    {
        $this->$pdo = $this->getPdoConnection($dsn, $user, $pass);

        $this->loadBlogsFromDb();
    }

    function getRawContentData()
    {
       return $this->content_raw;
    }

    function getBlogs()
    {
        return $this->blogs;
    }

    private function getPdoConnection($dsn, $user, $pass)
    {
        try 
        {
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            return new PDO($dsn, $user, $pass, $options);

        } 
        catch (\PDOException $e) 
        {
                throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    private function loadBlogsFromDb()
    {
        $stmt = $pdo->query('#########');

        while ($row = $stmt->fetch())
        {
            $this->extractBlogsFromRawContent($row);
        }
    }

    private function extractBlogsFromRawContent($data)
    {

        $blog = new Blog();

        $blog->setTitle($data);
        $blog->setSlug($data);
        $blog->setDate($data);
        $blog->setStatus($data);
        $blog->setContent(stripcslashes($data));
        $blog->setExcerpt(stripcslashes($data));
        
        array_push($this->blogs, $blog);
        
    }
}