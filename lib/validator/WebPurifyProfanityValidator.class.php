<?php

/**
 * WebPurifyProfanityValidator validates that a string does not
 * contain profanity.
 *
 * @author Mark Stralka, Kazaam Interactive
 * 
 */
class WebPurifyProfanityValidator extends sfValidatorBase
{
    /**
     * Configures the current validator.
     *
     * Available options:
     *
     *  * api_key: Your WebPurify API key.  The default value will be pulled from app.yml
     *
     * Available error codes:
     *
     * * profanity
     *
     * @param array $options   An array of options
     * @param array $messages  An array of error messages
     */
    protected function configure($options = array(), $messages = array())
    {
        $this->addMessage('profanity', 'Profanity is not allowed.');

        $this->addOption('api_key', sfConfig::get('app_webpurify_api_key'));
    }

    /**
     * @see sfValidatorBase
     */
    protected function doClean($value)
    {
        $clean = (string) $value;
        
        $command = new WebPurifyLiveCheckCommand($this->getOption('api_key'), $clean);
        if ($command->isClean() === false)
        {
            throw new sfValidatorError($this, 'profanity');
        }

        return $clean;
    }
}