<?php

namespace models;

use db\PDOFactory;
use PDO;

class User
{
    protected $id;
    protected $email;
    protected $password_hash;
    protected $login;

    public static function get($id, PDO | null $pdo) : User
    {
        // NOTE: Condition is usefull for unit testing
        if (empty($pdo)) 
            $pdo = PDOFactory::readInstance();

        $handle = $pdo->prepare('
        	SELECT id, email, login, password_hash
        	FROM `user` 
        	WHERE id = :id
        ');

        $handle->execute([':id' => $id]);

        // EXPLAIN: Must be one record only
        if ($handle->rowCount() === 1) 
            return $handle->fetchObject('\models\User');

        return new User();
    }

    public static function getByLogin(string $login, PDO | null $pdo) : User
    {
        // NOTE: Condition is usefull for unit testing
        if (empty($pdo)) 
            $pdo = PDOFactory::readInstance();

        $handle = $pdo->prepare('
        	SELECT id, email, login, password_hash
        	FROM `user` 
        	WHERE login = :login
        ');

        $handle->execute([':login' => $login ]);

        // NOTE: Must be one record only
        if ($handle->rowCount() === 1) 
            return $handle->fetchObject('\models\User');

        return new User();
    }
    
    public function getId() : ?int
    {
        return $this->id;
    }

    public function setId(int $id) : User
    {
        $this->id = $id;
        return $this;
    }

    public function getEmail() : ?string
    {
        return $this->email;
    }

    public function setEmail(string $email) : User
    {
        $this->email = $email;
        return $this;
    }

    public function getLogin() : ?string
    {
        return $this->login;
    }

    public function setLogin(string $login) : User
    {
        $this->login = $login;
        return $this;
    }

    public function getPasswordHash() : ?string
    {
        return $this->password_hash;
    }

    public function setPasswordHash(string $password_hash) : User
    {
        $this->password_hash = $password_hash;
        return $this;
    }

}