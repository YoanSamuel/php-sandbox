<?php
ini_set('memory_limit', '256M');
function readCSV($filename) {
    $rows = [];
    if (($handle = fopen($filename, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
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

    $desiredColumns = ['name', 'sku', 'supplier reference', 'pack of'];

    // Créer l'en-tête du fichier de sortie
    $mergedHeader = [];
    foreach ($desiredColumns as $column) {
        $mergedHeader[] = $column;
    }

    $mergedData = [$mergedHeader];

    foreach ($data1 as $row1) {
        $supplierReference = $row1[$supplierReferenceIndex1];

        $matchingRows = array_filter($data2, function ($row2) use ($supplierReference, $supplierReferenceIndex2) {
            return $row2[$supplierReferenceIndex2] === $supplierReference;
        });

        foreach ($matchingRows as $matchingRow) {
            // Créer la ligne fusionnée avec les colonnes désirées
            $mergedRow = [];
            foreach ($desiredColumns as $column) {
                if ($column == 'supplier reference') {
                    $mergedRow[] = $supplierReference; // Utiliser la valeur de "supplier reference" de $row1
                } else {
                    $mergedRow[] = $matchingRow[array_search($column, $header2)];
                }
            }
            $mergedData[] = $mergedRow;
        }
    }

    writeCSV($outputFile, $mergedData);
}


mergeCSVData('files//WAS.csv', 'files//was-pack.csv', 'files//was-packOf.csv');

echo "Le nouveau fichier a été créé avec succès.\n";
