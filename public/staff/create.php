<?php
require_once __DIR__ . "/../../bootstrap/bootstrap.php";
session_start();

class StaffCreatePage extends CRUDPage
{
    private ?Staff $staff;
    private ?array $errors = [];
    private int $state;

    protected function prepare(): void
    {
        parent::prepare();
        $this->findState();
        $this->title = "Založit novou osobu";

        if ($this->state === self::STATE_FORM_REQUESTED)
        {
            $this->staff = new Staff();
        }

        elseif($this->state === self::STATE_DATA_SENT) {
            $this->staff = Staff::readPost();

            //zkontroluj je, jinak formulář
            $this->errors = [];
            $isOk = $this->staff->validate($this->errors);
            if (!$isOk)
            {
                $this->state = self::STATE_FORM_REQUESTED;
            }
            else
            {
                //ulož je
                $success = $this->staff->insert();

                //přesměruj
                $this->redirect(self::ACTION_INSERT, $success);
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
    $page = new StaffCreatePage();
    $page->render();
}
else{
    echo("Nemáte práva");
}

?>