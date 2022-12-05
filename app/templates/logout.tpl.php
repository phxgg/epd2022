<?php
if (!defined('ACCESS')) exit;
session_destroy();
header('Location: '.BASE_URL.'/?page=login');
