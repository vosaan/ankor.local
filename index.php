<?
ini_set("display_errors", 1); 

include_once( './includes/application.php' );

Application::run();

echo Application::getContent();
