<?php

namespace Maalls\Charset;

class Charset {
  
  private $html;
  private $contentTypeHeader = "";
  
  
  public function __construct($html, $contentTypeHeader = "", $logger = null) {
    
    $this->html = $html;
    $this->contentTypeHeader = $contentTypeHeader;
    $this->logger = $logger;
    
  }
  
  public function getCharset() {
    $charset = $this->getCharsetFromHeader();
    if(!$charset) {
      $charset = $this->getCharsetFromHtml();
    }
    return $charset;
  }
  
  private function getCharsetFromHeader() {
    
    if(preg_match('@[\w/+]+;\s*charset=(\S+)?@i', $this->contentTypeHeader, $matches)) {
      
      $charset = strtolower($matches[1]);
    }
    else {
      $charset = false;
    }
    return $charset;
  }
  
  private function getCharsetFromHtml() {
    
    if(preg_match('@<meta\s+http-equiv="Content-Type"\s+content="([\w/]+)(;\s*charset=[\s]*([^\s"]+))@is', $this->html, $matches)) {
      
      if(isset($matches[3])) $charset = strtolower($matches[3]);
      else {
        
        $charset = false;
        $this->logInfo("Invalid charset parsing.");
        mail("bug@lenz.jp", "Invalid charset parsing", $this->html);
        
      }
    }
    else {
      $charset = false;
    }
    return $charset;
  }
  
  private function logInfo($msg) {
    
    if($this->logger) $this->logger->info($msg);
    
  }
  
}