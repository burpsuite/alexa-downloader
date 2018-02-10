<?php

namespace BurpSuite\Alexa;

class Command
{
    public static $cli_mode = false;
    public static $script_name = null;
    public $command_argv = [];
    public $command_key = [];
    public $command_value = [];
    public $command = [];

    public function detectCommandMode()
    {
        switch (true) {
            case PHP_SAPI === 'cli':
                self::$cli_mode = true;
                $this->command_argv = $_SERVER['argv'];
                self::$script_name = $this->command_argv[0];
                array_shift($this->command_argv);
                break;
            case PHP_SAPI !== 'cli':
                self::$cli_mode = false;
                foreach ($_GET as $key => $value) {
                    $this->command_argv[] = $key;
                    $this->command_argv[] = $value;
                }
                break;
            default:

        }
    }

    public function parseCommandArray()
    {
        for ($i = 0; $i < count($this->command_argv); $i++) {
            isset($this->command_argv[$i + 1 * $i]) ?
                $this->command_key[] = $this->command_argv[$i + 1 * $i] : false;
            isset($this->command_argv[$i + 1 * $i + 1]) ?
                $this->command_value[] = $this->command_argv[$i + 1 * $i + 1] : false;
        }
        $this->command_key = array_filter($this->command_key, 'strlen');
        $this->command_value = array_filter($this->command_value, 'strlen');
        foreach ($this->command_key as $key => $value) {
            isset($this->command_value[$key]) ?
                $this->command[$this->command_key[$key]] = $this->command_value[$key]
                : $this->command[$this->command_key[$key]] = null;
        }
    }

    public static function getCommandMode()
    {
        return self::$cli_mode;
    }

    public static function getScriptName()
    {
        return self::$script_name;
    }
}
