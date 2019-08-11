@ECHO OFF
REM Opens backend administration on given local web site to enter credentials

CLS

SET CmdArgs=
ECHO openBackendAdmin.bat $* 


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

REM --- Select browser --------------------------------------------------
SET J_BROWSER=chrome
if %1A NEQ A (
    SET J_BROWSER=%1
	SHIFT
)
ECHO J_BROWSER: %J_BROWSER% 

REM start chrome %J_URL%
REM start iexplore %J_URL%
REM start firefox %J_URL%
ECHO start %J_BROWSER% %J_URL%
ECHO Please Wait
start %J_BROWSER% %J_URL%
ECHO.
ECHO done: Please check Browser
ECHO.

