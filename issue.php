<?php
/**
 * PHP 5.3 php file to use the OAuthApi class
 */

//Github Command Line Command: php issue.php username password "https://github.com/rashmigupta15/test" "Issue title" "Issue Description"
//Bitbucket Command Line Command: php issue.php username password "https://bitbucket.org/rashmigupta15/test" "Issue title" "Issue Description"


//disable error messages
ini_set('display_errors', 0);

//include OAuthApi class to Handles github and bitbucket Api specific request
require_once('lib/OAuthApi.php');

//create object of createIssue
$createIssue = new createIssue($argv);

//Send request to create repository issue
$createIssue->sendRequest();


/**
 * PHP 5.3 create Issue class to use oAuth Api Library methods for the github and bitbucket
 * Send request to github|bitbucket to create repository issue based on passed arguments.
 * @link Github http://developer.github.com/v3/issues/#create-an-issue
 * @link Bitbucket https://confluence.atlassian.com/display/BITBUCKET/issues+Resource#issuesResource-POSTanewissue
 */
class createIssue {

    /**
     * Api username
     * @var string
     */
    private $username = "";

    /**
     * Api password
     * @var string
     */
    private $password = "";

    /**
     * Api repository url
     * @var string
     */
    private $repo_url = "";

    /**
     * Repository issue title
     * @var string
     */
    private $title = "";

    /**
     * Repository issue description
     * @var string
     */
    private $desc = "";

    /**
     * Instance of OAuthApi
     * @var string
     */
    private $OAuthApi = null;

    /**
     * Default constructor, paramters takes 
     * @param       string     $api_url     The Api url that dictates which API and event to use
     * @param       string     $username    The API username
     * @param       string     $password    The API password
     */

    /**
     * Default constructor, paramters takes an array of options
     * @param		array		$argv		Passed-in options to assign the required params
     */
    public function __construct(array $argv = array()) {
        //verify should be only access by command line
        if (PHP_SAPI !== 'cli') {
            die( PHP_EOL .'This file can be only run by command line'. PHP_EOL );
        }

        //check register_argc_argv is enable
        if(count($argv) == 0) {
            die( PHP_EOL .'Enable register_argc_argv in php.ini'. PHP_EOL );
        }

        // Assign passed arguments into variables
        list($filename, $this->username, $this->password, $this->repo_url, $this->title, $this->desc) = $argv;

        if($this->OAuthApi == null) {
            //create object of OAuthApi
            if(class_exists('OAuthApi')) {
                $this->oauthApi = new OAuthApi($this->repo_url, $this->username, $this->password);
            } else {
                die( PHP_EOL .'OAuthApi class not exists'. PHP_EOL );
            }
        }
    }

    /**
     * Validate required parameters
     * Send a request for github|bitbucket Api's Library
     *
     * @Show error|success message based on returned web response
     */
    public function sendRequest() {
        if($this->validateInput()) {
            //Send request to create repository issue on github|bitbucket
            $response = $this->oauthApi->post(array('title' => $this->title, 'desc' => $this->desc));

            //show message based on return response from Api's
            if(is_object($response) && isset($response->title)) {
                echo PHP_EOL . ucwords($this->oauthApi->getOption('api_type')) .' Repository issue posted successfully'. PHP_EOL;
            } else {    
                echo PHP_EOL . ucwords($this->oauthApi->getOption('api_type')) .' Repository issue not posted successfully'. PHP_EOL;
            }
        }
    }

    /**
     * validate api required parameters
     *
     * @access protected
     * @return void
     */
    protected function validateInput() {
        if($this->username == "") {
            echo PHP_EOL .'Username Required'. PHP_EOL;
            return false;
        } else if($this->password == "") {
            echo PHP_EOL .'Password Required'. PHP_EOL;
            return false;
        } else if($this->repo_url == "") {
            echo PHP_EOL .'Repository url Required'. PHP_EOL;
            return false;
        } else if($this->title == "") {
            echo PHP_EOL .'Title Required'. PHP_EOL;
            return false;
        }
        return true;
    }
}
