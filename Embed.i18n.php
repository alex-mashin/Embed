<?php
$messages = [];
// Import service settings from $wgEmbedServiceList:
$service_messages = [];
global $wgEmbedServiceList;
foreach ($wgEmbedServiceList as $service => $params) {
    foreach ($params as $param => $value) {
	    $service_messages ["embed-$service-$param"] = $value;
	}
}

$messages ['en'] = array_merge ($service_messages, [
    'embed-missing-params'          => 'Embed is missing a required parameter.'
  , 'embed-bad-params'              => 'Embed received a bad parameter.'
  , 'embed-unparsable-param-string' => 'Embed received the unparsable parameter string "<code>$1</code>".'
  , 'embed-unrecognized-service'    => 'Embed does not recognize the video service "<code>$1</code>".'
  , 'embed-bad-param'               => 'Embed received the bad parametre $1 "$2" for the service "$3".'
  , 'embed-illegal-width'           => 'Embed received the illegal width parameter "$1".'
  , 'embed-illegal-height'          => 'Embed received the illegal height parameter "$1".'
]);

$messages ['ru'] = array_merge ($service_messages, [
    'embed-missing-params'          => 'Embed не нашло необходимого параметра.'
  , 'embed-bad-params'              => 'Embed получило неверный параметр.'
  , 'embed-unparsable-param-string' => 'Embed не может разобрать параметр. "<code>$1</code>".'
  , 'embed-unrecognized-service'    => 'Embed не знает указанной видеослужбы. "<code>$1</code>".'
  , 'embed-bad-param'               => 'Embed получило неверный параметр $1 «$2» для службы «$3».'
  , 'embed-illegal-width'           => 'Embed получило неверную ширину "$1".'
  , 'embed-illegal-height'          => 'Embed получило неверную высоту "$1".'
]);
?>
