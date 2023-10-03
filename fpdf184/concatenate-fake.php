<?php
use setasign\Fpdi\Fpdi;

require_once('fpdf.php');
require_once('FPDI-2.3.6/src/autoload.php');

class ConcatPdf extends Fpdi
{
    public $files = array();

    public function setFiles($files)
    {
        $this->files = $files;
    }

    public function concat()
    {
        $path = '../PDF/';

        foreach($this->files AS $file) {
            $pageCount = $this->setSourceFile($path . $file);
            
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $pageId = $this->ImportPage($pageNo);
                $s = $this->getTemplatesize($pageId);
                $this->AddPage($s['orientation'], $s);
                $this->useImportedPage($pageId);
            }
        }
    }
}