<?php

    // Headers requis
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    //on verifie que la methode utilisée est correcte
    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        // On inclut les fichiers de configuration et d'accès aux données
        include_once '../config/Database.php';
        include_once '../models/Produits.php';

        //on instancie la Base de données
        $bd = new Database();

        //on instancie le produit
        $produit = new Produit($bd->getConnection());

        //on recupere les données
        $data = $produit->lire();

        //on verifie si on a au moins un produit

        if($data->rowCount() > 0){
            //on iitialise un tableau assiociatif
            $tableauProduits = [],
            $tableauProduits['produits'] = [];

            //on parcourt les produits
            while($row = $data->fetch(PDO::FETCH_ASSOC)){
                extract($row);

                $prod = [
                    "id" => $id,
                    "nom" => $nom,
                    "description" => $description,
                    "prix" => $prix,
                    "categories_id" => $categories_id,
                    "categories_nom" => $categories_nom
                ];

                $tableauProduits['produits'][] = $prod;
            }
            
                //on envoie le code de reponse 200 ok
                http_response_code(200);

                //on encode au format json et on envoi
                echo json_encode($tableauProduits);
        }
   }else{
        // On gère l'erreur
        http_response_code(405);
        echo json_encode(["message" => "La méthode n'est pas autorisée"]);
   }