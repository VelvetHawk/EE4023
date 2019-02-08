<head>
    <meta charset="UTF-8">
    <title><?php echo $page_title ?></title>
    <link rel="stylesheet" href="css/styles.css" />
    <script src="js/mainScripts.js"></script>
    <?php // Include only if on index page ?>

    <?php // Include file only if on specific page.php ?>    
    <?php if (basename($_SERVER['PHP_SELF']) == "your page.php"): ?>
        
    <?php endif; ?>
</head>

