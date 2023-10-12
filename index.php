<?php

function fetchData()
{
    $url = 'https://api.tefcold.com/api/XmlFeed/GetXmlFeed?key=s1Pw4yXLpldvwFf-0lLJbrVM0mKBMVO5SfbQXItV3nY&CustomerNumber=11494&ShopId=SHOP1&LangId=LANG5&AreaId=6'; // Remplacez URL_DU_XML par l'URL réelle du fichier XML distant
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $xmlData = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Erreur cURL : ' . curl_error($ch);
    }

    $xml = simplexml_load_string($xmlData);
    $csvFile = fopen('tefcold.csv', 'w');


    $headers = [
        'ITEM_ID', 'PRODUCTNAME', 'PRODUCT', 'SUMMARY', 'DESCRIPTION', 'DESCRIPTION2',
        'URL', 'CATEGORYTEXT1', 'ON_STOCK', 'PRICE', 'WARRANTY', 'IMGURL1', 'IMGURL5',
        'IMGURL6', 'ENERGY_ARROW', 'DATA_SHEET', 'USER_MANUAL', 'SPARE_PART_LIST',
        'ENERGY_LABEL', 'NEW_ITEM', 'ACTION', 'EAN', 'PRODUCTNO', 'DELIVERY_DATE',
    ];
    foreach ($xml->SHOPITEM as $item) {
        foreach ($item->PARAMETERS->Parameter as $parameter) {
//        var_dump($parameter); die();
            $paramName = (string)$parameter->ParamName;

            if(!in_array($paramName, $headers))
            {
                $headers[]= $paramName;
            }


        }
    }
    fputcsv($csvFile, $headers);

    foreach ($xml->SHOPITEM as $item) {

        $productData = [];

        foreach ($headers as $header) {

            if (property_exists($item, $header)) {

                $productData[$header] = (string)$item->$header;
                var_dump($item->$header);
            } else {
                $productData[$header] = '';
            }
        }

        foreach ($item->PARAMETERS->Parameter as $parameter) {
            $paramName = (string)$parameter->ParamName;
            $paramValue = (string)$parameter->ParamValue;

            $productData[$paramName] = $paramValue;
        }


        fputcsv($csvFile, $productData);

    }

    fclose($csvFile);
    echo 'Récupération des données effectuées';
}

public function reworkMagentoSku($reference,$supplier){
    $reference = preg_replace('/[\W]/', '', $reference);
    $name = preg_replace('/[\W]/', '', $supplier);
    return strtoupper($reference.'_'.$name);
}






?>



