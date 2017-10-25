<?php
  class StatusCode {
      
      private $code = -1;
      private $message = null;
      private $data = null;
      
      function __construct($code = null, $message = null) {
          $this->code = $code;
          $this->message = $message;
      }
      
      public function setCode($code) {
          $this->code = $code;
      }
      
      public function setMessage($message) {
          $this->message = $message;
      }
      
      public function setData($data) {
        $this->data = $data;
      }
      
      public function returnOutput() {
        $arr['status'] = $this->code;
        $arr['message'] = $this->message;
        if($this->data != null) {
            $arr['data'] = $this->data;
        }
        return json_encode($arr);
      }
      
      public function printOutput() {
        $arr['status'] = $this->code;
        $arr['message'] = $this->message;
        if($this->data != null) {
            $arr['data'] = $this->data;
        }
        echo json_encode($arr);
        exit();
      }
  }  
?>          