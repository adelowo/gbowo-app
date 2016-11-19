<?php

namespace App\Repository;

use App\Entity\User;

class UserRepository extends AbstractRepository
{

    public function add(User $user)
    {
        $data = [
            "fullname" => $user->getFullName(),
            "email" => $user->getEmailAddress(),
            "password" => password_hash($user->getPassword(), PASSWORD_DEFAULT),
            "created_at" => $user->getCreatedAt(),
            "updated_at" => $user->getUpdatedAt()
        ];

        if (!$this->findByEmail($user->getEmailAddress()) instanceof User) {
            return $this->connection->insert("users", $data);
        }

        return false;
    }

    public function findByEmail(string $emailAddress)
    {
        $statement = $this->connection->prepare("SELECT * FROM users WHERE email = :email");
        $statement->bindValue(":email", $emailAddress);

        $statement->execute();

        if ($result = $statement->fetch()) {
            return (new User())->setCreatedAt($result['created_at'])
                ->setUpdatedAt($result['updated_at'])
                ->setType($result['type'])
                ->setPassword(null)
                ->setFullName($result['fullname'])
                ->setEmailAddress($result['email']);
        }

        return false;
    }

    public function delete(User $user)
    {

    }
}
