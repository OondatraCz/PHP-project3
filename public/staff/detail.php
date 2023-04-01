<?php

require_once __DIR__ . "/../../bootstrap/bootstrap.php";
session_start();

class StaffDetailPage extends BasePage
{
    private $room;
    private $employees;

    protected function prepare(): void
    {
        parent::prepare();
        //získat data z GET
        $staffId = filter_input(INPUT_GET, 'staffId', FILTER_VALIDATE_INT);
        if (!$staffId)
            throw new BadRequestException();

        //najít místnost v databázi
        $this->staff = Room::findByID($staffId);
        if (!$this->staff)
            throw new NotFoundException();


        //$stmt = PDOProvider::get()->prepare("SELECT `surname`, `name`, `employee_id` FROM `employee` WHERE `room`= :roomId ORDER BY `surname`, `name`");
        $stmt = PDOProvider::get()->prepare("SELECT ro.name, ro.room_id FROM `room` AS ro JOIN `employee` AS em ON ro.room_id = em.room WHERE em.employee_id = :emloyeeId");
        $stmt->execute(['emloyeeId' => $staffId]);
        $this->employees = $stmt->fetchAll();
        $this->title = "Detail osoby {$this->staff->name}";

    }

    protected function pageBody()
    {
        //prezentovat data
        return MustacheProvider::get()->render('staffDetail',['staff' => $this->employees]);
    }

}

if (isset($_SESSION['id']) && isset($_SESSION['login']) && $_SESSION['admin'] == 1) {
    $page = new StaffDetailPage();
    $page->render();
}
else {
    echo("Nemáte práva");
}

?>