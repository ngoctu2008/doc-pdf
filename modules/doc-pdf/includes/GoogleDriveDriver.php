<?php

namespace NukeViet\Module\DocPdf;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;

class GoogleDriveDriver
{
    private $client;
    private $service;
    private $tempDir;

    public function __construct($jsonAuthContent, $tempDir)
    {
        $this->tempDir = $tempDir;

        $this->client = new Client();
        $this->client->setApplicationName('NukeViet PDF Converter');
        $this->client->setScopes(Drive::DRIVE_FILE);

        $authConfig = json_decode($jsonAuthContent, true);
        if ($authConfig) {
             $this->client->setAuthConfig($authConfig);
        } else {
             throw new \Exception("Invalid Google JSON Config");
        }

        $this->service = new Drive($this->client);
    }

    public function convertToPdf($filePath, $mimeType = 'application/vnd.google-apps.document')
    {
        // Upload file as Google Doc
        $fileMetadata = new DriveFile([
            'name' => basename($filePath),
            'mimeType' => $mimeType
        ]);

        $content = file_get_contents($filePath);
        $file = $this->service->files->create($fileMetadata, [
            'data' => $content,
            'mimeType' => mime_content_type($filePath),
            'uploadType' => 'multipart',
            'fields' => 'id'
        ]);

        $fileId = $file->id;

        // Export as PDF
        try {
            $response = $this->service->files->export($fileId, 'application/pdf', array(
                'alt' => 'media'
            ));

            $outContent = '';
            while (!$response->getBody()->eof()) {
                $outContent .= $response->getBody()->read(1024);
            }

            $outputFile = $this->tempDir . '/' . uniqid() . '.pdf';
            file_put_contents($outputFile, $outContent);

            return $outputFile;
        } finally {
            // Cleanup Drive file
            try {
                $this->service->files->delete($fileId);
            } catch (\Exception $e) {
                // Log error
            }
        }
    }

    public function convertToWord($filePath)
    {
        // Upload PDF as Google Doc (OCR/Convert)
        $fileMetadata = new DriveFile([
            'name' => basename($filePath),
            'mimeType' => 'application/vnd.google-apps.document'
        ]);

        $content = file_get_contents($filePath);
        $file = $this->service->files->create($fileMetadata, [
            'data' => $content,
            'mimeType' => 'application/pdf',
            'uploadType' => 'multipart',
            'fields' => 'id'
        ]);

        $fileId = $file->id;

        // Export as DOCX
        try {
            $response = $this->service->files->export($fileId, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', array(
                'alt' => 'media'
            ));

            $outContent = '';
            while (!$response->getBody()->eof()) {
                $outContent .= $response->getBody()->read(1024);
            }

            $outputFile = $this->tempDir . '/' . uniqid() . '.docx';
            file_put_contents($outputFile, $outContent);

            return $outputFile;
        } finally {
             // Cleanup Drive file
             try {
                $this->service->files->delete($fileId);
            } catch (\Exception $e) {
                // Log error
            }
        }
    }
}
