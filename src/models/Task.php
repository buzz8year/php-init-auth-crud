<?php

namespace models;

use db\PDOFactory;
use PDO;

class Task 
{
    public const int STATUS_COMPLETED = 2;
    public const string SORT_DEFAULT = 'id';
    public static array $slice = [];

    protected $id;
    protected $name;
    protected $text;
    protected $status;
    protected $user_email;
    protected $edited;

    private $task;

    public static function populateSlice(string $orderBy, int $limit, int $offset, PDO | null $pdo): void
    {
        // NOTE: Condition is usefull for unit testing
        if (empty($pdo)) 
            $pdo = PDOFactory::readInstance();

        $handle = $pdo->prepare('
            SELECT id, user_email, name, text, status, edited 
            FROM `task` 
            ORDER BY $orderBy 
            LIMIT :slice_limit 
            OFFSET :slice_offset
        ');

        $handle->execute([
            'slice_limit' => $limit,
            'slice_offset' => $offset,  
        ]);

        while ($object = $handle->fetchObject('\models\Task')) 
            self::$slice[(int)$object->getId()] = $object;
    }

    public static function getSlice(string $orderBy, int $limit, int $offset): array
    {
        self::populateSlice($orderBy, $limit, $offset, null);

        return self::$slice;
    }

    public static function get(int $id, PDO | null $pdo): Task
    {
        // NOTE: Condition is usefull for unit testing
        if (empty($pdo)) 
            $pdo = PDOFactory::readInstance();

        $handle = $pdo->prepare('
            SELECT id, user_email, name, text, status, edited 
            FROM `task` 
            WHERE id = :id
        ');

        $handle->execute([':id' => $id ]);

        self::$slice[(int)$id] = $handle->rowCount() === 1 
            ? $handle->fetchObject('\models\Task')
            : new self;

        return self::$slice[(int)$id];
    }

    public static function countAll(PDO | null $pdo): mixed
    {
        if (empty($pdo)) 
            $pdo = PDOFactory::readInstance();

        $handle = $pdo->prepare('SELECT count(*) FROM `task`');
        $handle->execute();

        return $handle->fetchColumn();  
    }

    public function create(array $data, PDO | null $pdo): bool
    {
        if (empty($data)) 
            return false;

        if (empty($pdo)) 
            $pdo = PDOFactory::readInstance();

        $handle = $pdo->prepare('
            INSERT INTO `task` 
            (
                user_email,
                status,
                edited,
                name,
                text
            ) 
            VALUES 
            (
                :user_email,
                :status,
                :edited,
                :name,
                :text
            ) 
        ');

        return $handle->execute([
            ':edited'       => 0,
            ':status'       => 1,
            ':name'         => $data['task_name'],
            ':user_email'   => $data['task_usermail'],
            ':text'         => $data['task_text'],
        ]);
    }

    public function update(array $data, PDO | null $pdo): bool
    {
        if (empty($data)) 
            return false;

        // NOTE: Condition is usefull for unit testing
        if (empty($pdo)) 
            $pdo = PDOFactory::readInstance();

        $handle = $pdo->prepare('
            UPDATE `task` SET
                user_email = :user_email,
                status = :status,
                edited = :edited,
                name = :name,
                text = :text
            WHERE id = :id
        ');

        $edited = !empty($data['task_text'])
            ? ($data['task_text'] === $this->getText() ? 0 : 1)
            : $this->getEdited();

        return $handle->execute([
            ':id'           => $data['task_id'] ?? $this->getId(),
            ':name'         => $data['task_name'] ?? $this->getName(),
            ':user_email'   => $data['task_usermail'] ?? $this->getUserEmail(),
            ':status'       => $data['task_status'] ?? $this->getStatus(),
            ':text'         => $data['task_text'] ?? $this->getText(),
            ':edited'       => $edited,
        ]);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): Task
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): Task
    {
        $this->name = $name;
        return $this;
    }

    public function getUserEmail(): ?string
    {
        return $this->user_email;
    }

    public function setUserEmail(string $user_email): Task
    {
        $this->user_email = $user_email;
        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): Task
    {
        $this->text = $text;
        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): Task
    {
        $this->status = $status;
        return $this;
    }

    public function getEdited(): ?int
    {
        return $this->edited;
    }

    public function setEdited(int $edited): Task
    {
        $this->edited = $edited;
        return $this;
    }

}