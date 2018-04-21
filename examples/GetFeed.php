<?
	require_once('../Loader.php');
	
	$YZ = new YandexZen();
	print_r($YZ->getFeed('lifehacker'));