<?php
require_once "ws-security.php";
$msg = array();
if(!isset($_GET['cnes'])) {
	$msg["code"] = 400;
	$msg["msg"] = "Parâmetro 'cnes' não encontrado";	
} else {
	try {
		$options = array( 'location' => 'https://servicos.saude.gov.br/cnes/LeitoService/v1r0', 
						  'encoding' => 'utf-8', 
						  'soap_version' => SOAP_1_2,
						  'connection_timeout' => 0,
						  'trace'        => 1, 
						  'exceptions'   => 1 );
		$client = new SoapClient('https://servicos.saude.gov.br/cnes/LeitoService/v1r0?wsdl', $options);   
		$client->__setSoapHeaders(soapClientWSSecurityHeader('CNES.PUBLICO', 'cnes#2015public'));
		$arguments= array( 'leit' => array(
											'CodigoCNES' => array( 'codigo' => $_GET['cnes'] )
							)
						  );
		$result = $client->__soapCall('consultarLeitosCNES', $arguments);
		$msg["code"] = 200;
		$msg["msg"] = "";
		$msg["data"] = $result;
	} catch(Exception $e) {
		$msg["code"] = 503;
		$msg["msg"] = trim($e->faultstring);
	}
}
echo json_encode($msg);
?>