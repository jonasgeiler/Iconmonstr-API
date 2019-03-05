<?php

require_once('models/IconRequest.php');

function getIconRequests() {
	$iconRequests = IconRequest::crawlIconRequests();

	if (!$iconRequests)
		return json(['success' => false, 'error' => 'Couldn\'t retrieve icon requests']);

	return json(['success' => true, 'icon_requests' => $iconRequests]);
}

function getIconRequest($slug) {
	$iconRequest = IconRequest::crawlIconRequest($slug);

	if (!$iconRequest)
		return json(['success' => false, 'error' => 'Couldn\'t retrieve icon request ' . $slug]);

	return json(['success' => true, 'icon_request' => $iconRequest]);
}