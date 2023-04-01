<?php
require_once __DIR__ . "/../../bootstrap/bootstrap.php";
session_start();

class StaffUpdatePage extends CRUDPage
{
    private ?Staff $staff;
    private ?array $errors = [];
    private int $state;

    protected function prepare(): void
    {
        parent::prepare();
        $this->findState();
        $this->title = "Upravit osobu";

        if ($this->state === self::STATE_FORM_REQUESTED)
        {
            $staffId = filter_input(INPUT_GET, 'staffId', FILTER_VALIDATE_INT);
            if (!$staffId)
                throw new BadRequestException();

            $this->staff = Staff::findByID($staffId);
            if (!$this->staff)
                throw new NotFoundException();
        }
        elseif($this->state === self::STATE_DATA_SENT) {
            $this->staff = Staff::readPost();

            $this->errors = [];
            $isOk = $this->staff->validate($this->errors);
            if (!$isOk)
            {
                $this->state = self::STATE_FORM_REQUESTED;
            }
            else
            {
                $success = $this->staff->update();

                $this->redirect(self::ACTION_UPDATE, $success);
            }
        }
    }

    protected function pageBody()
    {
        return MustacheProvider::get()->render(
            'staffForm',
            [
                'staff' => $this->staff,
                'errors' => $this->errors
            ]
        );
    }

    private function findState() : void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
            $this->state = self::STATE_DATA_SENT;
        else
            $this->state = self::STATE_FORM_REQUESTED;
    }

}

if (isset($_SESSION['id']) && isset($_SESSION['login']) && $_SESSION['admin'] == 1) {
    $page = new StaffUpdatePage();
    $page->render();
}
else{
    echo("Nemáte práva");
}

?>