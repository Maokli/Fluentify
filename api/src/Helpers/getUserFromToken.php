<?php

namespace App\Helpers;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;


function getUserFromToken(EntityManagerInterface $entityManager, string $token): ?User
{
    // Split the token into its parts
    $tokenParts = explode(".", $token);
    if (count($tokenParts) < 2) {
        // Invalid token
        return null;
    }

    // Extract the payload from the token
    $tokenPayload = base64_decode($tokenParts[1]);
    $jwtPayload = json_decode($tokenPayload);
    if (!$jwtPayload || !isset($jwtPayload->username)) {
        // Invalid token payload
        return null;
    }

    // Find the user in the database
    $user = $entityManager->getRepository(User::class)->findOneByEmail($jwtPayload->username);
    if (!$user) {
        // User not found in database
        return null;
    }

    return $user;
}

?>
