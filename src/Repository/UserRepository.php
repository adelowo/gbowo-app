<?php

namespace App\Repository;

use App\Entity\User;
use App\Exception\NotFoundEntityException;

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

        try {

            //Throws an exception if the user is not found.
            //Hence the real db persistence is in the `catch` block.
            //Seems a little bit fuzzy.
            $this->findByEmail($user->getEmailAddress());

            return false;
        } catch (NotFoundEntityException $e) {

            return $this->connection->insert("users", $data);
        }
    }

    public function findByEmail(string $emailAddress)
    {
        $statement = $this->connection->prepare("SELECT * FROM users WHERE email = :email");
        $statement->bindValue(":email", $emailAddress);

        $statement->execute();

        if ($result = $statement->fetch()) {
            return $this->loadEntity($result);
        }

        throw new NotFoundEntityException(
            "Unable to locate a user with the email address"
        );
    }

    protected function loadEntity(array $result)
    {
        return (new User())->setCreatedAt($result['created_at'])
            ->setUpdatedAt($result['updated_at'])
            ->setType($result['type'])
            ->setPassword(null)
            ->setFullName($result['fullname'])
            ->setEmailAddress($result['email']);
    }

    public function delete(User $user)
    {

    }
}
