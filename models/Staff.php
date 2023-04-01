<?php

class Staff
{
    public const DB_TABLE = "employee";

    public ?int $staff_id;
    public ?string $name;
    public ?string $surname;
    public ?string $job;
    public ?int $wage;
    public ?int $room;

    public function __construct(?int $staff_id = null, ?string $name = null, ?string $surname = null, ?string $job = null, ?int $wage = null, ?int $room = null)
    {
        $this->staff_id = $staff_id;
        $this->name = $name;
        $this->surname = $surname;
        $this->job = $job;
        $this->wage = $wage;
        $this->room = $room;
    }

    public static function findByID(int $id) : ?self
    {
        $pdo = PDOProvider::get();
        $stmt = $pdo->prepare("SELECT * FROM `".self::DB_TABLE."` WHERE `employee_id`= :staffId");
        //$stmt = $pdo->query('SELECT CONCAT(e.name, " ", e.surname) AS fullname, r.name, r.phone, e.job, e.employee_id FROM employee e INNER JOIN room r ON e.room = r.room_id ORDER BY fullname');
        $stmt->execute(['staffId' => $id]);

        if($stmt->rowCount() < 1)
            return null;

        $staff = new self();
        $staff->hydrate($stmt->fetch());
        return $staff;
    }

    public static function getAll($sorting = []) : array
    {
        $sortSQL = "";
        if (count($sorting))
        {
            $SQLchunks = [];
            foreach ($sorting as $field => $direction)
                $SQLchunks[] = "`{$field}` {$direction}";

            $sortSQL = " ORDER BY " . implode(', ', $SQLchunks);
        }
        $pdo = PDOProvider::get();
        $stmt = $pdo->prepare("SELECT employee_id AS staff_id, name, surname, job, wage, room, login, password, admin FROM `".self::DB_TABLE."`" . $sortSQL);
        $stmt->execute([]);

        $staffs = [];
        while ($staffData = $stmt->fetch())
        {
            $staff = new Staff();
            $staff->hydrate($staffData);
            $staffs[] = $staff;
        }

        return $staffs;
    }

    private function hydrate(array|object $data)
    {
        $fields = ['staff_id', 'name', 'surname', 'job', 'wage', 'room', 'login', 'password', 'admin'];
        if(is_array($data))
        {
            foreach ($fields as $field)
            {
                if(array_key_exists($field, $data))
                    $this->{$field} = $data[$field];
            }
        }
        else
        {
            foreach ($fields as $field)
            {
                if (property_exists($data, $field))
                    $this->{$field} = $data->{$field};
            }
        }
    }

    public function insert() : bool
    {
        $query = "INSERT INTO ".self::DB_TABLE." (`name`, `surname`, `job`, `wage`, `room`) VALUES (:name, :surname, :job, :wage, :room)";
        $stmt = PDOProvider::get()->prepare($query);
        $result = $stmt->execute(['name'=>$this->name, 'surname'=>$this->surname, 'job'=>$this->job, 'wage'=>$this->wage, 'room'=>$this->room]);
        if(!$result)
            return false;

        $this->staff_id = PDOProvider::get()->lastInsertId();
        return true;
    }

    public function update() : bool
    {
        if(!isset($this->staff_id) || !$this->staff_id)
            throw new Exception("Cannot update model without ID");
        $query = "UPDATE ".self::DB_TABLE." SET `name` = :name, `surname` = :surname, `job` = :job, `wage` = :wage, `room` = :room, `staff_id` = :staff_id WHERE `staff_id` = :staffId";
        $stmt = PDOProvider::get()->prepare($query);
        return $stmt->execute(['staffId'=>$this->staff_id, 'name'=>$this->name, 'surname'=>$this->surname, 'job'=>$this->job, 'wage'=>$this->wage, 'room'=>$this->room]);
    }

    public function delete() : bool
    {
        return self::deleteByID($this->staff_id);
    }

    public static function deleteByID(int $staffId) : bool
    {
        $query = "DELETE FROM `".self::DB_TABLE."` WHERE `employee_id` = :staffId";
        $stmt = PDOProvider::get()->prepare($query);
        return $stmt->execute(['staffId'=>$staffId]);
    }

    public function validate(&$errors = []) : bool
    {
        if (!isset($this->name) || (!$this->name))
            $errors['name'] = 'Jméno nesmí být prázdné';

        if (!isset($this->surname) || (!$this->surname))
            $errors['surname'] = 'Příjmení musí být vyplněno';

        return count($errors) === 0;
    }

    public static function readPost() : self
    {
        $staff = new Staff();
        $staff->staff_id = filter_input(INPUT_POST, 'staff_id', FILTER_VALIDATE_INT);

        $staff->name = filter_input(INPUT_POST, 'name');
        if ($staff->name)
            $staff->name = trim($staff->name);

        $staff->surname = filter_input(INPUT_POST, 'surname');
        if ($staff->surname)
            $staff->surname = trim($staff->surname);

        $staff->job = filter_input(INPUT_POST, 'job');
        if ($staff->job)
            $staff->job = trim($staff->job);
        if (!$staff->job)
            $staff->null;

        $staff->wage = filter_input(INPUT_POST, 'wage');
        if ($staff->wage)
            $staff->wage = trim($staff->wage);
        if (!$staff->wage)
            $staff->null;

        $staff->room = filter_input(INPUT_POST, 'room');
        if ($staff->room)
            $staff->room = trim($staff->room);
        if (!$staff->room)
            $staff->null;

        return $staff;
    }

}