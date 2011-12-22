<?php
/**
 * Abstract class representing a WebPurify command
 * @author Mark Stralka, Kazaam Interactive
 * @see http://www.webpurify.com/profanity/filter/documentation.php
 */
abstract class AbstractWebPurifyCommand
{
    private $restEndpointUrl = 'http://api1.webpurify.com/services/rest/';
    protected $apiKey = '';
    protected $parameters = array(
        'lang' => 'en'
    );
    protected $response = null;

    public function __construct($apiKey, $language = 'en')
    {
        if (trim($apiKey) == '')
        {
            die('WebPurify API key not provided.');
        }
        $this->parameters['api_key'] = $apiKey;
        $this->parameters['lang'] = $language;
    }

    protected final function getRestEndpointUrl()
    {
        return $this->restEndpointUrl;
    }

    protected final function getParameters()
    {
        return $this->parameters;
    }

    public final function buildUrl()
    {
        $ret = $this->getRestEndpointUrl();
        $ret .= '?';
        $ret .= http_build_query($this->getParameters());
        return $ret;
    }

    /**
     * Perform the WebPurify query and return a SimpleXMLElement object as the response.
     * The following must be set in php.ini
     * allow_url_fopen = On
     * @return SimpleXMLElement
     */
    public final function execute()
    {
        if (is_null($this->response))
        {
            try
            {
                $browser = new sfWebBrowser();
                $browser = new sfWebBrowser(array(), 'sfCurlAdapter', array('Timeout'=>10));
                //$this->response = simplexml_load_file($this->buildUrl(),'SimpleXMLElement');
            } catch (Exception $e)
            {
                sfContext::getInstance()->getLogger()->err('Error connecting to profanity filter: ' . $e);
                $this->response = false;
            }
        }
        return $this->response;
    }

    /**
     * Perform the query and return the response
     */
    public abstract function getResponse();
}