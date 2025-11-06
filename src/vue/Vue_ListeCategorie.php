<?php
namespace App\Vue;

class Vue_ListeCategorie {

    public static function donneHTML(array $tableauCategorie, string $msgErreur = ""): string
    { 
        $str = "<h1>Liste des catégories</h1>";

        // Affiche le message d'erreur si nécessaire
        if (!empty($msgErreur)) {
            $str .= "<p style='color:red;'>$msgErreur</p>";
        }

        // Formulaire de sélection pour voir les boissons
        $str .= "
        <form id='formSelection' action='/categorie/selection' method='GET'>
            <label for='categorie-select'>Choisir une catégorie :</label>
            <select name='categorie' id='categorie-select'>
                <option value=''>--Choisissez une option--</option>";

        foreach ($tableauCategorie as $categorie) {
            $str .= "<option value='" . htmlspecialchars($categorie->getId()) . "'>" 
                . htmlspecialchars($categorie->getLibelle()) . "</option>";
        }

        $str .= "
            </select>
            <input type='submit' value='Voir les boissons'>
        </form>";

        // Bouton Créer une catégorie
        $str .= "
        <form action='/categorie/creation' method='GET' style='margin-top:10px;'>
            <input type='submit' value='Créer une catégorie'>
        </form>";

        // Bouton Modifier
        $str .= "
        <form id='formModifier' method='GET' action='' style='margin-top:10px;'>
            <input type='hidden' name='categorie' id='categorieToEdit'>
            <input type='submit' value='Modifier'>
        </form>";

        // Bouton Supprimer
        $str .= "
        <form id='formSupprimer' action='/categorie/supprimer' method='POST' style='margin-top:10px;' onsubmit='return confirmSupprimer();'>
            <input type='hidden' name='categorie' id='categorieToDelete'>
            <input type='submit' value='Supprimer'>
        </form>";

        // JavaScript pour gérer les valeurs cachées et sécurité
        $str .= "
        <script>
        const select = document.getElementById('categorie-select');
        const deleteInput = document.getElementById('categorieToDelete');
        const editInput = document.getElementById('categorieToEdit');
        const editForm = document.getElementById('formModifier');

        // Met à jour les champs cachés à chaque changement de catégorie
        select.addEventListener('change', function() {
            deleteInput.value = this.value;
            editInput.value = this.value;
            editForm.action = '/categorie/editer/' + this.value;
        });

        // Vérifie avant suppression
        function confirmSupprimer() {
            if (!deleteInput.value) {
                alert('Vous devez sélectionner une catégorie avant de supprimer !');
                return false;
            }
            return confirm('Supprimer cette catégorie ?');
        }
        </script>";

        return $str;
    }
}
