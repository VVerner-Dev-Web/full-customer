<?php defined('ABSPATH') || exit;

class FULL_CUSTOMER_Plugin extends WP_REST_Controller
{
    private const NAMESPACE         = 'full-customer';
    private const TEMPORARY_DIR     = WP_CONTENT_DIR . '/full-temp';

    public static function registerRoutes(): void
    {
        $api = new self();

        register_rest_route(self::NAMESPACE, '/install-plugin', [
            [
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => [$api, 'installPlugin'],
                'permission_callback' => 'is_user_logged_in',
            ]
        ]);        
    }

    public function installPlugin(WP_REST_Request $request): WP_REST_Response
    {

        if (!function_exists('activate_plugin')) :
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        endif;
        
        $data   = $request->get_json_params();
        $file   = isset($data['file']) ? $data['file'] : null;

        if (!$file) : 
            return new WP_REST_Response(-1, 400);
        endif;

        $this->createTemporaryDir();

        $copied = $this->copyZipFile( $file );
        if (!$copied)  : 
            $this->removeTemporaryDir();
            return new WP_REST_Response(-2, 400);
        endif;

        $dirname = $this->getUnzippedDirname();
        $moved   = $this->movePluginFiles();
        if (!$moved)  : 
            $this->removeTemporaryDir();
            return new WP_REST_Response(-3, 400);
        endif;

        $activated = $this->activatePlugin( $dirname );
        if (!$activated)  : 
            $this->removeTemporaryDir();
            return new WP_REST_Response(-4, 400);
        endif;
        
        $this->removeTemporaryDir();

        return new WP_REST_Response(null, 200);
    }

    private function copyZipFile(string $source): bool
    {
        global $wp_filesystem;

        if (!$wp_filesystem) : 
            require_once ABSPATH . '/wp-admin/includes/file.php';
            WP_Filesystem();
        endif;

        $pluginRequest = wp_remote_get($source, ['sslverify' => false]);
        $zipContent    = wp_remote_retrieve_body( $pluginRequest );
        $zipPath       = self::TEMPORARY_DIR . '/plugin.zip';

        $wp_filesystem->put_contents($zipPath, $zipContent);

        $worker = new ZipArchive;
        $res    = $worker->open( $zipPath );
        
        if ($res !== true) :
            unlink( $zipPath );
            return false;
        endif;

        $worker->extractTo( self::TEMPORARY_DIR );
        $worker->close();

        unlink( $zipPath );

        return true;
    }

    private function movePluginFiles(): bool
    {
        $dirname = $this->getUnzippedDirname();

        if (!$dirname) : 
            return false;
        endif;

        return rename( 
            self::TEMPORARY_DIR . '/' . $dirname, 
            WP_PLUGIN_DIR . '/' . $dirname
        );
    }

    private function getUnzippedDirname(): ?string
    {
        $scan = scandir( self::TEMPORARY_DIR );
        $scan = array_diff($scan, ['.', '..']);
        $dir = array_pop($scan);

        return $dir ? $dir : null;
    }

    private function activatePlugin(string $dirname): bool
    {
        $completePluginPath = WP_PLUGIN_DIR . '/' . $dirname . '/' . $dirname . '.php';
        $activate           = activate_plugin( $completePluginPath );

        return is_wp_error($activate);
    }

    private function removeTemporaryDir(): void
    {
        if (is_dir(self::TEMPORARY_DIR)) : 
            rmdir(self::TEMPORARY_DIR);
        endif;
    }

    private function createTemporaryDir(): void
    {
        if (!is_dir(self::TEMPORARY_DIR)) : 
            mkdir(self::TEMPORARY_DIR);
        endif;
    }
}
