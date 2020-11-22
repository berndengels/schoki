<?php

namespace App\Libs;

use Exception;
use Illuminate\Support\Str;
use Spatie\DbDumper\Databases\MySql;
use Symfony\Component\Process\Process;

class MySqlDumper extends MySql
{
    public function dumpToFile(string $dumpFile)
    {
        $path = substr($dumpFile, 0, strrpos($dumpFile,DIRECTORY_SEPARATOR));
        $this->guardAgainstIncompleteCredentials();
        try {
//            $tempFileHandle = tmpfile();
            $tempFile = "$path/".time();
            $tempFileHandle =  fopen($tempFile, 'w+');
        } catch(Exception $e) {
            die($e->getMessage());
        }
        fwrite($tempFileHandle, $this->getContentsOfCredentialsFile());
        $temporaryCredentialsFile = stream_get_meta_data($tempFileHandle)['uri'];
        $command = $this->getDumpCommand($dumpFile, $temporaryCredentialsFile);
        $process = Process::fromShellCommandline($command, null, null, null, $this->timeout);
        $process->run();
        $this->checkIfDumpWasSuccessFul($process, $dumpFile);
        fclose($tempFileHandle);
        chmod($tempFile, 0666);
        unlink($tempFile);
        chmod($dumpFile, 0666);
    }

}
