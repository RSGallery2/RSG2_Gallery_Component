@ECHO OFF
REM <What it does>

CLS

SET CmdArgs=
ECHO python _emptyPy.py 

REM 
Call :AddNextArg -p "\pr004\entwickl\Schleif.nt"
                     
REM 
Call :AddNextArg -n "modules"
 
REM add command line 
REM Call :AddNextArg %*

ECHO.
ECHO ------------------------------------------------------------------------------
ECHO Start cmd:
ECHO.
ECHO python _emptyPy.py %CmdArgs% %* 
     python _emptyPy.py %CmdArgs% %* 

GOTO :EOF

REM ------------------------------------------
REM Adds given argument to the already known command arguments
:AddNextArg 
SET NextArg=%*
SET CmdArgs=%CmdArgs% %NextArg%
ECHO  '%NextArg%'
GOTO :EOF

