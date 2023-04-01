<?php
require_once __DIR__ . "/../../bootstrap/bootstrap.php";
session_start();

class RoomsPage extends CRUDPage
{
    private $alert = [];

    public function __construct()
    {
        $this->title = "Výpis místností";
    }

    protected function prepare(): void
    {
        parent::prepare();
        //pokud přišel výsledek, zachytím ho
        $crudResult = filter_input(INPUT_GET, 'success', FILTER_VALIDATE_INT);
        $crudAction = filter_input(INPUT_GET, 'action');

        if (is_int($crudResult)) {
            $this->alert = [
                'alertClass' => $crudResult === 0 ? 'danger' : 'success'
            ];

            $message = '';
            if ($crudResult === 0)
            {
                $message = 'Operace nebyla úspěšná';
            }
            else if ($crudAction === self::ACTION_DELETE)
            {
                $message = 'Smazání proběhlo úspěšně';
            }
            else if ($crudAction === self::ACTION_INSERT)
            {
                $message = 'Místnost založena úspěšně';
            }
            else if ($crudAction === self::ACTION_UPDATE)
            {
                $message = 'Úprava místnosti byla úspěšná';
            }

            $this->alert['message'] = $message;
        }

    }


    protected function pageBody()
    {
        $html = "";
        //zobrazit alert
        if ($this->alert) {
            $html .= MustacheProvider::get()->render('crudResult', $this->alert);
        }

        //získat data
        $rooms = Room::getAll(['name' => 'ASC']);
        //prezentovat data
        $html .= MustacheProvider::get()->render('roomList',['rooms' => $rooms]);

        return $html;
    }

}

if (isset($_SESSION['id']) && isset($_SESSION['login'])) {
    $page = new RoomsPage();
    $page->render();
    echo("<a href='../logout.php'><button>Odhlásit se</button></a>");
}
else{
    echo("Nemáte práva");
}

?>