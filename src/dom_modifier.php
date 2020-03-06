<?php

namespace DannyBriff;

class DomModifier {

    protected $html;        

    public function __construct($_html = '')
    {
        // init htmlobject & set
        $this->set_html_content($_html);
    }

    
    private function get_html()
    {
        if (!$this->html instanceof \DOMDocument)
        {
            $this->html = new \DOMDocument();
            $this->html->strictErrorChecking = false;
            $this->html->substituteEntities = false;
            $this->html->encoding = 'utf-8';
        }
        return $this->html;
    }

    public function set_html_content($_html)
    {        
        @$this->get_html()
             ->loadHTML(mb_convert_encoding($_html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    }

    public function get_html_content() 
    {
        return $this->html->saveHTML();
    }

    public function wrap_elements($element, $wrapper)
    {
        $elements = $this->get_html()->getElementsByTagName($element);

        $wrap_element = $this->get_html()->createElement($wrapper['element']);

        foreach($wrapper['attributes'] as $attr_name => $attr_value)
        {
            $wrap_element->setAttribute($attr_name, $attr_value);
        }

        //loop through all elements
        foreach($elements as $element)
        {
            //Clone our created element
            $wrap_element_clone = $wrap_element->cloneNode();
            //Replace child with this wrapper 
            $element->parentNode->replaceChild($wrap_element_clone, $element);
            //Append this child to wrapper element
            $wrap_element_clone->appendChild($element);
        }

        return $this;
    }

    public function add_attribute($element, $attribute_key, $attribute_value)
    {
        $elements = $this->get_html()->getElementsByTagName($element);
        
        //loop through all elements
        foreach($elements as $element)
        {
            $attr = $element->getAttribute($attribute_key);

            //if attribute already exists, insert only the difference, else set all values passed
            $to_add = ($attr != null) ? array_unique (array_merge ($attribute_value, explode(' ', $attr)))
                                      : $attribute_value;

            $element->setAttribute($attribute_key, implode(' ', $to_add));
        }

        return $this;
    }

    public function remove_attribute($element, $attribute_key, $attribute_value)
    {
        $elements = $this->get_html()->getElementsByTagName($element);
        
        //loop through all elements
        foreach($elements as $element)
        {
            $attr = $element->getAttribute($attribute_key);

            //if elements has some values to this attribute, return the difference to the passed elements.
            $to_add = ($attr != null) ? array_diff(explode(' ', $attr), $attribute_value)
                                      : null;

            if ($to_add != null)
            {
                $element->setAttribute($attribute_key, implode(' ', $to_add));
            }
        }

        return $this;
    }

    private function getHtmlElementType($_element)
    {
        //start at position 1, since position 0 is assumed to be a '<'
        //end till we encounter the first space or closing tag >
        $space_index = strpos($_element, ' ');
        $close_tag_index = strpos($_element, '>');

        // if space is found, check if smaller then close tag. if yes, use as index, else use close tag as index
        $index =  ($space_index != false && $space_index < $close_tag_index) ? $space_index : $close_tag_index;
                
        return substr($_element, 1, $index - 1);
    }

}