<?php

namespace importers;

interface ImporterInterface {
  
    function getRawContentData();
    function getBlogs();
}