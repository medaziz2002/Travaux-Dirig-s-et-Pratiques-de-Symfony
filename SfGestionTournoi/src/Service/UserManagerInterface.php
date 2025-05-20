<?php

namespace App\Service;

use App\Entity\Utilisateur;

interface UserManagerInterface
{
    public function proccessNewUser(Utilisateur $user, ?string $plainPassword): void;
}