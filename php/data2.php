<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Establish your database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "liege1";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the selected option value from the request body
$option = isset($_POST['option']) ? $_POST['option'] : null;

// Define the base query
$query = "SELECT 
    id,
    CONCAT(id, num_Point) AS Point_id,
    num_Point,

       CASE code_wilaya
        WHEN 6 THEN 'Béjaïa'
        WHEN 18 THEN 'Jijel'
        WHEN 13 THEN 'Tlemcen'
        ELSE 'Unknown' -- Adjust this as needed
    END AS wilaya_name,

    forest_name,
    serie,
    canton,
    parcelle,
     LOWER(sous_parcelle) AS sous_parcelle,
     altitude,
      exposition,
        roche_mere,
        profondeur_sol,
        erosion,
    CONCAT(parcelle, sous_parcelle) AS Sousparcelle,
    diametre_m, 
    hauteur_m,
    hauteur_démasclage_m  , 
    -- nombre_arbres_x10,
    nombre_arbres,
    -- ROUND(G, 5) AS G,
   ROUND(G,5) AS G ,
    -- volume_m3,
    volume_m3,
   -- ROUND(volume_m3 * surface_sous_parcelle , 2) AS Volume_total,
    surface_sous_parcelle,
-- SUM(G) ,

  epaisseur_m,
  hauteur_démasclage_calculé,
Perimetre_Moyen,
 -- ROUND(Perimetre_Moyen * hauteur_démasclage_calculé * epaisseur_m, 2) AS Volume_liege_m3,
 -- ROUND((Perimetre_Moyen * hauteur_démasclage_calculé * 5.5)/100, 2) AS Quantité_qt
average_diametre

  


FROM (
    SELECT 
        sp.id,
        points.numero AS num_Point,
        sp.serie,
        sp.canton,
        sp.code_wilaya,
        sp.parcelle,
        sp.forest_name,
        sp.sous_parcelle,
        sp.surface_sous_parcelle,
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
        sp.date_dern_incendie	,
        sp.details_coupe,
        sp.defrichment,
        sp.parcours,
        sp.aptitude,
        sp.vocation,
        sp.nature_voc_deff,
        sp.proportion_voc_diff,
     ((POW((arbres.diametre * 0.01), 2) / 4) * PI()) AS G,
         ROUND((arbres.diametre * 0.01 ),2) AS average_diametre,
        arbres.essence,
           ROUND(AVG(
        CASE 
            WHEN sp.code_wilaya = '18' THEN ROUND((arbres.epaisseur * 10) / 3.14, 2)
            WHEN sp.code_wilaya = '6' THEN ROUND((arbres.epaisseur * 10) / 3.19, 2)
            WHEN sp.code_wilaya = '13' THEN ROUND((arbres.epaisseur * 10) / 2.64, 2)
            ELSE NULL  
        END
    )) AS age,
    -- ROUND(AVG(arbres.diametre), 2) AS average_diametre, 
       ROUND(AVG(arbres.hauteur_total), 2) AS average_hauteur_total, 
           COUNT(arbres.id_arbre) AS nombre_tree, 

       CASE 
    WHEN sp.code_wilaya = '18' THEN ROUND(((arbres.diametre * PI())/100* 2.5), 2)
    WHEN sp.code_wilaya = '6' THEN ROUND(((arbres.diametre * PI())/100* 2), 2)
    WHEN sp.code_wilaya = '13' THEN ROUND(((arbres.diametre * PI())/100* 1.5), 2)
    ELSE NULL  
END AS hauteur_démasclage_calculé,
ROUND(arbres.diametre * PI(), 2) AS Perimetre_Moyen,
       ROUND(arbres.hauteur_total, 2) AS hauteur_m,
       ROUND(AVG(arbres.hauteur_demasclage), 2) AS hauteur_démasclage_m,
        COUNT(arbres.id_arbre) AS nombre_arbres,
        ROUND((arbres.epaisseur * 0.01), 4) AS epaisseur_m,
         ROUND(((POW(arbres.diametre * 0.01 , 2) / 4) * PI()) * arbres.hauteur_total * 
        CASE 
            WHEN arbres.hauteur_total >= 3.6 AND arbres.hauteur_total <= 4.5 THEN 0.790
            WHEN arbres.hauteur_total >= 4.6 AND arbres.hauteur_total <= 5.5 THEN 0.655
            WHEN arbres.hauteur_total >= 5.6 AND arbres.hauteur_total <= 6.5 THEN 0.580
            WHEN arbres.hauteur_total >= 6.6 AND arbres.hauteur_total <= 7.5 THEN 0.545
            WHEN arbres.hauteur_total >= 7.6 AND arbres.hauteur_total <= 8.5 THEN 0.505
            WHEN arbres.hauteur_total >= 8.6 AND arbres.hauteur_total <= 9.5 THEN 0.480
            WHEN arbres.hauteur_total >= 9.6 AND arbres.hauteur_total <= 10.5 THEN 0.461
            WHEN arbres.hauteur_total >= 10.6 AND arbres.hauteur_total <= 11.5 THEN 0.446
            WHEN arbres.hauteur_total >= 11.6 AND arbres.hauteur_total <= 12.5 THEN 0.436
            WHEN arbres.hauteur_total >= 12.6 AND arbres.hauteur_total <= 13.5 THEN 0.426
            WHEN arbres.hauteur_total >= 13.6 AND arbres.hauteur_total <= 14.5 THEN 0.414
            WHEN arbres.hauteur_total >= 14.6 AND arbres.hauteur_total <= 15.5 THEN 0.409
            WHEN arbres.hauteur_total >= 15.6 AND arbres.hauteur_total <= 16.5 THEN 0.402
            WHEN arbres.hauteur_total >= 16.6 AND arbres.hauteur_total <= 17.5 THEN 0.395
            WHEN arbres.hauteur_total >= 17.6 AND arbres.hauteur_total <= 18.5 THEN 0.390
            WHEN arbres.hauteur_total >= 18.6 AND arbres.hauteur_total <= 19.5 THEN 0.385
            ELSE 0.381
        END , 5) AS volume_m3
    FROM 
        sp
    JOIN 
        points ON sp.id = points.id_sp
    JOIN 
        arbres ON points.id_point = arbres.id_point 
    WHERE 
        sp.exist = 1 AND points.exist = 1 AND arbres.exist = 1
 GROUP BY arbres.id_arbre) AS subquery;"

// Adjust the query based on the selected option
switch ($option) {
    case '1':
        $query .= " GROUP BY sp.id) AS subquery ORDER BY id ASC";
        break;
    case '2':
        $query .= " GROUP BY points.id_point) AS subquery ORDER BY id ASC";
        break;
    case '3':
        $query .= " GROUP BY arbres.id_arbre) AS subquery ORDER BY id ASC";
        break;
    default:
        $query .= " GROUP BY sp.id) AS subquery ORDER BY id ASC";
        break;
}

// Execute the query
$result = $conn->query($query);


function formatField($fieldValue) {
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


// Format data as JSON
$data = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {

 // Apply formatting for specific fields
     $fields_to_format = ['occupation_actuelle', 'sous_bois', 'roche_mere'];

     foreach ($fields_to_format as $field) {
         if (isset($row[$field])) {
             $row[$field] = formatField($row[$field]);
         }
     }

        $fields_to_check = [
            'id', 'num_Point', 'forest_name', 'canton', 'serie', 'parcelle', 'sous_parcelle', 
            'surface_sous_parcelle', 'altitude', 'exposition', 'roche_mere', 'profondeur_sol', 
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
