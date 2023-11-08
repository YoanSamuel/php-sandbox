<?php

function fetchData(): void
{
    $url = 'https://api.tefcold.com/api/XmlFeed/GetXmlFeed?key=s1Pw4yXLpldvwFf-0lLJbrVM0mKBMVO5SfbQXItV3nY&CustomerNumber=11494&ShopId=SHOP1&LangId=LANG5&AreaId=6';
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
            $paramName = (string)$parameter->ParamName;

            if (!in_array($paramName, $headers)) {
                $headers[] = $paramName;
            }


        }
    }
    fputcsv($csvFile, $headers);

    foreach ($xml->SHOPITEM as $item) {

        $productData = [];

        foreach ($headers as $header) {

            if (property_exists($item, $header)) {
                $productData[$header] = (string)$item->$header;
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



function reworkMagentoSku($reference,$supplier)
{
    $reference = preg_replace('/[\W]/', '', $reference);
    $name = preg_replace('/[\W]/', '', $supplier);
    return strtoupper($reference.'_'.$name);
}

function transformCSV($inputFilePath, $outputFilePath)
{
    $file = fopen($inputFilePath, 'r');
    $fileMagento = fopen($outputFilePath, 'w');

    if ($file && $fileMagento) {
//      fputcsv($fileMagento, ['source_code', 'sku', 'status', 'quantity']);

        while (($line = fgetcsv($file)) !== false) {
            $sku = reworkMagentoSku($line[0], 'COOLHEADEUROPE');
            $status = 1;

            $quantity = intval($line[6]);
            $quantity < 0 ? $quantity = 0 : $quantity;


            fputcsv($fileMagento, ['default', $sku, $status, $quantity]);
        }

        fclose($file);
        fclose($fileMagento);

        return true;
    } else {
        return false;
    }
}
$inputFilePath = 'files/coolhead.csv';
$outputFilePath = 'files/stockCoolHead.csv';

if (transformCSV($inputFilePath, $outputFilePath)) {
    echo 'Transformation du fichier CSV terminée avec succès.';
} else {
    echo "Erreur lors de l'ouverture des fichiers CSV.";
}

$attributes = array (('allergens'),
    ('ampoule_incluse'),
    ('am_hide_price_customer_gr'),
    ('am_hide_price_mode'),
    ('anti_adhesive'),
    ('autonomie_mn'),
    ('autres'),
    ('backsplash'),
    ('bakingmolds_type'),
    ('best_seller'),
    ('beverages_type'),
    ('biblock_groups'),
    ('biodegradable'),
    ('blade_type'),
    ('brand'),
    ('brosse'),
    ('burners'),
    ('capacite_mirakl_en_cl'),
    ('capacite_mirakl_en_l'),
    ('capacite_mirakl_en_ml'),
    ('capacity_bottle'),
    ('capacity_cl'),
    ('capacity_kg'),
    ('capacity_l'),
    ('capacity_ml'),
    ('capacity_pin'),
    ('capacity_pizza'),
    ('category_ids'),
    ('champagne_type'),
    ('chicken_capacity'),
    ('climate_class'),
    ('cold_group'),
    ('cold_room_volume'),
    ('cold_type'),
    ('color'),
    ('complements_alimentaires'),
    ('compostable'),
    ('condenser_type'),
    ('connecte'),
    ('connectivity'),
    ('conservation_duration'),
    ('cost'),
    ('country_of_manufacture'),
    ('created_at'),
    ('crepemaker_type'),
    ('crustacean_type'),
    ('custom_design'),
    ('custom_design_from'),
    ('custom_design_to'),
    ('custom_layout'),
    ('custom_layout_update'),
    ('custom_layout_update_file'),
    ('cutter_function'),
    ('defrost_type'),
    ('delai_de_livraison'),
    ('delicatessen_type'),
    ('delivered_with'),
    ('depth'),
    ('depth_cm'),
    ('description'),
    ('diameter'),
    ('diameter_cm'),
    ('diametre_mirakl_en_cm'),
    ('diametre_mirakl_en_mm'),
    ('dishwasher_location'),
    ('display'),
    ('display_type'),
    ('diving_tank'),
    ('doors'),
    ('door_type'),
    ('double_sided_function'),
    ('drainer'),
    ('draining'),
    ('drain_valve'),
    ('drawers'),
    ('ean'),
    ('eco_participation'),
    ('eei_volume_liter'),
    ('energy'),
    ('energy_class'),
    ('energy_consumption'),
    ('energy_efficiency_index'),
    ('extract_groups'),
    ('faucet'),
    ('filtre'),
    ('fire_type'),
    ('fish_type'),
    ('flatwares'),
    ('foldable'),
    ('format'),
    ('format_d_affiche'),
    ('frequency'),
    ('fronts'),
    ('frozen_type'),
    ('fruit_type'),
    ('fryer_type'),
    ('gallery'),
    ('gastronomic_tray'),
    ('gas_load'),
    ('gas_type'),
    ('gears'),
    ('gender'),
    ('gift_message_available'),
    ('graphic_card'),
    ('guarantee'),
    ('hard_disk_capacity'),
    ('hard_drive_type'),
    ('has_options'),
    ('hauteur_mirakl_en_cm'),
    ('hauteur_mirakl_en_mm'),
    ('hdmi_ports'),
    ('head_type'),
    ('heating_type'),
    ('height'),
    ('height_cm'),
    ('height_m'),
    ('hide_quote_buy_button'),
    ('hinge'),
    ('hood_type'),
    ('icecubes_type'),
    ('id_prestashop'),
    ('image'),
    ('image_label'),
    ('induction_compatible'),
    ('ingredient'),
    ('inside_lighting'),
    ('installation_professional'),
    ('installation_type'),
    ('label_certification'),
    ('largeur_mirakl_en_cm'),
    ('largeur_mirakl_en_mm'),
    ('leap_m'),
    ('legs'),
    ('length'),
    ('Length_cm'),
    ('levels'),
    ('links_exist'),
    ('links_purchased_separately'),
    ('links_title'),
    ('lock'),
    ('longueur_mirakl_en_cm'),
    ('longueur_mirakl_en_mm'),
    ('manufacturing'),
    ('material'),
    ('max_flow'),
    ('meat_type'),
    ('media_gallery'),
    ('meta_description'),
    ('meta_keyword'),
    ('meta_title'),
    ('minimal_price'),
    ('mirakl_authorized_shop_ids'),
    ('mirakl_category_id'),
    ('mirakl_images_status'),
    ('mirakl_image_1'),
    ('mirakl_image_2'),
    ('mirakl_image_3'),
    ('mirakl_image_4'),
    ('mirakl_image_5'),
    ('mirakl_image_6'),
    ('mirakl_image_7'),
    ('mirakl_mcm_is_operator_master'),
    ('mirakl_mcm_product_id'),
    ('mirakl_mcm_variant_group_code'),
    ('mirakl_offer_state_ids'),
    ('mirakl_shops_skus'),
    ('mirakl_shop_ids'),
    ('mirakl_sync'),
    ('mirakl_variant_group_codes'),
    ('monoblock_groups'),
    ('msrp'),
    ('msrp_display_actual_price_type'),
    ('name'),
    ('news_from_date'),
    ('news_to_date'),
    ('nomenclature_nc8'),
    ('nt_unite_supplementaire'),
    ('old_id'),
    ('operating_system'),
    ('operation_type'),
    ('option'),
    ('options_container'),
    ('other_colors'),
    ('other_materials'),
    ('outdoor_use'),
    ('packaging'),
    ('pack_of'),
    ('page_layout'),
    ('panel_thickness'),
    ('pizza_per_hour'),
    ('plate_type'),
    ('power_kw'),
    ('power_w'),
    ('preparation_tips'),
    ('pre_assembled'),
    ('price'),
    ('price_type'),
    ('price_view'),
    ('print_size'),
    ('processor'),
    ('production'),
    ('profondeur_mirakl_en_cm'),
    ('profondeur_mirakl_en_mm'),
    ('programmable'),
    ('puissance_v'),
    ('quantity'),
    ('quantity_and_stock_status'),
    ('ram_capacity'),
    ('recyclability_rate'),
    ('recyclable'),
    ('refresh_rate'),
    ('refrigerant_gas_type'),
    ('regulation_type'),
    ('remote_type'),
    ('required_options'),
    ('response_time'),
    ('reversible_door'),
    ('rotisserie_type'),
    ('sac'),
    ('samples_title'),
    ('screen_resolution'),
    ('screen_size'),
    ('selled_by_chr'),
    ('shape'),
    ('shells_type'),
    ('shelves'),
    ('shipment_type'),
    ('short_description'),
    ('size'),
    ('sku'),
    ('sku_type'),
    ('slab_type'),
    ('slicer_type'),
    ('slide'),
    ('slots'),
    ('small_image'),
    ('small_image_label'),
    ('socket_provided'),
    ('soundlevel_db'),
    ('special_from_date'),
    ('special_price'),
    ('special_to_date'),
    ('speed_trmin'),
    ('spices_type'),
    ('spirits_type'),
    ('stackable'),
    ('stainless_steel_type'),
    ('standard'),
    ('status'),
    ('style_de_lampes'),
    ('supplier'),
    ('supplier_reference'),
    ('surface_m'),
    ('swatch_image'),
    ('tax_class_id'),
    ('tax_reduction'),
    ('technology_type'),
    ('temperature_range'),
    ('thickness'),
    ('thumbnail'),
    ('thumbnail_label'),
    ('tier_price'),
    ('top_groups'),
    ('tray_thickness'),
    ('trolleys_type'),
    ('tropicalized'),
    ('tubs'),
    ('type_de_cafe'),
    ('type_de_distributeur'),
    ('type_de_lampes'),
    ('type_de_linge'),
    ('type_de_matelas'),
    ('type_de_papier'),
    ('type_de_poignee'),
    ('type_de_sommier'),
    ('type_d_affichage'),
    ('type_d_ardoise'),
    ('type_d_etiquette'),
    ('updated_at'),
    ('url_key'),
    ('url_path'),
    ('usb_connector_type'),
    ('useful_capacity'),
    ('vegetables_type'),
    ('visibility'),
    ('voltage'),
    ('wafflemaker_type'),
    ('wafflemarker_rotating'),
    ('wash_locker'),
    ('weight'),
    ('weight_g'),
    ('weight_type'),
    ('wesupply_estimation_display'),
    ('width'),
    ('width_cm'),
    ('width_m'),
    ('wines_type'),
    ('workplan'),
    ('zones'));
function processAttributes($attributes) {
    $outputHeader = '';
    foreach ($attributes as $attribute) {
        $outputHeader .= "MAX(CASE WHEN ea.attribute_code = '$attribute' THEN eav.value END) AS $attribute," . PHP_EOL;
    }
    return rtrim($outputHeader, ',');
}


$outputFile = 'attributes.csv';

$outputSQL = processAttributes($attributes);
file_put_contents($outputFile, $outputSQL);


