SELECT 
    id,
    num_Point,
    serie,
    CONCAT(parcelle, sous_parcelle) AS Sousparcelle,
    average_diametre, 
    average_hauteur_total, 
    nombre_tree,
 surface_terriere,
    nombre_tree * 10 AS nombre_arbres_x10 ,
    surface_terriere * 10 AS nombre_tree_x10 ,
    volume_m3 * 10 AS volume_m3_x10 

FROM (
    SELECT 
        sp.id,
        points.numero AS num_Point,
        sp.serie,
        sp.parcelle,
        sp.sous_parcelle,
        sp.surface_sous_parcelle,
        AVG(arbres.diametre) AS average_diametre,
        AVG(arbres.hauteur_total) AS average_hauteur_total,
        COUNT(arbres.id_arbre) AS nombre_tree,
           ROUND((PI() * POW(arbres.diametre * 0.01, 2) / 4), 5)  AS surface_terriere,
    ROUND((PI() * POW(arbres.diametre * 0.01, 2) / 4) * arbres.hauteur_total * 
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
        END, 5) AS volume_m3
    FROM 
        sp
    JOIN 
        points ON sp.id = points.id_sp
    JOIN 
        arbres ON points.id_point = arbres.id_point 
    WHERE 
        sp.exist = 1 AND points.exist = 1 AND arbres.exist = 1
    GROUP BY 
        points.id_point
) AS subquery 
ORDER BY 
    id ASC;