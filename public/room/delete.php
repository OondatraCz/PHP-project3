<?php
require_once __DIR__ . "/../../bootstrap/bootstrap.php";
session_start();

class RoomDeletePage extends CRUDPage
{

    protected function prepare(): void
    {
        parent::prepare();

        $roomId = filter_input(INPUT_POST, 'roomId', FILTER_VALIDATE_INT);
        if (!$roomId)
            throw new BadRequestException();

        //když poslal data
        $success = Room::deleteByID($roomId);

        //přesměruj
        $this->redirect(self::ACTION_DELETE, $success);
    }

    protected function pageBody()
    {
        return "";
    }

}

if (isset($_SESSION['id']) && isset($_SESSION['login']) && $_SESSION['admin'] == 1) {
    $page = new RoomDeletePage();
    $page->render();
}
else
{
    echo("nemáte práva");
}

?>