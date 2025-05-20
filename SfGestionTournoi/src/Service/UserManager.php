<?php

namespace App\Service;

use App\Entity\Utilisateur;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserManager implements UserManagerInterface
{

    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordHasher,
    )
    {
    }

    /**
     * Chiffre le mot de passe puis l'affecte au champ correspondant dans la classe de l'user
     */
    private function chiffrerMotDePasse(Utilisateur $user, ?string $plainPassword): void
    {
        $password = $this->userPasswordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($password);
    }

    /**
     * Réalise toutes les opérations nécessaires avant l'enregistrement en base d'un nouvel user, après soumissions du formulaire (hachage du mot de passe, sauvegarde de la photo de profil...)
     */
    public function proccessNewUser(Utilisateur $user, ?string $plainPassword): void
    {
        $this->chiffrerMotDePasse($user, $plainPassword);
    }

}