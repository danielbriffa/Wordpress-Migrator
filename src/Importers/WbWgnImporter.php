<?php

namespace DannyBriff\importers;

use DannyBriff\objects\Blog;
use DannyBriff\importers\ImporterInterface;

class WbWgnImporter implements ImporterInterface 
{

    protected $pdo;
    protected $origin_url;
    protected $content_raw;
    protected $blogs = [];

    function __construct($dsn, $user, $pass, $show, $page)
    {
        $this->pdo = $this->getPdoConnection($dsn, $user, $pass);

        $this->loadBlogsFromDb($show, $page);
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
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            return new \PDO($dsn, $user, $pass, $options);

        }
        catch (\PDOException $e)
        {
                throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    private function loadBlogsFromDb($show, $page)
    {
        $stmt = $this->pdo->prepare('SELECT e.channel_id, e.status, e.published_at, et.slug, et.title, et.excerpt, et.content
                                    FROM entry_translations et
                                    JOIN entries e
                                    ON e.id = et.entry_id
                                    WHERE e.channel_id IN (:channels) AND e.deleted_at IS NULL LIMIT :page, :show'
                                );
        $stmt->execute(['channels' => getenv('CHANNEL_IDS'), 'page' => $page, 'show' => $show]);
        
        while ($row = $stmt->fetch())
        {
            $this->extractBlogsFromRawContent($row);
        }
    }

    private function extractBlogsFromRawContent($data)
    {

        $data = $this->payloadModifier($data);

        $blog = new Blog();

        $blog->setTitle($data['title']);
        $blog->setSlug($data['slug']);
        $blog->setDate($data['published_at']);
        $blog->setStatus($data['status']);
        $blog->setContent(stripcslashes($data['content']));
        $blog->setExcerpt(stripcslashes($data['excerpt']));
       
        array_push($this->blogs, $blog);        
    }

    private function payloadModifier($data)
    {
        $data = $this->statusMapping($data);
        $data = $this->updateImageUrlsFullUrl($data);

        return $data;
    }

    private function statusMapping($data)
    {
        if ($data['status'] == 'published')
        {
            $data['status'] = 'publish';
        }

        return $data;
    }

    private function updateImageUrlsFullUrl($data)
    {
        //get image paths starting with -- /images ending .png/.jpg
        $urls = getRelativeUrlsFromString($data['content']);

        // add old domain before (so the image importer will be able to grab the image and work)
         //Remove dupliactes
        $unique_urls = array_unique($urls);
        
        foreach ($unique_urls as $url)
        {
            //remove open & close quotes
            $new_url = '"'.getenv('OLD_DOMAIN').substr($url, 1, -1).'"';

            $transformed_data = str_replace($url, $new_url, $data['content']);

            //update content
            $data['content'] = $transformed_data;
        }
        
        return $data;
    }

}