<?php
require_once "ws-security.php";
$msg = array();
if(!isset($_GET['cpf'])) {
	$msg["code"] = 400;
	$msg["msg"] = "Parâmetro 'cpf' não encontrado";	
} else {
	try {
		$options = array( 'location' => 'https://servicoshm.saude.gov.br/cnes/ProfissionalSaudeService/v1r0', 
						'encoding' => 'utf-8', 
						'soap_version' => SOAP_1_2,
						'connection_timeout' => 180,
						'trace'        => 1, 
						'exceptions'   => 1 );
		$client = new SoapClient('https://servicoshm.saude.gov.br/cnes/ProfissionalSaudeService/v1r0?wsdl', $options);   
		$client->__setSoapHeaders(soapClientWSSecurityHeader('CNES.PUBLICO', 'cnes#2015public'));
		$arguments= array( 'prof' => array(
								'FiltroPesquisaProfissionalSaude' => array(
									'CPF' => array(
										'numeroCPF'  => $_GET['cpf']
									)
								)
							)
						);
		$result = $client->__soapCall('consultarProfissionalSaude', $arguments);
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