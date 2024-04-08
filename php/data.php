<?php
// Establish your database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "liege";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the selected option value from the request body
$option = isset($_POST['option']) ? $_POST['option'] : null;
$query="SELECT *
FROM ressousparcelle2
JOIN ex_old ON ressousparcelle2.id = ex_old.id";
// Define the base query
$query2 = "SELECT 
id, 
num_Point,
serie, 
CONCAT(parcelle, sous_parcelle) AS Sousparcelle, 
parcelle,
sous_parcelle,
forest_name,
canton,
surface_sous_parcelle,
code_wilaya, 
commune, 
total_diametre, 
average_diametre, 
nombre_tree, 
total_hauteur_total, 
average_hauteur_total, 
total_age, 
average_age,
epaisseur,
inv_row_num,
altitude,
exposition,
roche_mere,
profondeur_sol,
erosion,
occupation_actuelle,
stade_evolution,
Structure,
densite,
etat_sanitaire,
regeneration_naturelle,
sous_bois,
importance_incenide,
date_dern_incendie,
details_coupe,
defrichment,
parcours,
aptitude,
vocation,
nature_voc_deff,
proportion_voc_diff,
ROUND( nombre_tree / inv_row_num ) as nombre_moyen2  ,
ROUND((PI() * POW(average_diametre * 0.01, 2) / 4), 5)  AS surface_terriere ,

ROUND((PI() * POW(average_diametre * 0.01, 2) / (4 * 10000)), 8) AS surface_terriere_m2_ha,
ROUND((PI() * POW(average_diametre * 0.01, 2) / 4) * average_hauteur_total * 
        CASE 
            WHEN average_hauteur_total >= 3.6 AND average_hauteur_total <= 4.5 THEN 0.790
            WHEN average_hauteur_total >= 4.6 AND average_hauteur_total <= 5.5 THEN 0.655
            WHEN average_hauteur_total >= 5.6 AND average_hauteur_total <= 6.5 THEN 0.580
            WHEN average_hauteur_total >= 6.6 AND average_hauteur_total <= 7.5 THEN 0.545
            WHEN average_hauteur_total >= 7.6 AND average_hauteur_total <= 8.5 THEN 0.505
            WHEN average_hauteur_total >= 8.6 AND average_hauteur_total <= 9.5 THEN 0.480
            WHEN average_hauteur_total >= 9.6 AND average_hauteur_total <= 10.5 THEN 0.461
            WHEN average_hauteur_total >= 10.6 AND average_hauteur_total <= 11.5 THEN 0.446
            WHEN average_hauteur_total >= 11.6 AND average_hauteur_total <= 12.5 THEN 0.436
            WHEN average_hauteur_total >= 12.6 AND average_hauteur_total <= 13.5 THEN 0.426
            WHEN average_hauteur_total >= 13.6 AND average_hauteur_total <= 14.5 THEN 0.414
            WHEN average_hauteur_total >= 14.6 AND average_hauteur_total <= 15.5 THEN 0.409
            WHEN average_hauteur_total >= 15.6 AND average_hauteur_total <= 16.5 THEN 0.402
            WHEN average_hauteur_total >= 16.6 AND average_hauteur_total <= 17.5 THEN 0.395
            WHEN average_hauteur_total >= 17.6 AND average_hauteur_total <= 18.5 THEN 0.390
            WHEN average_hauteur_total >= 18.6 AND average_hauteur_total <= 19.5 THEN 0.385
            ELSE 0.381
        END, 5) AS volume_m3,

ROUND((PI() * POW(average_diametre * 0.01, 2) / 4) * total_hauteur_total * 
        CASE 
            WHEN total_hauteur_total >= 3.6 AND total_hauteur_total <= 4.5 THEN 0.790
            WHEN total_hauteur_total >= 4.6 AND total_hauteur_total <= 5.5 THEN 0.655
            WHEN total_hauteur_total >= 5.6 AND total_hauteur_total <= 6.5 THEN 0.580
            WHEN total_hauteur_total >= 6.6 AND total_hauteur_total <= 7.5 THEN 0.545
            WHEN total_hauteur_total >= 7.6 AND total_hauteur_total <= 8.5 THEN 0.505
            WHEN total_hauteur_total >= 8.6 AND total_hauteur_total <= 9.5 THEN 0.480
            WHEN total_hauteur_total >= 9.6 AND total_hauteur_total <= 10.5 THEN 0.461
            WHEN total_hauteur_total >= 10.6 AND total_hauteur_total <= 11.5 THEN 0.446
            WHEN total_hauteur_total >= 11.6 AND total_hauteur_total <= 12.5 THEN 0.436
            WHEN total_hauteur_total >= 12.6 AND total_hauteur_total <= 13.5 THEN 0.426
            WHEN total_hauteur_total >= 13.6 AND total_hauteur_total <= 14.5 THEN 0.414
            WHEN total_hauteur_total >= 14.6 AND total_hauteur_total <= 15.5 THEN 0.409
            WHEN total_hauteur_total >= 15.6 AND total_hauteur_total <= 16.5 THEN 0.402
            WHEN total_hauteur_total >= 16.6 AND total_hauteur_total <= 17.5 THEN 0.395
            WHEN total_hauteur_total >= 17.6 AND total_hauteur_total <= 18.5 THEN 0.390
            WHEN total_hauteur_total >= 18.6 AND total_hauteur_total <= 19.5 THEN 0.385
            ELSE 0.381
        END, 5) AS volume_m3_total

FROM (
SELECT 
    sp.id, 
    sp.serie, 
    sp.parcelle, 
    sp.sous_parcelle,
    sp.surface_sous_parcelle,
    sp.forest_name,
    sp.canton,
    sp.code_wilaya, 
    sp.commune, 
    sp.inv_row_num,
    sp.altitude,
    sp.exposition,
    sp.roche_mere,
    sp.profondeur_sol,
    sp.erosion,
    sp.occupation_actuelle,
    sp.stade_evolution,
    sp.Structure,
    sp.densite,
    sp.etat_sanitaire,
    sp.regeneration_naturelle,
    sp.sous_bois,
    sp.importance_incenide,
    sp.date_dern_incendie,
    sp.details_coupe,
    sp.defrichment,
    sp.parcours,
    sp.aptitude,
    sp.vocation,
    sp.nature_voc_deff,
    sp.proportion_voc_diff,


    ROUND(SUM(arbres.diametre), 2) AS total_diametre, 
    ROUND(AVG(arbres.diametre), 2) AS average_diametre, 
    COUNT(arbres.id_arbre) AS nombre_tree, 
    COUNT(arbres.id_arbre) as nombre_moyen2,
     
    ROUND(SUM(arbres.hauteur_total), 2) AS total_hauteur_total, 
    ROUND(AVG(arbres.hauteur_total), 2) AS average_hauteur_total, 
    ROUND(SUM(arbres.hauteur_demasclage), 2) AS total_hauteur_demasclage, 
    ROUND(AVG(arbres.hauteur_demasclage), 2) AS average_hauteur_demasclage, 
    
    ROUND(SUM(
        CASE 
            WHEN sp.code_wilaya = '18' THEN ROUND((arbres.epaisseur * 10) / 3.14, 2)
            WHEN sp.code_wilaya = '6' THEN ROUND((arbres.epaisseur * 10) / 3.19, 2)
            WHEN sp.code_wilaya = '13' THEN ROUND((arbres.epaisseur * 10) / 2.64, 2)
            ELSE NULL  
        END
    ), 2) AS total_age, 
    ROUND(AVG(
        CASE 
            WHEN sp.code_wilaya = '18' THEN ROUND((arbres.epaisseur * 10) / 3.14, 2)
            WHEN sp.code_wilaya = '6' THEN ROUND((arbres.epaisseur * 10) / 3.19, 2)
            WHEN sp.code_wilaya = '13' THEN ROUND((arbres.epaisseur * 10) / 2.64, 2)
            ELSE NULL  
        END
    )) AS average_age,
    arbres.age,
    ROUND(AVG(arbres.id_arbre))  AS nombre_moyen3, 
    ROUND(SUM(arbres.epaisseur), 2) AS epaisseur, 
    points.numero AS num_Point
   
  
FROM 
    sp
JOIN 
    points ON sp.id = points.id_sp
JOIN 
    arbres ON points.id_point = arbres.id_point 
WHERE 
    sp.exist = 1 AND points.exist = 1 AND arbres.exist = 1
    GROUP BY sp.id) AS subquery ORDER BY id ASC";

// Adjust the query based on the selected option
// switch ($option) {
//     case '1':
//         $query .= " GROUP BY sp.id) AS subquery ORDER BY id ASC";
//         break;
//     case '2':
//         $query .= " GROUP BY points.id_point) AS subquery ORDER BY id ASC";
//         break;
//     case '3':
//         $query .= " GROUP BY arbres.id_arbre) AS subquery ORDER BY id ASC";
//         break;
//     default:
//         $query .= " GROUP BY sp.id) AS subquery ORDER BY id ASC";
//         break;
// }

// Execute the query
$result = $conn->query($query);

// Function to format fields, removing leading and trailing commas for roche_mere
function formatField($fieldValue, $field) {
    if ($field === 'roche_mere') {
        // Remove leading and trailing commas if present
        return trim($fieldValue, ',');
    } else {
        // Process other fields
        $fieldArray = explode(',', $fieldValue);
        $formattedField = [];
        foreach ($fieldArray as $fieldItem) {
            $fieldParts = explode('-', $fieldItem);
            if (isset($fieldParts[1])) {
                $formattedField[] = trim($fieldParts[1]);
            }
        }
        return implode(', ', $formattedField);
    }
}



// Format data as JSON
$data = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {

 // Apply formatting for specific fields
 $fields_to_format = ['occupation_actuelle', 'sous_bois', 'roche_mere'];

 foreach ($fields_to_format as $field) {
     if (isset($row[$field])) {
         $row[$field] = formatField($row[$field], $field);
     }
 }

        $fields_to_check = [
            'id', 'num_Point', 'forest_name', 'canton', 'serie', 'parcelle', 'sous_parcelle', 
            'surface_sous_parcelle', 'altitude', 'exposition', '', 'profondeur_sol', 
            'erosion', 'occupation_actuelle', 'stade_evolution', 'Structure', 'densite', 
            'etat_sanitaire', 'regeneration_naturelle', 'sous_bois', 'importance_incenide', 
            'date_dern_incendie', 'details_coupe', 'defrichment', 'parcours', 'aptitude', 
            'vocation', 'nature_voc_deff', 'proportion_voc_diff', 'occupation_actuelle', 
            'stade_evolution', 'Structure', 'densite', 'etat_sanitaire'
        ];

        // Check and set empty fields to "Null"
        foreach ($fields_to_check as $field) {
            if (empty($row[$field]) || $row[$field] == '' || $row[$field] == 'Null'  ) {
                $row[$field] = "";
            }
        }



 // Handle special cases for the erosion field
 if ($row['erosion'] == "Array" || $row['erosion'] == "") {
    $row['erosion'] = "";
} else {
    // Remove the initial comma if present and remove the word "Array"
    // $row['erosion'] = str_replace('Array', '', ltrim(trim($row['erosion']), ','));
    $row['erosion'] = str_replace('Array', '', ltrim(trim($row['erosion']), ','));


}


    

        $data[] = $row;
    }
}

// Close connectsion
$conn->close();

// Output JSON
echo json_encode(["data" => $data]);
?>
