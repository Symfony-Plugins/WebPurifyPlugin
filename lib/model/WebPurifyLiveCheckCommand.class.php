<?php
/**
 * Implementation fo WP's LiveCheck method.
 * Basically if profanity is found it returns 1.
 * If the text is clean 0 (zero) is returned.
 * @author Mark Stralka, Kazaam Interactive
 */
class WebPurifyLiveCheckCommand extends AbstractWebPurifyCommand
{
    public function __construct($apiKey, $text, $language = 'en')
    {
        parent::__construct($apiKey, $language);
        $this->parameters['method'] = 'webpurify.live.check';
        $this->parameters['text'] = $text;
    }

    /**
     * if profanity is found it returns 1. If the text is clean 0 (zero) is returned.
     */
    public function getResponse()
    {
        $response = $this->execute();
        if ($response)
        {
            return $response->found;
        }
        return 0;
    }

    /**
     * Return TRUE if the text is clean, false if it has profanity
     * @return boolean
     */
    public function isClean()
    {
        $response = $this->getResponse();
        if ($response != 0)
        {
            return false;
        }
        return true;
    }
}