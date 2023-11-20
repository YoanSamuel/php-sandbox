<?php
ini_set('memory_limit', '256M');
function readCSV($filename) {
    $rows = [];
    if (($handle = fopen($filename, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 5000, ",")) !== FALSE) {
            $rows[] = $data;
        }
        fclose($handle);
    }
    return $rows;
}

function writeCSV($filename, $data) {
    if (($handle = fopen($filename, "w")) !== FALSE) {
        foreach ($data as $row) {
            fputcsv($handle, $row);
        }
        fclose($handle);
    }
}

function mergeCSVData($file1, $file2, $outputFile) {
    $data1 = readCSV($file1);
    $data2 = readCSV($file2);

    $header1 = array_shift($data1);
    $header2 = array_shift($data2);

    $supplierReferenceIndex1 = array_search('supplier_reference', $header1);
    $supplierReferenceIndex2 = array_search('supplier_reference', $header2);

    if ($supplierReferenceIndex1 === false || $supplierReferenceIndex2 === false) {
        die('Les fichiers ne semblent pas avoir de colonne "supplier reference".');
    }

    // Créer l'en-tête du fichier de sortie
    $mergedHeader = ['name', 'sku', 'supplier reference', 'pack of'];

    $mergedData = [$mergedHeader];

    foreach ($data2 as $row2) {
        $supplierReference = $row2[$supplierReferenceIndex2];

        // Chercher la référence du fournisseur dans le premier fichier
        $matchingRow1 = array_filter($data1, function ($row1) use ($supplierReference, $supplierReferenceIndex1) {
            return $row1[$supplierReferenceIndex1] === $supplierReference;
        });
        
        $row1 = reset($matchingRow1) ?: array_fill(0, count($header1), '');
        
        $mergedRow = [
            $row2[array_search('name', $header2)],
            $row2[array_search('sku', $header2)],
            $supplierReference,
            $row1[1],
        ];
         
        $mergedData[] = $mergedRow;
    }

    writeCSV($outputFile, $mergedData);
}


mergeCSVData('files//was-pack.csv', 'files//WAS.csv', 'files//was-packOf.csv');

echo "Le nouveau fichier a été créé avec succès.\n";