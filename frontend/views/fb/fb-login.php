<?php
/**
 * Created by PhpStorm.
 * User: abrikos
 * Date: 04.05.16
 * Time: 10:52
 */


$helper = $fb->getRedirectLoginHelper();
$permissions = ['email', 'public_profile','user_about_me']; // optional
$loginUrl = $helper->getLoginUrl('http://www.abrikos.su/fb/callback', $permissions);

echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';

