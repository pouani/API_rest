<?php

   class Produit{
        //propriètés des objets
        public $id;
        public $nom;
        public $description;
        public $prix;
        public $categories_id;
        public $categories_nom;
        public $created_at;


        //connection a la bd
        private $connexion;
        private $table = "produits";

        /**
         * constructeur pour connection a la base de donnees
         * @param $bd 
         */
        public function __construct($bd){
            $this->connexion = $bd;
        }

        /**
         * lecture des produits dans la bd
         * @return void
         */
        public function Read__Produit(){
            //requette de lecture
            $sql = "SELECT c.nom as categories_nom, p.id, p.nom, p.description, p.prix, p.categories_id, p.created_at 
                    FROM" .$this->table. "p LEFT JOIN categories c ON p.categories_id = c.id ORDER BY p.created_at DESC";

            //on prepare la requette
            $query = $this->connexion->prepare($sql);

            //on execute la requette
            $query->execute();

            //on retourne le resultat
            return $query;
        }

        /**
         * creation d'un produit
         * @return void
         */
        public function Create_Prduit(){
            //requette d'insertion ou de creation avec le nom de la table
            $sql = "INSERT INTO " . $this->table. " SET nom=:nom, prix=:prix, description=:description, categories_id=:categories_id";

            //preparation de la requette
            $query = $this->connexion->prepare($sql);

            //propetection contre les injections
            $this->nom=htmlspecialchars(strip_tags($this->nom));
            $this->description=htmlspecialchars(strip_tags($this->description));
            $this->prix=htmlspecialchars(strip_tags($this->prix));
            $this->categories_id=htmlspecialchars(strip_tags($this->categories_id));

            //Ajout des données protegées

            $query->bindParam(":nom", $this->nom);
            $query->bindParam(":prix", $this->prix);
            $query->bindParam(":description", $this->description);
            $query->bindParam(":categories_id", $this->categories_id);
        
            //on execute la requette
            if($query->execute()){
                return true;
            }
            
            return false;
        }

        /**
         * fonction pour lire un produit
         * @return void
         */
        public function lireUnProduit(){
            //on ecrit la requette
            $sql = "SELECT c.nom as categories_nom, p.id, p.nom, p.description, p.prix, p.categories_id, p.created_at 
            FROM " . $this->table . " p LEFT JOIN categories c ON p.categories_id = c.id 
            WHERE p.id = ? LIMIT 0,1";

            //on prepare la requette
            $query = $this->connexion->prepare($sql);

            //on attache l'id
            $query->bindParam(1, $this->id);

            //on execute la requette
            $query->execute();

            //on recupere la ligne 
            $row = $query->fetch(PDO::FETCH_ASSOC);

            //on hydrate l'objet(revient à lui fournir des données correspondant à ses attributs pour qu'il assigne les valeurs souhaitées à ces derniers) 
            $this->nom = $row['nom'];
            $this->prix = $row['prix'];
            $this->description = $row['description'];
            $this->categories_id = $row['categories_id'];
            $this->categories_nom = $row['categories_nom'];
        }

        /**
         * fonction supprimer
         * @return void
         */
        public function supprimer(){
            //requette
            $sql = "DELETE FROM " . $this->table . " WHERE id = ?";

            //on prepare la requette
            $query = $this->connexion->prepare($sql);

            //on protege les donnees
            $this->id = htmlspecialchars(strip_tags($this->id));

            //on attache l'id
            $query = bindParam(1, $this->id);

            //on execute la requette
            if($query->execute()){
                return true;
            }
            
            return false;
        }

        /**
         * Mettre à jour un produit
         * @return void
         */
        public function modifier(){
            // on ecrit la requette
            $sql = "UPDATE" . $this->table . " SET nom = :nom, prix = :prix, description = :description, categories_id = :categories_id WHERE id = :id";

            //on prepare la requette
            $query = $this->connexion->prepare($sql);

            // On sécurise les données
            $this->nom=htmlspecialchars(strip_tags($this->nom));
            $this->prix=htmlspecialchars(strip_tags($this->prix));
            $this->description=htmlspecialchars(strip_tags($this->description));
            $this->categories_id=htmlspecialchars(strip_tags($this->categories_id));
            $this->id=htmlspecialchars(strip_tags($this->id));
            
            // On attache les variables
            $query->bindParam(':nom', $this->nom);
            $query->bindParam(':prix', $this->prix);
            $query->bindParam(':description', $this->description);
            $query->bindParam(':categories_id', $this->categories_id);
            $query->bindParam(':id', $this->id);
            
            // On exécute
            if($query->execute()){
                return true;
            }
            
            return false;
            }
    }
?>