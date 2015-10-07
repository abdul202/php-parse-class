<?php
/**
 * @since 25/7/2015 
 * @category  PHP/cURL package 
 * @author Abdul Ibrahim <shuman202@hotmail.com>
 * @copyright 2015 Abdul Ibrahim
 * @license http://opensource.org/licenses/MIT  The MIT License (MIT)
 * @version 1.0
 * @link http://www.abdulibrahim.com/ my home page
 */
class Parse {
    
    /**
     * Returns a potion of the string that is either before or after    
     * the delineator. The parse is not case sensitive, but the case of
     * the parsed string is not effected.								
     * @param string $string       Input string to parse                            
     * @param string $delineator   Delineation point (place where split occurs)    
     * @param string $desired     <b> BEFORE</b>: return portion before delineator   <br>     
     *                             <b>AFTER</b>:  return portion before delineator     <br>   
     * @param string $type         <b>INCL</b>:   include delineator in parsed string    <br>
     *                            <b> EXCL</b>:   exclude delineator in parsed string   <br>
     * @return string the substring
     */
    public function splitString($string, $delineator, $desired, $type)
        {
        # Case insensitive parse, convert string and delineator to lower case
        $lc_str = strtolower($string);
            $marker = strtolower($delineator);

        # Return text BEFORE the delineator
        if($desired == 'BEFORE')
            {
            if($type == 'EXCL')  // Return text ESCL of the delineator
                $split_here = strpos($lc_str, $marker);
            else               // Return text INCL of the delineator
                $split_here = strpos($lc_str, $marker)+strlen($marker);

            $parsed_string = substr($string, 0, $split_here);
            }
        # Return text AFTER the delineator
        else
            {
            if($type=='EXCL')    // Return text ESCL of the delineator
                $split_here = strpos($lc_str, $marker) + strlen($marker);
            else               // Return text INCL of the delineator
                $split_here = strpos($lc_str, $marker) ;

            $parsed_string =  substr($string, $split_here, strlen($string));
            }
            return $parsed_string;
        }
  
    /**
    *        Returns a substring of $string delineated by $start and $end    
    *        The parse is not case sensitive, but the case of the parsed     
    *        string is not effected.                                         
    *@param string  $string Input string to parse                           
    *@param string  $start          Defines the beginning of the sub string         
    *@param string  $end            Defines the end of the sub string               
    *@param string  $type           <b>INCL</b>: include delineators in parsed string  <br>    
    *                               <b>EXCL</b>: exclude delineators in parsed string
    *@return string return the substring 
        */
    public function returnBetween($string, $start, $stop, $type)
        {
         $string ;
        $temp = $this->splitString($string, $start, 'AFTER', $type);
        return $this->splitString($temp, $stop, 'BEFORE', $type);
        
        }
    /**
    *    This method is usful for returning an array that contains     
    *    links, images, tables or any other data that appears more than once.    
    * @param string $string    String that contains the tags
    * @param string $beg_tag   Name of the open tag (i.e. "<a>") 
    * @param string $close_tag Name of the closing tag (i.e. '&lt;/title&gt;')
    * @return array of strings that exists repeatedly in $string
     */    
    public function parseArray($string, $beg_tag, $close_tag) {
        preg_match_all("($beg_tag(.*)$close_tag)siU", $string, $matching_data);
        return $matching_data[0];
    }
    /**
     * provides an interface that allows webbot developers to parse specific attribute values from HTML tags.
     * @param string $tag The tag that contains the attribute (i.e. <img src="img.gif" /> )
     * @param string $attribute  The name of the attribute, whose value you seek  (i.e. src )
     * @return string  the value of an attribute in a given tag.
     */
    public function getAttribute($tag, $attribute)
    {
        /** Use Tidy library to 'clean' input */
        $cleaned_html = $this->tidyHtml($tag);
        /** Remove all line feeds from the string */
        $cleaned_html = str_replace("\r", "", $cleaned_html);   
        $cleaned_html = str_replace("\n", "", $cleaned_html);
        /** Use return_between() to find the properly quoted value for the attribute */
        return $this->returnBetween($cleaned_html, strtoupper($attribute)."=\"", "\"", 'EXCL');
    }
    /**
     * Returns a "Cleans-up" (parsable) version raw HTML
     * @param string $inputString raw HTML
     * @return string string of cleaned-up HTML 
     */
    public function tidyHtml($inputString)
    {
    // Detect if Tidy is in configured
    if( function_exists('tidy_get_release') )
        {
            # Tidy for PHP version 4
            if(substr(phpversion(), 0, 1) == 4)
                {
                tidy_setopt('uppercase-attributes', TRUE);
                tidy_setopt('wrap', 800);
                tidy_parse_string($inputString);            
                $cleaned_html = tidy_get_output();  
                }
            # Tidy for PHP version 5
            if(substr(phpversion(), 0, 1) == 5)
                {
                $config = array(
                               'uppercase-attributes' => true,
                               'wrap'                 => 800);
                $tidy = new tidy;
                $tidy->parseString($inputString, $config, 'utf8');
                $tidy->cleanRepair();
                $cleaned_html  = tidy_get_output($tidy);  
                }
        } else {
            # Tidy not configured for this computer
            $cleaned_html = $inputString;
        }
        return $cleaned_html;
    }
    /**
     * Removes all text between <b>$open_tag</b> and <b>$close_tag</b> <br>
     * like removing javascript from page 
     * @param string $string The target of your parse
     * @param string $open_tag The starting delimitor
     * @param string $close_tag The ending delimitor 
     * @return string the text after removing the <b>$open_tag</b> and <b>$close_tag</b> and what's bewteen them 
     */
    public function remove($string, $open_tag, $close_tag)
    {
        # Get array of things that should be removed from the input string
        $remove_array = $this->parseArray($string, $open_tag, $close_tag);

        # Remove each occurrence of each array element from string;
        for($xx=0; $xx<count($remove_array); $xx++)
            $string = str_replace($remove_array, "", $string);
        return $string;
    }
   
}
