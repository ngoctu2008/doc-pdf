<?php

namespace NukeViet\Module\DocPdf;

use setasign\Fpdi\Fpdi;

class LocalPdfManipulator
{
    private $tempDir;

    public function __construct($tempDir)
    {
        $this->tempDir = $tempDir;
    }

    public function merge($filePaths)
    {
        $pdf = new Fpdi();

        foreach ($filePaths as $file) {
            $pageCount = $pdf->setSourceFile($file);
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $templateId = $pdf->importPage($pageNo);
                $size = $pdf->getTemplateSize($templateId);

                // Add a page with the same orientation and size
                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($templateId);
            }
        }

        $outputFile = $this->tempDir . '/' . uniqid() . '_merged.pdf';
        $pdf->Output($outputFile, 'F');

        return $outputFile;
    }

    /**
     * Splits PDF pages.
     *
     * @param string $filePath
     * @param string $ranges (e.g., "1-3,5")
     * @param bool $oneFilePerPage If true, extracts each page in range to a separate file.
     * @return array List of generated file paths.
     */
    public function split($filePath, $ranges, $oneFilePerPage = false)
    {
        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile($filePath);

        $pagesToExtract = $this->parseRange($ranges, $pageCount);
        $generatedFiles = [];

        if ($oneFilePerPage) {
            foreach ($pagesToExtract as $pageNo) {
                $newPdf = new Fpdi();
                $newPdf->setSourceFile($filePath);
                $templateId = $newPdf->importPage($pageNo);
                $size = $newPdf->getTemplateSize($templateId);
                $newPdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $newPdf->useTemplate($templateId);

                $outFile = $this->tempDir . '/' . uniqid() . '_page_' . $pageNo . '.pdf';
                $newPdf->Output($outFile, 'F');
                $generatedFiles[] = $outFile;
            }
        } else {
            // Merge extracted pages into one file
            $newPdf = new Fpdi();
            $newPdf->setSourceFile($filePath);
            foreach ($pagesToExtract as $pageNo) {
                 $templateId = $newPdf->importPage($pageNo);
                 $size = $newPdf->getTemplateSize($templateId);
                 $newPdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                 $newPdf->useTemplate($templateId);
            }
            $outFile = $this->tempDir . '/' . uniqid() . '_split.pdf';
            $newPdf->Output($outFile, 'F');
            $generatedFiles[] = $outFile;
        }

        return $generatedFiles;
    }

    private function parseRange($rangeStr, $maxPage)
    {
        $pages = [];
        $parts = explode(',', $rangeStr);
        foreach ($parts as $part) {
            $part = trim($part);
            if (empty($part)) continue;

            if (strpos($part, '-') !== false) {
                list($start, $end) = explode('-', $part);
                $start = max(1, intval($start));
                $end = min($maxPage, intval($end));
                for ($i = $start; $i <= $end; $i++) {
                    $pages[] = $i;
                }
            } else {
                $p = intval($part);
                if ($p >= 1 && $p <= $maxPage) {
                    $pages[] = $p;
                }
            }
        }
        return array_unique($pages);
    }

    public function getPageCount($filePath) {
        $pdf = new Fpdi();
        return $pdf->setSourceFile($filePath);
    }
}
