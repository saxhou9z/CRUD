<?php
namespace App\Vue;

class Vue_ListeCategorie {

    public static function donneHTML(array $tableauCategorie, string $msgErreur = ""): string
    { 
        // Titre et message d’erreur éventuel
        $str = "<h1>Liste des catégories</h1>";
        if (!empty($msgErreur)) {
            $str .= "<p style='color:red;'>$msgErreur</p>";
        }

        // Formulaire de sélection
        $str .= "
        <form action='/categorie/selection' method='GET'>
            <label for='categorie-select'>Choisir une catégorie :</label>
            <select name='categorie' id='categorie-select'>
                <option value=''>--Choisissez une option--</option>";
        
        foreach ($tableauCategorie as $categorie) {
            $str .= "<option value='" . htmlspecialchars($categorie->getId()) . "'>" 
                . htmlspecialchars($categorie->getLibelle()) . "</option>";
        }

        $str .= "
            </select>
            <input type='submit' value='Valider'>
        </form>

        <!-- Formulaire de suppression -->
        <form action='/categorie/supprimer' method='POST' onsubmit='return confirm(\"Supprimer cette catégorie ?\")'>
            <input type='hidden' name='categorie' id='categorieToDelete'>
            <input type='submit' value='Supprimer'>
        </form>

        <script>
        // Copie la valeur sélectionnée dans le champ caché avant suppression
        document.getElementById('categorie-select').addEventListener('change', function() {
            document.getElementById('categorieToDelete').value = this.value;
        });
        </script>
        ";

        return $str;
    }
}
