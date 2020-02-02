<?php

interface ImporterInterface {
  
    function getContentData();
    function getSlug();
    function getContent();
    function getDate();
    function replaceContent();
    //function updateContent();
}