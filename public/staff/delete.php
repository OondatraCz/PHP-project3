<?php
require_once __DIR__ . "/../../bootstrap/bootstrap.php";
session_start();

class StaffDeletePage extends CRUDPage
{

    protected function prepare(): void
    {
        parent::prepare();

        $staffId = filter_input(INPUT_POST, 'staffId', FILTER_VALIDATE_INT);
        if (!$staffId)
            throw new BadRequestException();

        $success = Staff::deleteByID($staffId);

        //přesměruj
        $this->redirect(self::ACTION_DELETE, $success);
    }

    protected function pageBody()
    {
        return "";
    }

}

if (isset($_SESSION['id']) && isset($_SESSION['login']) && $_SESSION['admin'] == 1) {
    $page = new StaffDeletePage();
    $page->render();
}
else{
    echo("Nemáte práva");
}

?>