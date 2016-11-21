<?php


namespace App\Repository;


use App\Entity\Product;
use App\Exception\NotFoundEntityException;

class ProductRepository extends AbstractRepository
{

    /**
     * @return Product[]
     */
    public function all()
    {
        $products = [];

        $statement = $this->connection->prepare("SELECT * FROM products");
        $statement->execute();

        while ($item = $statement->fetch()) {
            $products[] = $this->loadEntity($item);
        }

        return $products;
    }

    protected function loadEntity(array $result)
    {
        return (new Product())->setName($result['name'])
            ->setCreatedAt($result['created_at'])
            ->setPrice($result['price'])
            ->setDescription($result['description'])
            ->setImage($result['image'])
            ->setMoniker($result['moniker']);
    }

    public function findByMoniker(string $moniker)
    {
        $statement = $this->connection->prepare("SELECT * FROM products WHERE moniker=:moniker");
        $statement->bindParam(":moniker", $moniker);
        $statement->execute();

        if ($result = $statement->fetch()) {
            return $this->loadEntity($result);
        }

        throw new NotFoundEntityException(
            "This product,{$moniker} does not exist."
        );
    }
}