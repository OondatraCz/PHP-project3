<?php
require_once __DIR__ . "/../bootstrap/bootstrap.php";
session_start();

class IndexPage extends BasePage
{
    public function __construct()
    {
        $this->title = "Prohlížeč databáze firmy";
    }

    protected function pageBody()
    {
        return "";
    }
}

if (isset($_SESSION['id']) && isset($_SESSION['login'])) {
    echo("<a href='logout.php'><button>Odhlásit se</button></a>\n<a href='changePass.php'><button>Změnit heslo</button></a>");
}
    $page = new IndexPage();
    $page->render();

?>