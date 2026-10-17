<?php

namespace App\Controllers;

use App\Core\Controller;
use DOMDocument;
use XSLTProcessor;

class XmlController extends Controller {
    private string $xmlPath;
    private string $dtdPath;
    private string $xslPath;

    public function __construct() {
        $this->xmlPath = dirname(__DIR__, 2) . '/public/xml/services.xml';
        $this->dtdPath = dirname(__DIR__, 2) . '/public/xml/services.dtd';
        $this->xslPath = dirname(__DIR__, 2) . '/public/xml/services.xsl';
    }

    public function view(): void {
        $isValid = false;
        $validationMsg = '';
        $xmlContent = '';
        $dtdContent = '';
        $xslContent = '';
        $transformedHtml = '';

        if (file_exists($this->xmlPath)) {
            $xmlContent = file_get_contents($this->xmlPath);
            $dom = new DOMDocument();
            // Enable error handling
            libxml_use_internal_errors(true);
            $dom->load($this->xmlPath);
            $isValid = $dom->validate();
            if (!$isValid) {
                $errors = libxml_get_errors();
                foreach ($errors as $e) {
                    $validationMsg .= "Line {$e->line}: {$e->message} ";
                }
                libxml_clear_errors();
            } else {
                $validationMsg = "Document Type Definition (DTD) validation passed with zero errors.";
            }

            // Attempt XSLT Transformation if extension is loaded
            if (class_exists('XSLTProcessor') && file_exists($this->xslPath)) {
                $xslDom = new DOMDocument();
                $xslDom->load($this->xslPath);
                $proc = new XSLTProcessor();
                $proc->importStylesheet($xslDom);
                $transformedHtml = $proc->transformToXml($dom);
            }
        }

        if (file_exists($this->dtdPath)) {
            $dtdContent = file_get_contents($this->dtdPath);
        }

        if (file_exists($this->xslPath)) {
            $xslContent = file_get_contents($this->xslPath);
        }

        $this->render('xml/view', [
            'pageTitle' => 'XML, DTD & XSL Catalog Explorer — HomeFix',
            'isValid' => $isValid,
            'validationMsg' => $validationMsg,
            'xmlContent' => $xmlContent,
            'dtdContent' => $dtdContent,
            'xslContent' => $xslContent,
            'transformedHtml' => $transformedHtml,
            'rawXmlUrl' => 'public/xml/services.xml',
            'dtdUrl' => 'public/xml/services.dtd',
            'xslUrl' => 'public/xml/services.xsl'
        ]);
    }

    public function raw(): void {
        if (!file_exists($this->xmlPath)) {
            http_response_code(404);
            die("services.xml not found");
        }
        header('Content-Type: application/xml; charset=utf-8');
        echo file_get_contents($this->xmlPath);
        exit;
    }

    public function validateDtd(): void {
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $loaded = $dom->load($this->xmlPath);
        $valid = $loaded ? $dom->validate() : false;
        $errors = [];

        if (!$valid) {
            foreach (libxml_get_errors() as $err) {
                $errors[] = "Line {$err->line}: " . trim($err->message);
            }
            libxml_clear_errors();
        }

        $this->json([
            'valid' => $valid,
            'message' => $valid ? 'XML perfectly conforms to services.dtd' : 'Validation issues detected',
            'errors' => $errors
        ]);
    }
}
