<?php

namespace BurpSuite\Alexa;

use Exception;

class ExportException extends Exception
{
    /**
     * ConvertException constructor.
     * ConvertException thrown when client returns error.
     *
     * @param array $data
     */
    public function __construct($data = [])
    {
        try {
            throw new Exception();
        } catch (Exception $e) {
            getenv('BURPSUITE_DEBUG') ?
                error_log("[DEBUG] [Exception] Class:[{$data['class']}] Function:[{$data['function']}] ".
                    "Message:[{$data['message']}] Status:[{$data['status']}]") : false;
            if (Command::$cli_mode) {
                exit(Color::YELLOW.
                    '[AlexaTopSites CLI] [ExportException] >> '.
                    '[errorCode:'.$data['status'].'] | '.
                    '[message:'.$data['message'].']'."\r\n".
                    Color::BG_DEFAULT
                );
            }
        }
    }
}
