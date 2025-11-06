<?php
namespace App\Vue;

class Vue_ListeBoisson {

    /**
     * Affiche la liste des boissons avec les boutons Créer / Modifier / Supprimer
     * @param array $tableauBoisson Liste des objets Boisson
     * @param string $msgErreur Message d'erreur optionnel
     * @param \App\Entity\Categorie|null $categorie La catégorie sélectionnée
     */
    public static function donneHTML(array $tableauBoisson, $msgErreur = "", $categorie = null): string
    { 
        $str = "<h1>Liste des boissons</h1>";

        // Bouton pour revenir à la sélection des catégories
        $str .= "<form action='/categorie' method='GET'>
                    <input type='submit' value='⬅ Retour aux catégories'>
                 </form><br>";

        // Affichage des messages d'erreur
        if (!empty($msgErreur)) {
            $str .= "<p style='color:red;'>$msgErreur</p>";
        }

        if ($categorie) {
            $str .= "<h2>Catégorie : " . htmlspecialchars($categorie->getLibelle()) . "</h2>";
        }

        // Liste des boissons
        if ($tableauBoisson) {
            $str .= "<ul>";
            foreach ($tableauBoisson as $boisson) {
                $str .= "<li>" . htmlspecialchars($boisson->getNom()) . " (" . $boisson->getVolumeCL() . " cl - " . $boisson->getPrix() . " €) ";

                // Bouton modifier
                $str .= "<form style='display:inline;' method='GET' action='/boisson/editer/" . $boisson->getId() . "'>
                            <input type='submit' value='Modifier'>
                         </form>";

                // Bouton supprimer
                $str .= "<form style='display:inline;' method='POST' action='/boisson/supprimer' onsubmit='return confirm(\"Supprimer cette boisson ?\")'>
                            <input type='hidden' name='idBoisson' value='" . $boisson->getId() . "'>
                            <input type='submit' value='Supprimer'>
                         </form>";

                $str .= "</li>";
            }
            $str .= "</ul>";
        } else {
            $str .= "<p>Aucune boisson dans cette catégorie.</p>";
        }

        // Bouton pour créer une nouvelle boisson dans cette catégorie
        if ($categorie) {
            $str .= "<form method='GET' action='/boisson/creation/" . $categorie->getId() . "'>
                        <input type='submit' value='Créer une nouvelle boisson'>
                     </form>";
        }

        return $str;
    }
}
