<?php
foreach ($this->apiServices as $serviceName => $serviceWSDL) {
    $this->client = new SoapClient($serviceWSDL, $this->soapOptions);
    $this->apiMethods = array_merge($this->apiMethods, [$serviceName => array_map(
				[$this, 'extract_client'], $this->client->__getFunctions()
			)]);
}
