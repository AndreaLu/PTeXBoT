<?php
// Execute a system command with timeout control.
// $command      - System command.
// [opt]$timeout - Timeout in seconds (default 1 minute).
// [opt]$sleep   - Sleep cycle interval in milliseconds (default 2 ms).
//                 Sleep is used to wait for the command to finish.
// Return value:
// 0 Success.
// 1 Command error.
// 2 Timeout error.
function systemExecute($command, $timeout = 60, $sleep = 2) 
{ 
    // Execute $command in a new process
    $pid = PsExec($command); 
	
    if( $pid === false ) 
        return 1; 

    $cur = 0; 
    // Second, loop for $timeout seconds checking if process is running 
    while( $cur < $timeout )
	{ 
        sleep($sleep); 
        $cur += $sleep;
        // If process is no longer running, return true; 
        if( !PsExists($pid) ) 
            return 0; // Process must have exited, success! 
    } 

    // If process is still running after timeout, kill the process and return false 
    PsKill($pid); 
    return 2; 
} 
function PsExec($commandJob) { 

    $command = $commandJob.' > /dev/null 2>&1 & echo $!'; 
    exec($command ,$op); 
    $pid = (int)$op[0]; 

    if($pid!="") return $pid; 

    return false; 
} 

function PsExists($pid) { 

    exec("ps ax | grep $pid 2>&1", $output); 

    while( list(,$row) = each($output) ) { 

            $row_array = explode(" ", $row); 
            $check_pid = $row_array[0]; 

            if($pid == $check_pid) { 
                    return true; 
            } 

    } 

    return false; 
} 

function PsKill($pid) { 
    exec("kill -9 $pid", $output); 
}
?>
