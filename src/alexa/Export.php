<?php

namespace BurpSuite\Alexa;

use BurpSuite\Alexa\ExportException;
use BurpSuite\Alexa\Kernel;

class Export extends Command
{
    const VERSION = '0.1.0';
    const VERSION_DATE = '2018-02-10 15:05:53';
    public $convert_content = null;
    public $convert_buffer = [];
    public $convert_result = [];

    public function __toString()
    {
        return (string) $this->convert_result;
    }

    public function __construct()
    {
        $this->detectCommandMode();
        $this->parseCommandArray();
        $this->matchCommand();
    }

    public function matchCommand()
    {
        if (self::$cli_mode && $this->command !== null) {
            switch (self::$cli_mode) {
                case array_key_exists('-v', $this->command):
                    $this->setConsoleVersion();
                    break;
                case array_key_exists('-help', $this->command):
                    $this->setConsoleHelp();
                    break;
                case isset($this->command['id']) && isset($this->command['key'])
                    && isset($this->command['state']) && isset($this->command['start'])
                        && isset($this->command['end']) && isset($this->command['export']):
                    Report::setReport('alexa start...');
                    Report::setReport("accessKeyId:[{$this->command['id']}] ".
                        "secretAccessKey:[{$this->command['key']}]");
                    Report::setReport("start:[{$this->command['start']}] ".
                        "end:[{$this->command['end']}] ".
                        "countryCode:[{$this->command['state']}] ".
                        "exportDirectory:[{$this->command['export']}]");
                    $this->getAlexaContent($this->command['id'],$this->command['key'],
                        $this->command['state'],$this->command['start'],$this->command['end'],
                        $this->command['export']);
                    break;
                default:
                    Report::setReport('command not found.');
            }
        }
    }

    public function setConsoleHelp()
    {
        Report::setReport('Usage >>');
        Report::setReport('alexa id {accessKeyId} key {secretAccessKey} '.
            'state {countryCode} start {0-99999} end {0-99999} export {export_path}');
    }

    public function setConsoleVersion()
    {
        Report::setReport('version:'.self::VERSION.'|date:'.self::VERSION_DATE);
    }

    public function outputContent($dir_path, $export_path, $export_content)
    {
        if (!isset($dir_path) || !isset($export_path) || !isset($export_content)) {
            new ExportException([
                'class'=>__CLASS__,
                'function'=>__FUNCTION__,
                'message'=>'export_path/export_content is null!',
                'status'=>500
            ]);
        }
        if (!file_exists($dir_path)) {
            mkdir($dir_path, 0777, true);
        }
        $output = fopen($export_path,'a');
        fwrite($output, $export_content);
        fclose($output);
    }

    public function getAlexaContent($accessKey, $secretKey, $state, $start, $end, $dir_path)
    {
        if (!isset($accessKey) || !isset($secretKey) || !isset($state)) {
            new ExportException([
                'class'=>__CLASS__,
                'function'=>__FUNCTION__,
                'message'=>'accessKey/secretKey/state is null!',
                'status'=>500
            ]);
        }
        for ($i=0; $i < (int)($end-$start)/100; $i++) {
            !isset(Kernel::$start_count) ?
                Kernel::$start_count = $start
                : Kernel::$start_count = Kernel::$end_count + 1;
            !isset(Kernel::$end_count) ?
                Kernel::$end_count = Kernel::$start_count + 100
                : Kernel::$end_count = Kernel::$end_count + 101;

            $alexa = new Kernel($accessKey, $secretKey, $state);
            $save_path = $dir_path.'alexa_'.Kernel::$start_count.'-'.Kernel::$end_count.'.xml';
            $this->outputContent($dir_path, $save_path, $alexa);
            Report::setReport("write save_path[{$save_path}] complete!");
        }
        Report::setReport('all complete.');
    }
}
