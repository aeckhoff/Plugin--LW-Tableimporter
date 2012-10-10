<?php

/**************************************************************************
*  Copyright notice
*
*  Copyright 2011-2012 Logic Works GmbH



*
*  Licensed under the Apache License, Version 2.0 (the "License");
*  you may not use this file except in compliance with the License.
*  You may obtain a copy of the License at
*
*  http://www.apache.org/licenses/LICENSE-2.0
*  
*  Unless required by applicable law or agreed to in writing, software
*  distributed under the License is distributed on an "AS IS" BASIS,
*  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
*  See the License for the specific language governing permissions and
*  limitations under the License.
*  
***************************************************************************/

class lw_tableimporter extends lw_plugin 
{
    function __construct() 
    {
        parent::__construct();
        $auth = lw_registry::getInstance()->getEntry("auth");
        if (!$auth->isGodmode()) {
            die("not allowed");
        }
        $this->transporter = new lw_db_transporter();
    }
    
    function buildPageOutput() 
    {
        if ($this->request->getInt("import") == 1) {
            
            $this->transporter->setDebug($this->request->getInt("debug"));
            
            $file = $this->request->getFileData('importfile');
            if ($this->request->getRaw("xml")) {
                $this->transporter->importXML($this->request->getRaw("xml"));
            } 
            elseif($file['tmp_name']) {
                $xml = lw_io::loadFile($file['tmp_name']);
                $this->transporter->importXML($xml);
            }
            else {
                $out = $this->showImportForm();
            }
        }
        else {
            $out = $this->showImportForm();
        }
        die($out);
    }
    
    function showImportForm() 
    {
        $out.= "<h1>Table Exporter</h1>";
        $out.= '<form action="'.lw_page::getInstance()->getUrl(array("import"=>"1")).'" method="post" enctype="multipart/form-data">';
        $out.= '    Datei&nbsp;&nbsp;&nbsp;<input type="file" name="importfile" /><br/>'.PHP_EOL;
        $out.= '    <br/>oder<br/>'.PHP_EOL;
        $out.= '    XML-Text<br>'.PHP_EOL;
        $out.= '    <textarea name="xml" cols="120" rows="25"></textarea><br/><br/>'.PHP_EOL;
        $out.= '    Debug: <input type="checkbox" name="debug" value="1" checked="checked"><br/><br/>';
        $out.= '    <input type="submit" value="import">';
        $out.= "</form>";
        die($out);
    }
}
