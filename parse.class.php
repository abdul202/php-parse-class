<?php
/**
 * @since 20/7/2015 
 * @category  PHP/cURL package 
 * @author Abdul Ibrahim <shuman202@hotmail.com>
 * @copyright 2015 Abdul Ibrahim
 * @license http://opensource.org/licenses/MIT  The MIT License (MIT)
 * @version 1.0
 * @link http://www.abdulibrahim.com/ my home page
 */
class Curl {
    /**
    *	cURL handle
    */
    public $ch;
    /**
     * @var string Define how your Agent will appear in server logs
     */
    public $agent = 'Mozilla/5.0 (Windows NT 6.1; rv:18.0) Gecko/20100101 Firefox/18.0';
    /** 
     * It should contain the maximum time in seconds that you allow the connection phase to the server to take.<br>
     *  This only limits the connection phase, it has no impact once it has connected.
     * @var int Length of time cURL will wait for a response (seconds)
     */
    public $timeOut = 25 ; // default 25 seconds
    /**
     * @var string Location of your cookie file as default it will be store 
     * in cookie.txt file stored in your script home directory
     */    
    public $cookieFile ;
    /**
     * @var string The server referer variable it has a default
     * value set to https://www.google.com
     */
    public $referer = 'https://www.google.com';
    /**
     * @var string The target file to download
     */
    public $target ;
    /**
     * @var boolean set as a flag to Whether to return the response header or not
     * as a default it will not be returned
     */
    public $withHeader = FALSE ;
    /**
     * @var boolean set as a flag to Whether to return the response Body or not
     * as a default it will be returned
     */
    public $noBody = FALSE ;
    /**
     * @var string Location of your more details file
     * as default it will be located at your document root
     */     
    public $verboseFile ;
    /**
     * @var boolean set as a flag to Whether to or not to write more details about the trnasfer
     * as a default it will be not be available
     */    
    public $verbose = FALSE ;
    /**
     * @var int The maximum amount of HTTP redirections to follow
     * as default is set to 4 
     */  
    public $maxRedirs = 4 ;
    /**
     * @var array Get information regarding a specific transfer
     */  
    public $infoArray ;
    /**
     * @var string Contents of fetched file
     */    
    public $file ;
    /**
     * @var string Return a string containing the last error for the current session
     */     
    public $error ;
    /**
     * @var boolean as a flag to Whether or not to post data to a form
     */       
    public $postMethod = FALSE ;
    /**
     * @var array of the The full data to post in a HTTP "POST" operation
     */    
    public $formData = array();  # an array for form filed to post
    /**
     * @var boolean as a flag to Whether or not to handle form with get method
     */     
    public $getMethod = FALSE ;
    /**
     *
     * @var boolean as a flag to Whether or not use Basic Authentication
     */
    public $basicAuthentication = FALSE ;
    /**
     * @var string the user name and password for the basic Authentication
     */
    public $basicUserPass ;
    /**
     *
     * @var boolean as a flag to Whether or not use custom header
     */
    public $httpHeader = FALSE ;
    /**
     *
     * @var array of HTTP header fields to set 
     */
    public $headerArray ;
    /**
     * Construct
     * to set some defaults values
     */    
    public function __construct()
    {
        /**
        * A file name and path to save all internal cookies
        */
        $this->cookieFile = getcwd()."\\cookie.txt";
        /**
        * A file name and path to save all verbose data
        */        
        $this->verboseFile = fopen(getcwd()."\\verbose.txt", 'w');
    }
    /**
     * close the curl session
     */
    public function __destruct() {
        if (is_resource($this->ch)) {
            curl_close($this->ch);
        }
    }
    /**
    * @access public
    * @param  $agent the agent name to use instead of the default
    */     
    public function setAgent($agent) {
        $this->agent = $agent;
    }
    /**
    * @access public
    * @param  $timeOut the time out to use instead of the default
    */     
    public function setTimeOut($timeOut) {
        $this->timeOut = $timeOut;
    }
    /**
    * @access public
    * @param  $cookieFile just pass the path relative to your script folder
    */ 
    public function setCookieFile($cookieFile) {
        $this->cookieFile = getcwd(). '\\' .$cookieFile;
    }
    /**
    * @access public
    * @param  $referer the referer to use instead of the default
    */ 
    public function setReferer($referer) {
        $this->referer = $referer;
    }
    /**
    * Downloads an ASCII file without the http header 
    * @access public
    * @param  $url The target file to download
    */    
     public function getFile ($url) {
        $this->target = $url;
        $this->mainCurl();
    }
    /**
    * to include response header with the body
    * @access public
    */       
    public function includeHeader () {
        $this->withHeader = TRUE ;
    }
    /**
    * To enable verbose to write more details about the trnasfer
    * @access public
    */ 
    public function enableVerbose() {
        $this->verbose = TRUE ;
    }
    /**
    * To change the location of your Verbose file
    * @access public
     * @example logs/verbose.txt path is relative
    */     
    public function setVerboseFile($verboseFile) {
        $this->verboseFile = fopen(getcwd()."\\$verboseFile", 'w');
    }
    /**
    * To change the max redirections 
    * @access public
    * @param int $max limit of redirections
    */  
    public function setMaxRedirections ($max) {
        $this->maxRedirs = $max;
    }
    /**
     * To enable the return of the response header
     * @access public
     * @param string $url the target file
     */
    public function getHeader ($url) {
        $this->noBody = TRUE ;
        $this->withHeader = TRUE ;
        $this->target = $url;
        $this->mainCurl();
    }
    /**
     * Submits a form with the POST method
     * @access public
     * @param string $url the target file
     * @param array $data_array An array that defines the form variables see read me for more details
     */
    public function postForm($url, $data_array) {
        $this->target = $url ;
        $this->postMethod = TRUE ;
        if(isset($data_array)) {
            # http_build_query() Converts data array into a query string (ie player=abdul&sport=baseball)
            $this->formData = http_build_query($data_array) ;
        }
        # run the main method 
        $this->mainCurl();
    }
    /**
     * Submits a form with the GET method
     * @access public
     * @param type $url
     * @param array $data_array An array that defines the form variables see read me for more details
     */
    public function getForm($url, $data_array) {
        
        $this->getMethod = TRUE ;
            if(isset($data_array)) {
            # http_build_query() Converts data array into a query string (ie player=abdul&sport=baseball)
            $query_string = http_build_query($data_array) ;
            $this->target = $url . "?" . $query_string;
            }
        # run the main method 
        $this->mainCurl();
    }
    /**
     * it enables the basis authentication
     * @access public
     * @param string $username the user name for the basic auth
     * @param string $password the password for the basic auth
     */
    public function basicAuth($username, $password) {
        $this->basicAuthentication = TRUE ;
        $this->basicUserPass = $username . ':' . $password;
    }
    /**
     * 
     * @param array $header_array An array of HTTP header fields to set
     * @example $header_array[] = "Accept-Encoding: compress, gzip";
     */
    public function addHeader($header_array) {
        $this->httpHeader = TRUE ;
        $this->headerArray = $header_array ;
    }
    /**
     * This is the main method to run the php/curl
     */
    public function mainCurl() {

        
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, $this->target);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, TRUE);
        /**
         * whether or not to include the body. if not the request method is then set to HEAD
         */
        curl_setopt($this->ch, CURLOPT_NOBODY, $this->noBody);       
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, 1);
        /**
         * Cookie management.
         */
        curl_setopt($this->ch, CURLOPT_COOKIEJAR, $this->cookieFile);
	curl_setopt($this->ch, CURLOPT_COOKIEFILE, $this->cookieFile);
        curl_setopt($this->ch, CURLOPT_USERAGENT, $this->agent); 
        /**
         * No certificate
         */
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        /**
         * Limit redirections as default is set to 4
         */
	curl_setopt($this->ch, CURLOPT_MAXREDIRS, $this->maxRedirs);           
        /**
         * Timeout as default is set to 25 seconds use CURLOPT_CONNECTTIMEOUT instead of CURLOPT_TIMEOUT
         */
        curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, $this->timeOut); 
        /**
         * Referer value as default is set https://www.google.com
         */
        curl_setopt($this->ch, CURLOPT_REFERER, $this->referer);      
        /**
         * to include the respose header
         */
        if ($this->withHeader) {
            curl_setopt($this->ch, CURLOPT_HEADER, TRUE);                
        }
        # 
        /**
         * more details about the trnasfer
         */
        if ($this->verbose) {
            curl_setopt($this->ch, CURLOPT_VERBOSE, true);
            curl_setopt($this->ch, CURLOPT_STDERR, $this->verboseFile); 
        }
        /**
         * for post form
         */
        if($this->postMethod ) {
        curl_setopt ($this->ch, CURLOPT_POST, TRUE); 
        curl_setopt ($this->ch, CURLOPT_POSTFIELDS, $this->formData);
        }
        /**
         * for basic Authentication
         */
        if ($this->basicAuthentication) {
            curl_setopt($this->ch, CURLOPT_USERPWD, $this->basicUserPass);
            /**
             * TRUE to keep sending the username and password when following locations even when the hostname has changed
             */
            curl_setopt($this->ch, CURLOPT_UNRESTRICTED_AUTH, TRUE); // misspeeld
        }
        if ($this->httpHeader) {
            curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->headerArray);
        }
        ### what to return
        /**
        * return the file
        */
        $this->file = curl_exec($this->ch);
        /**
        * return information about the transfer
        */
        $this->infoArray = curl_getinfo($this->ch); 
        /**
         * return the last error
         */
        $this->error  = curl_error($this->ch);
        curl_close($this->ch);
    }
}




