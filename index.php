<?
ini_set("display_errors", 0); 

include_once( './includes/application.php' );

Application::run();

echo Application::getContent();
