<?php
$fb = \frontend\controllers\FbController::fbinit();
$helper = $fb->getRedirectLoginHelper();
$permissions = ['email', 'public_profile','user_likes', 'user_events', 'user_photos', 'publish_actions']; // optional
$loginUrl = $helper->getLoginUrl('http://'.$_SERVER['SERVER_NAME'].'/fb/timeline-post', $permissions);

echo '<a href="' . htmlspecialchars($loginUrl) . '" title="Post to timeline">Post to timeline</a>';

