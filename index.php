<?php	
require_once "controllers/template.controller.php";

require_once "controllers/branch.controller.php";
require_once "models/branch.model.php";

require_once "controllers/employees.controller.php";
require_once "models/employees.model.php";

require_once "controllers/bank.controller.php";
require_once "models/bank.model.php";

require_once "controllers/userrights.controller.php";
require_once "models/userrights.model.php";

require_once "controllers/clients.controller.php";
require_once "models/clients.model.php";

require_once "controllers/home.controller.php";
require_once "models/home.model.php";

require_once "controllers/sale.controller.php";
require_once "models/sale.model.php";

require_once "controllers/receivable.controller.php";
require_once "models/receivable.model.php";

require_once "controllers/collectionreport.controller.php";
require_once "models/collectionreport.model.php";

require_once "controllers/category.controller.php";
require_once "models/category.model.php";

require_once "controllers/brand.controller.php";
require_once "models/brand.model.php";

require_once "controllers/measure.controller.php";
require_once "models/measure.model.php";

require_once "controllers/products.controller.php";
require_once "models/products.model.php";

require_once "controllers/reset.controller.php";
require_once "models/reset.model.php";

$template = new ControllerTemplate();
$template -> ctrTemplate();