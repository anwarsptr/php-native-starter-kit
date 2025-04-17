@echo off
set /p PORT=Enter the port (default 8080): 
if "%PORT%"=="" set PORT=8080
php -S localhost:%PORT% -t .
pause
