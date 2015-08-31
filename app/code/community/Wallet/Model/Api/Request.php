<?php


/**
 * The following properties need to be set using Varien_Objects
 * magic setter methods
 */
class Wallet_Model_Api_Request extends Varien_Object 
{

    private $_params = array();

    private $_sendFlag = false;

    public function addParam($key, $value) 
    {
        $this->_params[$key] = $value;
        return $this;
    }

    /**
     * Method to send the request to the wallet api
     * identified by the endpoint (url)
     */
    public function send() 
    {
        $config = $this->getWalletConfig();
        $this->_params = array_merge($this->_params, array(
            'mid' => $config['merchant_id']
          
        ));

        $fields = $this->_params;
        
        $query_string = http_build_query($fields);        
        
        //open connection
        $ch = curl_init();
        
        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $this->getUrl());
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        
        //execute post
        $result = curl_exec($ch);

        // incase of an error, log it
        if(curl_errno($ch)) {
            Mage::log('Curl error: ' . curl_error($ch));
            Mage::throwException('Request not completed because of an error connecting to wallet server for transaction update. See exception logs');
        } else {
            //close connection
            curl_close($ch);
            $this->_processResponse($result);
            $this->_sentFlag = true;
        }
    }

    protected function _processResponse($response) 
    {
        $this->setResponse($response);
        $responseObj = simplexml_load_string($response);
        $this->setResponseObj($responseObj);
        $this->setResponseCode((int)$responseObj->response_element->responsecode);
        $this->setResponseDescription((string) $responseObj->response_element->description);
    }

    public function isResponseCode($code) 
    {
        return $this->getResponseCode() == $code;
    }
}
