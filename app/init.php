<?php

use Full\Customer\License;

defined('ABSPATH') || exit;

require_once dirname(FULL_CUSTOMER_FILE) . '/vendor/autoload.php';

require_once FULL_CUSTOMER_APP . '/controller/inc/License.php';

require_once FULL_CUSTOMER_APP . '/api/Controller.php';

require_once FULL_CUSTOMER_APP . '/api/Connection.php';
require_once FULL_CUSTOMER_APP . '/api/Copy.php';
require_once FULL_CUSTOMER_APP . '/api/Env.php';
require_once FULL_CUSTOMER_APP . '/api/Health.php';
require_once FULL_CUSTOMER_APP . '/api/Login.php';
require_once FULL_CUSTOMER_APP . '/api/PluginInstallation.php';
require_once FULL_CUSTOMER_APP . '/api/ElementorTemplates.php';
require_once FULL_CUSTOMER_APP . '/api/ElementorAi.php';
require_once FULL_CUSTOMER_APP . '/api/Widgets.php';

require_once FULL_CUSTOMER_APP . '/controller/inc/Health.php';
require_once FULL_CUSTOMER_APP . '/controller/inc/Proxy.php';
require_once FULL_CUSTOMER_APP . '/controller/inc/FileSystem.php';
require_once FULL_CUSTOMER_APP . '/controller/inc/RemoteLogin.php';

if (License::isActive()) :
  require_once FULL_CUSTOMER_APP . '/api/PluginUpdate.php';
  require_once FULL_CUSTOMER_APP . '/api/Whitelabel.php';

  require_once FULL_CUSTOMER_APP . '/controller/security/Firewall.php';
endif;

require_once FULL_CUSTOMER_APP . '/controller/hooks.php';
require_once FULL_CUSTOMER_APP . '/controller/actions.php';
require_once FULL_CUSTOMER_APP . '/controller/filters.php';
require_once FULL_CUSTOMER_APP . '/controller/helpers.php';
require_once FULL_CUSTOMER_APP . '/controller/upgrade.php';
