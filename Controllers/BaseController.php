<?php

namespace BaseController;

class BaseController 
{
    /**
     * Load a view with data and layout
     */
    protected function loadView($view, $data = []) 
    {
        // Extract data to make variables available in view
        extract($data);
        
        // Start output buffering to capture view content
        ob_start();
        
        // Include the view file
        $viewFile = "views/{$view}.php";
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            echo "<h1>Error: View '{$view}' not found</h1>";
        }
        
        // Get view content
        $content = ob_get_clean();
        
        // Load main layout with content
        include "views/layouts/main.php";
    }
    
    /**
     * Redirect to another URL
     */
    protected function redirect($url) 
    {
        header("Location: {$url}");
        exit;
    }
    
    /**
     * Get POST data safely
     */
    protected function getPostData($key = null) 
    {
        if ($key) {
            return $_POST[$key] ?? null;
        }
        return $_POST;
    }
    
    /**
     * Get GET data safely
     */
    protected function getGetData($key = null) 
    {
        if ($key) {
            return $_GET[$key] ?? null;
        }
        return $_GET;
    }
    
    /**
     * Check if request is POST
     */
    protected function isPost() 
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
}

?>