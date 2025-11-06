<?php
namespace App\Vue;

class Vue_CreationBoisson {
    public static function donneHTML(\App\Entity\Categorie $categorie): string
    {
        return "<h2>Créer une boisson pour la catégorie : " . htmlspecialchars($categorie->getLibelle()) . "</h2>
                <form action='/boisson/creer' method='POST'>
                    <input type='hidden' name='categorie' value='{$categorie->getId()}'>
                    <input type='text' name='nom' placeholder='Nom de la boisson' required>
                    <input type='submit' value='Créer'>
                </form>
                <br><a href='/boisson/selection?categorie={$categorie->getId()}'>⬅ Retour aux boissons</a>";
    }
}
