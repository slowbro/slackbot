<?php

class PagerDuty {

    private $apiKey;
    private $domain;
    protected $error=false;

    public function __construct($domain, $key, $requester_id){
        $this->domain = $domain;
        $this->apiKey = $key;
        $this->requester_id = $requester_id;
    }

    private function buildCurl($type, $url, $ln=false){
        $ch = curl_init("https://{$this->domain}.pagerduty.com/api/v1/$url");
        if($ln)
            $head = array('Content-type: application/json', "Authorization: Token token={$this->apiKey}", "Content-length: $ln");
        else
            $head = array('Content-type: application/json', "Authorization: Token token={$this->apiKey}");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        return $ch;
    }

    public function getError(){
        return $this->error;
    }

    private function setError($err){
        $this->error = $err;
    }

    private function parseResponse($ch){
        $ret = json_decode(curl_exec($ch), true);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        switch($code){
            case '200':
            case '201':
                return $ret;
                break;
            case '400':
                $this->setError($ret['error']['message']);
                break;
            case '500':
                $this->setError('PagerDuty returned 500 ISE!');
                break;
            default:
                $this->setError('Unknown PagerDuty response: '.$code);
        }
        return false;

    }

    public function get($url, $data=false){
        if($data)
            $url .= '?'.http_build_query($data);
        $ch = $this->buildCurl('GET', $url);
        return $this->parseResponse($ch);
    }

    public function put($url, $data=array()){
        $data = json_encode($data);
        $ln = strlen($data);
        $ch = $this->buildCurl('PUT', $url, $ln);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        return $this->parseResponse($ch);
    }

    public function post($url, $data=array()){
        $data = json_encode($data);
        $ln = strlen($data);
        $ch = $this->buildCurl('POST', $url, $ln);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        return $this->parseResponse($ch);
    }

    public function incident($id=false, $params=array()){
        if($id){
            $request = "incidents/$id";
            return $this->get($request);
        } else {
            $request = 'incidents';
            return $this->get($request, $params);
        }
    }

    public function incidentAcknowledge($id){
        return $this->put("incidents/$id/acknowledge", array('requester_id'=>$this->requester_id));
    }

    public function incidentResolve($id){
        return $this->put("incidents/$id/resolve", array('requester_id'=>$this->requester_id));
    }

    public function incidentReassign($id, $new_user){
        return $this->put("incidents/$id/reassign", array('requester_id'=>$this->requester_id, 'assigned_to_user'=>$new_user));
    }

    public function incidentNote($id, $note){
        return $this->post("incidents/$id/notes", array('requester_id'=>$this->requester_id, 'note'=>array('content'=>$note)));
    }

    public function user($id=false){
        $request = 'users';
        if($id)
            $request .= "/$id";
        return $this->get($request);
    }

}

