@ECHO OFF
REM Opens rsgallery backend  web site to check if they work

CLS

SET CmdArgs=
ECHO openAllForms.backend.bat $* 

REM --- Select which Jommla installation to use ---------------------

REM SET J_URL_PART=Joomla3x
REM SET J_URL_PART=Joomla3xMyGalleries
REM SET J_URL_PART=Joomla3xRelease
SET J_URL_PART=Joomla3xNextRelease
REM SET J_URL_PART=Joomla3xNext

if %1A NEQ A (
    SET J_URL_PART=%1
	SHIFT
)

SET J_URL=http://127.0.0.1/%J_URL_PART%/administrator/index.php
ECHO J_URL: %J_URL% 

Call :AddNextArg -j J_URL 


REM --- Select browser --------------------------------------------------
SET J_BROWSER=chrome
if %1A NEQ A (
    SET J_BROWSER=%1
 	SHIFT
)
ECHO J_BROWSER: %J_BROWSER% Call :AddNextArg .

Call :AddNextArg -b J_BROWSER

REM Call :AddNextArg .
REM Call :AddNextArg .

REM Further command line parameters 
Call :AddNextArg %*

			
ECHO.
ECHO ------------------------------------------------------------------------------
ECHO Start cmd:
ECHO.
ECHO python openAllForms.backend.py %CmdArgs%* 
REM     "c:\Program Files (x86)\Python27\python.exe" openAllForms.backend.py %CmdArgs% 
	python openAllForms.backend.py %CmdArgs% 
	 
goto :EOF

REM ------------------------------------------
REM Adds given argument to the already known command arguments
:AddNextArg 
Set NextArg=%*
Set CmdArgs=%CmdArgs% %NextArg%
ECHO  '%NextArg%'
GOTO :EOF

