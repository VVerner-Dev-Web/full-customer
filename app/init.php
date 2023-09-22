<?php defined('ABSPATH') || exit;

require_once dirname(FULL_CUSTOMER_FILE) . '/vendor/autoload.php';

require_once FULL_CUSTOMER_APP . '/api/Controller.php';

require_once FULL_CUSTOMER_APP . '/api/Backup.php';
require_once FULL_CUSTOMER_APP . '/api/Connection.php';
require_once FULL_CUSTOMER_APP . '/api/ElementorTemplates.php';
require_once FULL_CUSTOMER_APP . '/api/ElementorAi.php';
require_once FULL_CUSTOMER_APP . '/api/Env.php';
require_once FULL_CUSTOMER_APP . '/api/Health.php';
require_once FULL_CUSTOMER_APP . '/api/Login.php';
require_once FULL_CUSTOMER_APP . '/api/PluginInstallation.php';
require_once FULL_CUSTOMER_APP . '/api/PluginUpdate.php';
require_once FULL_CUSTOMER_APP . '/api/Whitelabel.php';
require_once FULL_CUSTOMER_APP . '/api/Widgets.php';

require_once FULL_CUSTOMER_APP . '/backup/Controller.php';
require_once FULL_CUSTOMER_APP . '/backup/MysqlDump.php';
require_once FULL_CUSTOMER_APP . '/backup/Cron.php';

require_once FULL_CUSTOMER_APP . '/health/Controller.php';

require_once FULL_CUSTOMER_APP . '/firewall/Controller.php';

require_once FULL_CUSTOMER_APP . '/controller/Proxy.php';
require_once FULL_CUSTOMER_APP . '/controller/FileSystem.php';

require_once FULL_CUSTOMER_APP . '/controller/hooks.php';
require_once FULL_CUSTOMER_APP . '/controller/actions.php';
require_once FULL_CUSTOMER_APP . '/controller/filters.php';
require_once FULL_CUSTOMER_APP . '/controller/helpers.php';
require_once FULL_CUSTOMER_APP . '/controller/upgrade.php';
